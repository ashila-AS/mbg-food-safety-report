<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Services\LaporanWorkflow;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        $laporan = Laporan::with('distribusi.sekolah')
            ->latest()
            ->paginate(10);

        return view('tinjauan.index', compact('laporan'));
    }

    public function start(Request $request, Laporan $laporan, LaporanWorkflow $workflow)
    {
        $laporan->peninjau_id = $request->user()->getKey();
        $workflow->transition($laporan, Laporan::DITINJAU, $request->user());

        return redirect()->route('laporan.show', $laporan)
            ->with('success', 'Laporan mulai ditinjau.');
    }

    public function addNote(Request $request, Laporan $laporan, LaporanWorkflow $workflow)
    {
        $data = $request->validate([
            'isi' => ['required', 'string', 'min:5', 'max:1000'],
        ]);

        $workflow->addNote($laporan, $request->user(), $data['isi']);

        return redirect()->route('laporan.show', $laporan)
            ->with('success', 'Catatan disimpan.');
    }

    public function sendBack(Request $request, Laporan $laporan, LaporanWorkflow $workflow)
    {
        $workflow->transition($laporan, Laporan::DIKEMBALIKAN, $request->user());

        return redirect()->route('laporan.show', $laporan)
            ->with('success', 'Laporan dikembalikan kepada pelapor untuk dilengkapi.');
    }

    public function flag(Request $request, Laporan $laporan, LaporanWorkflow $workflow)
    {
        $workflow->transition($laporan, Laporan::DITANDAI, $request->user());

        return redirect()->route('laporan.show', $laporan)
            ->with('success', 'Laporan ditandai untuk diteruskan.');
    }
}