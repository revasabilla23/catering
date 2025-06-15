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
        Schema::create('tb_jadwalPesanan', function (Blueprint $table) {
            $table->id('IdPesanan');
            $table->date('tanggalPesanan');
            $table->unsignedBigInteger('IdShift');
            $table->unsignedBigInteger('IdMenu');
            $table->integer('JumlahPesanan');
            $table->boolean('statusPesanan')->default(false); // false = Unverify
            $table->timestamp('VerifAt')->nullable();

            $table->foreign('IdShift')->references('IdShift')->on('tb_shift')->onDelete('cascade');
            $table->foreign('IdMenu')->references('IdMenu')->on('tb_menu')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_jadwal_pesanan');
    }
};
