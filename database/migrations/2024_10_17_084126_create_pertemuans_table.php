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
        Schema::create('pertemuans', function (Blueprint $table) {
            $table->increments('id_pertemuan');
            $table->unsignedBigInteger('id_kelas'); // Change to unsignedBigInteger
            $table->foreign('id_kelas')->references('id_kelas')->on('kelas')->onDelete('cascade'); // Ensure it matches
            $table->string('nama_pertemuan', 50);
            $table->dateTime('tgl_pertemuan');
            $table->string('materi', 255);
            $table->boolean('aktivasi_absen')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pertemuans');
    }
};