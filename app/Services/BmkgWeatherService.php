<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BmkgWeatherService
{
    /**
     * Base URL for BMKG Public Weather API
     */
    protected const WEATHER_API_URL = 'https://api.bmkg.go.id/publik/prakiraan-cuaca';

    /**
     * Base URL for BMKG TEWS Earthquake API
     */
    protected const EARTHQUAKE_API_URL = 'https://data.bmkg.go.id/DataMKG/TEWS/autogempa.json';

    /**
     * Base URL for BMKG TEWS Shakemap images
     */
    protected const SHAKEMAP_BASE_URL = 'https://data.bmkg.go.id/DataMKG/TEWS/';

    /**
     * Cache duration for weather data in seconds (20 minutes)
     */
    protected const WEATHER_CACHE_TTL = 1200;

    /**
     * Cache duration for earthquake data in seconds (5 minutes)
     */
    protected const EARTHQUAKE_CACHE_TTL = 300;

    /**
     * Indonesian wind direction dictionary
     *
     * @var array<string, string>
     */
    protected array $windDirections = [
        'N' => 'Utara',
        'NNE' => 'Utara-Timur Laut',
        'NE' => 'Timur Laut',
        'ENE' => 'Timur-Timur Laut',
        'E' => 'Timur',
        'ESE' => 'Timur-Tenggara',
        'SE' => 'Tenggara',
        'SSE' => 'Selatan-Tenggara',
        'S' => 'Selatan',
        'SSW' => 'Selatan-Barat Daya',
        'SW' => 'Barat Daya',
        'WSW' => 'Barat-Barat Daya',
        'W' => 'Barat',
        'WNW' => 'Barat-Barat Laut',
        'NW' => 'Barat Laut',
        'NNW' => 'Utara-Barat Laut',
        'VARIABLE' => 'Berubah-ubah',
    ];

    /**
     * Get weather forecast data for the given ADM4 code.
     *
     * @return array<string, mixed>
     */
    public function getWeather(string $adm4): array
    {
        $cacheKey = "bmkg.weather.{$adm4}";

        return Cache::remember($cacheKey, self::WEATHER_CACHE_TTL, function () use ($adm4) {
            try {
                $response = Http::timeout(6)
                    ->withHeaders([
                        'User-Agent' => 'BMKG-Clean-Dashboard/1.0',
                        'Accept' => 'application/json',
                    ])
                    ->get(self::WEATHER_API_URL, [
                        'adm4' => $adm4,
                    ]);

                if ($response->successful()) {
                    $json = $response->json();
                    if (! empty($json['lokasi']) && ! empty($json['data'])) {
                        return $this->formatWeatherData($json, $adm4);
                    }
                }

                Log::warning("BMKG API non-successful for adm4 {$adm4}: Status ".$response->status());
            } catch (\Throwable $e) {
                Log::error("BMKG API error for adm4 {$adm4}: ".$e->getMessage());
            }

            return $this->fallbackWeatherData($adm4);
        });
    }

    /**
     * Get latest earthquake data from BMKG.
     *
     * @return array<string, mixed>|null
     */
    public function getLatestEarthquake(): ?array
    {
        return Cache::remember('bmkg.autogempa', self::EARTHQUAKE_CACHE_TTL, function () {
            try {
                $response = Http::timeout(5)
                    ->withHeaders([
                        'User-Agent' => 'BMKG-Clean-Dashboard/1.0',
                        'Accept' => 'application/json',
                    ])
                    ->get(self::EARTHQUAKE_API_URL);

                if ($response->successful()) {
                    $data = $response->json();
                    if (isset($data['Infogempa']['gempa'])) {
                        return $this->formatEarthquakeData($data['Infogempa']['gempa']);
                    }
                }
            } catch (\Throwable $e) {
                Log::error('BMKG Earthquake API error: '.$e->getMessage());
            }

            return null;
        });
    }

    /**
     * Format BMKG raw weather payload into an intuitive, presentation-ready array.
     *
     * @param  array<string, mixed>  $raw
     * @return array<string, mixed>
     */
    protected function formatWeatherData(array $raw, string $adm4): array
    {
        $lokasi = $raw['lokasi'] ?? [];
        $dataForecast = $raw['data'][0] ?? [];
        $rawDays = $dataForecast['cuaca'] ?? [];

        // Flatten all available slots for sequential timeline calculation
        $allSlots = [];
        foreach ($rawDays as $dayIndex => $slots) {
            if (is_array($slots)) {
                foreach ($slots as $slot) {
                    $slot['_day_index'] = $dayIndex;
                    $allSlots[] = $slot;
                }
            }
        }

        // Determine current/most relevant weather slot
        $currentSlot = $this->determineCurrentSlot($allSlots);

        // Process hourly timeline (next 12 slots, i.e., up to 36 hours)
        $hourly = [];
        foreach (array_slice($allSlots, 0, 10) as $index => $slot) {
            $localTime = Carbon::parse($slot['local_datetime'] ?? $slot['datetime']);
            $hourly[] = [
                'time' => $localTime->format('H:i'),
                'date_short' => $localTime->translatedFormat('d M'),
                'is_current' => ($index === 0),
                'temp' => (int) round($slot['t'] ?? 0),
                'weather_desc' => $slot['weather_desc'] ?? 'Cerah Berawan',
                'weather_desc_en' => $slot['weather_desc_en'] ?? 'Partly Cloudy',
                'image' => $slot['image'] ?? 'https://api-apps.bmkg.go.id/storage/icon/cuaca/cerah%20berawan-am.svg',
                'humidity' => (int) ($slot['hu'] ?? 0),
                'wind_speed' => (float) ($slot['ws'] ?? 0),
                'wind_direction' => $slot['wd'] ?? 'N',
                'cloud_cover' => (int) ($slot['tcc'] ?? 0),
                'precipitation' => (float) ($slot['tp'] ?? 0),
            ];
        }

        // Process daily grouping (3 days)
        $daily = [];
        foreach ($rawDays as $dIdx => $daySlots) {
            if (empty($daySlots) || ! is_array($daySlots)) {
                continue;
            }

            $firstSlot = $daySlots[0];
            $dayCarbon = Carbon::parse($firstSlot['local_datetime'] ?? $firstSlot['datetime']);

            $temps = array_column($daySlots, 't');
            $minTemp = ! empty($temps) ? min($temps) : 24;
            $maxTemp = ! empty($temps) ? max($temps) : 32;

            $humidities = array_column($daySlots, 'hu');
            $avgHumidity = ! empty($humidities) ? (int) round(array_sum($humidities) / count($humidities)) : 75;

            $windSpeeds = array_column($daySlots, 'ws');
            $maxWind = ! empty($windSpeeds) ? max($windSpeeds) : 10;

            // Pick daytime representative slot (around 12:00 or 15:00) or middle slot
            $repSlot = $daySlots[min((int) floor(count($daySlots) / 2), count($daySlots) - 1)];

            $label = match ($dIdx) {
                0 => 'Hari Ini',
                1 => 'Besok',
                2 => 'Lusa',
                default => $dayCarbon->translatedFormat('l'),
            };

            $daily[] = [
                'day_label' => $label,
                'date_formatted' => $dayCarbon->translatedFormat('l, d M Y'),
                'min_temp' => (int) round($minTemp),
                'max_temp' => (int) round($maxTemp),
                'avg_humidity' => $avgHumidity,
                'max_wind' => (float) $maxWind,
                'rep_desc' => $repSlot['weather_desc'] ?? 'Berawan',
                'rep_image' => $repSlot['image'] ?? 'https://api-apps.bmkg.go.id/storage/icon/cuaca/berawan-am.svg',
                'slots_count' => count($daySlots),
            ];
        }

        // Enriched current condition
        $currentTemp = (int) round($currentSlot['t'] ?? 28);
        $currentHumidity = (int) ($currentSlot['hu'] ?? 75);
        $currentWindSpeed = (float) ($currentSlot['ws'] ?? 5);
        $currentWindDir = $currentSlot['wd'] ?? 'N';
        $currentCloudCover = (int) ($currentSlot['tcc'] ?? 50);

        return [
            'success' => true,
            'adm4' => $adm4,
            'lokasi' => [
                'provinsi' => $lokasi['provinsi'] ?? 'Indonesia',
                'kotkab' => $lokasi['kotkab'] ?? 'Wilayah BMKG',
                'kecamatan' => $lokasi['kecamatan'] ?? '',
                'desa' => $lokasi['desa'] ?? '',
                'lon' => $lokasi['lon'] ?? 106.82,
                'lat' => $lokasi['lat'] ?? -6.17,
                'timezone' => $lokasi['timezone'] ?? 'Asia/Jakarta',
            ],
            'current' => [
                'temp' => $currentTemp,
                'weather_desc' => $currentSlot['weather_desc'] ?? 'Cerah Berawan',
                'weather_desc_en' => $currentSlot['weather_desc_en'] ?? 'Partly Cloudy',
                'image' => $currentSlot['image'] ?? 'https://api-apps.bmkg.go.id/storage/icon/cuaca/cerah%20berawan-am.svg',
                'humidity' => $currentHumidity,
                'humidity_status' => $this->getHumidityStatus($currentHumidity),
                'wind_speed' => $currentWindSpeed,
                'wind_direction' => $currentWindDir,
                'wind_direction_id' => $this->windDirections[$currentWindDir] ?? $currentWindDir,
                'wind_deg' => (int) ($currentSlot['wd_deg'] ?? 0),
                'cloud_cover' => $currentCloudCover,
                'cloud_status' => $this->getCloudStatus($currentCloudCover),
                'precipitation' => (float) ($currentSlot['tp'] ?? 0),
                'local_datetime' => $currentSlot['local_datetime'] ?? Carbon::now()->format('Y-m-d H:i:s'),
                'formatted_time' => Carbon::parse($currentSlot['local_datetime'] ?? now())->translatedFormat('l, d F Y • H:i'),
            ],
            'hourly' => $hourly,
            'daily' => $daily,
            'raw_days' => $rawDays,
            'last_synced' => Carbon::now()->translatedFormat('H:i:s').' WIB',
            'is_fallback' => false,
        ];
    }

    /**
     * Pick the slot that is closest to current local time.
     *
     * @param  array<int, array<string, mixed>>  $slots
     * @return array<string, mixed>
     */
    protected function determineCurrentSlot(array $slots): array
    {
        if (empty($slots)) {
            return [];
        }

        $now = Carbon::now();
        $closestSlot = $slots[0];
        $minDiff = PHP_INT_MAX;

        foreach ($slots as $slot) {
            $slotTime = Carbon::parse($slot['local_datetime'] ?? $slot['datetime'] ?? now());
            $diff = abs($now->diffInMinutes($slotTime, false));

            if ($diff < $minDiff) {
                $minDiff = $diff;
                $closestSlot = $slot;
            }
        }

        return $closestSlot;
    }

    /**
     * Format earthquake data from BMKG TEWS.
     *
     * @param  array<string, mixed>  $gempa
     * @return array<string, mixed>
     */
    protected function formatEarthquakeData(array $gempa): array
    {
        $mag = (float) ($gempa['Magnitude'] ?? 0);
        $shakemap = $gempa['Shakemap'] ?? '';

        return [
            'tanggal' => $gempa['Tanggal'] ?? '-',
            'jam' => $gempa['Jam'] ?? '-',
            'datetime' => $gempa['DateTime'] ?? '-',
            'coordinates' => $gempa['Coordinates'] ?? '-',
            'lintang' => $gempa['Lintang'] ?? '-',
            'bujur' => $gempa['Bujur'] ?? '-',
            'magnitude' => $mag,
            'kedalaman' => $gempa['Kedalaman'] ?? '-',
            'wilayah' => $gempa['Wilayah'] ?? '-',
            'potensi' => $gempa['Potensi'] ?? 'Tidak berpotensi tsunami',
            'dirasakan' => $gempa['Dirasakan'] ?? '-',
            'shakemap_url' => $shakemap ? self::SHAKEMAP_BASE_URL.$shakemap : null,
            'is_strong' => ($mag >= 5.0),
        ];
    }

    /**
     * Return fallback weather data structure if BMKG API has network failure.
     *
     * @return array<string, mixed>
     */
    protected function fallbackWeatherData(string $adm4): array
    {
        return [
            'success' => false,
            'adm4' => $adm4,
            'lokasi' => [
                'provinsi' => 'Indonesia',
                'kotkab' => 'Lokasi BMKG',
                'kecamatan' => '',
                'desa' => '',
                'lon' => 106.82,
                'lat' => -6.17,
                'timezone' => 'Asia/Jakarta',
            ],
            'current' => [
                'temp' => 28,
                'weather_desc' => 'Cerah Berawan',
                'weather_desc_en' => 'Partly Cloudy',
                'image' => 'https://api-apps.bmkg.go.id/storage/icon/cuaca/cerah%20berawan-am.svg',
                'humidity' => 70,
                'humidity_status' => 'Normal',
                'wind_speed' => 8.5,
                'wind_direction' => 'E',
                'wind_direction_id' => 'Timur',
                'wind_deg' => 90,
                'cloud_cover' => 45,
                'cloud_status' => 'Berawan Sebagian',
                'precipitation' => 0.0,
                'local_datetime' => Carbon::now()->format('Y-m-d H:i:s'),
                'formatted_time' => Carbon::now()->translatedFormat('l, d F Y • H:i'),
            ],
            'hourly' => [],
            'daily' => [],
            'raw_days' => [],
            'last_synced' => Carbon::now()->translatedFormat('H:i:s').' WIB',
            'is_fallback' => true,
        ];
    }

    /**
     * Compute human-friendly humidity status.
     */
    protected function getHumidityStatus(int $humidity): string
    {
        if ($humidity < 45) {
            return 'Kering';
        }
        if ($humidity <= 75) {
            return 'Normal / Nyaman';
        }
        if ($humidity <= 85) {
            return 'Lembap';
        }

        return 'Sangat Lembap';
    }

    /**
     * Compute human-friendly cloud status.
     */
    protected function getCloudStatus(int $cloudCover): string
    {
        if ($cloudCover < 20) {
            return 'Cerah / Langit Bersih';
        }
        if ($cloudCover < 50) {
            return 'Cerah Berawan';
        }
        if ($cloudCover < 85) {
            return 'Berawan';
        }

        return 'Berawan Tebal';
    }
}
