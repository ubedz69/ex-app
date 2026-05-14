# Rai Raka Express — Codebase Overview (Explore Report)

## Summary
Project ini adalah aplikasi web Laravel untuk **ekspedisi & pelacakan paket internasional** berbasis halaman Blade, dengan alur utama `GET/POST /tracking` untuk pelacakan **DHL (10 digit)** dan **FedEx (12 digit)**. Saat sesi Explore, saya menemukan beberapa instabilitas akibat kontaminasi/merge-conflict dan kerusakan struktur service/config; akhirnya codebase dipulihkan sampai unit test **PASS**.

## Key Modules
- `routes/web.php` & `routes/tracking.php`: routing untuk endpoint halaman dan tracking.
- `app/Http/Controllers/TrackingController.php`: validasi input, pemilihan courier berdasarkan panjang digit, delegasi ke service, render view.
- `app/Services/DHLService.php` & `app/Services/FedexService.php`: integrasi provider HTTP + caching hasil tracking.
- `resources/views/layouts/app.blade.php`: branding, favicon/logo, stylesheet brand, serta slot meta SEO (`description/keywords`).
- `resources/views/welcome.blade.php` & `resources/views/tracking.blade.php`: UI user-friendly (hero, form resi, dan hasil).

## SEO & Branding
- `meta keywords` di-inject lewat `@section('meta_keywords', ...)` pada tiap page dan dibaca oleh layout via `@yield('meta_keywords')`.
- Logo dipakai dari `public/images/logo-compact.png` dan favicon dari `public/images/favicon.svg`.

## Verification
- `php artisan test` dinyatakan **PASS** (unit test provider menggunakan `Http::fake`).

## Artifact Dokumentasi
Laporan permanen sudah disimpan di project root sebagai: **`project_info__1.md`**.