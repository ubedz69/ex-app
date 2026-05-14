@extends('layouts.app')

@section('title', 'Tracking Paket — ' . config('app.name'))

@section('meta_description', 'Lacak paket internasional Rai Raka Express dengan nomor AWB DHL (10 digit) atau FedEx (12 digit).')
@section('meta_keywords', 'jasa ekspedisi internasional, jasa kirim barang luar negeri, cargo internasional murah, pengiriman barang ke Jepang, ekspedisi Indonesia Jepang, jasa import export terpercaya, pengiriman door to door internasional, jasa kirim paket cepat luar negeri, cargo udara internasional, jasa pengiriman barang UMKM export, Rai Raka Express, Rai Raka Express cargo, Rai Raka Express Jepang, Rai Raka Express tracking, Rai Raka Express ekspedisi internasional, Rai Raka Express pengiriman luar negeri')

@section('content')
    <div class="container">
        <section class="hero card" aria-label="Tracking paket" style="background:#fff; padding:22px;">
            <div class="left" style="max-width:640px;">
                <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:14px;flex-wrap:wrap;">
                    <div style="display:flex;align-items:center;gap:12px;">
                        <div style="width:52px;height:52px;border:4px solid var(--brand-blue);border-radius:16px;display:flex;align-items:center;justify-content:center;background:#fff;">
                            <img src="{{ asset('images/logo-compact.png') }}" alt="{{ config('app.name') }} logo" style="height:28px;width:auto;">
                        </div>
                        <div>
                            <h1 class="hero-title" style="margin:0; font-size:28px;">Pelacakan Paket</h1>
                        </div>
                    </div>

                    <a href="{{ url('/') }}" class="btn-outline" style="padding:10px 14px;border:2px solid rgba(2,6,23,0.08);border-radius:12px;font-weight:900;">
                        ← Beranda
                    </a>
                </div>

                <div style="margin-top:16px; border:3px solid rgba(2,6,23,0.04); border-radius:16px; padding:14px; background:#fff;">
                    <h2 style="margin:0 0 8px 0; color:var(--brand-dark); font-size:18px; font-weight:900; letter-spacing:-.01em;">
                        Masukkan Nomor Resi
                    </h2>

                    <form method="POST" action="{{ url('/tracking') }}" class="tracking-form" aria-label="Form tracking">
                        @csrf

                        <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:flex-end;">
                            <div style="flex:1;min-width:260px;">
                                <label for="tracking_number" class="sr-only">Nomor AWB</label>
                                <input
                                    id="tracking_number"
                                    type="text"
                                    name="tracking_number"
                                    placeholder="Contoh: 1234567890"
                                    required
                                    maxlength="12"
                                    pattern="(\d{10}|\d{12})"
                                    inputmode="numeric"
                                    title="Nomor resi angka saja"
                                    class="input"
                                    style="padding:14px 16px;border-radius:12px;border:2px solid rgba(11,93,167,0.18);font-weight:800;"
                                >
                                <div class="site-footer" style="border-top:none;padding:8px 0;margin-top:6px;color:#54617a;">
                                    Tips: hanya angka, tanpa spasi/tanda baca.
                                </div>
                            </div>

                            <button type="submit" class="btn" style="padding:14px 18px;border-radius:12px;border:none;box-shadow:0 10px 30px rgba(11,93,167,0.12);">
                                Cek Status
                            </button>
                        </div>
                    </form>
                </div>

                <div style="margin-top:14px; display:flex; gap:10px; flex-wrap:wrap;">
                    <a href="{{ url('/services') }}" class="btn-outline" style="border:2px solid rgba(11,93,167,0.18); padding:12px 14px;border-radius:12px;font-weight:900;color:var(--brand-blue);">
                        Lihat Layanan
                    </a>
                    <a href="{{ url('/contact') }}" class="btn-outline" style="border:2px solid rgba(2,6,23,0.08); padding:12px 14px;border-radius:12px;font-weight:900;">
                        Butuh Bantuan?
                    </a>
                </div>
            </div>

            <div class="right" style="flex-basis:420px;">
                <div class="card" style="width:100%; background:#fff; box-shadow:0 10px 30px rgba(2,6,23,0.06); border:3px solid rgba(11,93,167,0.12);">
                    <div style="padding:16px;">
                        <div style="display:flex;align-items:center;justify-content:space-between;gap:12px; margin-bottom:10px; flex-wrap:wrap;">
                            <h2 style="margin:0;color:var(--brand-dark);font-size:18px;font-weight:900;">Hasil Pelacakan</h2>
                            <div style="display:inline-flex;align-items:center;gap:8px;">
                                <span style="width:10px;height:10px;background:var(--brand-orange);border:2px solid #fff;box-shadow:0 0 0 3px rgba(255,154,28,0.25);border-radius:999px;"></span>
                                <span style="font-weight:900;color:#1b1b18;font-size:14px;">LIVE</span>
                            </div>
                        </div>

                        <div id="tracking-result" aria-live="polite" aria-atomic="true">
                            @if(isset($result) && is_array($result) && isset($result['error']))
                                <div role="status" style="background:#fef3c7;border:1px solid rgba(180,35,24,0.22);color:#b42318;padding:12px;border-radius:14px;font-weight:800;">
                                    {{ $result['error'] }}
                                </div>
                            @else
                                @if(isset($result))
                                    <div style="background:#f8fafc;border:1px solid rgba(2,6,23,0.06);padding:12px;border-radius:14px;max-height:360px;overflow:auto;">
                                        <pre style="margin:0;white-space:pre-wrap;word-break:break-word;font-size:12px;">{{ print_r($result, true) }}</pre>
                                    </div>
                                @else
                                    <div style="background:#fff;border:1px dashed rgba(2,6,23,0.12);padding:14px;border-radius:14px;color:#54617a;font-weight:700;">
                                        Belum ada hasil. Masukkan nomor resi untuk mulai pelacakan.
                                    </div>
                                @endif
                            @endif
                        </div>

                        <div style="margin-top:12px;color:#54617a;font-weight:700; font-size:13px;">
                            Data yang ditampilkan berasal langsung dari sistem kurir.
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
