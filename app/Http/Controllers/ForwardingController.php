<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\Penerusan;
use App\Services\LaporanWorkflow;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ForwardingController extends Controller
{
    public function store(Request $request, Laporan $laporan, LaporanWorkflow $workflow)
    {
        $data = $request->validate([
            'pihak' => ['required', Rule::in(Penerusan::PIHAK)],
            'keterangan' => ['nullable', 'string', 'max:500'],
        ]);

        try {
            $workflow->recordForwarding(
                $laporan,
                $request->user(),
                $data['pihak'],
                $data['keterangan'] ?? null
            );
        } catch (ValidationException $e) {
            return redirect()->route('laporan.show', $laporan)
                ->withErrors($e->errors());
        }

        return redirect()->route('laporan.show', $laporan)
            ->with('success', 'Penerusan dicatat.');
    }
}