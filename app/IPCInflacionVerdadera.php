<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IPCInflacionVerdadera extends Model
{
	protected $table = 'serie_inflacion_verdadera_hasta_2017_11';

	/**
	 * The attributes that should be mutated to dates.
	 *
	 * @var array
	 */
	protected $dates = [
		'fecha'
	];

    protected $casts = [
        'valor' => 'real',
    ];

}
