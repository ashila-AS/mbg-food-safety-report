@extends('layouts.app')

@section('title', 'Laporan Saya')

@section('content')
    <div class="card">
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <h2>Laporan Saya</h2>
            <a href="{{ route('laporan.create') }}" class="btn">Buat Laporan</a>
        </div>

        @if ($laporan->isEmpty())
            <p>Belum ada laporan.</p>
        @else
            <div style="overflow-x:auto;">
                <table>
                    <thead>
                        <tr>
                            <th>Waktu kejadian</th>
                            <th>Sekolah</th>
                            <th>Distribusi</th>
                            <th>Status</th>
                            <th>Dikirim</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($laporan as $l)
                            <tr>
                                <td>{{ $l->waktu_kejadian->format('d/m/Y H:i') }}</td>
                                <td>{{ $l->distribusi->sekolah->nama_sekolah }}</td>
                                <td>
                                    {{ \Illuminate\Support\Carbon::parse($l->distribusi->tanggal)->format('d/m/Y') }}
                                    | {{ $l->distribusi->menu }}
                                </td>
                                <td><span class="badge">{{ $l->status }}</span></td>
                                <td>{{ $l->created_at->format('d/m/Y H:i') }}</td>
                                <td><a href="{{ route('laporan.show', $l->laporan_id) }}" class="btn btn-light">Detail</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{ $laporan->links('pagination.pastel') }}
        @endif
    </div>
@endsection