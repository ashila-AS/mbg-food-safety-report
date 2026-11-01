
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

        @if (auth()->user()->role !== 'pelapor')
            <h3 style="margin-top:24px;">Catatan peninjauan</h3>

            @forelse ($laporan->catatan->sortBy('waktu') as $c)
                <p style="margin:6px 0;">
                    <strong>{{ $c->penulis->nama }}</strong>
                    <small>{{ $c->waktu->format('d/m/Y H:i') }}</small><br>
                    {!! nl2br(e($c->isi)) !!}
                </p>
            @empty
                <p>Belum ada catatan.</p>
            @endforelse

            @if (auth()->user()->role === 'pemantau' && $laporan->status === \App\Models\Laporan::DITINJAU)
                <form method="POST" action="{{ route('laporan.catatan', $laporan->laporan_id) }}">
                    @csrf

                    <label for="isi">Tulis catatan</label>
                    <textarea name="isi" id="isi" rows="3" required>{{ old('isi') }}</textarea>

                    <button type="submit" style="margin-top:8px;">Simpan catatan</button>
                </form>
            @endif
        @endif

        @if (auth()->user()->role === 'pemantau' && $laporan->status === \App\Models\Laporan::DITINJAU)
            <div style="display:flex; gap:8px; margin-top:18px;">
                <form method="POST" action="{{ route('laporan.kembalikan', $laporan->laporan_id) }}">
                    @csrf
                    <button type="submit" class="btn-light">Kembalikan untuk dilengkapi</button>
                </form>

                <form method="POST" action="{{ route('laporan.tandai', $laporan->laporan_id) }}">
                    @csrf
                    <button type="submit">Tandai untuk diteruskan</button>
                </form>
            </div>
        @endif

        @if (auth()->user()->role === 'pelapor' && in_array($laporan->status, [\App\Models\Laporan::DIKIRIM, \App\Models\Laporan::DIKEMBALIKAN], true))
            <a href="{{ route('laporan.edit', $laporan->laporan_id) }}" class="btn" style="margin-top:18px;">Edit laporan</a>
        @endif

        <p style="margin-top:18px;">
            <a href="{{ auth()->user()->role === 'pelapor' ? route('laporan.index') : route('tinjauan.index') }}" class="btn btn-light">
                Kembali
            </a>
        </p>
    </div>
@endsection
