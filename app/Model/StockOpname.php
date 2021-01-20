<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class StockOpname extends Model
{
    public $incrementing = true;
    // public $timestamps = false;
    protected $fillable = ['id', 'distributor_product_id', 'qty', 'note', 'input_date'];

    public static function ubah($id, $arData)
    {
        static::where('id', $id)->update($arData);
    }
}
