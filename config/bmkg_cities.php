<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Selected City
    |--------------------------------------------------------------------------
    */
    'default_adm4' => '31.71.01.1001', // Jakarta Pusat (Gambir)

    /*
    |--------------------------------------------------------------------------
    | Featured Quick Selector Cities
    |--------------------------------------------------------------------------
    */
    'featured' => [
        '31.71.01.1001', // Jakarta Pusat
        '35.78.07.1001', // Surabaya
        '32.73.08.1001', // Bandung
        '51.71.01.1001', // Denpasar (Bali)
        '34.71.04.1001', // Yogyakarta
        '12.71.04.1001', // Medan
        '73.71.04.1001', // Makassar
        '33.74.05.1001', // Semarang
        '64.71.01.1001', // Balikpapan
        '64.09.01.1001', // IKN Nusantara
        '53.15.01.2001', // Labuan Bajo
        '35.73.01.1001', // Malang
    ],

    /*
    |--------------------------------------------------------------------------
    | Curated List of Indonesian Cities & Regions
    |--------------------------------------------------------------------------
    */
    'cities' => [
        // DKI Jakarta
        [
            'adm4' => '31.71.01.1001',
            'name' => 'Jakarta Pusat',
            'province' => 'DKI Jakarta',
            'island' => 'Jawa',
            'description' => 'Ibu Kota & Pusat Pemerintahan',
            'popular' => true,
        ],
        [
            'adm4' => '31.74.04.1001',
            'name' => 'Jakarta Selatan',
            'province' => 'DKI Jakarta',
            'island' => 'Jawa',
            'description' => 'Kebayoran Baru',
            'popular' => false,
        ],
        [
            'adm4' => '31.73.02.1001',
            'name' => 'Jakarta Barat',
            'province' => 'DKI Jakarta',
            'island' => 'Jawa',
            'description' => 'Grogol Petamburan',
            'popular' => false,
        ],
        [
            'adm4' => '31.75.03.1001',
            'name' => 'Jakarta Timur',
            'province' => 'DKI Jakarta',
            'island' => 'Jawa',
            'description' => 'Jatinegara',
            'popular' => false,
        ],
        [
            'adm4' => '31.72.02.1001',
            'name' => 'Jakarta Utara',
            'province' => 'DKI Jakarta',
            'island' => 'Jawa',
            'description' => 'Tanjung Priok',
            'popular' => false,
        ],

        // Jawa Barat
        [
            'adm4' => '32.73.08.1001',
            'name' => 'Bandung',
            'province' => 'Jawa Barat',
            'island' => 'Jawa',
            'description' => 'Kota Kembang',
            'popular' => true,
        ],
        [
            'adm4' => '32.71.01.1001',
            'name' => 'Bogor',
            'province' => 'Jawa Barat',
            'island' => 'Jawa',
            'description' => 'Kota Hujan',
            'popular' => true,
        ],
        [
            'adm4' => '32.75.03.1001',
            'name' => 'Bekasi',
            'province' => 'Jawa Barat',
            'island' => 'Jawa',
            'description' => 'Kota Patriot',
            'popular' => false,
        ],
        [
            'adm4' => '32.76.01.1001',
            'name' => 'Depok',
            'province' => 'Jawa Barat',
            'island' => 'Jawa',
            'description' => 'Pancoran Mas',
            'popular' => false,
        ],
        [
            'adm4' => '32.74.01.1001',
            'name' => 'Cirebon',
            'province' => 'Jawa Barat',
            'island' => 'Jawa',
            'description' => 'Kota Udang',
            'popular' => false,
        ],
        [
            'adm4' => '32.78.01.1001',
            'name' => 'Tasikmalaya',
            'province' => 'Jawa Barat',
            'island' => 'Jawa',
            'description' => 'Priangan Timur',
            'popular' => false,
        ],

        // Banten
        [
            'adm4' => '36.73.01.1001',
            'name' => 'Serang',
            'province' => 'Banten',
            'island' => 'Jawa',
            'description' => 'Ibu Kota Banten',
            'popular' => false,
        ],
        [
            'adm4' => '36.74.01.1001',
            'name' => 'Tangerang Selatan',
            'province' => 'Banten',
            'island' => 'Jawa',
            'description' => 'Serpong & BSD',
            'popular' => false,
        ],

        // Jawa Tengah
        [
            'adm4' => '33.74.05.1001',
            'name' => 'Semarang',
            'province' => 'Jawa Tengah',
            'island' => 'Jawa',
            'description' => 'Kota Lumpia',
            'popular' => true,
        ],
        [
            'adm4' => '33.72.01.1001',
            'name' => 'Surakarta (Solo)',
            'province' => 'Jawa Tengah',
            'island' => 'Jawa',
            'description' => 'Kota Budaya Solo',
            'popular' => true,
        ],

        // DI Yogyakarta
        [
            'adm4' => '34.71.04.1001',
            'name' => 'Yogyakarta',
            'province' => 'DI Yogyakarta',
            'island' => 'Jawa',
            'description' => 'Kota Pelajar & Budaya',
            'popular' => true,
        ],
        [
            'adm4' => '34.04.07.2001',
            'name' => 'Sleman',
            'province' => 'DI Yogyakarta',
            'island' => 'Jawa',
            'description' => 'Kawasan Kaliurang & Kampus',
            'popular' => false,
        ],

        // Jawa Timur
        [
            'adm4' => '35.78.07.1001',
            'name' => 'Surabaya',
            'province' => 'Jawa Timur',
            'island' => 'Jawa',
            'description' => 'Kota Pahlawan',
            'popular' => true,
        ],
        [
            'adm4' => '35.73.01.1001',
            'name' => 'Malang',
            'province' => 'Jawa Timur',
            'island' => 'Jawa',
            'description' => 'Kota Wisata & Pendidikan',
            'popular' => true,
        ],
        [
            'adm4' => '35.79.01.1001',
            'name' => 'Batu',
            'province' => 'Jawa Timur',
            'island' => 'Jawa',
            'description' => 'Kota Apel & Pegunungan',
            'popular' => false,
        ],
        [
            'adm4' => '35.71.01.1001',
            'name' => 'Kediri',
            'province' => 'Jawa Timur',
            'island' => 'Jawa',
            'description' => 'Kota Tahu Kediri',
            'popular' => false,
        ],

        // Bali & Nusa Tenggara
        [
            'adm4' => '51.71.01.1001',
            'name' => 'Denpasar (Bali)',
            'province' => 'Bali',
            'island' => 'Bali & Nusa Tenggara',
            'description' => 'Pusat Wisata Pulau Dewata',
            'popular' => true,
        ],
        [
            'adm4' => '52.71.02.1001',
            'name' => 'Mataram (Lombok)',
            'province' => 'Nusa Tenggara Barat',
            'island' => 'Bali & Nusa Tenggara',
            'description' => 'Pulau Lombok',
            'popular' => true,
        ],
        [
            'adm4' => '53.71.03.1001',
            'name' => 'Kupang',
            'province' => 'Nusa Tenggara Timur',
            'island' => 'Bali & Nusa Tenggara',
            'description' => 'Kota Kasih Timor',
            'popular' => false,
        ],
        [
            'adm4' => '53.15.01.2001',
            'name' => 'Labuan Bajo',
            'province' => 'Nusa Tenggara Timur',
            'island' => 'Bali & Nusa Tenggara',
            'description' => 'Pintu Gerbang Komodo',
            'popular' => true,
        ],

        // Sumatera
        [
            'adm4' => '11.71.01.2001',
            'name' => 'Banda Aceh',
            'province' => 'Aceh',
            'island' => 'Sumatera',
            'description' => 'Serambi Mekkah',
            'popular' => true,
        ],
        [
            'adm4' => '12.71.04.1001',
            'name' => 'Medan',
            'province' => 'Sumatera Utara',
            'island' => 'Sumatera',
            'description' => 'Metropolitan Sumatera Utara',
            'popular' => true,
        ],
        [
            'adm4' => '13.71.02.1001',
            'name' => 'Padang',
            'province' => 'Sumatera Barat',
            'island' => 'Sumatera',
            'description' => 'Ranah Minang',
            'popular' => true,
        ],
        [
            'adm4' => '14.71.01.1002',
            'name' => 'Pekanbaru',
            'province' => 'Riau',
            'island' => 'Sumatera',
            'description' => 'Kota Bertuah',
            'popular' => false,
        ],
        [
            'adm4' => '21.71.10.1001',
            'name' => 'Batam',
            'province' => 'Kepulauan Riau',
            'island' => 'Sumatera',
            'description' => 'Kawasan Perdagangan Bebas',
            'popular' => true,
        ],
        [
            'adm4' => '21.72.01.1001',
            'name' => 'Tanjung Pinang',
            'province' => 'Kepulauan Riau',
            'island' => 'Sumatera',
            'description' => 'Pulau Bintan',
            'popular' => false,
        ],
        [
            'adm4' => '16.71.01.1001',
            'name' => 'Palembang',
            'province' => 'Sumatera Selatan',
            'island' => 'Sumatera',
            'description' => 'Kota Pempek & Sungai Musi',
            'popular' => true,
        ],
        [
            'adm4' => '17.71.01.1001',
            'name' => 'Bengkulu',
            'province' => 'Bengkulu',
            'island' => 'Sumatera',
            'description' => 'Bumi Rafflesia',
            'popular' => false,
        ],
        [
            'adm4' => '19.71.02.1001',
            'name' => 'Pangkal Pinang',
            'province' => 'Bangka Belitung',
            'island' => 'Sumatera',
            'description' => 'Pulau Bangka',
            'popular' => false,
        ],

        // Kalimantan
        [
            'adm4' => '61.71.05.1001',
            'name' => 'Pontianak',
            'province' => 'Kalimantan Barat',
            'island' => 'Kalimantan',
            'description' => 'Kota Khatulistiwa',
            'popular' => true,
        ],
        [
            'adm4' => '62.71.01.1001',
            'name' => 'Palangka Raya',
            'province' => 'Kalimantan Tengah',
            'island' => 'Kalimantan',
            'description' => 'Kota Cantik Kahayan',
            'popular' => false,
        ],
        [
            'adm4' => '63.71.03.1001',
            'name' => 'Banjarmasin',
            'province' => 'Kalimantan Selatan',
            'island' => 'Kalimantan',
            'description' => 'Kota Seribu Sungai',
            'popular' => true,
        ],
        [
            'adm4' => '64.71.01.1001',
            'name' => 'Balikpapan',
            'province' => 'Kalimantan Timur',
            'island' => 'Kalimantan',
            'description' => 'Kota Minyak Beriman',
            'popular' => true,
        ],
        [
            'adm4' => '64.72.01.1001',
            'name' => 'Samarinda',
            'province' => 'Kalimantan Timur',
            'island' => 'Kalimantan',
            'description' => 'Kota Tepian Mahakam',
            'popular' => false,
        ],
        [
            'adm4' => '64.09.01.1001',
            'name' => 'IKN Nusantara',
            'province' => 'Kalimantan Timur',
            'island' => 'Kalimantan',
            'description' => 'Ibu Kota Nusantara (Penajam)',
            'popular' => true,
        ],
        [
            'adm4' => '65.71.01.1001',
            'name' => 'Tarakan',
            'province' => 'Kalimantan Utara',
            'island' => 'Kalimantan',
            'description' => 'Bumi Paguntaka',
            'popular' => false,
        ],

        // Sulawesi
        [
            'adm4' => '73.71.04.1001',
            'name' => 'Makassar',
            'province' => 'Sulawesi Selatan',
            'island' => 'Sulawesi',
            'description' => 'Kota Daeng & Anging Mammiri',
            'popular' => true,
        ],
        [
            'adm4' => '71.71.01.1001',
            'name' => 'Manado',
            'province' => 'Sulawesi Utara',
            'island' => 'Sulawesi',
            'description' => 'Kota Tinutuan & Bunaken',
            'popular' => true,
        ],
        [
            'adm4' => '72.71.02.1002',
            'name' => 'Palu',
            'province' => 'Sulawesi Tengah',
            'island' => 'Sulawesi',
            'description' => 'Teluk Palu',
            'popular' => false,
        ],
        [
            'adm4' => '74.71.03.1001',
            'name' => 'Kendari',
            'province' => 'Sulawesi Tenggara',
            'island' => 'Sulawesi',
            'description' => 'Kota Lulo',
            'popular' => false,
        ],
        [
            'adm4' => '75.71.01.1001',
            'name' => 'Gorontalo',
            'province' => 'Gorontalo',
            'island' => 'Sulawesi',
            'description' => 'Serambi Madinah Sulawesi',
            'popular' => false,
        ],
        [
            'adm4' => '76.02.01.1002',
            'name' => 'Mamuju',
            'province' => 'Sulawesi Barat',
            'island' => 'Sulawesi',
            'description' => 'Ibu Kota Sulawesi Barat',
            'popular' => false,
        ],

        // Maluku & Papua
        [
            'adm4' => '81.71.01.2001',
            'name' => 'Ambon',
            'province' => 'Maluku',
            'island' => 'Maluku & Papua',
            'description' => 'Ambon Manise',
            'popular' => true,
        ],
        [
            'adm4' => '82.71.01.1001',
            'name' => 'Ternate',
            'province' => 'Maluku Utara',
            'island' => 'Maluku & Papua',
            'description' => 'Pulau Rempah & Gunung Gamalama',
            'popular' => false,
        ],
        [
            'adm4' => '91.71.01.1001',
            'name' => 'Jayapura',
            'province' => 'Papua',
            'island' => 'Maluku & Papua',
            'description' => 'Port Numbay & Teluk Youtefa',
            'popular' => true,
        ],
        [
            'adm4' => '92.02.03.2001',
            'name' => 'Manokwari',
            'province' => 'Papua Barat',
            'island' => 'Maluku & Papua',
            'description' => 'Kota Injil Papua Barat',
            'popular' => false,
        ],
    ],
];
