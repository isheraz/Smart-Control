<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SmartHome extends Model {
	protected $fillable = [
		'batch',
		'serial',
		'user_id',
		'connection',
		'location_icon',
		'location_name'
	];
	
	public function user() {
		return $this->belongsTo( 'App\User', 'user_id' );
	}
	
	
	public function nodes(){
		return $this->hasMany('App\Appliance');
	}
	
	public function device_metas(){
		return $this->hasMany('App\SmartHomeMeta');
	}
}
