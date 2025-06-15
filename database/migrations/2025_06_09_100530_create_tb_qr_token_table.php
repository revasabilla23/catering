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
        Schema::create('tb_qrToken', function (Blueprint $table) {
            $table->id('IdQrToken');
            $table->unsignedBigInteger('IdUsers');
            $table->string('token', 255);
            $table->timestamp('create')->useCurrent(); // OK
            $table->timestamp('expired')->nullable(); // Perbaiki di sini
        
            $table->foreign('IdUsers')->references('IdUsers')->on('tb_users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_qr_token');
    }
};
