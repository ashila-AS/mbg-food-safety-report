<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('catatan_laporan', function (Blueprint $table) {
            $table->id('catatan_id');
            $table->foreignId('laporan_id')
                ->constrained('laporan', 'laporan_id')
                ->restrictOnDelete();
            $table->foreignId('user_id')
                ->constrained('users', 'user_id')
                ->restrictOnDelete();
            $table->text('isi');
            $table->timestamp('waktu')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catatan_laporan');
    }
};
