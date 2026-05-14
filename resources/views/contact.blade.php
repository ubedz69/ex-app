@extends('layouts.app')

@section('title', 'Contact — '.config('app.name'))
@section('meta_description', 'Hubungi Rai Raka Express untuk jasa ekspedisi internasional, pengiriman barang ke Jepang, dan solusi import export terpercaya.')
@section('meta_keywords', 'jasa ekspedisi internasional, jasa kirim barang luar negeri, cargo internasional murah, pengiriman barang ke Jepang, ekspedisi Indonesia Jepang, jasa import export terpercaya, pengiriman door to door internasional, jasa kirim paket cepat luar negeri, cargo udara internasional, jasa pengiriman barang UMKM export, Rai Raka Express, Rai Raka Express cargo, Rai Raka Express Jepang, Rai Raka Express tracking, Rai Raka Express ekspedisi internasional, Rai Raka Express pengiriman luar negeri')

@section('content')
<div class="container">
    <div class="card">
        <h2 class="hero-title">Hubungi Kami</h2>
        <p class="hero-sub">Punya pertanyaan atau butuh bantuan? Isi form di bawah dan tim kami akan menghubungi Anda.</p>

        @if(session('status'))
            <div class="mb-4 text-green-600">{{ session('status') }}</div>
        @endif

        <form action="{{ url('/contact') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="name" class="block text-sm font-medium">Nama</label>
                <input id="name" name="name" value="{{ old('name') }}" required class="input">
                @error('name') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-medium">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required class="input">
                @error('email') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
            </div>

            <div>
                <label for="message" class="block text-sm font-medium">Pesan</label>
                <textarea id="message" name="message" rows="4" required class="input">{{ old('message') }}</textarea>
                @error('message') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
            </div>

            <div>
                <button type="submit" class="btn">Kirim Pesan</button>
            </div>
        </form>
    </div>
</div>
@endsection
