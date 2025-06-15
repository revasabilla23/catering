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
        Schema::create('tb_logs', function (Blueprint $table) {
            $table->id('IdLogs');
            $table->unsignedBigInteger('IdUser'); // yang scan (Karyawan)
            $table->unsignedBigInteger('IdQrToken');
            $table->boolean('statusLogs'); // true = Berhasil, false = Gagal
            $table->text('reason')->nullable();
            $table->unsignedBigInteger('scannedBy'); // siapa yang scan (Koki atau HRGA)
            $table->timestamp('scannedAt');

            $table->foreign('IdUser')->references('IdUsers')->on('tb_users')->onDelete('cascade');
            $table->foreign('IdQrToken')->references('IdQrToken')->on('tb_qrToken')->onDelete('cascade');
            $table->foreign('scannedBy')->references('IdUsers')->on('tb_users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_logs');
    }
};
