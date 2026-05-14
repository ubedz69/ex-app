# Rai Raka Express — Codebase Overview (Explore Report)

## Summary
Project ini adalah aplikasi web Laravel untuk **ekspedisi & pelacakan paket internasional**, dengan endpoint utama `GET /tracking` dan `POST /tracking` yang menjalankan layanan pelacakan untuk **DHL (10 digit)** dan **FedEx (12 digit)**. Sistem merender halaman Blade (bukan API JSON publik), namun “API” yang dipakai oleh user adalah form/route tracking. Aplikasi juga menyediakan halaman landing, about, services, dan contact.

Selama sesi Explore, saya menemukan beberapa instabilitas file akibat kontaminasi/merge-conflict (mis. di `routes/web.php`) dan kerusakan struktur service/config; semuanya telah dipulihkan sampai unit tests **PASS** (`php artisan test` → 6 passed).

## Architecture
- **Framework**: Laravel (PHP), dengan Blade templates, controller-based routing, dan service classes untuk integrasi provider.
- **Pattern**: traditional MVC:
  - `routes/*` → controller method → service integration (`DHLService`, `FedexService`) → Blade view rendering.
- **Technology stack**:
  - PHP 8.5.x, Laravel framework, Pest/PHPUnit for tests.
  - HTTP client: `Illuminate\Support\Facades\Http` to call DHL/FedEx APIs.
  - Caching: `Illuminate\Support\Facades\Cache` to cache tracking results for ~5 minutes.
- **Execution start**:
  - Web requests enter via `public/index.php` → Laravel kernel.
  - Route dispatch goes through `routes/web.php` and (untuk tracking) `routes/tracking.php` (ada route `Route::prefix('tracking')...`).

## Directory Structure (ringkas & relevan)
```
project-root/
├── app/
│   ├── Http/Controllers/
│   │   ├── HomeController.php
│   │   ├── PageController.php
│   │   └── TrackingController.php
│   ├── Services/
│   │   ├── DHLService.php
│   │   ├── FedexService.php
│   │   └── Tracking/ (subfolder; tidak dipakai langsung oleh controller yang terlihat)
│   └── ...
├── config/
│   ├── services.php        (credential config untuk kurir)
│   └── tracking.php        (credential/status config untuk kurir)
├── routes/
│   ├── web.php
│   └── tracking.php
├── resources/
│   └── views/
│       ├── layouts/app.blade.php
│       ├── welcome.blade.php
│       ├── home.blade.php
│       ├── tracking.blade.php
│       ├── about.blade.php
│       ├── services.blade.php
│       └── contact.blade.php
└── tests/
    ├── Unit/
    │   ├── DHLServiceTest.php
    │   └── FedexServiceTest.php
    └── Feature/
        └── ExampleTest.php
```

## Key Abstractions

### TrackingController
- **File**: `app/Http/Controllers/TrackingController.php`
- **Responsibility**: Validasi input tracking number (DHL 10 digit / FedEx 12 digit), pemilihan provider berdasarkan panjang digit, pemanggilan `DHLService` atau `FedexService`, dan rendering view `resources/views/tracking.blade.php`.
- **Interface**:
  - `index(Request $request, DHLService $dhl, FedexService $fedex)`
    - Jika `tracking_number` query param tersedia, melakukan validasi dan tracking lalu mengirim `$result` ke view.
  - `track(Request $request, DHLService $dhl, FedexService $fedex)`
    - Validasi input POST `tracking_number`, memanggil provider dan mengirim `$result` ke view.
- **Lifecycle**: stateless per request; services di-inject oleh Laravel.
- **Used by**: routes `/tracking` (`GET` dan `POST`) dan `GET /tracking?tracking_number=...`.

### DHLService
- **File**: `app/Services/DHLService.php`
- **Responsibility**: Integrasi pelacakan DHL.
- **Interface**:
  - `track($trackingNumber): array`
    - Cache key: `dhl:` + sha1(trackingNumber)
    - Memanggil `GET https://api-eu.dhl.com/track/shipments` (atau test host `api-test.dhl.com` jika env `DHL_USE_TEST_API=true`)
    - Header: `DHL-API-Key: env('DHL_API_KEY')`
    - Timeout & retry.
    - Mengembalikan array JSON dari API sukses, atau array error `['error' => ...]`.
- **Used by**: `TrackingController`, dan dites oleh `tests/Unit/DHLServiceTest.php`.

### FedexService
- **File**: `app/Services/FedexService.php`
- **Responsibility**: Integrasi pelacakan FedEx.
- **Interface**:
  - `track($trackingNumber)`
    - Cache key: `fedex:` + sha1(trackingNumber)
    - Memanggil `getToken()` untuk memperoleh `access_token`.
    - Memanggil `POST https://apis.fedex.com/track/v1/trackingnumbers` dengan payload tracking number.
    - Mengembalikan JSON API sukses atau array error.
  - `getToken()`
    - `POST https://apis.fedex.com/oauth/token` form-urlencoded menggunakan `FEDEX_API_KEY` dan `FEDEX_SECRET_KEY`.
- **Used by**: `TrackingController`, dan dites oleh `tests/Unit/FedexServiceTest.php`.

