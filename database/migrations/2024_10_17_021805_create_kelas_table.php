<?php
// 2024_10_17_021805_create_kelas_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('kelas', function (Blueprint $table) {
            $table->id('id_kelas');
            $table->string('id_matkul', 45);
            $table->unsignedBigInteger('id_akun');
            $table->string('nama_kelas', 50);
            $table->unsignedBigInteger('id_ruangan');
            $table->enum('hari', ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat']);
            $table->string('waktu_mulai', 45);
            $table->string('waktu_selesai', 45);
            $table->string('id_smt', 5);


            $table->timestamps();

            $table->foreign('id_matkul')
                ->references('id_matkul')
                ->on('matkuls')
                ->onDelete('cascade');

            $table->foreign('id_akun')
                ->references('id_akun')
                ->on('accounts')
                ->onDelete('cascade');

            $table->foreign('id_ruangan')->references('id_ruangan')->on('t_ruangan')->onDelete('cascade');
            $table->foreign('id_smt')->references('id_smt')->on('t_smt')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('kelas');
    }
};
