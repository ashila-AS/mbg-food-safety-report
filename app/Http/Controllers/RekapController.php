<?php

namespace App\Http\Controllers;

use App\Models\Sekolah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class RekapController extends Controller
{
    private const DIKIRIM = 'Dikirim';
    private const DITINJAU = 'Ditinjau';
    private const DIKEMBALIKAN = 'Dikembalikan';
    private const DITANDAI = 'Ditandai untuk diteruskan';
    private const DITERUSKAN = 'Diteruskan';

    public function index(Request $request): View
    {
        $request->validate([
            'sekolah_id' => ['nullable', 'integer', 'exists:sekolah,sekolah_id'],
            'dari' => ['nullable', 'date'],
            'sampai' => ['nullable', 'date', 'after_or_equal:dari'],
        ]);

        $query = DB::table('laporan')
            ->join('distribusi', 'distribusi.distribusi_id', '=', 'laporan.distribusi_id')
            ->join('sekolah', 'sekolah.sekolah_id', '=', 'distribusi.sekolah_id');

        if ($request->filled('sekolah_id')) {
            $query->where('sekolah.sekolah_id', $request->integer('sekolah_id'));
        }

        if ($request->filled('dari')) {
            $query->whereDate('laporan.waktu_kejadian', '>=', $request->input('dari'));
        }

        if ($request->filled('sampai')) {
            $query->whereDate('laporan.waktu_kejadian', '<=', $request->input('sampai'));
        }

        $statusProses = [self::DIKIRIM, self::DITINJAU, self::DIKEMBALIKAN, self::DITANDAI];

        $total = (clone $query)->count();
        $dalamProses = (clone $query)->whereIn('laporan.status', $statusProses)->count();
        $diteruskan = (clone $query)->where('laporan.status', self::DITERUSKAN)->count();

        $rekap = (clone $query)
            ->selectRaw(
                'sekolah.sekolah_id,
                 sekolah.nama_sekolah,
                 COUNT(*) as total,
                 SUM(laporan.status = ?) as dikirim,
                 SUM(laporan.status = ?) as ditinjau,
                 SUM(laporan.status = ?) as dikembalikan,
                 SUM(laporan.status = ?) as ditandai,
                 SUM(laporan.status = ?) as diteruskan',
                [self::DIKIRIM, self::DITINJAU, self::DIKEMBALIKAN, self::DITANDAI, self::DITERUSKAN]
            )
            ->groupBy('sekolah.sekolah_id', 'sekolah.nama_sekolah')
            ->orderBy('sekolah.nama_sekolah')
            ->get();

        return view('rekap.index', [
            'sekolah' => Sekolah::orderBy('nama_sekolah')->get(),
            'total' => $total,
            'dalamProses' => $dalamProses,
            'diteruskan' => $diteruskan,
            'rekap' => $rekap,
        ]);
    }
}