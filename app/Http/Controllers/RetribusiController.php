<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Model\ProductSales;
use App\Model\TotalSales;
use App\Model\view\VDistributorProduct;
use Exception;
use Illuminate\Http\Request;
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

class RetribusiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index()
    // {
    //     return 'chacha';
    // }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('transaction.form');
    }

    public function searchProduct(Request $request)
    {
        if ($request->ajax()) {
            $products = VDistributorProduct::where('stock_product', '>', 0)
                ->where(function ($query) {
                    $query->whereRaw("replace(LOWER(product_name), ' ', '') ilike '%" . str_replace(' ', '', request()->search) . "%'")
                        ->orWhereRaw("replace(LOWER(barcode), ' ', '') ilike '%" . str_replace(' ', '', request()->search) . "%'");
                });
            $count_product = $products->count();
            $id = '';
            $barcode = $request->search;
            $product_name = '';
            $selling_price = 0;
            $stock_product = '';
            if ($count_product == 1) {
                $getProducts = $products->first();
                $id = $getProducts->id;
                $barcode = $getProducts->barcode;
                $product_name = $getProducts->product_name;
                $selling_price = $getProducts->selling_price;
                $stock_product = $getProducts->stock_product;
            }
            $output['count_data'] = $count_product;
            $output['id'] = $id;
            $output['barcode'] = $barcode;
            $output['product_name'] = $product_name;
            $output['selling_price'] = Helpers::MoneyFormat($selling_price);
            $output['selling_price_ori'] = $selling_price;
            $output['stock_product'] = $stock_product;
            return response()->json($output);
        }
    }

    public function listProduct(Request $request)
    {
        if ($request->ajax()) {
            $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
            $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
            $sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'product_name';
            $order = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
            $offset = ($page - 1) * $rows;

            $products = VDistributorProduct::where('stock_product', '>', 0)
                ->where(function ($query) {
                    $query->whereRaw("replace(LOWER(product_name), ' ', '') ilike '%" . str_replace(' ', '', request()->search) . "%'")
                        ->orWhereRaw("replace(LOWER(barcode), ' ', '') ilike '%" . str_replace(' ', '', request()->search) . "%'");
                });
            $count = $products->count();
            $productss = $products->orderBy($sort, $order)
                ->limit($rows)
                ->skip($offset)
                ->get();
            $dataJson = array();
            foreach ($productss as $value) {
                $dataJson[] = array(
                    "id" => $value->barcode,
                    "barcode" => $value->barcode,
                    "product_name" => $value->product_name,
                    "stock_product" => $value->stock_product,
                    "purchase_price" => Helpers::MoneyFormat($value->selling_price)
                );
            }
            $arData =  array(
                'total' => $count,
                'rows' => $dataJson
            );
            return response()->json($arData);
        }
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
            $grand_discon = $request->grand_discon;
            $numerator = ProductSales::getNumerator();
            //INSERT
            ProductSales::insert($product, $numerator);
            //SELECT
            $select = TotalSales::where('numerator', $numerator);
            // $get = $select->first();
            // $total_sales_price = $get->total_sales_price;
            // $total_selling_price = $get->total_selling_price;
            // $discon_price = $get->discon_price;
            $select->update([
                "grand_discon" => $grand_discon
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
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

    public function test()
    {
        try {
            // Enter the share name for your printer here, as a smb:// url format
            $connector = new WindowsPrintConnector("nota");
            //$connector = new WindowsPrintConnector("smb://Guest@computername/Receipt Printer");
            //$connector = new WindowsPrintConnector("smb://FooUser:secret@computername/workgroup/Receipt Printer");
            //$connector = new WindowsPrintConnector("smb://User:secret@computername/Receipt Printer");

            /* Print a "Hello world" receipt" */
            $printer = new Printer($connector);
            $printer->text("Hello World!\n");
            $printer->cut();

            /* Close printer */
            $printer->close();
        } catch (Exception $e) {
            echo "Couldn't print to this printer: " . $e->getMessage() . "\n";
        }
    }
}
