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
        Schema::create('laporan', function (Blueprint $table) {
            $table->id('laporan_id');
            $table->foreignId('user_id')
                ->constrained('users', 'user_id')
                ->restrictOnDelete();
            $table->foreignId('distribusi_id')
                ->constrained('distribusi', 'distribusi_id')
                ->restrictOnDelete();
            $table->foreignId('peninjau_id')->nullable()
                ->constrained('users', 'user_id')
                ->restrictOnDelete();
            $table->dateTime('waktu_kejadian');
            $table->text('deskripsi');
            $table->text('gejala');
            $table->unsignedInteger('jumlah_terdampak');
            $table->string('path_bukti', 100)->nullable();
            $table->string('status', 30)->default('Dikirim');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan');
    }
};
