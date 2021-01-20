<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Model\Distributor;
use App\Model\DistributorProduct;
use App\Model\view\VDistributorProduct;
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
        $listProduct = VDistributorProductList::get();
        $distributor = Distributor::orderBy('distributor_name', 'asc')->first()->distributor_name;
        return view('product_prices.index', compact('listProduct', 'distributor'));
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
                DistributorProduct::create($arDataDistributorProduct);
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

    public function show(Request $request)
    {
        $columns = array(
            0 => 'product_name',
            1 => 'distributor_name',
            2 => 'total_product',
            3 => 'stock_product',
            4 => 'purchase_price',
            5 => 'selling_price',
            6 => 'profit',
            7 => 'delivery_date',
            8 => 'action',
            9 => 'id'
        );

        $queryListProduct = VDistributorProduct::select(
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
        );
        $totalData = $queryListProduct->count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            $posts = VDistributorProduct::select(
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
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');

            $posts =  VDistributorProduct::select(
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
                ->where('product_name', 'ILIKE', "%{$search}%")
                ->orWhere('distributor_name', 'ILIKE', "{$search}")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            $totalFiltered = VDistributorProduct::select(
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
                ->where('product_name', 'ILIKE', "%{$search}%")
                ->orWhere('distributor_name', 'ILIKE', "{$search}")
                ->count();
        }

        $data = array();
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $selectOption = '';
                $productDistributor = VDistributorProductList::get();
                foreach ($productDistributor as $getDataProduct) {
                    $selected = '';
                    if ($getDataProduct->product_id == $post->product_id) {
                        $selected = 'selected';
                    }
                    $selectOption .= "<option class data-subtext='$getDataProduct->distributor_name' value='$getDataProduct->id' $selected>
                        $getDataProduct->product_name
                    </option>";
                }
                $id = $post->id;
                $purchase_price_span = Helpers::MoneyFormat($post->purchase_price);
                $purchase_price_form = number_format($post->purchase_price, 0, ',', '.');
                $selling_price_span = Helpers::MoneyFormat($post->selling_price);
                $selling_price_form = number_format($post->selling_price, 0, ',', '.');
                $profit = Helpers::MoneyFormat($post->profit);
                $delivery_date_span = Helpers::customDate($post->delivery_date, 'short');
                $delivery_date_form = date('d/m/Y', strtotime($post->delivery_date));

                $nestedData['product_name'] = "<span class='editSpan product_name'>$post->product_name</span>
                <div class='editInput' style='display: none; width:215px !important'>
                    <select name='product_id' class='form-control show-tick product_id varInput'
                        data-live-search='true' data-size='3' onchange='selectInput(this)'>
                        $selectOption
                    </select>
                </div>";

                $nestedData['distributor_name'] = "<span class='distributor_name'>$post->distributor_name</span>";

                $nestedData['total_product'] = "<span class='editSpan total_product'>$post->total_product</span>
                <input class='editInput total_product form-control input-sm varInput' type='text' 
                name='total_product' value='$post->total_product' style='display: none; width:50px'>";

                $nestedData['stock_product'] = "<span class='stock_product'>$post->stock_product</span>";

                $nestedData['purchase_price'] = "<span
                class='editSpan purchase_price'>$purchase_price_span</span>
                <input id='purchase_price_$id' class='editInput purchase_price form-control input-sm' type='text'
                value='$purchase_price_form' style='display: none; width:77px' onkeyup='priceRow(this)'>
                <input id='purchase_price_" . $id . "_ori' type='hidden'
                    class='form-control text-center varInput' name='purchase_price'
                    value='$post->purchase_price'>";

                $nestedData['selling_price'] = "<span class='editSpan selling_price'>$selling_price_span</span>
                <input id='selling_price_$id' class='editInput selling_price form-control input-sm' type='text'
                value='$selling_price_form' style='display: none; width:77px' onkeyup='priceRow(this)'>
                <input id='selling_price_" . $id . "_ori' type='hidden' class='form-control text-center varInput' name='selling_price'
                value='$post->selling_price'>";

                $nestedData['profit'] = "<span class='profit'>$profit</span>";

                $nestedData['delivery_date'] = "<span class='editSpan delivery_date'>$delivery_date_span</span>
                <input class='editInput delivery_date form-control input-sm varInput mask_date date_max_today'
                type='text' name='delivery_date' value='$delivery_date_form' style='display: none; width:85px'>";

                $nestedData['action'] = "<div class='btn-group btn-group-sm edit-delete'>
                    <button type='button' class='btn btn-default waves-effect editBtn' style='float: none;'
                        onclick='editButton(this)'>
                        <i class='material-icons'>mode_edit</i>
                    </button>
                    <button type='button' class='btn btn-default waves-effect deleteBtn'
                        style='float: none;' onclick='deleteButton(this)'>
                        <i class='material-icons'>delete</i>
                    </button>
                </div>
                <div class='btn-group btn-group-sm save-confirm-cancel'>
                    <button type='button' class='btn btn-success waves-effect saveBtn'
                        style='float: none; display: none;' onclick='saveButton(this,$id)'>
                        <i class='material-icons'>save</i>
                    </button>
                    <button type='button' class='btn btn-success waves-effect confirmBtn'
                        style='float: none; display: none;' onclick='actionDelete(this,$id)'>
                        <i class='material-icons'>check</i>
                    </button>
                    <button type='button' class='btn bg-red waves-effect cancelBtn'
                        style='float: none; display: none;' onclick='cancelButton(this)'>
                        <i class='material-icons'>refresh</i>
                    </button>
                </div>";

                $nestedData['id'] = "$id";
                $data[] = $nestedData;
            }
        }

        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );

        echo json_encode($json_data);
    }
}
