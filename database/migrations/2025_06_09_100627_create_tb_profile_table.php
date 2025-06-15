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
        Schema::create('tb_profile', function (Blueprint $table) {
            $table->id('IdProfile');
            $table->unsignedBigInteger('IdUsers');
            $table->string('name', 50);
            $table->enum('gender', ['L', 'P']);
            $table->string('nik', 50);
            $table->date('tanggalLahir');
            $table->text('address');
            $table->string('foto', 255);
            $table->bigInteger('noTelepon');

            $table->foreign('IdUsers')->references('IdUsers')->on('tb_users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_profile');
    }
};
