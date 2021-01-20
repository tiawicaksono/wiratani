<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['id', 'product_name', 'product_category_id'];
    public static function ubah($id, $arData)
    {
        static::where('id', $id)->update($arData);
    }

    public static function get()
    {
        $data = static::select(
            'id',
            'product_name'
        )
            ->orderBy('product_name', 'asc')
            ->get();
        return $data;
    }

    public static function edit($product_id, $product_name, $product_category_id)
    {
        Product::where('id', $product_id)
            ->update([
                'product_name' => strtoupper($product_name),
                'product_category_id' => $product_category_id
            ]);
    }
}
