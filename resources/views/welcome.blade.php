@extends('layouts.app')

@section('title', config('app.name') . ' — Ekspedisi Luar Negeri')
@section('meta_description', 'Rai Raka Express menyediakan jasa ekspedisi internasional door-to-door ke luar negeri, termasuk Jepang. Dapatkan harga kompetitif dan tracking real-time.')
@section('meta_keywords', 'jasa ekspedisi internasional, jasa kirim barang luar negeri, cargo internasional murah, pengiriman barang ke Jepang, ekspedisi Indonesia Jepang, jasa import export terpercaya, pengiriman door to door internasional, jasa kirim paket cepat luar negeri, cargo udara internasional, jasa pengiriman barang UMKM export, Rai Raka Express, Rai Raka Express cargo, Rai Raka Express Jepang, Rai Raka Express tracking, Rai Raka Express ekspedisi internasional, Rai Raka Express pengiriman luar negeri')

@section('content')
    <div class="container">
        <section class="hero card" style="background:#fff; padding:22px;">
            <div class="left" style="max-width:560px;">
                <div style="display:flex;align-items:center;gap:12px; margin-bottom:10px;">
                    <div style="width:72px;height:72px;border:5px solid var(--brand-blue);border-radius:18px;display:flex;align-items:center;justify-content:center;background:#fff;">
                        <img src="{{ asset('images/logo-compact.png') }}" alt="{{ config('app.name') }}" style="height:40px;width:auto;object-fit:contain;">
                    </div>
                    <div>
                        <h1 class="hero-title" style="margin:0;">Kirim Barang ke Luar Negeri</h1>
                        <div class="hero-sub" style="margin:6px 0 0 0;">Cepat • Aman • Bisa dilacak</div>
                    </div>
                </div>

                <div style="border:3px solid #1118271a;border-radius:16px;padding:14px 14px;background:#fff;margin-top:14px;">
                    <h2 style="margin:0 0 8px 0;color:var(--brand-dark);font-size:18px;font-weight:800;letter-spacing:-.01em;">
                        Cek Resi Sekarang
                    </h2>

                    <p style="margin:0 0 12px 0;color:#54617a;">
                        Masukkan <b>10</b>atau<b>12 digit Nomor</b>. Sistem otomatis pilih kurir.
                    </p>

                    <form method="POST" action="{{ url('/tracking') }}" class="tracking-form" aria-label="Form tracking">
                        @csrf
                        <div class="form-row" style="gap:10px;flex-wrap:wrap;">
                            <div style="flex:1;min-width:240px;">
                                <label for="tracking_number_welcome" class="sr-only">Nomor resi</label>
                                <input
                                    id="tracking_number_welcome"
                                    type="text"
                                    name="tracking_number"
                                    placeholder="Contoh: 1234567890"
                                    required
                                    maxlength="12"
                                    pattern="(\d{10}|\d{12})"
                                    inputmode="numeric"
                                    title="Masukkan nomor resi (angka saja)"
                                    class="input"
                                    style="padding:14px 16px;border-radius:12px;border:2px solid rgba(11,93,167,0.15);"
                                >
                                <div class="site-footer" style="border-top:none;padding:8px 0;margin-top:6px;color:#54617a;">
                                    Tips: tanpa spasi, tanpa tanda baca.
                                </div>
                            </div>

                            <div style="display:flex;align-items:flex-end;">
                                <button type="submit" class="btn" style="padding:14px 18px;border-radius:12px;border:none; box-shadow:0 10px 30px rgba(11,93,167,0.12);">
                                    Lacak Paket
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <div style="display:flex;gap:10px;flex-wrap:wrap; margin-top:14px;">
                    <a href="{{ url('/services') }}" class="btn-outline" style="border:2px solid rgba(2,6,23,0.08); padding:12px 14px; border-radius:12px; font-weight:800;">
                        Lihat Layanan
                    </a>
                    <a href="{{ url('/contact') }}" class="btn-outline" style="border:2px solid rgba(2,6,23,0.08); padding:12px 14px; border-radius:12px; font-weight:900; background:transparent; color:#000; text-decoration:none;">
                        Butuh Bantuan?
                    </a>
                </div>
            </div>

            <div class="right" style="flex-basis:420px; display:flex; align-items:center; justify-content:center;">
                <div class="card" style="width:100%; background:#fff; box-shadow:0 10px 30px rgba(2,6,23,0.06); border:3px solid rgba(11,93,167,0.12);">
                    <div style="padding:16px;">
                        <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:10px;">
                            <div style="font-weight:900;color:var(--brand-dark);font-size:14px;">READY FOR INTERNATIONAL</div>
                            <div style="display:inline-flex;align-items:center;gap:8px;">
                                <span style="width:10px;height:10px;background:var(--brand-orange);border:2px solid #fff;box-shadow:0 0 0 3px rgba(255,154,28,0.25);border-radius:999px;"></span>
                                <span style="font-weight:900;color:#1b1b18;font-size:14px;">TRACKABLE</span>
                            </div>
                        </div>

                        <img src="{{ asset('images/hero-illustration-custom.svg') }}" alt="Ilustrasi ekspedisi luar negeri" class="brand-hero-logo" style="width:100%;max-height:320px;object-fit:contain;">
                        <div style="margin-top:10px; font-size:13px; color:#54617a; font-weight:600;">
                            Logo + warna brand mengikuti template site.
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="features" aria-label="Keunggulan" style="margin-top:18px;">
            <div class="feature" style="background:#fff;border:2px solid rgba(2,6,23,0.06);padding:16px;border-radius:14px;">
                <div class="icon" style="background:var(--brand-blue); border-radius:12px; width:48px; height:48px;">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M3 12h18" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
                    </svg>
                </div>
                <div>
                    <h4 style="margin:0;font-size:16px;font-weight:900;">Pelacakan Real-time</h4>
                    <p style="margin:6px 0 0 0;color:#56697f;font-weight:600;">Status paket tampil jelas dan cepat.</p>
                </div>
            </div>

            <div class="feature" style="background:#fff;border:2px solid rgba(2,6,23,0.06);padding:16px;border-radius:14px;">
                <div class="icon" style="background:var(--brand-orange); border-radius:12px; width:48px; height:48px;">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 2v20" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
                    </svg>
                </div>
                <div>
                    <h4 style="margin:0;font-size:16px;font-weight:900;">Ekspedisi Luar Negeri</h4>
                    <p style="margin:6px 0 0 0;color:#56697f;font-weight:600;">Layanan internasional dengan mitra terpercaya.</p>
                </div>
            </div>

            <div class="feature" style="background:#fff;border:2px solid rgba(2,6,23,0.06);padding:16px;border-radius:14px;">
                <div class="icon" style="background:#1B1B18; border-radius:12px; width:48px; height:48px;">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M3 6h18M3 12h18M3 18h18" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
                    </svg>
                </div>
                <div>
                    <h4 style="margin:0;font-size:16px;font-weight:900;">Keamanan Paket</h4>
                    <p style="margin:6px 0 0 0;color:#56697f;font-weight:600;">Proses terkontrol untuk menjaga paket tetap aman.</p>
                </div>
            </div>
        </section>

        <section class="blog-latest" style="margin-top:18px;padding:18px 16px;border-radius:14px;border:2px solid rgba(2,6,23,0.06);background:#fff;">
            <h3 style="margin:0 0 10px 0;font-size:16px;font-weight:900;color:#0f172a;">Blog Terbaru</h3>
            <p style="margin:0 0 12px 0;color:#54617a;font-weight:700;line-height:1.6;">
                Ringkasan posting terbaru kami tampil di sini. (Dari halaman Blog)
            </p>

            @php
                $posts = app('App\Http\Controllers\BlogController')->latestSummaries(3);
                $posts = is_array($posts) ? $posts : [];
            @endphp

            @if(count($posts) === 0)
                <div style="padding:14px;border-radius:12px;border:2px dashed rgba(2,6,23,0.12);color:#54617a;font-weight:700;background:rgba(2,6,23,0.02);">
                    Belum ada posting blog.
                </div>
            @else
                <div style="display:grid;grid-template-columns:1fr;gap:12px;">
                    @foreach($posts as $post)
                        <a href="{{ url('/blog') }}" style="text-decoration:none;color:inherit;">
                            <div style="padding:14px;border-radius:12px;border:2px solid rgba(2,6,23,0.06);background:#fff;">
                                <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:10px;">
                                    <h4 style="margin:0;font-size:15px;font-weight:900;color:#0f172a;line-height:1.3;">
                                        {{ $post['title'] ?? '' }}
                                    </h4>
                                    <div style="color:#54617a;font-weight:700;font-size:12px;white-space:nowrap;">
                                        {{ $post['created_at'] ?? '' }}
                                    </div>
                                </div>
                                <p style="margin:8px 0 0 0;color:#56697f;font-weight:700;line-height:1.6;">
                                    {{ $post['summary'] ?? '' }}
                                </p>
                            </div>
                        </a>
                    @endforeach
                </div>

                <div style="margin-top:12px;">
                    <a href="{{ url('/blog') }}" class="btn btn-outline" style="background:transparent;color:#000;border:2px solid rgba(2,6,23,0.08);text-decoration:none;padding:10px 14px;border-radius:12px;display:inline-block;font-weight:900;">
                        Lihat Semua Blog
                    </a>
                </div>
            @endif
        </section>

        <section class="site-cta" style="margin-top:18px; padding:18px 16px; border-radius:14px;">
            <h3 style="margin:0 0 6px 0;">Siap Kirim?</h3>
            <p style="margin:0 0 12px 0; opacity:0.95; font-weight:700;">Dapatkan solusi ekspedisi dan pelacakan yang rapi.</p>
            <div style="display:flex;gap:10px;flex-wrap:wrap; justify-content:center;">
                <a href="{{ url('/contact') }}" class="btn-outline" style="border:2px solid rgba(255,255,255,0.65); padding:12px 16px; border-radius:12px; font-weight:900; background:rgba(255,255,255,0.08); color:#fff; text-decoration:none;">
                    Hubungi Sales
                </a>
                <a href="{{ url('/tracking') }}" class="btn-outline" style="border:2px solid rgba(255,255,255,0.65); padding:12px 16px; border-radius:12px; font-weight:900; background:rgba(255,255,255,0.08); color:#fff; text-decoration:none;">
                    Buka Tracking
                </a>
            </div>
        </section>
    </div>
@endsection
