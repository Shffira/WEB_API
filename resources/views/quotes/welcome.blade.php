<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quotes App</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,500;0,9..144,700;1,9..144,400&family=Figtree:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --navy: #0f2a43;
            --navy-soft: #bcd0e2;
            --brass: #c8963e;
            --paper: #f6f7f9;
            --card: #ffffff;
            --ink: #14202e;
            --muted: #637183;
            --line: #e2e6eb;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            background: var(--paper);
            color: var(--ink);
            font-family: "Figtree", system-ui, sans-serif;
            display: grid;
            grid-template-columns: 340px 1fr;
            min-height: 100vh;
        }

        /* Side panel */
        .side {
            background: var(--navy);
            color: #fff;
            padding: 56px 40px;
            position: sticky;
            top: 0;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .side h1 {
            margin: 0 0 16px;
            font-family: "Fraunces", Georgia, serif;
            font-weight: 700;
            font-size: 2.6rem;
            line-height: 1.05;
        }
        .side p { margin: 0; color: var(--navy-soft); max-width: 28ch; }
        .bar { width: 56px; height: 4px; background: var(--brass); margin: 24px 0; }
        .total { color: var(--navy-soft); font-size: .95rem; }
        .total strong { display: block; color: #fff; font-family: "Fraunces", Georgia, serif; font-size: 2.4rem; }

        /* Main */
        .main { padding: 56px 48px 80px; max-width: 840px; }

        .featured {
            margin: 0 0 40px;
            background: var(--navy);
            color: #fff;
            border-radius: 16px;
            border-left: 6px solid var(--brass);
            padding: 36px 36px 28px;
        }
        .featured blockquote {
            margin: 0 0 24px;
            font-family: "Fraunces", Georgia, serif;
            font-style: italic;
            font-size: clamp(1.5rem, 3.5vw, 2.1rem);
            line-height: 1.35;
        }
        .featured .row { display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap; }
        .featured .name { color: var(--brass); font-weight: 600; font-size: 1.05rem; }
        .again {
            color: #fff;
            text-decoration: none;
            border: 1px solid rgba(255,255,255,.35);
            border-radius: 999px;
            padding: 8px 18px;
            font-size: .9rem;
        }
        .again:hover { border-color: var(--brass); }
        .again:focus-visible { outline: 2px solid var(--brass); outline-offset: 3px; }

        .main h2 {
            margin: 0 0 18px;
            font-family: "Fraunces", Georgia, serif;
            font-size: 1.4rem;
        }
        .list { list-style: none; margin: 0; padding: 0; display: grid; gap: 16px; }
        .quote {
            margin: 0;
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: 12px;
            padding: 22px 26px 20px;
        }
        .quote blockquote {
            margin: 0 0 16px;
            font-family: "Fraunces", Georgia, serif;
            font-style: italic;
            font-size: 1.2rem;
            line-height: 1.45;
        }
        .quote figcaption { display: flex; align-items: center; gap: 12px; }
        .mono {
            width: 32px; height: 32px;
            border-radius: 8px;
            background: var(--navy);
            color: var(--brass);
            display: grid; place-items: center;
            font-family: "Fraunces", Georgia, serif;
            font-weight: 700;
            font-size: .9rem;
        }
        .quote .name { font-weight: 600; font-size: .95rem; }

        .empty {
            background: var(--card);
            border: 1px dashed var(--line);
            border-radius: 12px;
            padding: 32px;
            text-align: center;
            color: var(--muted);
        }

        @media (prefers-reduced-motion: no-preference) {
            .quote { transition: border-color .2s; }
            .quote:hover { border-color: var(--brass); }
        }
        @media (max-width: 860px) {
            body { grid-template-columns: 1fr; }
            .side { position: static; height: auto; padding: 40px 24px; gap: 24px; }
            .main { padding: 32px 20px 64px; }
            .featured { padding: 28px 22px 22px; }
        }
    </style>
</head>
<body>
    <aside class="side">
        <div>
            <h1>Quotes</h1>
            <div class="bar"></div>
            <p>Kutipan pilihan dari orang-orang yang layak didengar.</p>
        </div>
        <div class="total">
            <strong>{{ count($quotes) }}</strong>
            kutipan ditampilkan
        </div>
    </aside>

    <main class="main">
        @if ($featured)
            <figure class="featured">
                <blockquote>{{ $featured['quote'] }}</blockquote>
                <figcaption class="row">
                    <span class="name">{{ $featured['author'] ?? 'Anonim' }}</span>
                    <a class="again" href="{{ url('/') }}">Kutipan acak lainnya</a>
                </figcaption>
            </figure>

            <h2>Semua kutipan</h2>
            <ul class="list">
                @foreach ($quotes as $q)
                    <li>
                        <figure class="quote">
                            <blockquote>{{ $q['quote'] }}</blockquote>
                            <figcaption>
                                <span class="mono" aria-hidden="true">{{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($q['author'] ?? '?', 0, 1)) }}</span>
                                <span class="name">{{ $q['author'] ?? 'Anonim' }}</span>
                            </figcaption>
                        </figure>
                    </li>
                @endforeach
            </ul>
        @else
            <div class="empty">Kutipan gagal dimuat. Periksa koneksi internet lalu muat ulang halaman.</div>
        @endif
    </main>
</body>
</html>