@extends('layouts.app')

@section('title', 'Buat Laporan')

@section('content')
    <div class="card">
        <h2>Buat Laporan Kejadian</h2>

        @if ($errors->any())
            <div class="alert alert-error">
                <ul style="margin:0; padding-left:18px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('laporan.store') }}">
            @csrf

            <label for="sekolah_id">Sekolah</label>
            <select name="sekolah_id" id="sekolah_id" required>
                <option value="">-- Pilih sekolah --</option>
                @foreach ($sekolah as $s)
                    <option value="{{ $s->sekolah_id }}" @selected(old('sekolah_id') == $s->sekolah_id)>
                        {{ $s->nama_sekolah }}
                    </option>
                @endforeach
            </select>

            <label for="distribusi_id">Distribusi makanan</label>
            <select name="distribusi_id" id="distribusi_id" required>
                <option value="">-- Pilih distribusi --</option>
                @foreach ($distribusi as $d)
                    <option value="{{ $d->distribusi_id }}" @selected(old('distribusi_id') == $d->distribusi_id)>
                        {{ $d->sekolah->nama_sekolah }} |
                        {{ \Illuminate\Support\Carbon::parse($d->tanggal)->format('d/m/Y') }} |
                        {{ $d->menu }}
                    </option>
                @endforeach
            </select>

            <label for="waktu_kejadian">Waktu kejadian</label>
            <input type="datetime-local" name="waktu_kejadian" id="waktu_kejadian"
                   value="{{ old('waktu_kejadian') }}" required>

            <label for="deskripsi">Deskripsi kejadian</label>
            <textarea name="deskripsi" id="deskripsi" rows="4" required>{{ old('deskripsi') }}</textarea>

            <label for="gejala">Gejala yang dialami</label>
            <textarea name="gejala" id="gejala" rows="3" required>{{ old('gejala') }}</textarea>

            <label for="jumlah_terdampak">Jumlah terdampak</label>
            <input type="number" name="jumlah_terdampak" id="jumlah_terdampak"
                   value="{{ old('jumlah_terdampak') }}" min="1" required>

            <p style="margin-top:18px;">
                <button type="submit">Kirim Laporan</button>
                <a href="{{ route('laporan.index') }}" class="btn btn-light">Batal</a>
            </p>
        </form>
    </div>
@endsection