<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Label extends Model
{
    protected $guarded = [];

    public function smartHome(){
        return $this->belongsTo('App\SmartHome', 'device_id');
    }

    
}
