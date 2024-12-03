<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('t_ruangan', function (Blueprint $table) {
            $table->id('id_ruangan'); // Menjadikan id_ruangan sebagai primary key
            $table->string('nama_ruangan', 45);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('t_ruangan');
    }
};
