<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Alfa6661\AutoNumber\AutoNumberTrait;

class DistributorProduct extends Model
{
    use AutoNumberTrait;
    protected $table = 'distributor_products';
    // protected $primaryKey = 'id';
    public $incrementing = true;
    // public $timestamps = false;
    protected $fillable = ['id', 'delivery_date', 'total_product', 'purchase_price', 'selling_price', 'distributor_products_list_id'];

    public static function getAutoNumberOptions()
    {
        return [
            'barcode' => [
                'format' => 'BARCODE.?', // Format kode yang akan digunakan.
                'length' => 5 // Jumlah digit yang akan digunakan sebagai nomor urut
            ]
        ];
    }

    public static function ubah($id, $arData)
    {
        static::where('id', $id)->update($arData);
    }

    public static function tambah($arData)
    {
        static::create($arData);
    }
}
