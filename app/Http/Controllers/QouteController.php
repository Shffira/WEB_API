<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class QouteController extends Controller
{
    public function index()
    {
        $quotes = Cache::remember('quotes.list', 3600, function () {
            $res = Http::timeout(10)->get('https://dummyjson.com/quotes', ['limit' => 30]);
            return $res->successful() ? $res->json()['quotes'] : [];
        });

        $featured = $quotes ? $quotes[array_rand($quotes)] : null;

        return view('quotes.index', compact('quotes', 'featured'));
    }
}
