<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProductSales extends Model
{
    protected $table = 'product_sales';
    // public $timestamps = false;

    public static function getNumerator()
    {
        $numerator = DB::select("select get_numerator()");
        $a = json_decode(json_encode($numerator), true);
        return $a[0]['get_numerator'];
    }

    public static function insert($product, $numerator)
    {
        foreach ($product as $value) {
            $db = new ProductSales();
            $db->distributor_product_id = $value['id'];
            $db->qty = $value['qty'];
            $db->discon_price = $value['discon'];
            $db->sales_date = date('d/m/Y');
            $db->numerator = $numerator;
            $db->save();
        }
    }
}
