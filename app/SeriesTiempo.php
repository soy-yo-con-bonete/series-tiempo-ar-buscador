<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SeriesTiempo extends Model
{
	use FullTextSearch;

	protected $table = 'series_tiempo';

	/**
	 * The attributes that should be mutated to dates.
	 *
	 * @var array
	 */
	protected $dates = [
		'serie_indice_inicio',
		'serie_indice_final',
	];

	protected $searchable = [
		'serie_titulo',
		'serie_descripcion',
	];

}
