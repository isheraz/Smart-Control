<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Appliance extends Model
{
	protected $guarded = [];
	public function device() {
		return $this->belongsTo( 'App\SmartHome', 'id' );
	}
}
