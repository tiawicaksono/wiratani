<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Alfa6661\AutoNumber\AutoNumberTrait;

class DistributorProductList extends Model
{
    use AutoNumberTrait;
    protected $table = 'distributor_products_list';
    public $incrementing = true;
    public $timestamps = false;
    protected $fillable = ['id', 'distributor_id', 'product_id', 'code'];

    public static function getAutoNumberOptions()
    {
        return [
            'product_code' => [
                'format' => 'WRTN.?', // Format kode yang akan digunakan.
                'length' => 5 // Jumlah digit yang akan digunakan sebagai nomor urut
            ]
        ];
    }
}
