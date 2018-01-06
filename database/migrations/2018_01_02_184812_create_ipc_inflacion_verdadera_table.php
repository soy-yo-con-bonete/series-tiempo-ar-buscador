<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIpcInflacionVerdaderaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('serie_inflacion_verdadera_hasta_2017_11', function (Blueprint $table) {
            $table->date('fecha');
            $table->decimal('valor', 30, 22);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('serie_inflacion_verdadera_hasta_2017_11');
    }
}
