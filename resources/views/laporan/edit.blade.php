@extends('layouts.app')

@section('title', 'Edit Laporan')

@section('content')
    <div class="card">
        <h2>Edit Laporan #{{ $laporan->laporan_id }}</h2>
        <p>
            Sekolah: <strong>{{ $laporan->distribusi->sekolah->nama_sekolah }}</strong><br>
            Distribusi:
            {{ \Illuminate\Support\Carbon::parse($laporan->distribusi->tanggal)->format('d/m/Y') }}
            | {{ $laporan->distribusi->menu }}
        </p>

        @if ($errors->any())
            <div class="alert alert-error">
                <ul style="margin:0; padding-left:18px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('laporan.update', $laporan->laporan_id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <label for="waktu_kejadian">Waktu kejadian</label>
            <input type="datetime-local" name="waktu_kejadian" id="waktu_kejadian"
                   value="{{ old('waktu_kejadian', $laporan->waktu_kejadian->format('Y-m-d\TH:i')) }}" required>

            <label for="deskripsi">Deskripsi kejadian</label>
            <textarea name="deskripsi" id="deskripsi" rows="4" required>{{ old('deskripsi', $laporan->deskripsi) }}</textarea>

            <label for="gejala">Gejala yang dialami</label>
            <textarea name="gejala" id="gejala" rows="3" required>{{ old('gejala', $laporan->gejala) }}</textarea>

            <label for="jumlah_terdampak">Jumlah terdampak</label>
            <input type="number" name="jumlah_terdampak" id="jumlah_terdampak"
                   value="{{ old('jumlah_terdampak', $laporan->jumlah_terdampak) }}" min="1" required>

            <label for="bukti">Bukti pendukung (opsional)</label>
            @if ($laporan->path_bukti)
                <small>Sudah ada bukti.
                    <a href="{{ route('laporan.bukti', $laporan->laporan_id) }}" target="_blank">Lihat</a>.
                    Pilih file baru hanya kalau ingin menggantinya.</small>
            @endif
            <input type="file" name="bukti" id="bukti" accept=".jpg,.jpeg,.png,.pdf">
            <small>Format JPG, PNG, atau PDF. Maksimal 2 MB.</small>

            <p style="margin-top:18px;">
                <button type="submit">Simpan perubahan</button>
                <a href="{{ route('laporan.show', $laporan->laporan_id) }}" class="btn btn-light">Batal</a>
            </p>
        </form>
    </div>
@endsection