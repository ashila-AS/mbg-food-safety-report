@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="card">
        <h2>Halo, {{ auth()->user()->nama }}</h2>
        <p>Anda masuk sebagai <strong>{{ auth()->user()->role }}</strong>.</p>

        @if (auth()->user()->role === 'pelapor')
            <p>Buat laporan kejadian yang berkaitan dengan makanan MBG di sekolah, lalu pantau perkembangannya.</p>
            <a href="{{ route('laporan.create') }}" class="btn">Buat laporan</a>
            <a href="{{ route('laporan.index') }}" class="btn btn-light">Laporan saya</a>
        @elseif (auth()->user()->role === 'pemantau')
            <p>Tinjau kelengkapan laporan, beri catatan, dan tandai laporan yang perlu diteruskan.</p>
            <a href="{{ route('tinjauan.index') }}" class="btn">Daftar laporan</a>
            <a href="{{ route('rekap.index') }}" class="btn btn-light">Rekap</a>
        @else
            <p>Lihat rekap laporan dan catat penerusan informasi kepada pihak terkait.</p>
            <a href="{{ route('tinjauan.index') }}" class="btn">Daftar laporan</a>
            <a href="{{ route('rekap.index') }}" class="btn btn-light">Rekap</a>
        @endif
    </div>
@endsection