@extends('layouts.app')

@section('title', 'About — '.config('app.name'))
@section('meta_description', 'Rai Raka Express: jasa ekspedisi internasional dan pengiriman barang ke luar negeri. Solusi ekspedisi terpercaya, termasuk pengiriman ke Jepang.')
@section('meta_keywords', 'jasa ekspedisi internasional, jasa kirim barang luar negeri, cargo internasional murah, pengiriman barang ke Jepang, ekspedisi Indonesia Jepang, jasa import export terpercaya, pengiriman door to door internasional, jasa kirim paket cepat luar negeri, cargo udara internasional, jasa pengiriman barang UMKM export, Rai Raka Express, Rai Raka Express cargo, Rai Raka Express Jepang, Rai Raka Express tracking, Rai Raka Express ekspedisi internasional, Rai Raka Express pengiriman luar negeri')

@section('content')
<div class="container">
    <div class="card">
        <h2 class="hero-title">Tentang {{ config('app.name') }}</h2>
        <p class="hero-sub">Kami adalah perusahaan ekspedisi terpercaya yang menyediakan layanan pengiriman nasional dan internasional. Dengan jaringan luas dan sistem pelacakan real-time, kami memastikan paket Anda sampai dengan cepat dan aman.</p>
        <h3>Nilai Kami</h3>
        <ul>
            <li>Kecepatan dan ketepatan waktu</li>
            <li>Keamanan pengiriman</li>
            <li>Layanan pelanggan 24/7</li>
        </ul>
    </div>
</div>
@endsection
