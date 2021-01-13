<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class MKecamatan extends Model
{
    protected $table = 'm_kecamatan';

    public function getRouteKeyName()
    {
        return 'id_kecamatan';
    }
}
