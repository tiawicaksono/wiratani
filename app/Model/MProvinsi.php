<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class MProvinsi extends Model
{
    protected $table = 'm_provinsi';

    public function getRouteKeyName()
    {
        return 'id_provinsi';
    }
}
