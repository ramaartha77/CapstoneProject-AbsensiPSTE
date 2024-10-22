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
        Schema::create('krs', function (Blueprint $table) {
            $table->unsignedBigInteger('id_akun'); // Foreign key to accounts
            $table->unsignedBigInteger('id_kelas'); // Foreign key to kelas (must match the id_kelas type in kelas table)

            // Foreign key constraints
            $table->foreign('id_akun')->references('id_akun')->on('accounts')->onDelete('cascade');
            $table->foreign('id_kelas')->references('id_kelas')->on('kelas')->onDelete('cascade');

            // Composite primary key
            $table->primary(['id_akun', 'id_kelas']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('krs');
    }
};
