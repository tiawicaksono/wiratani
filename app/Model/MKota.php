<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class MKota extends Model
{
    protected $table = 'm_kota';

    public function getRouteKeyName()
    {
        return 'id_kota';
    }
}
