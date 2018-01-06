<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateMetadataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*Schema::create('series_tiempo', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
        });*/

        // TODO: This is just a hack for now. Even after fixed, a hack will still be needed for the fulltext index, not supported by Laravel.
        DB::unprepared('CREATE TABLE `series_tiempo` (
            `catalogo_id` TEXT NULL,
            `dataset_id` TEXT NULL,
            `distribucion_id` TEXT NULL,
            `serie_id` TEXT NULL,
            `indice_tiempo_frecuencia` TEXT NULL,
            `serie_titulo` TEXT NULL,
            `serie_unidades` TEXT NULL,
            `serie_descripcion` TEXT NULL,
            `distribucion_titulo` TEXT NULL,
            `distribucion_descripcion` TEXT NULL,
            `distribucion_url_descarga` TEXT NULL,
            `dataset_responsable` TEXT NULL,
            `dataset_fuente` TEXT NULL,
            `dataset_titulo` TEXT NULL,
            `dataset_descripcion` TEXT NULL,
            `dataset_tema` TEXT NULL,
            `serie_indice_inicio` DATETIME NULL DEFAULT NULL,
            `serie_indice_final` DATETIME NULL DEFAULT NULL,
            `serie_valores_cant` BIGINT(20) NULL DEFAULT NULL,
            `serie_dias_no_cubiertos` BIGINT(20) NULL DEFAULT NULL,
            `serie_actualizada` TINYINT(1) NULL DEFAULT NULL,
            `serie_valor_ultimo` FLOAT NULL DEFAULT NULL,
            `serie_valor_anterior` FLOAT NULL DEFAULT NULL,
            `serie_var_pct_anterior` FLOAT NULL DEFAULT NULL,
            `indice_tiempo_frecuencia_numerico` INT NULL DEFAULT NULL,
            FULLTEXT INDEX `serie_titulo` (`serie_titulo`, `serie_descripcion`)
        )
        COLLATE=\'utf8_general_ci\'
        ENGINE=InnoDB
');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('series_tiempo');
    }
}
