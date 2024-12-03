<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('matkuls', function (Blueprint $table) {
            $table->string('id_matkul', 45)->primary();
            $table->unsignedBigInteger('id_akun');
            $table->string('nama_matkul', 45);
            $table->tinyInteger('sks');
            $table->integer('semester');
            $table->timestamps();

            $table->foreign('id_akun')->references('id_akun')->on('accounts')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('matkuls');
    }
};
