<?php

namespace Database\Seeders;

use App\Models\CatatanLaporan;
use App\Models\Distribusi;
use App\Models\Laporan;
use App\Models\Penerusan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;

class LaporanSeeder extends Seeder
{
    private const DESKRIPSI = [
        'Beberapa siswa mengeluh tidak enak badan beberapa jam setelah makan siang dari program MBG di sekolah.',
        'Orang tua melaporkan anaknya merasa tidak nyaman setelah mengonsumsi makanan yang dibagikan di sekolah.',
        'Sejumlah siswa di satu kelas melaporkan keluhan serupa setelah jam makan siang.',
        'Pelapor mendengar dari beberapa orang tua bahwa anak mereka mengalami keluhan setelah menyantap makanan MBG.',
        'Makanan yang diterima siswa tampak berbau tidak biasa dan beberapa siswa mengeluh setelahnya.',
        'Setelah pembagian makanan, guru menyampaikan ada siswa yang meminta izin ke ruang kesehatan sekolah.',
    ];

    private const GEJALA = [
        'Mual dan sakit perut',
        'Pusing dan lemas',
        'Mual dan muntah ringan',
        'Sakit kepala dan mual',
        'Diare ringan dan sakit perut',
        'Sakit perut dan tidak nafsu makan',
    ];

    private const CATATAN_UMUM = [
        'Data laporan sudah lengkap, ada beberapa laporan lain pada distribusi yang sama.',
        'Informasi pada laporan cukup jelas dan perlu disampaikan kepada pihak terkait.',
        'Isi laporan konsisten dengan laporan lain pada sekolah yang sama.',
    ];

    private const CATATAN_KEMBALI = [
        'Mohon lengkapi keterangan jumlah siswa yang terdampak dan waktu keluhan mulai muncul.',
        'Mohon tambahkan bukti pendukung jika tersedia dan perjelas gejala yang dialami.',
    ];

    public function run(): void
    {
        $pelapor = [
            User::where('email', 'pelapor1@mbg.test')->firstOrFail(),
            User::where('email', 'pelapor2@mbg.test')->firstOrFail(),
        ];
        $pemantau = User::where('email', 'pemantau@mbg.test')->firstOrFail();
        $koordinator = User::where('email', 'koordinator@mbg.test')->firstOrFail();
        $distribusi = Distribusi::orderBy('distribusi_id')->get();

        $rencanaStatus = [
            Laporan::DIKIRIM,
            Laporan::DITINJAU,
            Laporan::DIKEMBALIKAN,
            Laporan::DITANDAI,
            Laporan::DITERUSKAN,
        ];

        for ($i = 0; $i < 28; $i++) {
            $pemilik = $i % 3 === 0 ? $pelapor[1] : $pelapor[0];
            $dist = $distribusi[$i % $distribusi->count()];
            $waktu = $dist->tanggal->copy()->setTime(11 + $i % 6, ($i * 7) % 60);

            Auth::setUser($pemilik);

            $laporan = new Laporan([
                'distribusi_id' => $dist->distribusi_id,
                'waktu_kejadian' => $waktu,
                'deskripsi' => self::DESKRIPSI[$i % count(self::DESKRIPSI)],
                'gejala' => self::GEJALA[$i % count(self::GEJALA)],
                'jumlah_terdampak' => 3 + ($i * 2) % 17,
            ]);
            $laporan->user_id = $pemilik->user_id;
            $laporan->status = Laporan::DIKIRIM;
            $laporan->created_at = $waktu->copy()->addHours(2);
            $laporan->save();

            $this->proses($laporan, $rencanaStatus[$i % 5], $i, $pemantau, $koordinator);
        }

        Auth::forgetUser();
    }

    private function proses(Laporan $laporan, string $target, int $i, User $pemantau, User $koordinator): void
    {
        if ($target === Laporan::DIKIRIM) {
            return;
        }

        Auth::setUser($pemantau);

        $laporan->peninjau_id = $pemantau->user_id;
        $laporan->status = Laporan::DITINJAU;
        $laporan->save();

        if ($target === Laporan::DITINJAU) {
            if ($i % 2 === 0) {
                $this->catatan($laporan, $pemantau, self::CATATAN_UMUM[$i % count(self::CATATAN_UMUM)]);
            }

            return;
        }

        if ($target === Laporan::DIKEMBALIKAN) {
            $this->catatan($laporan, $pemantau, self::CATATAN_KEMBALI[$i % count(self::CATATAN_KEMBALI)]);
            $laporan->status = Laporan::DIKEMBALIKAN;
            $laporan->save();

            return;
        }

        $this->catatan($laporan, $pemantau, self::CATATAN_UMUM[$i % count(self::CATATAN_UMUM)]);
        $laporan->status = Laporan::DITANDAI;
        $laporan->save();

        if ($target === Laporan::DITANDAI) {
            return;
        }

        Auth::setUser($koordinator);

        $pihak = Penerusan::PIHAK[intdiv($i, 5) % count(Penerusan::PIHAK)];
        $this->penerusan($laporan, $koordinator, $pihak);

        if ($i % 10 === 4) {
            $this->penerusan($laporan, $koordinator, 'SPPG');
        }

        $laporan->status = Laporan::DITERUSKAN;
        $laporan->save();
    }

    private function catatan(Laporan $laporan, User $penulis, string $isi): void
    {
        CatatanLaporan::create([
            'laporan_id' => $laporan->laporan_id,
            'user_id' => $penulis->user_id,
            'isi' => $isi,
        ]);
    }

    private function penerusan(Laporan $laporan, User $pencatat, string $pihak): void
    {
        Penerusan::create([
            'laporan_id' => $laporan->laporan_id,
            'dicatat_oleh' => $pencatat->user_id,
            'pihak' => $pihak,
            'keterangan' => 'Informasi laporan diteruskan untuk ditindaklanjuti sesuai kewenangan pihak terkait.',
        ]);
    }
}