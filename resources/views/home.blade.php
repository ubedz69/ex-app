@extends('layouts.app')

@section('title', config('app.name') . ' | Jasa Pengiriman Keluar Negeri')
@section('meta_description', 'Rai Raka Express melayani ekspedisi internasional ke luar negeri, termasuk pengiriman ke Jepang. Lacak paket cepat dan dapatkan status real-time.')
@section('meta_keywords', 'jasa ekspedisi internasional, jasa kirim barang luar negeri, cargo internasional murah, pengiriman barang ke Jepang, ekspedisi Indonesia Jepang, jasa import export terpercaya, pengiriman door to door internasional, jasa kirim paket cepat luar negeri, cargo udara internasional, jasa pengiriman barang UMKM export, Rai Raka Express, Rai Raka Express cargo, Rai Raka Express Jepang, Rai Raka Express tracking, Rai Raka Express ekspedisi internasional, Rai Raka Express pengiriman luar negeri')

@section('content')
<div class="container">
    <section class="hero card" aria-labelledby="hero-heading">
        <div class="left">
            <h1 id="hero-heading" class="hero-title">Lacak Paket Anda Di Sini</h1>
            <p class="hero-sub">Masukkan nomor AWB untuk mendapatkan status pengiriman real-time. Kami melayani pengiriman internasional dengan jaminan keamanan.</p>

            <form action="{{ url('/tracking') }}" method="POST" class="tracking-form" novalidate>
                @csrf
                <div class="form-row">
                    <input name="tracking_number" id="tracking_number" placeholder="Masukkan nomor AWB" required maxlength="12" pattern="(\d{10}|\d{12})" inputmode="numeric" title="Masukkan nomor AWB" class="input" aria-label="Nomor AWB">
                    <button class="btn" type="submit">Cek Sekarang</button>
                </div>
            </form>

            <div class="mt-4">
                <a href="{{ url('/contact') }}" class="btn btn-outline" style="background:transparent;color:#000;border:2px solid rgba(2,6,23,0.08);text-decoration:none;padding:12px 14px;border-radius:10px;display:inline-flex;align-items:center;justify-content:center;font-weight:900;min-height:44px;white-space:nowrap;">Butuh Bantuan? Hubungi Kami</a>
            </div>
        </div>

        <div class="right">
            <div class="card" style="display:flex;align-items:center;justify-content:center;">
                <img src="{{ asset('images/hero-illustration-custom.svg') }}" alt="Ilustrasi pelacakan" class="brand-hero-logo">
            </div>
        </div>
    </section>

    <section class="features">
        <div class="feature">
            <div class="icon" style="background:linear-gradient(90deg,var(--brand-blue),var(--brand-orange));">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M3 12h18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
            </div>
            <div>
                <h4>Pelacakan Real-time</h4>
                <p>Update status langsung dari sistem kurir untuk setiap langkah pengiriman.</p>
            </div>
        </div>

        <div class="feature">
            <div class="icon" style="background:var(--brand-blue);">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 2v20" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
            </div>
            <div>
                <h4>Jaringan Luas</h4>
                <p>Jangkauan internasional dengan mitra terpercaya.</p>
            </div>
        </div>

        <div class="feature">
            <div class="icon" style="background:var(--brand-orange);">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M3 6h18M3 12h18M3 18h18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
            </div>
            <div>
                <h4>Keamanan Paket</h4>
                <p>Proses penanganan yang ketat untuk menjaga paket tetap aman.</p>
            </div>
        </div>
    </section>

    <section class="testimonials">
        <div class="testimonial">
            <p>"Pengiriman cepat dan informasinya akurat — sangat membantu untuk bisnis kami."</p>
            <small>— PT. Toko Cepat</small>
        </div>

        <div class="testimonial">
            <p>"Customer service responsif ketika saya butuh update khusus untuk pengiriman internasional."</p>
            <small>— Anak Usaha</small>
        </div>
    </section>

    <section class="site-cta">
        <h3 style="margin:0 0 8px 0;">Siap Mengirimkan Barang Anda?</h3>
        <p style="margin:0 0 12px 0;opacity:0.95">Dapatkan layanan cepat dan terpercaya. Hubungi kami untuk penawaran khusus atau gunakan fitur cek resi sekarang.</p>
        <a href="{{ url('/contact') }}" class="btn">Hubungi Sales</a>
    </section>
</div>
@endsection
