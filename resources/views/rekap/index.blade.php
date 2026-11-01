
@extends('layouts.app')

@section('title', 'Rekap Laporan')

@section('content')

    <!-- FILTER LAPORAN -->
    <div class="card">
        <h2>Rekap Laporan</h2>

        @if ($errors->any())
            <div class="alert alert-error">
                {{ $errors->first() }}
            </div>
        @endif

        <form
            method="GET"
            action="{{ route('rekap.index') }}"
            style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 12px; align-items: end;"
        >
            <div>
                <label for="sekolah_id">Sekolah</label>

                <select id="sekolah_id" name="sekolah_id">
                    <option value="">Semua sekolah</option>

                    @foreach ($sekolah as $s)
                        <option
                            value="{{ $s->sekolah_id }}"
                            @selected((string) request('sekolah_id') === (string) $s->sekolah_id)
                        >
                            {{ $s->nama_sekolah }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="dari">Kejadian dari</label>

                <input
                    type="date"
                    id="dari"
                    name="dari"
                    value="{{ request('dari') }}"
                >
            </div>

            <div>
                <label for="sampai">Kejadian sampai</label>

                <input
                    type="date"
                    id="sampai"
                    name="sampai"
                    value="{{ request('sampai') }}"
                >
            </div>

            <div>
                <button type="submit">Terapkan</button>

                <a href="{{ route('rekap.index') }}" class="btn btn-light">
                    Reset
                </a>
            </div>
        </form>
    </div>


    <!-- RINGKASAN LAPORAN -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin: 16px 0;">

        <div class="card">
            <div>Total laporan</div>
            <h1 style="margin: 6px 0 0;">
                {{ $total }}
            </h1>
        </div>

        <div class="card">
            <div>Masih diproses</div>
            <h1 style="margin: 6px 0 0;">
                {{ $dalamProses }}
            </h1>
        </div>

        <div class="card">
            <div>Sudah diteruskan</div>
            <h1 style="margin: 6px 0 0;">
                {{ $diteruskan }}
            </h1>
        </div>

    </div>


    <!-- TABEL LAPORAN PER SEKOLAH -->
    <div class="card">
        <h3>Laporan per sekolah</h3>

        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th>Sekolah</th>
                        <th>Dikirim</th>
                        <th>Ditinjau</th>
                        <th>Dikembalikan</th>
                        <th>Ditandai</th>
                        <th>Diteruskan</th>
                        <th>Total</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($rekap as $baris)
                        <tr>
                            <td>{{ $baris->nama_sekolah }}</td>
                            <td>{{ $baris->dikirim }}</td>
                            <td>{{ $baris->ditinjau }}</td>
                            <td>{{ $baris->dikembalikan }}</td>
                            <td>{{ $baris->ditandai }}</td>
                            <td>{{ $baris->diteruskan }}</td>
                            <td>
                                <strong>{{ $baris->total }}</strong>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                Tidak ada laporan untuk filter ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection
