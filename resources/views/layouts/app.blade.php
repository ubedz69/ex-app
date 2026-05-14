<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('title', 'Rai Raka Express')</title>
    <meta name="description" content="@yield('meta_description', 'Cepat cek nomor resi DHL & FedEx. Pelacakan cepat, aman, mudah.')">
    <meta name="keywords" content="@yield('meta_keywords', '')">
    <link rel="canonical" href="{{ url()->current() }}">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ asset('images/favicon.svg') }}" type="image/svg+xml">
    <link rel="stylesheet" href="{{ asset('css/brand.css') }}">
    @stack('head')
</head>
<body>
    <a href="#main-content" class="skip-link">Lanjut ke konten</a>
    <div id="announcements" aria-live="polite" class="sr-only" aria-atomic="true"></div>
    <header class="site-header">
        <div class="topbar" style="background:transparent;border-bottom:1px solid rgba(15,23,42,0.03);">
            <div class="container header-inner">
                <div class="top-left">
                    <small>Pilih negara / bahasa</small>
                </div>
                <div class="top-right">
                    <a href="{{ url('/contact') }}" class="btn btn-ghost">Help & Support</a>
                </div>
            </div>
        </div>

        <div class="container header-inner" style="padding-top:18px;padding-bottom:18px;">
<a href="/" class="logo-link"><img src="{{ asset('images/logo-compact.png') }}" alt="Rai Raka Express" class="logo-img"></a>

            <button id="menu-button" aria-controls="main-nav" aria-expanded="false" class="btn btn-ghost" aria-label="Toggle navigation">Menu</button>

            <nav id="main-nav" class="main-nav" role="navigation" aria-label="Primary navigation">
                <a href="{{ url('/about') }}">About</a>
                <a href="{{ url('/services') }}">Services</a>
                <a href="{{ url('/tracking') }}">Tracking</a>
                <a href="{{ url('/locations') }}">Locations</a>
                <a href="{{ url('/contact') }}">Contact</a>
            </nav>

            <div class="header-actions" style="display:flex;align-items:center;gap:12px">
                <form action="{{ url('/tracking') }}" method="GET" class="inline-track-form" style="display:flex;gap:8px;align-items:center" role="search" aria-label="Search tracking">
                    <label for="header-tracking" class="sr-only">Nomor resi</label>
                    <input id="header-tracking" type="search" name="tracking_number" placeholder="Masukkan nomor AWB" class="input" style="width:220px;padding:8px 10px;" aria-describedby="header-track-help" maxlength="12" pattern="(\d{10}|\d{12})" inputmode="numeric" title="Masukkan 10 atau 12 nomor AWB">
                    <span id="header-track-help" class="sr-only">Masukkan nomor AWB lalu tekan Track</span>
                    <button type="submit" class="btn" style="padding:8px 12px">Track</button>
                </form>

                <a href="{{ url('/services') }}" class="btn btn-outline">Get a Quote</a>
            </div>
        </div>
    </header>

    <main id="main-content" class="site-main" role="main" tabindex="-1">
        @yield('content')
    </main>

    <footer class="site-footer">
        <div class="container footer-inner">
<small>&copy; {{ date('Y') }} Rai Raka Express — Logistics & Ekspedisi</small>
        </div>
    </footer>

    <script src="{{ asset('js/accessibility.js') }}" defer></script>
    @stack('scripts')
</body>
</html>
