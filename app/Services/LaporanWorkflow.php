<?php

namespace App\Services;

use App\Models\Laporan;
use App\Models\CatatanLaporan;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Penerusan;

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

        $needsNote = [Laporan::DIKEMBALIKAN, Laporan::DITANDAI];

        if (in_array($to, $needsNote, true) && ! $laporan->catatan()->exists()) {
            throw ValidationException::withMessages([
                'status' => 'Tulis minimal satu catatan sebelum mengubah status ini.',
            ]);
        }

        if ($to === Laporan::DITERUSKAN && ! $laporan->penerusan()->exists()) {
            throw ValidationException::withMessages([
                'status' => 'Catat penerusan sebelum mengubah status ini.',
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

        public function recordForwarding(Laporan $laporan, User $user, string $pihak, ?string $keterangan): void
    {
        if ($user->role !== 'koordinator') {
            throw ValidationException::withMessages([
                'pihak' => 'Hanya koordinator yang dapat mencatat penerusan.',
            ]);
        }

        // BR-03: penerusan hanya saat status Ditandai atau Diteruskan
        if (! in_array($laporan->status, [Laporan::DITANDAI, Laporan::DITERUSKAN], true)) {
            throw ValidationException::withMessages([
                'pihak' => "Penerusan tidak dapat dicatat saat laporan berstatus {$laporan->status}.",
            ]);
        }

        // BR-03: tidak boleh dua kali ke pihak yang sama
        if ($laporan->penerusan()->where('pihak', $pihak)->exists()) {
            throw ValidationException::withMessages([
                'pihak' => "Informasi sudah pernah diteruskan ke {$pihak}.",
            ]);
        }

        DB::transaction(function () use ($laporan, $user, $pihak, $keterangan) {
            Penerusan::create([
                'laporan_id' => $laporan->getKey(),
                'dicatat_oleh' => $user->getKey(),
                'pihak' => $pihak,
                'keterangan' => $keterangan,
            ]);

            if ($laporan->status === Laporan::DITANDAI) {
                $this->transition($laporan, Laporan::DITERUSKAN, $user);
            }
        });
    }

    public function assertEditable(Laporan $laporan): void
    {
        // BR-01: laporan hanya dapat diedit saat status Dikirim atau Dikembalikan
        if (! in_array($laporan->status, [Laporan::DIKIRIM, Laporan::DIKEMBALIKAN], true)) {
            throw ValidationException::withMessages([
                'status' => "Laporan tidak dapat diubah karena berstatus {$laporan->status}.",
            ]);
        }
    }

    public function updateReport(Laporan $laporan, User $user, array $data, ?UploadedFile $bukti = null): void
    {
        $this->assertEditable($laporan);

        DB::transaction(function () use ($laporan, $user, $data, $bukti) {
            $returned = $laporan->status === Laporan::DIKEMBALIKAN;
            $oldPath = $laporan->path_bukti;

            if ($bukti) {
                $data['path_bukti'] = $bukti->store('bukti', 'local');
            }

            $laporan->fill($data);
            $laporan->save();

            if ($returned) {
                $this->transition($laporan, Laporan::DIKIRIM, $user);
            }

            if ($bukti && $oldPath) {
                Storage::disk('local')->delete($oldPath);
            }
        });
    }
}