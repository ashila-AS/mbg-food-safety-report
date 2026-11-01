@extends('layouts.app')

@section('title', 'Daftar Laporan')

@section('content')
    <div class="card">
        <h2>Daftar Laporan</h2>

        @if ($errors->any())
            <div class="alert alert-error">{{ $errors->first() }}</div>
        @endif

        <form method="GET" action="{{ route('tinjauan.index') }}" style="display:grid; grid-template-columns: repeat(auto-fit, minmax(190px, 1fr)); gap:12px; align-items:end; margin-bottom:16px;">
            <div>
                <label for="q">Cari</label>
                <input type="text" id="q" name="q" value="{{ request('q') }}" placeholder="ID, sekolah, menu, gejala...">
            </div>
            <div>
                <label for="status">Status</label>
                <select id="status" name="status">
                    <option value="">Semua status</option>
                    @foreach ($statuses as $st)
                        <option value="{{ $st }}" @selected(request('status') === $st)>{{ $st }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="sekolah_id">Sekolah</label>
                <select id="sekolah_id" name="sekolah_id">
                    <option value="">Semua sekolah</option>
                    @foreach ($sekolah as $s)
                        <option value="{{ $s->sekolah_id }}" @selected((string) request('sekolah_id') === (string) $s->sekolah_id)>
                            {{ $s->nama_sekolah }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <button type="submit">Terapkan</button>
                <a href="{{ route('tinjauan.index') }}" class="btn btn-light">Reset</a>
            </div>
        </form>

        @if ($laporan->isEmpty())
            <p>Tidak ada laporan yang cocok dengan pencarian atau filter ini.</p>
        @else
            <p><small>Menampilkan {{ $laporan->firstItem() }} sampai {{ $laporan->lastItem() }} dari {{ $laporan->total() }} laporan.</small></p>

            <div style="overflow-x:auto;">
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
            </div>

            <div style="margin-top:16px;">
                {{ $laporan->links('pagination::default') }}
            </div>
        @endif
    </div>
@endsection