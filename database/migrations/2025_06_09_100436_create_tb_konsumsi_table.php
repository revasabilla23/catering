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
        Schema::create('tb_konsumsi', function (Blueprint $table) {
            $table->id('IdKonsumsi');
            $table->unsignedBigInteger('IdUsers');
            $table->unsignedBigInteger('IdShift');
            $table->unsignedBigInteger('IdPesanan'); // Relasi ke JadwalPesanan
            $table->date('tanggalKonsumsi');
            $table->enum('statusQr',['berhasil','gagal'])-> nullable(); 
            $table->timestamp('waktuScan');

            $table->foreign('IdUsers')->references('IdUsers')->on('tb_users')->onDelete('cascade');
            $table->foreign('IdShift')->references('IdShift')->on('tb_shift')->onDelete('cascade');
            $table->foreign('IdPesanan')->references('IdPesanan')->on('tb_jadwalPesanan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_konsumsi');
    }
};
