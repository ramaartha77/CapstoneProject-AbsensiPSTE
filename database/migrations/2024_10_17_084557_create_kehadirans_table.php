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
        Schema::create('kehadiran', function (Blueprint $table) {
            $table->increments('id_kehadiran');

            // Foreign Key to accounts (correct as is)
            $table->unsignedBigInteger('id_akun');
            $table->foreign('id_akun')->references('id_akun')->on('accounts')->onDelete('cascade');

            // Foreign Key to pertemuans (correct as is)
            $table->unsignedInteger('id_pertemuan');
            $table->foreign('id_pertemuan')->references('id_pertemuan')->on('pertemuans')->onDelete('cascade');

            // Foreign Key to alat_absen (correct as is)
            $table->string('id_alat_absen', 20);
            $table->foreign('id_alat_absen')->references('id_alat_absen')->on('alat_absen')->onDelete('cascade');

            // Other columns
            $table->dateTime('waktu_absen');
            $table->enum('status', ['hadir', 'tidak hadir', 'izin']);

            // Timestamps
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kehadiran');
    }
};
