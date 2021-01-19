<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Supply extends Model
{
    public static function insert($product, $input_date, $distributor_id)
    {
        foreach ($product as $value) {
            $source = 'W';
            if (isset($value['source_price'])) {
                $source = 'H';
            }
            $db = new Supply();
            if (empty($distributor_id)) {
                $db->note = $value['product_code'];
            } else {
                $db->distributor_product_id = $value['id'];
            }
            $db->qty = $value['qty'];
            $db->price = $value['price'];
            $db->total = $value['qty'] * $value['price'];
            $db->source = $source;
            $db->delivery_date = $input_date;
            $db->save();
        }
    }
}
