<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Quotes & News')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,600;0,9..144,700;1,9..144,400&family=Figtree:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --navy: #0f2a43; --navy-soft: #bcd0e2; --brass: #c8963e;
            --paper: #f6f7f9; --card: #fff; --ink: #14202e; --muted: #637183; --line: #e2e6eb;
        }
        * { box-sizing: border-box; }
        body { margin: 0; background: var(--paper); color: var(--ink); font-family: "Figtree", system-ui, sans-serif; }
        a { color: inherit; }

        .nav { background: var(--navy); color: #fff; }
        .nav-in { max-width: 1100px; margin: 0 auto; padding: 0 20px; height: 64px; display: flex; align-items: center; justify-content: space-between; }
        .brand { font-family: "Fraunces", Georgia, serif; font-weight: 700; font-size: 1.4rem; text-decoration: none; }
        .brand span { color: var(--brass); }
        .tabs { display: flex; gap: 6px; }
        .tabs a { text-decoration: none; padding: 8px 16px; border-radius: 999px; color: var(--navy-soft); font-weight: 500; }
        .tabs a:hover { color: #fff; }
        .tabs a.active { background: var(--brass); color: var(--navy); }
        .tabs a:focus-visible { outline: 2px solid var(--brass); outline-offset: 2px; }

        .wrap { max-width: 1100px; margin: 0 auto; padding: 40px 20px 80px; }
        h1.page { margin: 0 0 8px; font-family: "Fraunces", Georgia, serif; font-size: clamp(1.8rem, 5vw, 2.6rem); }
        .sub { margin: 0 0 28px; color: var(--muted); }

        .grid { list-style: none; margin: 0; padding: 0; display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 22px; }
        .empty, .error { background: var(--card); border: 1px dashed var(--line); border-radius: 12px; padding: 32px; text-align: center; color: var(--muted); }
        .error { border-color: #d98a8a; color: #8a2d2d; }

        @media (prefers-reduced-motion: no-preference) {
            .lift { transition: border-color .2s, transform .2s; }
            .lift:hover { border-color: var(--brass); transform: translateY(-3px); }
        }
        @media (max-width: 520px) { .nav-in { height: auto; padding: 12px 20px; flex-direction: column; gap: 8px; } }
    </style>
    @stack('styles')
</head>
<body>
    <header class="nav">
        <div class="nav-in">
            <a class="brand" href="{{ route('quotes.index') }}">Quotes<span>&amp;</span>News</a>
            <nav class="tabs" aria-label="Menu utama">
                <a href="{{ route('quotes.index') }}" class="{{ request()->routeIs('quotes.index') ? 'active' : '' }}">Quotes</a>
                <a href="{{ route('news.index') }}" class="{{ request()->routeIs('news.index') ? 'active' : '' }}">Berita</a>
            </nav>
        </div>
    </header>

    <main class="wrap">
        @yield('content')
    </main>
</body>
</html>
