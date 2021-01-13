<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class MKelurahan extends Model
{
    protected $table = 'm_kelurahan';

    public function getRouteKeyName()
    {
        return 'id_kelurahan';
    }
}
