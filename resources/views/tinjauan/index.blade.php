@extends('layouts.app')

@section('title', 'Daftar Laporan')

@section('content')
    <div class="card">
        <h2>Daftar Laporan</h2>

        @if ($laporan->isEmpty())
            <p>Belum ada laporan.</p>
        @else
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Waktu kejadian</th>
                        <th>Sekolah</th>
                        <th>Distribusi</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($laporan as $l)
                        <tr>
                            <td>{{ $l->laporan_id }}</td>
                            <td>{{ $l->waktu_kejadian->format('d/m/Y H:i') }}</td>
                            <td>{{ $l->distribusi->sekolah->nama_sekolah }}</td>
                            <td>
                                {{ \Illuminate\Support\Carbon::parse($l->distribusi->tanggal)->format('d/m/Y') }}
                                | {{ $l->distribusi->menu }}
                            </td>
                            <td><span class="badge">{{ $l->status }}</span></td>
                            <td><a href="{{ route('laporan.show', $l->laporan_id) }}" class="btn btn-light">Detail</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection