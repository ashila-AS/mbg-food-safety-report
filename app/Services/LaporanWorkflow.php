<?php

namespace App\Services;

use App\Models\Laporan;
use App\Models\CatatanLaporan;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class LaporanWorkflow
{
    // BR-02: perubahan status hanya mengikuti alur ini.
    // Bentuknya: status asal => [status tujuan => role yang boleh menjalankan]
    private const TRANSITIONS = [
        Laporan::DIKIRIM => [
            Laporan::DITINJAU => 'pemantau',
        ],
        Laporan::DITINJAU => [
            Laporan::DIKEMBALIKAN => 'pemantau',
            Laporan::DITANDAI => 'pemantau',
        ],
        Laporan::DIKEMBALIKAN => [
            Laporan::DIKIRIM => 'pelapor',
        ],
        Laporan::DITANDAI => [
            Laporan::DITERUSKAN => 'koordinator',
        ],
    ];

    public function canTransition(Laporan $laporan, string $to, User $user): bool
    {
        return (self::TRANSITIONS[$laporan->status][$to] ?? null) === $user->role;
    }

    public function transition(Laporan $laporan, string $to, User $user): void
    {
        if (! $this->canTransition($laporan, $to, $user)) {
            throw ValidationException::withMessages([
                'status' => "Perubahan status dari {$laporan->status} ke {$to} tidak diizinkan.",
            ]);
        }

        $laporan->status = $to;
        $laporan->save();
    }

    public function addNote(Laporan $laporan, User $user, string $isi): void
    {
        if ($user->role !== 'pemantau' || $laporan->status !== Laporan::DITINJAU) {
            throw ValidationException::withMessages([
                'isi' => 'Catatan hanya dapat ditulis pemantau saat laporan berstatus Ditinjau.',
            ]);
        }

        CatatanLaporan::create([
            'laporan_id' => $laporan->getKey(),
            'user_id' => $user->getKey(),
            'isi' => $isi,
        ]);
    }
}