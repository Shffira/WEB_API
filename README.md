# 🌤️ Cuaca BMKG - Portal Informasi Cuaca & Gempa Indonesia

Aplikasi web modern berbasis **Laravel 12** dan **Tailwind CSS v4** yang menyajikan data prakiraan cuaca dan pemantauan gempa bumi terkini secara *real-time* langsung dari API resmi **Badan Meteorologi, Klimatologi, dan Geofisika (BMKG)** Indonesia dengan tampilan antarmuka yang bersih (*clean design*), elegan, dan responsif.

---

## 📸 Fitur Utama

- **Prakiraan Cuaca Real-Time**:
  - Menampilkan suhu udara aktual (°C), deskripsi kondisi cuaca dwibahasa (Indonesia & Inggris), serta ikon cuaca SVG resmi dari BMKG.
  - Metrik cuaca komprehensif: kelembapan udara (%), kecepatan dan arah mata angin (km/h), tutupan awan (%), dan intensitas curah hujan (mm).
- **Prakiraan Waktu Berkala (Per 3 Jam)**:
  - Garis waktu horizontal (*scrollable hourly strip*) yang menampilkan proyeksi cuaca setiap 3 jam ke depan.
- **Prakiraan 3 Hari ke Depan (Daily Outlook)**:
  - Analisis rentang suhu minimum/maksimum, rata-rata kelembapan, dan kecepatan angin puncak harian.
- **Pemantauan Gempa Bumi Terkini (BMKG InaTEWS)**:
  - Informasi gempa terbaru: magnitudo, waktu, kedalaman, koordinat, wilayah episentrum, dan potensi tsunami.
  - Modal interaktif untuk melihat gambar **Peta Guncangan (*Shakemap*)** resmi BMKG.
- **Pencarian Cepat & Direktori Wilayah Nusantara**:
  - Kolom pencarian dengan saran otomatis (*instant autocomplete*) mencakup puluhan kota/kabupaten di seluruh 38 provinsi.
  - Filter cepat berdasarkan pulau/kawasan: *Jawa, Sumatera, Kalimantan, Sulawesi, Bali & Nusa Tenggara, Maluku & Papua*.
  - Tombol pintas (*Quick Pills*) untuk kota-kota populer (Jakarta, Surabaya, Bandung, Denpasar, Medan, Makassar, IKN Nusantara, Labuan Bajo, dll.).
- **Performa Tinggi & Smart Caching**:
  - Dilengkapi *caching layer* (20 menit untuk cuaca, 5 menit untuk gempa) guna menjaga kecepatan akses serta mematuhi batas *rate limit* BMKG (maksimal 60 permintaan/menit).

---

## 🛠️ Teknologi yang Digunakan

- **Backend**: [Laravel 12](https://laravel.com) (PHP 8.5+)
- **Frontend**: Blade Templating, [Tailwind CSS v4](https://tailwindcss.com), Vanilla JavaScript
- **Tipografi**: [Plus Jakarta Sans](https://fonts.google.com/specimen/Plus+Jakarta+Sans)
- **Sumber Data API**:
  - Cuaca: `https://api.bmkg.go.id/publik/prakiraan-cuaca?adm4={kode_wilayah}`
  - Gempa: `https://data.bmkg.go.id/DataMKG/TEWS/autogempa.json`

---

## 🚀 Panduan Instalasi & Menjalankan

### Prasyarat
Pastikan sistem Anda telah terpasang:
- **PHP** >= 8.2 (disarankan PHP 8.5)
- **Composer** >= 2.0
- **Node.js** >= 18.0 & **npm**
- Web Server / Environment lokal seperti **Laragon**, **XAMPP**, atau PHP Built-in Server

### Langkah-langkah

1. **Clone atau Buka Direktori Proyek**
   ```bash
   cd c:/laragon/www/Json
   ```

2. **Instal Dependensi PHP**
   ```bash
   composer install
   ```

3. **Konfigurasi Environment**
   Salin file `.env.example` ke `.env` (jika belum ada) dan buat application key:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Jalankan Migrasi Database**
   Pastikan database telah dibuat di MySQL, lalu jalankan:
   ```bash
   php artisan migrate
   ```

5. **Instal Dependensi Frontend & Kompilasi Aset**
   ```bash
   npm install
   npm run build
   ```

6. **Jalankan Server Aplikasi**
   - Jika menggunakan **Laragon**: akses langsung melalui browser di:
     ```
     http://localhost/Json/public/
     ```
     atau via virtual host:
     ```
     http://json.test/
     ```
   - Atau menggunakan server artisan bawaan:
     ```bash
     php artisan serve
     ```
     lalu buka `http://127.0.0.1:8000` di browser.

---

## 📂 Struktur Berkas Penting

```
Json/
├── app/
│   ├── Http/Controllers/
│   │   └── WeatherController.php       # Controller logika dashboard, search, & API
│   └── Services/
│       └── BmkgWeatherService.php      # Service layer integrasi data BMKG & caching
├── config/
│   └── bmkg_cities.php                 # Katalog kota, provinsi, dan kode ADM4 resmi
├── resources/
│   ├── css/
│   │   └── app.css                     # Tailwind CSS v4 styling & konfigurasi source
│   └── views/
│       └── weather/
│           └── index.blade.php         # Tampilan antarmuka utama clean dashboard
├── routes/
│   └── web.php                         # Definisi rute web & endpoint pencarian
└── tests/
    └── Feature/
        └── WeatherTest.php             # Pengujian otomatis fitur cuaca (PHPUnit)
```

---

## 🧪 Menjalankan Pengujian (Testing)

Proyek ini telah dilengkapi dengan pengujian fitur menggunakan **PHPUnit**:

```bash
php artisan test --compact
```

---

## ⚖️ Sumber Data & Hak Cipta

- Seluruh data meteorologi, klimatologi, dan geofisika bersumber dari **[Badan Meteorologi, Klimatologi, dan Geofisika (BMKG)](https://data.bmkg.go.id)** Republik Indonesia.
- Hak cipta data milik BMKG. Penggunaan data mengacu pada ketentuan data terbuka publik BMKG.

---

## 📄 Lisensi

Aplikasi ini dilisensikan di bawah lisensi terbuka [MIT License](LICENSE).