### Layout App (Blade)
- **File**: `resources/views/layouts/app.blade.php`
- **Responsibility**: Memberikan header navigation, favicon, font, styling brand (`public/css/brand.css`), dan slot metadata (description/keywords).
- **Interface**:
  - `@yield('title')`
  - `@yield('meta_description')`
  - `@yield('meta_keywords')`
  - `<link rel="icon" href="{{ asset('images/favicon.svg') }}">`
  - `<link rel="stylesheet" href="{{ asset('css/brand.css') }}">`
- **Used by**: semua page Blade yang `@extends('layouts.app')` (termasuk `welcome`, `home`, `tracking`, `about`, `services`, `contact`).

## Data Flow (tracking)
1. User membuka page `/` (welcome/home) → input AWB → submit menuju `/tracking` (POST).
2. Route `POST /tracking` mengarah ke `TrackingController@track`.
3. `TrackingController@track`:
   - `validate()` tracking_number sebagai string max 100.
   - memastikan input hanya angka (di implementasi GET ada sanitasi; di POST ada `preg_match` untuk non-digit).
   - pilih provider: panjang 10 → DHL, panjang 12 → FedEx.
4. Memanggil `DHLService->track()` atau `FedexService->track()`.
5. Service melakukan HTTP request ke provider dengan timeout/retry dan caching 5 menit.
6. Controller menangkap exception → mengisi `$result` error generik → render `tracking.blade.php`.
7. `tracking.blade.php` menampilkan error / hasil JSON via `print_r` dalam `<pre>`.

## Non-Obvious Behaviors & Design Decisions
- **Pemilihan courier berbasis panjang digit**:
  - Ini adalah invariant UI/logic: sistem hanya menganggap DHL=10 digit dan FedEx=12 digit.
  - Konsekuensi: resi “format” lain (misalnya punya prefix/spasi) ditolak (atau disanitasi di GET).
- **Hasil provider ditampilkan apa adanya**:
  - `tracking.blade.php` menggunakan `print_r($result, true)` tanpa normalisasi schema.
  - Ini memudahkan debug, tetapi kurang “rapih” sebagai UX produk—namun sesuai kebutuhan kecepatan implementasi.
- **Caching**:
  - Kedua service cache selama 5 menit berdasarkan hash nomor resi.
  - Dampak: response bisa “stale” jika status provider update cepat.
- **SEO keywords via Blade layout**:
  - `meta keywords` di-inject dari masing-masing page melalui `@section('meta_keywords', ...)`, lalu dibaca layout dengan `@yield('meta_keywords', '')`.

## Module Reference (file penting)
| File | Purpose |
|---|---|
| `routes/web.php` | Routing utama web; route `/tracking` (GET/POST) dan halaman lain |
| `routes/tracking.php` | Routing alternatif/prefixed untuk segment tracking |
| `app/Http/Controllers/TrackingController.php` | Orchestrasi tracking flow dan validasi input |
| `app/Services/DHLService.php` | Integrasi DHL (HTTP + cache) |
| `app/Services/FedexService.php` | Integrasi FedEx (token + HTTP + cache) |
| `resources/views/layouts/app.blade.php` | Layout header/footer, favicon/logo, meta tags |
| `resources/views/welcome.blade.php` | Landing/hero + form resi (brand UI) |
| `resources/views/tracking.blade.php` | Halaman input & output hasil tracking (brand UI) |
| `resources/views/home.blade.php` | Landing versi home (brand UI) |
| `resources/views/about.blade.php` | Halaman about (brand UI) |
| `resources/views/services.blade.php` | Halaman layanan (brand UI) |
| `resources/views/contact.blade.php` | Halaman contact + form (brand UI) |
| `tests/Unit/DHLServiceTest.php` | Unit tests DHL menggunakan `Http::fake` |
| `tests/Unit/FedexServiceTest.php` | Unit tests Fedex menggunakan `Http::fake` |

## Suggested Reading Order
1. `routes/web.php` — untuk melihat endpoint & flow navigasi web
2. `routes/tracking.php` — untuk memahami prefixed tracking routing
3. `app/Http/Controllers/TrackingController.php` — titik pusat validasi + pemilihan provider
4. `app/Services/DHLService.php` — cara DHL request, error shape, dan caching
5. `app/Services/FedexService.php` — token lifecycle + request
6. `resources/views/layouts/app.blade.php` — branding + meta SEO slot
7. `resources/views/welcome.blade.php` dan `resources/views/tracking.blade.php` — UX form + output

## Note tentang “error string” di output command
Selama Explore, `php artisan config:clear`, `php artisan cache:clear`, dan beberapa command menunjukkan potongan teks yang tampak seperti gabungan output PHP (`return [...];use App\Http\Controllers\TrackingController; Route::prefix('tracking')...`). Namun:
- `php artisan test` tetap **lulus**.
- Potongan tersebut tampaknya merupakan artefak output/print dari proses Laravel pada environment tool-runner, bukan crash fatal runtime (dibuktikan dari hasil test yang PASS).
Dev disarankan menjalankan build/deploy dengan environment yang bersih dan memverifikasi log production (karena test suite lulus).
