<?php

namespace App\Policies;

use App\Models\Laporan;
use App\Models\User;

class LaporanPolicy
{
    public function view(User $user, Laporan $laporan): bool
    {
        if ($user->role === 'pelapor') {
            return $this->isOwner($user, $laporan);
        }

        return in_array($user->role, ['pemantau', 'koordinator'], true);
    }

    public function update(User $user, Laporan $laporan): bool
    {
        return $user->role === 'pelapor' && $this->isOwner($user, $laporan);
    }

    public function downloadEvidence(User $user, Laporan $laporan): bool
    {
        return $this->view($user, $laporan);
    }

    private function isOwner(User $user, Laporan $laporan): bool
    {
        return (int) $laporan->user_id === (int) $user->getKey();
    }
}