<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReportRequest;
use App\Models\Distribusi;
use App\Models\Laporan;
use App\Models\Sekolah;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $laporan = Laporan::where('user_id', $request->user()->getKey())
            ->with('distribusi.sekolah')
            ->latest()
            ->paginate(10);

        return view('laporan.index', compact('laporan'));
    }

    public function create()
    {
        $sekolah = Sekolah::orderBy('nama_sekolah')->get();
        $distribusi = Distribusi::with('sekolah')->orderByDesc('tanggal')->get();

        return view('laporan.create', compact('sekolah', 'distribusi'));
    }

    public function store(StoreReportRequest $request)
    {
        $laporan = new Laporan($request->safe()->only([
            'distribusi_id',
            'waktu_kejadian',
            'deskripsi',
            'gejala',
            'jumlah_terdampak',
        ]));
        $laporan->user_id = $request->user()->getKey();
        $laporan->save();

        return redirect()->route('laporan.index')
            ->with('success', 'Laporan berhasil dikirim.');
    }
}