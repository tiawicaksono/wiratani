<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Withdraw extends Model
{
    public static function insert($product, $input_date)
    {
        foreach ($product as $value) {
            $db = new Withdraw();
            $db->note = $value['note'];
            $db->qty = $value['qty'];
            $db->price = $value['price'];
            $db->total = $value['qty'] * $value['price'];
            $db->delivery_date = $input_date;
            $db->save();
        }
    }
}
