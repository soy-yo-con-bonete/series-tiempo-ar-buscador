<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

// Buscar series
$router->get('query', function ()  {
    // DB::enableQueryLog();

    $inputs = \Illuminate\Support\Facades\Input::all();
	$query = trim($inputs['q']);
	$minPeriodicity = trim($inputs['min_periodicity']);
    $minLastYear = trim($inputs['min_last_year']);
    $maxFirstYear = trim($inputs['max_first_year']);
    $datasetTema = trim($inputs['dataset_tema']);



    $x = \App\SeriesTiempo::where('indice_tiempo_frecuencia_numerico', '<=', $minPeriodicity);
    $x = $x->whereYear('serie_indice_final', '>=', $minLastYear);
    $x = $x->whereYear('serie_indice_inicio', '<=', $maxFirstYear);
    if ($datasetTema !== 'any') $x = $x->where('dataset_tema', '=', $datasetTema);
    if (strlen($query) > 0) {
        $x = $x->search($query);
    }

    $x = $x->paginate(25);

	$queryStringExcludingPage = '';
	foreach( $inputs as $field => $query ){
		if( $field !== 'page' ) {
			$queryStringExcludingPage .= '&' . $field . '=' . $query ;
		}
	}
	// if there are next or previous pages then set URL
	$fields = ['first_page_url', 'last_page_url', 'next_page_url', 'prev_page_url'];
	foreach ($fields as $field) {
		if( ! is_null( $x[$field] ) ){
			$x[$field] .= $queryStringExcludingPage;
		}
	}

    // $laQuery = DB::getQueryLog();
    // dd($laQuery);


    return response($x)
		->header('Access-Control-Allow-Origin', '*');
});

// Mandar deflactor
$router->get('deflactor/mensual', function () {
    $output = collect();
    $x = \App\IPCInflacionVerdadera::orderBy('fecha')->get();
    foreach ($x as $record) {
        $recordArray = [$record->fecha->toDateString(), $record->valor];
        $output->push($recordArray);
    }

    $output = collect(["series_name_updated_data" => "103.1_I2N_2016_M_19", "data" => $output]);
    return response($output)
        ->header('Access-Control-Allow-Origin', '*');
});


