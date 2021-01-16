<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\model\StockOpname;
use App\model\view\VDistributorProduct;
use App\Model\view\VStockOpname;
use DateTime;
use Illuminate\Http\Request;

class StockOpnameController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $listProduct = VDistributorProduct::where('stock_product', '<>', 0)->get();
        return view('stock_opname.index', compact('listProduct'));
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
            $distributor_product = $request->product_id;
            $total_product = $request->qty;
            $note = $request->note;
            $input_date = $request->input_date;

            try {
                $arDataDistributorProduct = array(
                    'distributor_product_id' => $distributor_product,
                    'qty' => $total_product,
                    'note' => $note,
                    'input_date' => $input_date,
                );
                StockOpname::create($arDataDistributorProduct);
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
            $id = $request->id;
            $distributor_product = $request->product_id;
            $total_product = $request->qty;
            $note = $request->note;
            $input_date = $request->input_date;

            try {
                $arDataDistributorProduct = array(
                    'distributor_product_id' => $distributor_product,
                    'qty' => $total_product,
                    'note' => $note,
                    'input_date' => $input_date,
                );
                StockOpname::ubah($id, $arDataDistributorProduct);

                $getData = VStockOpname::select(
                    'product_name',
                )
                    ->where('id', $id)
                    ->first();
                $output['product_name'] = $getData->product_name;
                $output['qty'] = $total_product;
                $output['note'] = $note;
                $tglDelivery = DateTime::createFromFormat('d/m/Y', $input_date);
                $output['input_date'] = $input_date;
                $output['format_input_date'] = Helpers::customDate($tglDelivery->format('m/d/Y'), 'short');
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
                StockOpname::destroy($request->id);
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
            $output['stock'] = '';
            try {
                $arDistributorProduct = VDistributorProduct::select('stock_product')
                    ->where('id', $request->id)
                    ->first();
                $output['stock'] = $arDistributorProduct->stock_product;
            } catch (\Throwable $th) {
            }
            return response()->json($output);
        }
    }

    public function selectpicker(Request $request)
    {
        if ($request->ajax()) {
            try {
                $listProduct = VDistributorProduct::where('stock_product', '<>', 0)->get();
                $data = array();
                foreach ($listProduct as $key => $value) {
                    $data[] = array(
                        'id' => $value->id,
                        'distributor' => $value->distributor_name,
                        'product' => $value->product_name,
                        'stock_product' => $value->stock_product,
                    );
                }
            } catch (\Throwable $th) {
                $data = array();
            }
            return response()->json($data);
        }
    }

    public function show(Request $request)
    {
        $columns = array(
            0 => 'product_name',
            1 => 'distributor_name',
            2 => 'qty',
            3 => 'note',
            4 => 'input_date',
            5 => 'action',
        );

        $queryListProduct = VStockOpname::select(
            'id',
            'product_name',
            'distributor_name',
            'qty',
            'note',
            'input_date'
        );
        $totalData = $queryListProduct->count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            $posts = VStockOpname::select(
                'id',
                'product_name',
                'distributor_name',
                'qty',
                'note',
                'input_date'
            )
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');

            $posts =  VStockOpname::select(
                'id',
                'product_name',
                'distributor_name',
                'qty',
                'note',
                'input_date'
            )
                ->where('product_name', 'ILIKE', "%{$search}%")
                ->orWhere('distributor_name', 'ILIKE', "{$search}")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            $totalFiltered = VStockOpname::select(
                'id',
                'product_name',
                'distributor_name',
                'qty',
                'note',
                'input_date'
            )
                ->where('product_name', 'ILIKE', "%{$search}%")
                ->orWhere('distributor_name', 'ILIKE', "{$search}")
                ->count();
        }

        $data = array();
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $selectOption = '';
                $productDistributor = VDistributorProduct::where('stock_product', '<>', 0)->get();
                foreach ($productDistributor as $getDataProduct) {
                    $selected = '';
                    if ($getDataProduct->id == $post->id) {
                        $selected = 'selected';
                    }
                    $selectOption .= "<option class data-subtext='$getDataProduct->distributor_name' value='$getDataProduct->id' $selected>
                        $getDataProduct->product_name
                    </option>";
                }
                $id = $post->id;
                $input_date_span = Helpers::customDate($post->input_date, 'short');
                $input_date_form = date('d/m/Y', strtotime($post->input_date));

                $nestedData['product_name'] = "<span class='editSpan product_name'>$post->product_name</span>
                <div class='editInput' style='display: none; width:250px !important'>
                    <select name='product_id' class='form-control show-tick product_id varInput'
                        data-live-search='true' data-size='3' onchange='selectInput(this)'>
                        $selectOption
                    </select>
                </div>";

                $nestedData['distributor_name'] = "<span class='distributor_name' style='width:100px'>$post->distributor_name</span>";

                $nestedData['qty'] = "<span class='editSpan qty'>$post->qty</span>
                <input class='editInput qty form-control input-sm varInput' type='text' 
                name='qty' value='$post->qty' style='display: none; width:50px'>";

                $nestedData['note'] = "<span class='editSpan note' style='width:300px !important'>$post->note</span>
                <input class='editInput note form-control input-sm varInput' type='text' 
                name='note' value='$post->note' style='display: none; width:400px !important'>";

                $nestedData['input_date'] = "<span class='editSpan input_date'>$input_date_span</span>
                <input class='editInput input_date form-control input-sm varInput mask_date date_max_today'
                type='text' name='input_date' value='$input_date_form' style='display: none; width:85px'>";

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
            "data"            => $data
        );

        echo json_encode($json_data);
    }
}
