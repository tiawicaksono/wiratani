<?php

namespace App\model\view;

use Illuminate\Database\Eloquent\Model;

class VDistributorProduct extends Model
{
    protected $table = 'v_distributor_product';
    // protected $primaryKey = 'pegawai_id';
    // public $incrementing = false;
    // protected $keyType = 'string';
    // public $timestamps = false;

    public static function get()
    {
        $data = static::select(
            'id',
            'distributor_id',
            'distributor_name',
            'product_id',
            'barcode',
            'product_name',
            'delivery_date',
            'total_product',
            'stock_product',
            'purchase_price',
            'selling_price',
            'distributor_products_list_id',
            'profit'
        )
            ->orderBy('product_name', 'asc')
            ->get();
        return $data;
    }
}
