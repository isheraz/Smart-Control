<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chart extends Model
{
    protected $guarded = [];

    public function smartHome(){
        return $this->belongsTo('App\SmartHome', 'device_id');
    }

    public function chartValues(){
        return $this->hasMany('App\ChartData', 'chart_id', 'id');
    }
}
