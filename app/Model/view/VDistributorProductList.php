<?php

namespace App\Model\view;

use Illuminate\Database\Eloquent\Model;

class VDistributorProductList extends Model
{
    protected $table = 'v_distributor_product_list';

    public static function get()
    {
        // $data = static::orderBy('product_name', 'asc')
        //     ->pluck('product_name', 'id');
        $data = static::orderBy('distributor_name', 'asc')->get();
        return $data;
    }
}
