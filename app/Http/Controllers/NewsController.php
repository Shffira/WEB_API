<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class NewsController extends Controller
{
    private const PER_PAGE = 12;

    public function index(Request $request)
    {
        $q    = trim($request->query('q', 'tesla')) ?: 'tesla';
        $page = max(1, (int) $request->query('page', 1));

        $data = Cache::remember("news.$q.$page", 600, function () use ($q, $page) {
            $res = Http::timeout(10)->get('https://newsapi.org/v2/everything', [
                'q'        => $q,
                'from'     => now()->subDays(30)->toDateString(),
                'sortBy'   => 'publishedAt',
                'pageSize' => self::PER_PAGE,
                'page'     => $page,
                'apiKey'   => config('services.newsapi.key'),
            ]);

            return $res->json() ?? ['status' => 'error', 'message' => 'Tidak ada respons dari NewsAPI.'];
        });

        // Jangan simpan hasil error di cache
        if (($data['status'] ?? '') !== 'ok') {
            Cache::forget("news.$q.$page");
        }

        $articles = collect($data['articles'] ?? [])
            ->reject(fn ($a) => ($a['title'] ?? '') === '[Removed]')
            ->values();

        // Paket gratis NewsAPI hanya mengizinkan 100 hasil teratas
        $totalPages = (int) min(ceil(($data['totalResults'] ?? 0) / self::PER_PAGE), floor(100 / self::PER_PAGE));

        return view('news.index', [
            'articles'   => $articles,
            'q'          => $q,
            'page'       => $page,
            'totalPages' => $totalPages,
            'error'      => ($data['status'] ?? '') === 'ok' ? null : ($data['message'] ?? 'Berita gagal dimuat.'),
        ]);
    }
}
