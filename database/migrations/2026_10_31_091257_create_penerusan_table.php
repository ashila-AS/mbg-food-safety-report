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
        Schema::create('penerusan', function (Blueprint $table) {
            $table->id('penerusan_id');
            $table->foreignId('laporan_id')
                ->constrained('laporan', 'laporan_id')
                ->restrictOnDelete();
            $table->foreignId('dicatat_oleh')
                ->constrained('users', 'user_id')
                ->restrictOnDelete();
            $table->string('pihak', 60);
            $table->text('keterangan')->nullable();
            $table->timestamp('waktu')->useCurrent();
            $table->unique(['laporan_id', 'pihak']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penerusan');
    }
};
