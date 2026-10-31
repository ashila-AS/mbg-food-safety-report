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
        Schema::create('distribusi', function (Blueprint $table) {
            $table->id('distribusi_id');
            $table->foreignId('sekolah_id')
                ->constrained('sekolah', 'sekolah_id')
                ->restrictOnDelete();
            $table->date('tanggal');
            $table->string('menu', 100)->nullable();
            $table->string('nama_sppg', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('distribusi');
    }
};
