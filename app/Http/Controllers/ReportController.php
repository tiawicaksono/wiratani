<?php

namespace App\Http\Controllers;

use App\Exports\SalesTransactionExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;

use App\Helpers\Helpers;
use App\Model\view\VProductSales;
use Exception;
use Illuminate\Http\Request;

class ReportController extends Controller
{

    /**
     * EXPORT EXCEL
     */

    public function exportSalesTransaction(Request $request)
    {
        $dari_tgl = $request->dari_tgl;
        $sampai_tgl = $request->sampai_tgl;
        return Excel::download(new SalesTransactionExport($dari_tgl, $sampai_tgl), 'sales_transaction_' . date("mdy", strtotime($dari_tgl)) . '_' . date("mdy", strtotime($sampai_tgl)) . '.xlsx');
    }

    public function indexSalesTransaction()
    {
        // $mytime = Carbon::now();
        // echo $mytime->toDateTimeString();
        return view('report.sales_transaction');
    }

    public function listSalesTransaction(Request $request)
    {
        try {
            $from_date = $request->from_date;
            $to_date = $request->to_date;
            $columns = array(
                0 => 'no_kuitansi',
                1 => 'sales_date',
                2 => 'distributor_name',
                3 => 'product_name',
                4 => 'qty',
                5 => 'basic_selling_price',
                6 => 'discon_price',
                7 => 'total_selling_price',
                8 => 'profit',
            );
            $getSelect = VProductSales::select(
                'no_kuitansi',
                'sales_date',
                'distributor_name',
                'product_name',
                'qty',
                'basic_selling_price',
                'discon_price',
                'total_selling_price',
                'profit'
            );
            $totalData = $getSelect->count();
            $totalFiltered = $totalData;

            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');

            $posts = $getSelect->whereBetween('sales_date', [$from_date, $to_date])
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            $totalFiltered = VProductSales::whereBetween('sales_date', [$from_date, $to_date])->count();
            $sum = VProductSales::whereBetween('sales_date', [$from_date, $to_date]);
            $sum_product_price = $sum->sum('basic_selling_price');
            $sum_discount_price = $sum->sum('discon_price');
            $sum_selling_price = $sum->sum('total_selling_price');
            $sum_profit = $sum->sum('profit');
            $data = array();
            if (!empty($posts)) {
                foreach ($posts as $post) {
                    $nestedData['no_kuitansi'] = $post->no_kuitansi;
                    $nestedData['sales_date'] = date('d/m/y', strtotime($post->sales_date));
                    $nestedData['distributor_name'] = $post->distributor_name;
                    $nestedData['product_name'] = $post->product_name;
                    $nestedData['qty'] = $post->qty;
                    $nestedData['basic_selling_price'] = "Rp " . number_format($post->basic_selling_price, 0, ',', '.');
                    $nestedData['discon_price'] = "Rp " . number_format($post->discon_price, 0, ',', '.');
                    $nestedData['total_selling_price'] = "Rp " . number_format($post->total_selling_price, 0, ',', '.');
                    $nestedData['profit'] = "Rp " . number_format($post->profit, 0, ',', '.');
                    $data[] = $nestedData;
                }
            }

            $json_data = array(
                "draw"                 => intval($request->input('draw')),
                "recordsTotal"         => intval($totalData),
                "recordsFiltered"      => intval($totalFiltered),
                "data"                 => $data,
                // "total_product_price"  => number_format($sum_product_price, 0, ',', '.'),
                "total_discount"       => number_format($sum_discount_price, 0, ',', '.'),
                "total_selling_price"  => number_format($sum_selling_price, 0, ',', '.'),
                "total_profit"         => number_format($sum_profit, 0, ',', '.')
            );

            echo json_encode($json_data);
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
}
