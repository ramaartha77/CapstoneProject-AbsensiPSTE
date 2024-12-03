<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('t_smt', function (Blueprint $table) {
            $table->string('id_smt', 5)->primary(); // Primary Key
            $table->string('nama_smt', 45);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('t_smt');
    }
};
