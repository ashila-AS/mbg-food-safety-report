<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Services\LaporanWorkflow;
use Illuminate\Http\Request;
use App\Models\Sekolah;
use Illuminate\Validation\Rule;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $statuses = [
            Laporan::DIKIRIM,
            Laporan::DITINJAU,
            Laporan::DIKEMBALIKAN,
            Laporan::DITANDAI,
            Laporan::DITERUSKAN,
        ];

        $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', 'string', Rule::in($statuses)],
            'sekolah_id' => ['nullable', 'integer', 'exists:sekolah,sekolah_id'],
        ]);

        $query = Laporan::with('distribusi.sekolah');

        if ($request->filled('q')) {
            $q = trim($request->input('q'));

            $query->where(function ($w) use ($q) {
                $w->where('deskripsi', 'like', "%{$q}%")
                    ->orWhere('gejala', 'like', "%{$q}%")
                    ->orWhereHas('distribusi', function ($d) use ($q) {
                        $d->where('menu', 'like', "%{$q}%")
                            ->orWhereHas('sekolah', function ($s) use ($q) {
                                $s->where('nama_sekolah', 'like', "%{$q}%");
                            });
                    });

                if (ctype_digit($q)) {
                    $w->orWhere('laporan_id', (int) $q);
                }
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('sekolah_id')) {
            $sekolahId = $request->integer('sekolah_id');

            $query->whereHas('distribusi', function ($d) use ($sekolahId) {
                $d->where('sekolah_id', $sekolahId);
            });
        }

        $laporan = $query
            ->latest()
            ->orderByDesc('laporan_id')
            ->paginate(10)
            ->withQueryString();

        $sekolah = Sekolah::orderBy('nama_sekolah')->get();

        return view('tinjauan.index', compact('laporan', 'sekolah', 'statuses'));
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