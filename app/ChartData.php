<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChartData extends Model
{
    protected $gaurded = [];
    protected $hidden = ['id','chart_id','created_at','updated_at'];

    function getChart(){
        $this->belongsTo('App\Chart','chart_id', 'id');
    }
}
