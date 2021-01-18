<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Model\Distributor;
use App\model\view\VDistributorProduct;
use App\Model\view\VDistributorProductList;
use App\model\view\VSupply;
use Exception;
use Illuminate\Http\Request;

class SupplyController extends Controller
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
        return view('supply.index', compact('listProduct', 'distributor'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function show(Request $request)
    {
        try {
            $from_date = $request->from_date;
            $to_date = $request->to_date;
            $columns = array(
                0 => 'product_name',
                1 => 'distributor_name',
                2 => 'note',
                3 => 'price',
                4 => 'qty',
                5 => 'total',
                6 => 'delivery_date',
                7 => 'source',
                8 => 'action'
            );

            $getSelect = VSupply::select(
                'id',
                'product_name',
                'distributor_name',
                'note',
                'price',
                'qty',
                'total',
                'delivery_date',
                'source'
            );
            $totalData = $getSelect->count();
            $totalFiltered = $totalData;

            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');
            $posts = $getSelect->whereBetween('delivery_date', [$from_date, $to_date])
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            $totalFiltered = VSupply::whereBetween('delivery_date', [$from_date, $to_date])->count();
            $sum_total = VSupply::whereBetween('delivery_date', [$from_date, $to_date])->sum('total');
            $sum_helios = VSupply::where('source', 'H')->whereBetween('delivery_date', [$from_date, $to_date])->sum('total');
            $sum_wiratani = VSupply::where('source', 'W')->whereBetween('delivery_date', [$from_date, $to_date])->sum('total');

            $data = array();
            if (!empty($posts)) {
                foreach ($posts as $post) {
                    $selectOption = '';
                    $productDistributor = VDistributorProduct::get();
                    $selectOption .= "<option class data-subtext='' value='0'>Other</option>";
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
                    $price_span = Helpers::MoneyFormat($post->price);
                    $price_form = number_format($post->price, 0, ',', '.');
                    $delivery_date_span = Helpers::customDate($post->delivery_date, 'short');
                    $delivery_date_form = date('d/m/Y', strtotime($post->delivery_date));

                    $nestedData['product_name'] = "<span class='editSpan product_name'>$post->product_name</span>
                    <div class='editInput' style='display: none; width:185px !important'>
                        <select name='product_id' class='form-control show-tick product_id varInput'
                            data-live-search='true' data-size='3' onchange='selectInput(this)'>
                            $selectOption
                        </select>
                    </div>";

                    $nestedData['distributor_name'] = "<span class='distributor_name'>$post->distributor_name</span>";

                    $nestedData['note'] = "<span class='editSpan note'>$post->note</span>
                    <input class='editInput note form-control input-sm varInput' type='text' 
                    name='note' value='$post->note' style='display: none; width:150px !important'>";

                    $nestedData['price'] = "<span
                    class='editSpan price'>$price_span</span>
                    <input id='price$id' class='editInput price form-control input-sm' type='text'
                    value='$price_form' style='display: none; width:77px' onkeyup='priceRow(this)'>
                    <input id='price" . $id . "_ori' type='hidden'
                    class='form-control text-center varInput' name='price'
                    value='$post->price'>";

                    $nestedData['qty'] = "<span class='editSpan qty'>$post->qty</span>
                    <input id='qty_$id' class='editInput qty form-control input-sm' type='text'
                    value='$post->qty' style='display: none; width:50px'>";

                    $nestedData['total'] = "<span class='total'>" . Helpers::MoneyFormat($post->total) . "</span>";

                    $nestedData['delivery_date'] = "<span class='editSpan delivery_date'>$delivery_date_span</span>
                    <input class='editInput delivery_date form-control input-sm varInput mask_date date_max_today'
                    type='text' name='delivery_date' value='$delivery_date_form' style='display: none; width:85px'>";

                    $nestedData['source'] = "<span class='total'>" . $post->source . "</span>";

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
                    $data[] = $nestedData;
                }
            }

            $json_data = array(
                "draw"            => intval($request->input('draw')),
                "recordsTotal"    => intval($totalData),
                "recordsFiltered" => intval($totalFiltered),
                "data"            => $data,
                "total"           => number_format($sum_total, 0, ',', '.'),
                "total_helios"    => number_format($sum_helios, 0, ',', '.'),
                "total_wiratani"  => number_format($sum_wiratani, 0, ',', '.'),
            );

            echo json_encode($json_data);
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
}
