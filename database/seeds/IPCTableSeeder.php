<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;

class IPCTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $chunkSize = 200;

        echo "Insertando en table IPC...\n";
        $csv = Reader::createFromPath('./ipc-consumidor-hasta-2016-11.csv', 'r');
        $csv->setHeaderOffset(0); //set the CSV header offset
        $records = $csv->getRecords();
        foreach ($records as $offset => $record) {
            try {
                $insertArray[] = $record;
                if ($offset % $chunkSize === 0) {
                    echo "Insertando filas " . ($offset - ($chunkSize - 1)) ."-$offset\n";
                    DB::table('serie_inflacion_verdadera_hasta_2017_11')->insert($insertArray);
                    $insertArray = array();
                }
            } catch(Exception $e) {
                echo "Error importing .csv data. Message: " . $e->getMessage() . "\n";
                die();
            }
        }
        echo "Insertando filas remanentes hasta $offset\n";
        DB::table('serie_inflacion_verdadera_hasta_2017_11')->insert($insertArray);

        echo "Bajando datos nuevos...\n";
        $newData = file_get_contents('http://apis.datos.gob.ar/series/api/series/?ids=103.1_I2N_2016_M_19&format=csv&start_date=2016-11-01');
        echo "Insertando datos nuevos...\n";
        $csv = Reader::createFromString($newData);
        $csv->setHeaderOffset(0); //set the CSV header offset
        $records = $csv->getRecords();
        foreach ($records as $offset => $record) {
            try {
                $insertArray = ["fecha" => $record["indice_tiempo"], "valor" => $record["ipc_2016_nivgeneral"]];
                DB::table('serie_inflacion_verdadera_hasta_2017_11')->insert($insertArray);
            } catch(Exception $e) {
                echo "Error importing .csv data. Message: " . $e->getMessage() . "\n";
                die();
            }
        }

    }
}
