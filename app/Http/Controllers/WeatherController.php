<?php

namespace App\Http\Controllers;

use App\Services\BmkgWeatherService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WeatherController extends Controller
{
    /**
     * WeatherController constructor with promoted property.
     */
    public function __construct(
        protected BmkgWeatherService $weatherService
    ) {}

    /**
     * Display the main clean weather dashboard.
     */
    public function index(Request $request): View
    {
        $defaultAdm4 = config('bmkg_cities.default_adm4', '31.71.01.1001');
        $adm4 = $request->query('adm4', $defaultAdm4);

        $allCities = config('bmkg_cities.cities', []);
        $featuredCodes = config('bmkg_cities.featured', []);

        // Find active city metadata
        $selectedCity = collect($allCities)->firstWhere('adm4', $adm4);
        if (! $selectedCity) {
            $selectedCity = [
                'adm4' => $adm4,
                'name' => 'Lokasi Terpilih',
                'province' => 'Indonesia',
                'island' => 'Indonesia',
                'description' => 'Wilayah BMKG',
                'popular' => false,
            ];
        }

        // Prepare featured city pill list
        $featuredCities = collect($allCities)
            ->filter(fn ($city) => in_array($city['adm4'], $featuredCodes))
            ->values()
            ->all();

        // Fetch weather and earthquake data
        $weather = $this->weatherService->getWeather($adm4);
        $earthquake = $this->weatherService->getLatestEarthquake();

        return view('weather.index', [
            'weather' => $weather,
            'earthquake' => $earthquake,
            'selectedCity' => $selectedCity,
            'featuredCities' => $featuredCities,
            'allCities' => $allCities,
        ]);
    }

    /**
     * Search cities endpoint for instant client-side autocomplete.
     */
    public function search(Request $request): JsonResponse
    {
        $query = mb_strtolower(trim((string) $request->query('q', '')));
        $allCities = config('bmkg_cities.cities', []);

        if (empty($query)) {
            return response()->json(array_slice($allCities, 0, 10));
        }

        $filtered = collect($allCities)->filter(function ($city) use ($query) {
            return str_contains(mb_strtolower($city['name']), $query)
                || str_contains(mb_strtolower($city['province']), $query)
                || str_contains(mb_strtolower($city['island']), $query)
                || str_contains(mb_strtolower($city['description'] ?? ''), $query);
        })->values()->take(12)->all();

        return response()->json($filtered);
    }

    /**
     * JSON data endpoint for seamless AJAX weather updates without reloading page.
     */
    public function apiData(Request $request): JsonResponse
    {
        $defaultAdm4 = config('bmkg_cities.default_adm4', '31.71.01.1001');
        $adm4 = $request->query('adm4', $defaultAdm4);

        $allCities = config('bmkg_cities.cities', []);
        $selectedCity = collect($allCities)->firstWhere('adm4', $adm4);

        $weather = $this->weatherService->getWeather($adm4);

        return response()->json([
            'weather' => $weather,
            'city' => $selectedCity,
        ]);
    }
}
