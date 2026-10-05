<?php

use App\Http\Controllers\QouteController;
use Illuminate\Support\Facades\Route;

// Data produk
$produk = [
    [
        'id' => 1,
        'nama' => 'Buku Satu Sekawan',
        'harga' => 10000,
        'stok' => 5,
        'kategori' => 'Buku',
        'tersedia' => true,
    ],
    [
        'id' => 2,
        'nama' => 'Buku Lima Sekawan',
        'harga' => 50000,
        'stok' => 10,
        'kategori' => 'Buku',
        'tersedia' => false,
    ],
    [
        'id' => 3,
        'nama' => 'Buku Tiga Sekawan',
        'harga' => 30000,
        'stok' => 15,
        'kategori' => 'Buku',
        'tersedia' => true,
    ],
    [
        'id' => 4,
        'nama' => 'Buku Empat Sekawan',
        'harga' => 40000,
        'stok' => 20,
        'kategori' => 'Buku',
        'tersedia' => true,
    ],
    [
        'id' => 5,
        'nama' => 'Buku Lima Sekawan',
        'harga' => 50000,
        'stok' => 0,
        'kategori' => 'Buku',
        'tersedia' => false,
    ],
];

// GET /produk
Route::get('/produk', function () use ($produk) {
    return response()->json($produk);
});

// GET /produk/{id}
Route::get('/produk/{id}', function ($id) use ($produk) {

    foreach ($produk as $item) {
        if ($item['id'] == $id) {
            return response()->json($item);
        }
    }

    return response()->json([
        'message' => 'Produk tidak ditemukan',
    ], 404);
});

use App\Http\Controllers\WeatherController;

Route::get('/', [WeatherController::class, 'index'])->name('weather.index');
Route::get('/weather/search', [WeatherController::class, 'search'])->name('weather.search');
Route::get('/weather/api', [WeatherController::class, 'apiData'])->name('weather.api');

Route::get('/qoutes', [QouteController::class, 'index'])->name('quotes.index');
