
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

        <form method="POST" action="{{ route('laporan.store') }}" enctype="multipart/form-data">
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
            <select name="distribusi_id" id="distribusi_id" required disabled>
                <option value="">-- Pilih sekolah dulu --</option>
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

            <!-- Input upload bukti -->
            <label for="bukti">Bukti pendukung (opsional)</label>
            <input type="file" name="bukti" id="bukti" accept=".jpg,.jpeg,.png,.pdf">
            <small>Format JPG, PNG, atau PDF. Maksimal 2 MB.</small>

            <p style="margin-top:18px;">
                <button type="submit">Kirim Laporan</button>
                <a href="{{ route('laporan.index') }}" class="btn btn-light">Batal</a>
            </p>
        </form>
    </div>

    <script>
        const sekolahSelect = document.getElementById('sekolah_id');
        const distribusiSelect = document.getElementById('distribusi_id');
        const urlTemplate = @json(route('sekolah.distribusi', ['sekolah' => '__ID__']));
        const distribusiLama = @json(old('distribusi_id'));

        function setPlaceholder(teks) {
            distribusiSelect.innerHTML = '';
            const opt = document.createElement('option');
            opt.value = '';
            opt.textContent = teks;
            distribusiSelect.appendChild(opt);
        }

        async function muatDistribusi(sekolahId, dipilih) {
            if (!sekolahId) {
                setPlaceholder('-- Pilih sekolah dulu --');
                distribusiSelect.disabled = true;
                return;
            }

            setPlaceholder('Memuat distribusi...');
            distribusiSelect.disabled = true;

            try {
                const response = await fetch(urlTemplate.replace('__ID__', sekolahId), {
                    headers: { 'Accept': 'application/json' },
                });

                if (!response.ok) {
                    throw new Error('Gagal memuat distribusi');
                }

                const data = await response.json();

                setPlaceholder(data.length ? '-- Pilih distribusi --' : 'Belum ada distribusi untuk sekolah ini');

                data.forEach(function (d) {
                    const opt = document.createElement('option');
                    opt.value = d.id;
                    opt.textContent = d.tanggal + ' | ' + (d.menu ?? '-');

                    if (dipilih && String(dipilih) === String(d.id)) {
                        opt.selected = true;
                    }

                    distribusiSelect.appendChild(opt);
                });

                distribusiSelect.disabled = data.length === 0;
            } catch (error) {
                setPlaceholder('Gagal memuat distribusi, coba lagi');
                distribusiSelect.disabled = true;
            }
        }

        sekolahSelect.addEventListener('change', function () {
            muatDistribusi(sekolahSelect.value, null);
        });

        if (sekolahSelect.value) {
            muatDistribusi(sekolahSelect.value, distribusiLama);
        }
    </script>
@endsection
