<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-JWVN3MHW1T"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'G-JWVN3MHW1T');
    </script>

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
                <div class="top-right" style="display:flex;align-items:center;justify-content:flex-end;gap:10px;">
                    <div aria-label="Social links" style="display:flex;gap:10px;align-items:center;justify-content:flex-end;">
<a href="https://wa.me/6285121112486" target="_blank" rel="noopener noreferrer"
                           class="btn btn-outline"
                           aria-label="WhatsApp"
                           style="display:inline-flex;align-items:center;justify-content:center;padding:8px 10px;border-radius:12px;min-height:44px;min-width:44px;white-space:nowrap;border:1px solid rgba(2,6,23,0.08);text-decoration:none;background:transparent;color:#000;">
<img aria-hidden="true" src="{{ asset('images/icon-wa.png') }}" alt="" width="26" height="26" style="display:block;border-radius:8px;">
                        </a>

                        <a href="https://facebook.com/rairakaexpress" target="_blank" rel="noopener noreferrer"
                           class="btn btn-outline"
                           aria-label="Facebook"
                           style="display:inline-flex;align-items:center;justify-content:center;padding:8px 10px;border-radius:12px;min-height:44px;min-width:44px;white-space:nowrap;border:1px solid rgba(2,6,23,0.08);text-decoration:none;background:transparent;color:#000;">
                            <img aria-hidden="true" src="{{ asset('images/icon-fb.svg') }}" alt="" width="26" height="26" style="display:block;border-radius:8px;">
                        </a>

                        <a href="https://instagram.com/rairaka_express" target="_blank" rel="noopener noreferrer"
                           class="btn btn-outline"
                           aria-label="Instagram"
                           style="display:inline-flex;align-items:center;justify-content:center;padding:8px 10px;border-radius:12px;min-height:44px;min-width:44px;white-space:nowrap;border:1px solid rgba(2,6,23,0.08);text-decoration:none;background:transparent;color:#000;">
                            <img aria-hidden="true" src="{{ asset('images/icon-ig.svg') }}" alt="" width="26" height="26" style="display:block;border-radius:8px;">
                        </a>
                    </div>
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
                <a href="{{ url('/blog') }}">Blog</a>
                <a href="{{ url('/contact') }}">Contact</a>
            </nav>

            <div class="header-actions" style="display:flex;align-items:center;gap:12px">
                    <form action="{{ url('/tracking') }}" method="GET" class="inline-track-form" style="display:flex;gap:8px;align-items:center" role="search" aria-label="Search tracking">
                        <label for="header-tracking" class="sr-only">Nomor resi</label>
                        <input
                            id="header-tracking"
                            type="search"
                            name="tracking_number"
                            placeholder="Masukkan nomor AWB"
                            class="input"
                            style="width:220px;padding:8px 10px;"
                            aria-describedby="header-track-help"
                            maxlength="12"
                            pattern="[0-9]{0,12}"
                            inputmode="numeric"
                            title="Masukkan 10 atau 12 nomor AWB (angka saja)"
                            oninput="this.value=this.value.replace(/[^0-9]/g,'')"
                            onpaste="event.preventDefault(); const t=(event.clipboardData||window.clipboardData).getData('text'); const d=(t||'').replace(/[^0-9]/g,'').slice(0,12); document.getElementById('header-tracking').value=d;"
                        >
                        <span id="header-track-help" class="sr-only">Masukkan nomor AWB lalu tekan Track</span>
                        <button type="submit" class="btn" style="padding:8px 12px">Track</button>
                    </form>

                <a href="{{ url('/services') }}" class="btn btn-outline" style="background:transparent;color:#000;border:1px solid rgba(2,6,23,0.08);text-decoration:none;">Get a Quote</a>
            </div>
        </div>
    </header>

    <main id="main-content" class="site-main" role="main" tabindex="-1">
        @yield('content')
    </main>

    <footer class="site-footer">
        <div class="container footer-inner" style="display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap;">
            <small>&copy; {{ date('Y') }} Rai Raka Express — Logistics & Ekspedisi</small>

        </div>
    </footer>

    <script src="{{ asset('js/accessibility.js') }}" defer></script>
    @stack('scripts')
</body>
</html>
