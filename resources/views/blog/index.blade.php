@extends('layouts.app')

@section('title', 'Blog — '.config('app.name'))
@section('meta_description', 'Blog Rai Raka Express')
@section('meta_keywords', 'blog')

@section('content')
<div class="container">
    <div class="card" style="padding:28px;max-width:900px;margin:0 auto;">
        <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px;flex-wrap:wrap;margin-bottom:12px;">
            <div>
                <h1 class="hero-title" style="font-size:32px;line-height:1.1;margin:0 0 6px 0;">Blog</h1>
                <p class="hero-sub" style="margin:0;color:#54617a;font-weight:600;">Posting dan ringkasan ditampilkan di halaman utama.</p>
            </div>
            <a href="{{ url('/blog/create') }}" class="btn" style="text-decoration:none;">Buat Post</a>
        </div>

        @if (count($posts) === 0)
            <div style="padding:16px;border-radius:14px;border:2px solid rgba(2,6,23,0.06);background:#fff;color:#54617a;font-weight:700;">
                Belum ada posting blog.
            </div>
        @else
            <div style="display:grid;grid-template-columns:1fr;gap:12px;">
                @foreach ($posts as $post)
                    <div class="card" style="padding:16px;border-radius:14px;box-shadow:none;border:2px solid rgba(2,6,23,0.06);background:#fff;">
                        <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px;flex-wrap:wrap;">
                            <h3 style="margin:0;font-size:18px;font-weight:900;color:#0f172a;">{{ $post['title'] ?? '' }}</h3>
                            <div style="color:#54617a;font-weight:700;font-size:13px;">{{ $post['created_at'] ?? '' }}</div>
                        </div>
                        <p style="margin:8px 0 0 0;color:#56697f;font-weight:700;line-height:1.6;">
                            {{ $post['summary'] ?? '' }}
                        </p>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
