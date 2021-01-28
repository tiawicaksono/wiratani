<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Model\view\VProfit;
use App\Model\Withdraw;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class ProfitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sum_profit = VProfit::whereYear("sales_date", Carbon::now()->year)->sum('profit');
        $sum_pemakaian_profit = Withdraw::whereYear("delivery_date", Carbon::now()->year)->sum('total');
        $sum_sisa_profit = $sum_profit - $sum_pemakaian_profit;
        $longMonth = Helpers::longMonth();
        return view('profit.index', compact('sum_profit', 'longMonth', 'sum_sisa_profit'));
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
        if ($request->ajax()) {
            $product = $request->batch;
            $input_date = $request->input_date;
            //INSERT
            Withdraw::insert($product, $input_date);
            $sum_profit = VProfit::whereYear("sales_date", Carbon::now()->year)->sum('profit');
            $sum_pemakaian_profit = Withdraw::whereYear("delivery_date", Carbon::now()->year)->sum('total');
            $sum_sisa_profit = $sum_profit - $sum_pemakaian_profit;
            $json_data = array(
                'sum_profit' => 'Rp ' . number_format($sum_profit, 0, ',', '.'),
                'sum_sisa_profit' => 'Rp ' . number_format($sum_sisa_profit, 0, ',', '.')
            );
            echo json_encode($json_data);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showWithDraw(Request $request)
    {
        $bulan = $request->bulan;
        $columns = array(
            0 => 'delivery_date',
            1 => 'note',
            2 => 'qty',
            3 => 'price',
            4 => 'total',
            5 => 'total_hidden',
            6 => 'action'
        );

        $getSelect = Withdraw::select(
            'id',
            'note',
            'price',
            'qty',
            'total',
            'delivery_date'
        );
        $totalData = $getSelect->count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $posts = $getSelect->whereMonth('delivery_date', $bulan)
            ->whereYear("delivery_date", Carbon::now()->year)
            ->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->get();

        $totalFiltered = Withdraw::whereMonth('delivery_date', $bulan)
            ->whereYear("delivery_date", Carbon::now()->year)
            ->count();
        $sum_total = Withdraw::whereMonth('delivery_date', $bulan)
            ->whereYear("delivery_date", Carbon::now()->year)
            ->sum('total');

        $data = array();
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $id = $post->id;
                $price_span = Helpers::MoneyFormat($post->price);
                $price_form = number_format($post->price, 0, ',', '.');
                $delivery_date_span = Helpers::customDate($post->delivery_date, 'short');
                $delivery_date_form = date('d/m/Y', strtotime($post->delivery_date));

                $nestedData['delivery_date'] = "<span class='editSpan delivery_date'>$delivery_date_span</span>
                    <input class='editInput delivery_date form-control input-sm varInput mask_date date_max_today'
                    type='text' name='delivery_date' value='$delivery_date_form' style='display: none; width:85px'>";

                $nestedData['note'] = "<span class='editSpan note_text'>$post->note</span>
                    <input class='editInput note form-control input-sm varInput' type='text' 
                    name='note' value='$post->note' style='display: none; width:150px !important'>";

                $nestedData['qty'] = "<span class='editSpan qty'>$post->qty</span>
                    <input id='qty_$id' class='editInput qty form-control input-sm varInput' name='qty' type='text'
                    value='$post->qty' style='display: none; width:50px'>";

                $nestedData['price'] = "<span
                    class='editSpan price_text'>$price_span</span>
                    <input id='price$id' class='editInput price form-control input-sm' type='text'
                    value='$price_form' style='display: none; width:77px' onkeyup='priceRow(this)'>
                    <input id='price" . $id . "_ori' type='hidden'
                    class='form-control text-center varInput' name='price'
                    value='$post->price'>";

                $nestedData['total'] = "<span class='total'>" . Helpers::MoneyFormat($post->total) . "</span>";
                $nestedData['total_hidden'] = $post->total;

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
            "total"           => number_format($sum_total, 0, ',', '.')
        );

        echo json_encode($json_data);
    }

    public function showProfit(Request $request)
    {
        $bulan = $request->bulan;
        $columns = array(
            0 => 'sales_date',
            1 => 'profit'
        );
        $totalData = VProfit::whereMonth('sales_date', $bulan)
            ->whereYear("sales_date", Carbon::now()->year)
            ->count();
        $totalFiltered = $totalData;
        $sum_total = VProfit::whereMonth('sales_date', $bulan)
            ->whereYear("sales_date", Carbon::now()->year)
            ->sum('profit');
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $posts = VProfit::whereMonth('sales_date', $bulan)
            ->whereYear("sales_date", Carbon::now()->year)
            ->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->get();

        $data = array();
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $nestedData['sales_date'] = "<span class='sales_date'>" . Helpers::customDate($post->sales_date, 'short') . "</span>";
                $nestedData['profit'] = "<span class='profit'>Rp. " . number_format($post->profit, 0, ',', '.') . "</span>";
                $data[] = $nestedData;
            }
        }

        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data,
            "total_profit"           => number_format($sum_total, 0, ',', '.')
        );

        echo json_encode($json_data);
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
    public function update(Request $request)
    {
        if ($request->ajax()) {
            try {
                $id = $request->id;
                $note = $request->note;
                $qty = $request->qty;
                $price = $request->price;
                $total = $qty * $price;

                $arWithDraw = Withdraw::find($id);
                $arWithDraw->note = $note;
                $arWithDraw->price = $price;
                $arWithDraw->qty = $qty;
                $arWithDraw->total = $total;
                $arWithDraw->save();

                $output['note'] = $note;
                $output['price'] = $price;
                $output['qty'] = $qty;
                $output['total'] = $total;
                $output['status'] = 'ok';
            } catch (\Throwable $th) {
                $output['status'] = 'error';
                $output['statuss'] = $th;
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
                Withdraw::destroy($request->id);
                $sum_profit = VProfit::whereYear("sales_date", Carbon::now()->year)->sum('profit');
                $sum_pemakaian_profit = Withdraw::whereYear("delivery_date", Carbon::now()->year)->sum('total');
                $sum_sisa_profit = $sum_profit - $sum_pemakaian_profit;
                $output['sum_profit'] = 'Rp ' . number_format($sum_profit, 0, ',', '.');
                $output['sum_sisa_profit'] = 'Rp ' . number_format($sum_sisa_profit, 0, ',', '.');
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
}
