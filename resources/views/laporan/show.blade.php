
@extends('layouts.app')

@section('title', 'Detail Laporan')

@section('content')
    <div class="card">
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <h2>Detail Laporan #{{ $laporan->laporan_id }}</h2>
            <span class="badge">{{ $laporan->status }}</span>
        </div>

        @if ($errors->any())
            <div class="alert alert-error">{{ $errors->first() }}</div>
        @endif

        <table>
            @if (auth()->user()->role === 'pelapor')
                <tr>
                    <th style="width:220px;">Pelapor</th>
                    <td>{{ $laporan->pelapor->nama }}</td>
                </tr>
            @endif

            <tr>
                <th>Sekolah</th>
                <td>{{ $laporan->distribusi->sekolah->nama_sekolah }}</td>
            </tr>

            <tr>
                <th>Distribusi</th>
                <td>
                    {{ \Illuminate\Support\Carbon::parse($laporan->distribusi->tanggal)->format('d/m/Y') }}
                    | {{ $laporan->distribusi->menu }}
                    | {{ $laporan->distribusi->nama_sppg }}
                </td>
            </tr>

            <tr>
                <th>Waktu kejadian</th>
                <td>{{ $laporan->waktu_kejadian->format('d/m/Y H:i') }}</td>
            </tr>

            <tr>
                <th>Jumlah terdampak</th>
                <td>{{ $laporan->jumlah_terdampak }}</td>
            </tr>

            <tr>
                <th>Deskripsi</th>
                <td>{!! nl2br(e($laporan->deskripsi)) !!}</td>
            </tr>

            <tr>
                <th>Gejala</th>
                <td>{!! nl2br(e($laporan->gejala)) !!}</td>
            </tr>

            <tr>
                <th>Bukti pendukung</th>
                <td>
                    @if ($laporan->path_bukti)
                        <a href="{{ route('laporan.bukti', $laporan->laporan_id) }}" target="_blank">
                            Lihat bukti
                        </a>
                    @else
                        Tidak ada
                    @endif
                </td>
            </tr>

            <tr>
                <th>Dikirim</th>
                <td>{{ $laporan->created_at->format('d/m/Y H:i') }}</td>
            </tr>
        </table>

        @if (auth()->user()->role === 'pemantau' && $laporan->status === \App\Models\Laporan::DIKIRIM)
            <form method="POST" action="{{ route('laporan.mulai-tinjau', $laporan->laporan_id) }}" style="margin-top:18px;">
                @csrf
                <button type="submit">Mulai meninjau</button>
            </form>
        @endif

        <p style="margin-top:18px;">
            <a href="{{ auth()->user()->role === 'pelapor' ? route('laporan.index') : route('tinjauan.index') }}" class="btn btn-light">
                Kembali
            </a>
        </p>
    </div>
@endsection
