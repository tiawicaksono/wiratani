<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Model\Distributor;
use App\model\DistributorProduct;
use App\model\Product;
use App\model\view\VDistributorProduct;
use App\Model\view\VDistributorProductList;
use DateTime;
use Illuminate\Http\Request;

class ProductPricesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = VDistributorProduct::get();
        $listProduct = VDistributorProductList::get();
        $distributor = Distributor::orderBy('distributor_name', 'asc')->first()->distributor_name;
        return view('product_prices.index', compact('data', 'listProduct', 'distributor'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->ajax()) {
            $distributor_product_list_id = $request->product_id;
            $total_product = $request->total_product;
            $purchase_price = $request->purchase_price;
            $selling_price = $request->selling_price;
            $delivery_date = $request->delivery_date;

            try {
                $arDataDistributorProduct = array(
                    'total_product' => $total_product,
                    'purchase_price' => $purchase_price,
                    'selling_price' => $selling_price,
                    'delivery_date' => $delivery_date,
                    'distributor_products_list_id' => $distributor_product_list_id,
                );
                $id = DistributorProduct::create($arDataDistributorProduct)->id;

                $getData = VDistributorProduct::select(
                    'distributor_name',
                    'product_name',
                    'stock_product',
                    'profit'
                )
                    ->where('id', $id)
                    ->first();
                $output['id'] = $id;
                $output['product_name'] = $getData->product_name;
                $output['distributor_name'] = $getData->distributor_name;
                $output['purchase_price'] = number_format($purchase_price, 0, ',', '.');
                $output['selling_price'] = number_format($selling_price, 0, ',', '.');
                // $output['purchase_price_ori'] = $getData->purchase_price;
                // $output['selling_price_ori'] = $getData->selling_price;
                $output['total_product'] = $total_product;
                $output['stock_product'] = $getData->stock_product;
                $output['profit'] = $getData->profit;
                $output['delivery_date'] = $delivery_date;
                $tglDelivery = DateTime::createFromFormat('d/m/Y', $delivery_date);
                $output['format_delivery_date'] = Helpers::customDate($tglDelivery->format('m/d/Y'), 'short');
                $output['status'] = 'ok';
            } catch (\Throwable $th) {
                $output['status'] = 'error';
            }

            return response()->json($output);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if ($request->ajax()) {
            $distributor_product_id = $request->id;
            $distributor_product_list_id = $request->product_id;
            $total_product = $request->total_product;
            $purchase_price = $request->purchase_price;
            $selling_price = $request->selling_price;
            $delivery_date = $request->delivery_date;

            try {
                $arDataDistributorProduct = array(
                    'total_product' => $total_product,
                    'purchase_price' => $purchase_price,
                    'selling_price' => $selling_price,
                    'delivery_date' => $delivery_date,
                    'distributor_products_list_id' => $distributor_product_list_id,
                );
                DistributorProduct::ubah($distributor_product_id, $arDataDistributorProduct);

                $getData = VDistributorProduct::select(
                    'product_name',
                    'stock_product',
                    'profit'
                )
                    ->where('id', $distributor_product_id)
                    ->first();
                $output['purchase_price'] = number_format($purchase_price, 0, ',', '.');
                $output['selling_price'] = number_format($selling_price, 0, ',', '.');
                // $output['purchase_price_ori'] = $getData->purchase_price;
                // $output['selling_price_ori'] = $getData->selling_price;
                $output['product_name'] = $getData->product_name;
                $output['total_product'] = $total_product;
                $output['stock_product'] = $getData->stock_product;
                $output['profit'] = $getData->profit;
                $tglDelivery = DateTime::createFromFormat('d/m/Y', $delivery_date);
                $output['delivery_date'] = $delivery_date;
                $output['format_delivery_date'] = Helpers::customDate($tglDelivery->format('m/d/Y'), 'short');
                $output['status'] = 'ok';
            } catch (\Throwable $th) {
                //throw $th;
                $output['status'] = 'error';
            }

            return response()->json($output);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if ($request->ajax()) {
            try {
                DistributorProduct::destroy($request->id);
                $output['status'] = 'ok';
                $output['msg'] = 'ok';
            } catch (\Throwable $th) {
                //throw $th;
                $output['status'] = 'error';
                $output['msg'] = "Can't be deleted, because the transaction has already been used";
            }
            return response()->json($output);
        }
    }

    public function detailProduct(Request $request)
    {
        if ($request->ajax()) {
            $output['purchase_price'] = '';
            $output['selling_price'] = '';
            try {
                $arPrice = DistributorProduct::select('purchase_price', 'selling_price')
                    ->where('distributor_products_list_id', $request->id)
                    ->orderBy('delivery_date', 'DESC')
                    ->first();
                $output['purchase_price'] = $arPrice->purchase_price;
                $output['selling_price'] = $arPrice->selling_price;
            } catch (\Throwable $th) {
            }
            return response()->json($output);
        }
    }

    public function selectpicker(Request $request)
    {
        if ($request->ajax()) {
            try {
                $listProduct = VDistributorProductList::get();
                $data = array();
                foreach ($listProduct as $key => $value) {
                    $data[] = array(
                        'id' => $value->id,
                        'distributor' => $value->distributor_name,
                        'product' => $value->product_name,
                    );
                }
            } catch (\Throwable $th) {
                $data = array();
            }
        }
        return response()->json($data);
    }
}
