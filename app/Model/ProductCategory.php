<?php

namespace App\Model;

use App\Model\Product;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    protected $table = 'product_categorys';

    public static function getProductCategory()
    {
        $findAll = ProductCategory::get();
        foreach ($findAll as $dataCategory) :
            $result["category"][$dataCategory->id] = $dataCategory;
            $dataCategory->id;
            $findAllProduct = Product::where('product_category_id', $dataCategory->id)
                ->orderBy('product_name', 'asc')
                ->get();
            foreach ($findAllProduct as $dataProduct) :
                $result["product"][$dataCategory->id][] = $dataProduct;
            endforeach;
        endforeach;
        return $result;
    }
}
