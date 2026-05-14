@extends('layouts.app')

@section('title', 'Buat Post Blog — '.config('app.name'))
@section('meta_description', 'Buat posting blog untuk Rai Raka Express.')
@section('meta_keywords', 'blog, ekspedisi')

@section('content')
<div class="container">
    <div class="card" style="padding:28px;max-width:900px;margin:0 auto;">
        <h1 class="hero-title" style="font-size:32px;line-height:1.1;margin:0 0 6px 0;">Buat Post Blog</h1>
        <p class="hero-sub" style="margin:0 0 18px 0;color:#54617a;font-weight:600;">
            Isi judul, ringkasan, dan konten. Ringkasan akan ditampilkan di halaman Welcome.
        </p>

        <form method="POST" action="{{ url('/blog') }}" style="display:grid;grid-template-columns:1fr;gap:12px;">
            @csrf

            <div>
                <label style="display:block;margin:0 0 6px 0;color:#0f172a;font-weight:900;" for="title">Judul</label>
                <input id="title" name="title" value="{{ old('title') }}" required
                       class="input"
                       style="width:100%;padding:14px 16px;border-radius:12px;border:2px solid rgba(2,6,23,0.08);background:#fff;">
                @error('title')
                    <div style="margin-top:6px;color:#b91c1c;font-weight:900;">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label style="display:block;margin:0 0 6px 0;color:#0f172a;font-weight:900;" for="summary">Ringkasan (max 500)</label>
                <textarea id="summary" name="summary" rows="4" required
                          class="input"
                          style="width:100%;padding:14px 16px;border-radius:12px;border:2px solid rgba(2,6,23,0.08);background:#fff;resize:vertical;">{{ old('summary') }}</textarea>
                @error('summary')
                    <div style="margin-top:6px;color:#b91c1c;font-weight:900;">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label style="display:block;margin:0 0 6px 0;color:#0f172a;font-weight:900;" for="content">Konten</label>
                <textarea id="content" name="content" rows="8" required
                          class="input"
                          style="width:100%;padding:14px 16px;border-radius:12px;border:2px solid rgba(2,6,23,0.08);background:#fff;resize:vertical;">{{ old('content') }}</textarea>
                @error('content')
                    <div style="margin-top:6px;color:#b91c1c;font-weight:900;">{{ $message }}</div>
                @enderror
            </div>

            <div style="display:flex;gap:12px;flex-wrap:wrap;align-items:center;">
                <button type="submit" class="btn" style="padding:14px 18px;border-radius:12px;font-size:16px;font-weight:900;min-height:44px;">
                    Simpan Post
                </button>
                <a href="{{ url('/blog') }}" class="btn btn-outline"
                   style="padding:14px 18px;border-radius:12px;font-size:16px;font-weight:900;border:2px solid rgba(2,6,23,0.08);text-decoration:none;min-height:44px;">
                    Kembali
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
