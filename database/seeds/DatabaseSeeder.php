<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call('UsersTableSeeder');

        echo "Downloading metadata...\n";
        $csvMetadata = file_get_contents('http://infra.datos.gob.ar/catalog/modernizacion/dataset/1/distribution/1.2/download/series-tiempo-metadatos.csv');
        echo "Done\n";

        // $csv = Reader::createFromPath('./series-tiempo-metadatos.csv', 'r');
        $csv = Reader::createFromString($csvMetadata);
        $csv->setHeaderOffset(0); //set the CSV header offset

        $records = $csv->getRecords();
        $insertArray = array();
        $chunkSize = 200;
        foreach ($records as $offset => $record) {
            try {
                switch ($record['indice_tiempo_frecuencia']) {
                    case 'R/P1Y':
                        $record['indice_tiempo_frecuencia_numerico'] = 360;
                        break;
                    case 'R/P6M':
                        $record['indice_tiempo_frecuencia_numerico'] = 180;
                        break;
                    case 'R/P3M':
                        $record['indice_tiempo_frecuencia_numerico'] = 90;
                        break;
                    case 'R/P1M':
                        $record['indice_tiempo_frecuencia_numerico'] = 30;
                        break;
                    case 'R/P1D':
                        $record['indice_tiempo_frecuencia_numerico'] = 1;
                        break;
                    default:
                        throw new Exception('Encontré un indice_tiempo_frecuencia desconocido.');
                        break;
                }
                $insertArray[] = $record;
                if ($offset % $chunkSize === 0) {
                    echo "Insertando filas " . ($offset - ($chunkSize - 1)) ."-$offset\n";
                    DB::table('series_tiempo')->insert($insertArray);
                    $insertArray = array();
                }
            } catch(Exception $e) {
                echo "Error importing .csv data. Message: " . $e->getMessage() . "\n";
                die();
            }
        }
        // Do the final insert
        echo "Insertando en tabla principal...\n";
        echo "Insertando filas remanentes hasta $offset\n";
        DB::table('series_tiempo')->insert($insertArray);

        // Correr tabla IPC
        $this->call('IPCTableSeeder');

    }
}
