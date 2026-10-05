<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class WeatherTest extends TestCase
{
    /**
     * Test that the homepage loads successfully and displays weather dashboard.
     */
    public function test_weather_dashboard_can_be_rendered(): void
    {
        Http::fake([
            'https://api.bmkg.go.id/publik/prakiraan-cuaca*' => Http::response([
                'lokasi' => [
                    'adm1' => '31',
                    'adm2' => '31.71',
                    'adm3' => '31.71.01',
                    'adm4' => '31.71.01.1001',
                    'provinsi' => 'DKI Jakarta',
                    'kotkab' => 'Kota Adm. Jakarta Pusat',
                    'kecamatan' => 'Gambir',
                    'desa' => 'Gambir',
                    'lon' => 106.82,
                    'lat' => -6.17,
                    'timezone' => 'Asia/Jakarta',
                ],
                'data' => [
                    [
                        'cuaca' => [
                            [
                                [
                                    'datetime' => '2026-10-05T09:00:00Z',
                                    't' => 29,
                                    'tcc' => 50,
                                    'tp' => 0,
                                    'weather' => 2,
                                    'weather_desc' => 'Cerah Berawan',
                                    'weather_desc_en' => 'Partly Cloudy',
                                    'wd' => 'NE',
                                    'ws' => 8.5,
                                    'hu' => 70,
                                    'image' => 'https://api-apps.bmkg.go.id/storage/icon/cuaca/cerah%20berawan-am.svg',
                                    'local_datetime' => '2026-10-05 16:00:00',
                                ],
                            ],
                        ],
                    ],
                ],
            ], 200),
            'https://data.bmkg.go.id/DataMKG/TEWS/autogempa.json' => Http::response([
                'Infogempa' => [
                    'gempa' => [
                        'Tanggal' => '05 Okt 2026',
                        'Jam' => '10:00:00 WIB',
                        'DateTime' => '2026-10-05T03:00:00+00:00',
                        'Coordinates' => '-8.5,115.2',
                        'Lintang' => '8.5 LS',
                        'Bujur' => '115.2 BT',
                        'Magnitude' => '4.5',
                        'Kedalaman' => '10 km',
                        'Wilayah' => 'Pusat gempa berada di laut',
                        'Potensi' => 'Tidak berpotensi tsunami',
                        'Dirasakan' => 'II Badung',
                        'Shakemap' => 'sample.jpg',
                    ],
                ],
            ], 200),
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Cuaca BMKG');
        $response->assertSee('Kota Adm. Jakarta Pusat');
        $response->assertSee('DKI Jakarta');
    }

    /**
     * Test the city search endpoint returns JSON.
     */
    public function test_city_search_endpoint_returns_json(): void
    {
        $response = $this->getJson('/weather/search?q=Surabaya');

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'name' => 'Surabaya',
            'province' => 'Jawa Timur',
        ]);
    }

    /**
     * Test weather API endpoint returns structured JSON data.
     */
    public function test_weather_api_endpoint_returns_data(): void
    {
        Http::fake([
            'https://api.bmkg.go.id/publik/prakiraan-cuaca*' => Http::response([
                'lokasi' => [
                    'kotkab' => 'Kota Surabaya',
                    'provinsi' => 'Jawa Timur',
                ],
                'data' => [
                    [
                        'cuaca' => [
                            [
                                [
                                    'datetime' => '2026-10-05T09:00:00Z',
                                    't' => 31,
                                    'tcc' => 40,
                                    'weather_desc' => 'Cerah',
                                    'local_datetime' => '2026-10-05 16:00:00',
                                ],
                            ],
                        ],
                    ],
                ],
            ], 200),
        ]);

        $response = $this->getJson('/weather/api?adm4=35.78.07.1001');

        $response->assertStatus(200);
        $response->assertJsonPath('city.name', 'Surabaya');
        $response->assertJsonPath('weather.current.weather_desc', 'Cerah');
    }
}
