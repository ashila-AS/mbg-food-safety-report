<?php

namespace App\Http\Controllers;

use App\Models\Laporan;

class ReviewController extends Controller
{
    public function index()
    {
        $laporan = Laporan::with('distribusi.sekolah')
            ->latest()
            ->paginate(10);

        return view('tinjauan.index', compact('laporan'));
    }
}