<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReportRequest;
use App\Models\Distribusi;
use App\Models\Laporan;
use App\Models\Sekolah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\UpdateReportRequest;
use App\Services\LaporanWorkflow;
use Illuminate\Validation\ValidationException;

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

        if ($request->hasFile('bukti')) {
            $laporan->path_bukti = $request->file('bukti')->store('bukti', 'local');
        }

        $laporan->save();

        return redirect()->route('laporan.index')
            ->with('success', 'Laporan berhasil dikirim.');
    }

    public function show(Request $request, Laporan $laporan)
    {
        Gate::authorize('view', $laporan);

        $laporan->load('distribusi.sekolah');

        if ($request->user()->role === 'pelapor') {
            $laporan->load('pelapor');
        } else {
            $laporan->load('catatan.penulis');
        }

        return view('laporan.show', compact('laporan'));
    }

    public function evidence(Laporan $laporan)
    {
        Gate::authorize('downloadEvidence', $laporan);

        abort_if(
            ! $laporan->path_bukti || ! Storage::disk('local')->exists($laporan->path_bukti),
            404
        );

        return Storage::disk('local')->response($laporan->path_bukti);
    }

    public function edit(Laporan $laporan, LaporanWorkflow $workflow)
    {
        Gate::authorize('update', $laporan);

        try {
            $workflow->assertEditable($laporan);
        } catch (ValidationException $e) {
            return redirect()->route('laporan.show', $laporan)
                ->withErrors($e->errors());
        }

        $laporan->load('distribusi.sekolah');

        return view('laporan.edit', compact('laporan'));
    }

    public function update(UpdateReportRequest $request, Laporan $laporan, LaporanWorkflow $workflow)
    {
        try {
            $workflow->updateReport(
                $laporan,
                $request->user(),
                $request->safe()->only(['waktu_kejadian', 'deskripsi', 'gejala', 'jumlah_terdampak']),
                $request->file('bukti')
            );
        } catch (ValidationException $e) {
            return redirect()->route('laporan.show', $laporan)
                ->withErrors($e->errors());
        }

        return redirect()->route('laporan.show', $laporan)
            ->with('success', 'Laporan berhasil diperbarui.');
    }
}