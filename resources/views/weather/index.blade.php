<!DOCTYPE html>
<html lang="id" class="h-full antialiased">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prakiraan Cuaca BMKG • Informasi Cuaca & Gempa Indonesia</title>
    <meta name="description" content="Portal prakiraan cuaca resmi BMKG Indonesia dengan desain bersih, data real-time, prakiraan per jam, 3 hari ke depan, dan pemantauan gempa bumi terkini.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        }
        /* Custom scrollbar for horizontal forecast scroll */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        .custom-scrollbar::-webkit-scrollbar {
            height: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(226, 232, 240, 0.4);
            border-radius: 9999px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(148, 163, 184, 0.5);
            border-radius: 9999px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(100, 116, 139, 0.8);
        }
    </style>
</head>
<body class="min-h-full bg-slate-50/80 text-slate-800 flex flex-col selection:bg-sky-500 selection:text-white">

    <!-- Subtle atmospheric background glow -->
    <div class="fixed inset-0 pointer-events-none -z-10 overflow-hidden">
        <div class="absolute -top-40 -left-40 w-96 h-96 bg-sky-200/40 rounded-full blur-3xl"></div>
        <div class="absolute top-1/4 -right-40 w-96 h-96 bg-blue-100/50 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 left-1/3 w-96 h-96 bg-indigo-100/30 rounded-full blur-3xl"></div>
    </div>

    <!-- Navigation Header -->
    <header class="sticky top-0 z-40 backdrop-blur-xl bg-white/80 border-b border-slate-200/80 transition-all">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 h-18 flex items-center justify-between gap-4">
            
            <!-- Brand Logo -->
            <a href="{{ route('weather.index') }}" class="flex items-center gap-3 group shrink-0">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-sky-500 to-blue-600 flex items-center justify-center text-white shadow-sm shadow-sky-500/20 group-hover:scale-105 transition-transform">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z" />
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-1.5">
                        <span class="font-bold text-lg text-slate-900 tracking-tight leading-tight">Cuaca BMKG</span>
                        <span class="text-[10px] font-semibold tracking-wider uppercase px-1.5 py-0.5 rounded bg-sky-100 text-sky-800 border border-sky-200/60">Live</span>
                    </div>
                    <p class="text-xs text-slate-500 leading-none">Prakiraan Cuaca & Pemantauan Gempa</p>
                </div>
            </a>

            <!-- Right Controls: Live Time & Refresh -->
            <div class="flex items-center gap-3 sm:gap-4">
                <!-- Clock -->
                <div class="hidden sm:flex items-center gap-2 px-3 py-1.5 rounded-full bg-slate-100/80 text-xs font-medium text-slate-600 border border-slate-200/60">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span id="liveClock">Memuat waktu...</span>
                </div>

                <!-- Refresh Button -->
                <a href="{{ request()->fullUrl() }}" 
                   title="Muat Ulang Data"
                   class="p-2 text-slate-500 hover:text-slate-800 hover:bg-slate-100 rounded-xl border border-slate-200/60 transition-colors flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Container -->
    <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex-1 w-full space-y-8">

        <!-- Top Search & Quick City Pills -->
        <section class="space-y-4">
            <div class="relative max-w-2xl mx-auto">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input 
                        type="text" 
                        id="citySearchInput"
                        placeholder="Cari kota, kabupaten, atau provinsi (contoh: Surabaya, Denpasar, Medan)..." 
                        autocomplete="off"
                        class="w-full pl-11 pr-10 py-3.5 bg-white border border-slate-200 rounded-2xl shadow-sm shadow-slate-200/50 text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition-all text-sm sm:text-base font-normal"
                    >
                    <button 
                        type="button" 
                        id="clearSearchBtn"
                        class="hidden absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Autocomplete Dropdown List -->
                <div 
                    id="searchDropdown" 
                    class="hidden absolute top-full left-0 right-0 mt-2 bg-white rounded-2xl border border-slate-200 shadow-xl shadow-slate-200/70 overflow-hidden z-50 max-h-80 overflow-y-auto divide-y divide-slate-100">
                    <!-- Populated dynamically via JS -->
                </div>
            </div>

            <!-- Quick City Pills -->
            <div class="flex items-center gap-2 overflow-x-auto no-scrollbar py-1 text-xs sm:text-sm font-medium">
                <span class="text-slate-400 text-xs shrink-0 font-medium pl-1 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Populer:
                </span>
                @foreach($featuredCities as $pill)
                    @php $isActive = ($pill['adm4'] === ($selectedCity['adm4'] ?? '')); @endphp
                    <a href="{{ route('weather.index', ['adm4' => $pill['adm4']]) }}" 
                       class="shrink-0 px-3.5 py-1.5 rounded-full transition-all flex items-center gap-1.5 {{ $isActive ? 'bg-sky-600 text-white font-semibold shadow-sm shadow-sky-500/30' : 'bg-white text-slate-600 border border-slate-200 hover:border-slate-300 hover:bg-slate-100/60' }}">
                        <span>{{ $pill['name'] }}</span>
                    </a>
                @endforeach
            </div>
        </section>

        <!-- Weather Hero Section -->
        <section class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Main Current Weather Card (Span 2 cols on lg) -->
            <div class="lg:col-span-2 relative overflow-hidden rounded-3xl bg-white/90 backdrop-blur-xl border border-slate-200/90 shadow-sm p-6 sm:p-8 flex flex-col justify-between">
                
                <!-- Card Background Accents -->
                <div class="absolute top-0 right-0 w-80 h-80 bg-gradient-to-bl from-sky-100/70 via-blue-50/40 to-transparent rounded-bl-full pointer-events-none -z-0"></div>

                <div class="relative z-10 space-y-6">
                    
                    <!-- Location & Live Status Header -->
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <div>
                            <div class="flex items-center gap-2">
                                <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">
                                    {{ $weather['lokasi']['kotkab'] ?? $selectedCity['name'] }}
                                </h1>
                                <span class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-slate-100 text-slate-700 border border-slate-200">
                                    {{ $weather['lokasi']['provinsi'] ?? $selectedCity['province'] }}
                                </span>
                            </div>
                            <p class="text-xs sm:text-sm text-slate-500 mt-1 flex items-center gap-1.5">
                                @if(!empty($weather['lokasi']['kecamatan']))
                                    <span>Kecamatan {{ $weather['lokasi']['kecamatan'] }}</span>
                                    <span>•</span>
                                    <span>Desa {{ $weather['lokasi']['desa'] }}</span>
                                    <span>•</span>
                                @endif
                                <span>Zona: {{ $weather['lokasi']['timezone'] ?? 'Asia/Jakarta' }}</span>
                            </p>
                        </div>

                        <div class="flex items-center gap-2 text-xs font-medium text-slate-500 bg-slate-50 px-3 py-1.5 rounded-xl border border-slate-200/80">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span>Diperbarui: {{ $weather['last_synced'] ?? '-' }}</span>
                        </div>
                    </div>

                    <!-- Main Temperature & Big Icon -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 pt-2 pb-4">
                        <div class="flex items-baseline gap-2">
                            <span class="text-6xl sm:text-7xl lg:text-8xl font-black text-slate-900 tracking-tighter">
                                {{ $weather['current']['temp'] ?? 28 }}
                            </span>
                            <span class="text-3xl sm:text-4xl font-light text-slate-400">°C</span>
                        </div>

                        <div class="flex items-center gap-4">
                            <div class="relative w-20 h-20 sm:w-24 sm:h-24 shrink-0 flex items-center justify-center p-2 rounded-2xl bg-sky-50/80 border border-sky-100">
                                @if(!empty($weather['current']['image']))
                                    <img src="{{ $weather['current']['image'] }}" 
                                         alt="{{ $weather['current']['weather_desc'] ?? 'Kondisi Cuaca' }}"
                                         class="w-full h-full object-contain filter drop-shadow-sm transition-transform hover:scale-110"
                                         onerror="this.onerror=null; this.src='https://api-apps.bmkg.go.id/storage/icon/cuaca/cerah%20berawan-am.svg';">
                                @else
                                    <svg class="w-12 h-12 text-sky-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                @endif
                            </div>
                            <div>
                                <h2 class="text-xl sm:text-2xl font-bold text-slate-900">
                                    {{ $weather['current']['weather_desc'] ?? 'Cerah Berawan' }}
                                </h2>
                                <p class="text-sm text-slate-500 font-medium">
                                    {{ $weather['current']['weather_desc_en'] ?? 'Partly Cloudy' }}
                                </p>
                                <div class="mt-1.5 flex items-center gap-1.5 text-xs font-semibold text-sky-700">
                                    <span class="inline-block w-1.5 h-1.5 rounded-full bg-sky-500"></span>
                                    <span>{{ $weather['current']['cloud_status'] ?? 'Kondisi Normal' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 4-Grid Weather Metrics inside Hero -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-4 border-t border-slate-100">
                        
                        <!-- Kelembapan -->
                        <div class="p-3.5 rounded-2xl bg-slate-50/80 border border-slate-100">
                            <div class="flex items-center gap-1.5 text-xs font-medium text-slate-500 mb-1">
                                <svg class="w-4 h-4 text-sky-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                </svg>
                                <span>Kelembapan</span>
                            </div>
                            <div class="flex items-baseline gap-1">
                                <span class="text-xl font-bold text-slate-800">{{ $weather['current']['humidity'] ?? 0 }}%</span>
                            </div>
                            <span class="text-[11px] font-medium text-slate-500">{{ $weather['current']['humidity_status'] ?? 'Normal' }}</span>
                        </div>

                        <!-- Kecepatan Angin -->
                        <div class="p-3.5 rounded-2xl bg-slate-50/80 border border-slate-100">
                            <div class="flex items-center gap-1.5 text-xs font-medium text-slate-500 mb-1">
                                <svg class="w-4 h-4 text-teal-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                                <span>Angin</span>
                            </div>
                            <div class="flex items-baseline gap-1">
                                <span class="text-xl font-bold text-slate-800">{{ $weather['current']['wind_speed'] ?? 0 }}</span>
                                <span class="text-xs text-slate-500">km/h</span>
                            </div>
                            <span class="text-[11px] font-medium text-slate-500 truncate block">Arah: {{ $weather['current']['wind_direction_id'] ?? $weather['current']['wind_direction'] }} ({{ $weather['current']['wind_direction'] }})</span>
                        </div>

                        <!-- Tutupan Awan -->
                        <div class="p-3.5 rounded-2xl bg-slate-50/80 border border-slate-100">
                            <div class="flex items-center gap-1.5 text-xs font-medium text-slate-500 mb-1">
                                <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z" />
                                </svg>
                                <span>Tutupan Awan</span>
                            </div>
                            <div class="flex items-baseline gap-1">
                                <span class="text-xl font-bold text-slate-800">{{ $weather['current']['cloud_cover'] ?? 0 }}%</span>
                            </div>
                            <span class="text-[11px] font-medium text-slate-500 truncate block">{{ $weather['current']['cloud_status'] ?? '-' }}</span>
                        </div>

                        <!-- Curah Hujan -->
                        <div class="p-3.5 rounded-2xl bg-slate-50/80 border border-slate-100">
                            <div class="flex items-center gap-1.5 text-xs font-medium text-slate-500 mb-1">
                                <svg class="w-4 h-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                                </svg>
                                <span>Curah Hujan</span>
                            </div>
                            <div class="flex items-baseline gap-1">
                                <span class="text-xl font-bold text-slate-800">{{ $weather['current']['precipitation'] ?? 0 }}</span>
                                <span class="text-xs text-slate-500">mm</span>
                            </div>
                            <span class="text-[11px] font-medium text-slate-500">Intensitas Presipitasi</span>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Right Column: BMKG Earthquake (Gempa Terkini) Widget -->
            <div class="rounded-3xl bg-white/90 backdrop-blur-xl border border-slate-200/90 shadow-sm p-6 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center font-bold text-sm border border-rose-100">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-900 text-sm">Gempa Bumi Terkini</h3>
                                <p class="text-[11px] text-slate-500">BMKG Real-time TEWS</p>
                            </div>
                        </div>
                        @if($earthquake)
                            <span class="px-2 py-0.5 text-xs font-bold rounded-full {{ $earthquake['is_strong'] ? 'bg-rose-100 text-rose-700 border border-rose-200' : 'bg-amber-100 text-amber-700 border border-amber-200' }}">
                                M {{ $earthquake['magnitude'] }}
                            </span>
                        @endif
                    </div>

                    @if($earthquake)
                        <div class="mt-4 space-y-3.5">
                            <div>
                                <span class="text-[11px] uppercase tracking-wider text-slate-400 font-semibold">Pusat Gempa</span>
                                <p class="text-sm font-semibold text-slate-800 mt-0.5 leading-snug">
                                    {{ $earthquake['wilayah'] }}
                                </p>
                            </div>

                            <div class="grid grid-cols-2 gap-2 text-xs">
                                <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                                    <span class="text-slate-400 block text-[10px] uppercase font-semibold">Kedalaman</span>
                                    <span class="font-bold text-slate-800">{{ $earthquake['kedalaman'] }}</span>
                                </div>
                                <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                                    <span class="text-slate-400 block text-[10px] uppercase font-semibold">Waktu Gempa</span>
                                    <span class="font-bold text-slate-800">{{ $earthquake['jam'] }}</span>
                                </div>
                            </div>

                            <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100 text-xs">
                                <span class="text-slate-400 block text-[10px] uppercase font-semibold">Koordinat & Tanggal</span>
                                <span class="text-slate-700 font-medium">{{ $earthquake['lintang'] }} • {{ $earthquake['bujur'] }} ({{ $earthquake['tanggal'] }})</span>
                            </div>

                            <div class="p-2.5 rounded-xl bg-emerald-50/70 border border-emerald-100 text-xs text-emerald-800">
                                <span class="font-semibold block text-[11px] mb-0.5">Potensi:</span>
                                <span>{{ $earthquake['potensi'] }}</span>
                            </div>
                        </div>
                    @else
                        <div class="py-12 text-center text-slate-400 text-xs">
                            <p>Data gempa belum dapat dimuat saat ini.</p>
                        </div>
                    @endif
                </div>

                @if($earthquake && !empty($earthquake['shakemap_url']))
                    <div class="pt-4 mt-4 border-t border-slate-100">
                        <button 
                            type="button"
                            onclick="openShakemapModal('{{ $earthquake['shakemap_url'] }}', '{{ addslashes($earthquake['wilayah']) }}')"
                            class="w-full py-2 px-3 rounded-xl bg-slate-100 hover:bg-slate-200/80 text-slate-700 text-xs font-semibold flex items-center justify-center gap-1.5 transition-colors">
                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span>Lihat Peta Guncangan (Shakemap)</span>
                        </button>
                    </div>
                @endif

            </div>

        </section>

        <!-- Hourly Forecast Strip (Prakiraan Per 3 Jam) -->
        <section class="space-y-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <h2 class="text-lg font-bold text-slate-900">Prakiraan Waktu Berkala</h2>
                    <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-slate-200/60 text-slate-600">Interval 3 Jam</span>
                </div>
                <span class="text-xs text-slate-400 hidden sm:inline">Geser horizontal untuk melihat rentang waktu selanjutnya →</span>
            </div>

            <div class="flex gap-3 overflow-x-auto custom-scrollbar pb-3 pt-1">
                @forelse($weather['hourly'] as $item)
                    <div class="shrink-0 w-28 sm:w-32 rounded-2xl p-3.5 text-center flex flex-col items-center justify-between gap-2 transition-all {{ $item['is_current'] ? 'bg-gradient-to-b from-sky-50 to-white border-2 border-sky-400 shadow-sm' : 'bg-white border border-slate-200/80 hover:border-slate-300' }}">
                        
                        <div>
                            <span class="text-[11px] font-semibold text-slate-400 block leading-tight">{{ $item['date_short'] }}</span>
                            <span class="text-sm font-bold text-slate-800">{{ $item['time'] }}</span>
                        </div>

                        <div class="w-12 h-12 my-1 flex items-center justify-center">
                            <img src="{{ $item['image'] }}" 
                                 alt="{{ $item['weather_desc'] }}" 
                                 class="w-10 h-10 object-contain"
                                 onerror="this.onerror=null; this.src='https://api-apps.bmkg.go.id/storage/icon/cuaca/cerah%20berawan-am.svg';">
                        </div>

                        <div>
                            <span class="text-lg font-extrabold text-slate-900 block leading-tight">{{ $item['temp'] }}°C</span>
                            <span class="text-[10px] font-medium text-slate-500 line-clamp-1 mt-0.5" title="{{ $item['weather_desc'] }}">{{ $item['weather_desc'] }}</span>
                        </div>

                        <div class="w-full pt-2 border-t border-slate-100 flex items-center justify-between text-[10px] text-slate-400 font-medium">
                            <span>💧 {{ $item['humidity'] }}%</span>
                            <span>💨 {{ $item['wind_speed'] }}</span>
                        </div>

                    </div>
                @empty
                    <div class="w-full py-8 text-center text-slate-400 bg-white rounded-2xl border border-slate-200 text-sm">
                        Data perkiraan per jam tidak tersedia.
                    </div>
                @endforelse
            </div>
        </section>

        <!-- 3-Day Daily Forecast Section -->
        <section class="space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-bold text-slate-900">Prakiraan 3 Hari Ke Depan</h2>
                <span class="text-xs text-slate-500">Analisis Model Numerik Cuaca BMKG</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @forelse($weather['daily'] as $day)
                    <div class="rounded-3xl bg-white border border-slate-200/80 p-5 shadow-sm hover:border-sky-300 transition-all space-y-4 flex flex-col justify-between">
                        
                        <div>
                            <div class="flex items-center justify-between">
                                <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-sky-50 text-sky-700 border border-sky-100">
                                    {{ $day['day_label'] }}
                                </span>
                                <span class="text-xs text-slate-400 font-medium">{{ $day['date_formatted'] }}</span>
                            </div>

                            <div class="flex items-center gap-4 mt-4">
                                <div class="w-14 h-14 rounded-2xl bg-slate-50 p-2 border border-slate-100 shrink-0 flex items-center justify-center">
                                    <img src="{{ $day['rep_image'] }}" 
                                         alt="{{ $day['rep_desc'] }}" 
                                         class="w-full h-full object-contain"
                                         onerror="this.onerror=null; this.src='https://api-apps.bmkg.go.id/storage/icon/cuaca/cerah%20berawan-am.svg';">
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-900 text-base leading-tight">{{ $day['rep_desc'] }}</h4>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="text-sm font-extrabold text-slate-800">{{ $day['max_temp'] }}°C</span>
                                        <span class="text-xs text-slate-400">/</span>
                                        <span class="text-xs font-semibold text-slate-500">{{ $day['min_temp'] }}°C</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Stats Details -->
                        <div class="pt-3 border-t border-slate-100 grid grid-cols-2 gap-2 text-xs">
                            <div class="p-2 rounded-xl bg-slate-50 text-slate-600">
                                <span class="text-[10px] text-slate-400 uppercase font-semibold block">Rata-rata Lembap</span>
                                <span class="font-bold text-slate-800">{{ $day['avg_humidity'] }}%</span>
                            </div>
                            <div class="p-2 rounded-xl bg-slate-50 text-slate-600">
                                <span class="text-[10px] text-slate-400 uppercase font-semibold block">Maks. Angin</span>
                                <span class="font-bold text-slate-800">{{ $day['max_wind'] }} km/h</span>
                            </div>
                        </div>

                    </div>
                @empty
                    <div class="col-span-3 py-8 text-center text-slate-400 bg-white rounded-2xl border border-slate-200 text-sm">
                        Data prakiraan 3 hari tidak tersedia.
                    </div>
                @endforelse
            </div>
        </section>

        <!-- Region Explorer by Island / Category -->
        <section class="rounded-3xl bg-white border border-slate-200/80 p-6 shadow-sm space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <h3 class="font-bold text-slate-900 text-base">Jelajahi Cuaca Kota Lainnya</h3>
                    <p class="text-xs text-slate-500">Pilih dari puluhan kota dan kabupaten di seluruh wilayah Nusantara</p>
                </div>

                <!-- Island Tab Selector -->
                <div class="flex items-center gap-1.5 overflow-x-auto no-scrollbar py-1">
                    <button type="button" onclick="filterIsland('semua')" class="island-tab px-3 py-1 text-xs font-semibold rounded-lg bg-slate-900 text-white transition-colors" data-island="semua">Semua</button>
                    <button type="button" onclick="filterIsland('Jawa')" class="island-tab px-3 py-1 text-xs font-semibold rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 transition-colors" data-island="Jawa">Jawa</button>
                    <button type="button" onclick="filterIsland('Sumatera')" class="island-tab px-3 py-1 text-xs font-semibold rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 transition-colors" data-island="Sumatera">Sumatera</button>
                    <button type="button" onclick="filterIsland('Kalimantan')" class="island-tab px-3 py-1 text-xs font-semibold rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 transition-colors" data-island="Kalimantan">Kalimantan</button>
                    <button type="button" onclick="filterIsland('Sulawesi')" class="island-tab px-3 py-1 text-xs font-semibold rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 transition-colors" data-island="Sulawesi">Sulawesi</button>
                    <button type="button" onclick="filterIsland('Bali & Nusa Tenggara')" class="island-tab px-3 py-1 text-xs font-semibold rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 transition-colors" data-island="Bali & Nusa Tenggara">Bali & Nusa</button>
                    <button type="button" onclick="filterIsland('Maluku & Papua')" class="island-tab px-3 py-1 text-xs font-semibold rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 transition-colors" data-island="Maluku & Papua">Maluku & Papua</button>
                </div>
            </div>

            <!-- Cities Grid -->
            <div id="citiesGrid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-2.5 pt-2">
                @foreach($allCities as $c)
                    @php $isCurr = ($c['adm4'] === ($selectedCity['adm4'] ?? '')); @endphp
                    <a href="{{ route('weather.index', ['adm4' => $c['adm4']]) }}" 
                       class="city-card p-3 rounded-2xl border transition-all text-left group {{ $isCurr ? 'bg-sky-50/70 border-sky-400 ring-2 ring-sky-400/20' : 'bg-slate-50/60 hover:bg-white border-slate-200/80 hover:border-slate-300 hover:shadow-xs' }}"
                       data-island="{{ $c['island'] }}"
                       data-name="{{ mb_strtolower($c['name']) }}"
                       data-province="{{ mb_strtolower($c['province']) }}">
                        <span class="text-xs font-bold text-slate-800 group-hover:text-sky-600 block truncate">{{ $c['name'] }}</span>
                        <span class="text-[10px] text-slate-400 block truncate mt-0.5">{{ $c['province'] }}</span>
                    </a>
                @endforeach
            </div>
        </section>

    </main>

    <!-- Shakemap Modal -->
    <div id="shakemapModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm transition-opacity">
        <div class="relative max-w-lg w-full bg-white rounded-3xl overflow-hidden shadow-2xl border border-slate-200">
            <div class="p-4 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h4 class="font-bold text-slate-900 text-sm">Peta Guncangan Gempa (Shakemap)</h4>
                    <p id="modalShakemapWilayah" class="text-xs text-slate-500"></p>
                </div>
                <button type="button" onclick="closeShakemapModal()" class="p-1.5 text-slate-400 hover:text-slate-700 rounded-lg hover:bg-slate-100">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="p-4 bg-slate-100 flex items-center justify-center min-h-[300px]">
                <img id="modalShakemapImg" src="" alt="Shakemap Gempa BMKG" class="max-w-full h-auto rounded-xl shadow-sm border border-slate-200">
            </div>
            <div class="p-3 bg-white text-center text-xs text-slate-500 border-t border-slate-100">
                Data bersumber langsung dari BMKG Shakemap TEWS
            </div>
        </div>
    </div>

    <!-- Clean Footer -->
    <footer class="mt-auto border-t border-slate-200/80 bg-white/70 backdrop-blur-md">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-500">
            
            <div class="flex items-center gap-3 text-center sm:text-left">
                <div class="w-7 h-7 rounded-lg bg-sky-100 text-sky-700 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-slate-700">Sumber Data Resmi: BMKG (Badan Meteorologi, Klimatologi, dan Geofisika)</p>
                    <p class="text-[11px] text-slate-400 mt-0.5">Data cuaca publik (Kep. Mendagri ADM4) &amp; BMKG InaTEWS Indonesia</p>
                </div>
            </div>

            <div class="flex items-center gap-4 text-slate-400 text-xs">
                <span>Cache TTL: 20 Menit</span>
                <span>•</span>
                <a href="https://data.bmkg.go.id" target="_blank" rel="noopener" class="text-sky-600 hover:underline">Portal Data BMKG</a>
                <span>•</span>
                <a href="{{ route('quotes.index') }}" class="text-slate-500 hover:text-slate-800">Quotes</a>
            </div>

        </div>
    </footer>

    <!-- Client-side Interactive Logic -->
    <script>
        // Live Clock (WIB)
        function updateLiveClock() {
            const now = new Date();
            const options = { 
                weekday: 'short', 
                day: '2-digit', 
                month: 'short', 
                year: 'numeric', 
                hour: '2-digit', 
                minute: '2-digit', 
                second: '2-digit',
                hour12: false,
                timeZone: 'Asia/Jakarta'
            };
            const clockEl = document.getElementById('liveClock');
            if (clockEl) {
                clockEl.textContent = new Intl.DateTimeFormat('id-ID', options).format(now) + ' WIB';
            }
        }
        setInterval(updateLiveClock, 1000);
        updateLiveClock();

        // Shakemap Modal Controls
        function openShakemapModal(url, wilayah) {
            document.getElementById('modalShakemapImg').src = url;
            document.getElementById('modalShakemapWilayah').textContent = wilayah;
            document.getElementById('shakemapModal').classList.remove('hidden');
        }

        function closeShakemapModal() {
            document.getElementById('shakemapModal').classList.add('hidden');
        }

        // Close modal on Escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeShakemapModal();
        });

        // Search Autocomplete
        const searchInput = document.getElementById('citySearchInput');
        const clearBtn = document.getElementById('clearSearchBtn');
        const dropdown = document.getElementById('searchDropdown');
        let searchTimeout = null;

        searchInput.addEventListener('input', function(e) {
            const query = e.target.value.trim();
            if (query.length > 0) {
                clearBtn.classList.remove('hidden');
            } else {
                clearBtn.classList.add('hidden');
                dropdown.classList.add('hidden');
                return;
            }

            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                fetch(`{{ route('weather.search') }}?q=${encodeURIComponent(query)}`)
                    .then(res => res.json())
                    .then(data => {
                        dropdown.innerHTML = '';
                        if (data.length === 0) {
                            dropdown.innerHTML = `<div class="p-4 text-center text-xs text-slate-400">Tidak ada kota yang cocok dengan "${query}"</div>`;
                            dropdown.classList.remove('hidden');
                            return;
                        }

                        data.forEach(item => {
                            const btn = document.createElement('a');
                            btn.href = `{{ route('weather.index') }}?adm4=${item.adm4}`;
                            btn.className = 'flex items-center justify-between px-4 py-3 hover:bg-slate-50 transition-colors text-left';
                            btn.innerHTML = `
                                <div>
                                    <span class="text-sm font-semibold text-slate-800 block">${item.name}</span>
                                    <span class="text-xs text-slate-400 block">${item.province} • ${item.description || item.island}</span>
                                </div>
                                <span class="text-xs text-sky-600 font-medium group-hover:translate-x-1 transition-transform">Pilih →</span>
                            `;
                            dropdown.appendChild(btn);
                        });
                        dropdown.classList.remove('hidden');
                    })
                    .catch(() => {
                        dropdown.classList.add('hidden');
                    });
            }, 250);
        });

        clearBtn.addEventListener('click', function() {
            searchInput.value = '';
            clearBtn.classList.add('hidden');
            dropdown.classList.add('hidden');
            searchInput.focus();
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });

        // Filter Island Tab
        function filterIsland(island) {
            // Update active tab styles
            document.querySelectorAll('.island-tab').forEach(tab => {
                if (tab.getAttribute('data-island') === island) {
                    tab.classList.remove('bg-slate-100', 'text-slate-600');
                    tab.classList.add('bg-slate-900', 'text-white');
                } else {
                    tab.classList.remove('bg-slate-900', 'text-white');
                    tab.classList.add('bg-slate-100', 'text-slate-600');
                }
            });

            // Filter cards
            document.querySelectorAll('.city-card').forEach(card => {
                if (island === 'semua' || card.getAttribute('data-island') === island) {
                    card.classList.remove('hidden');
                } else {
                    card.classList.add('hidden');
                }
            });
        }
    </script>
</body>
</html>
