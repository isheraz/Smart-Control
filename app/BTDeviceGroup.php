<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BTDeviceGroup extends Model
{
    protected $hidden = ['id','name','allowed','created_at','updated_at'];
}
