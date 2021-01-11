<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Streaming extends Model {
	protected $fillable = [
		'device_serial',
		's_name',
		's_link'		
	];
	
	
}
