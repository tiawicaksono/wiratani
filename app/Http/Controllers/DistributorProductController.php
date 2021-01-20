<?php

namespace App\Http\Controllers;

use App\Model\DistributorProductList;
use App\Model\Distributor;
use App\Model\ProductCategory;
use App\Model\view\VDistributorProduct;
use App\Model\view\VDistributorProductList;
use Illuminate\Http\Request;

class DistributorProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dataProductCategory = ProductCategory::getProductCategory();
        $productCategory = ProductCategory::get();
        $data = VDistributorProduct::get();
        $listProduct = VDistributorProductList::get();
        $distributor = Distributor::select('id', 'distributor_name')->get();
        return view('distributor_product.index', compact('data', 'dataProductCategory', 'listProduct', 'distributor', 'productCategory'));
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
            try {
                foreach ($request->arrData as $value) {
                    $item = DistributorProductList::firstOrNew(array(
                        'distributor_id' => $request->distributor_id,
                        'product_id' => $value
                    ));
                    $item->save();
                }
                $output['status'] = 'ok';
            } catch (\Throwable $th) {
                $output['status'] = 'error';
            }

            return response()->json($output);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $columns = array(
            0 => 'product_code',
            1 => 'product_name',
            2 => 'distributor_name',
            3 => 'action'
        );

        $queryListProduct = VDistributorProductList::select('id', 'product_code', 'product_name', 'distributor_name');
        $totalData = $queryListProduct->count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            $posts = VDistributorProductList::select('id', 'product_code', 'product_name', 'distributor_name')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');

            $posts =  VDistributorProductList::select('id', 'product_code', 'product_name', 'distributor_name')
                ->where('product_code', 'ILIKE', "$search")
                ->orWhere('product_name', 'ILIKE', "%{$search}%")
                ->orWhere('distributor_name', 'ILIKE', "%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            $totalFiltered = VDistributorProductList::select('id', 'product_code', 'product_name', 'distributor_name')
                ->where('product_code', 'ILIKE', "$search")
                ->orWhere('product_name', 'ILIKE', "%{$search}%")
                ->orWhere('distributor_name', 'ILIKE', "%{$search}%")
                ->count();
        }

        $data = array();
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $nestedData['product_code'] = strtoupper($post->product_code);
                $nestedData['product_name'] = strtoupper($post->product_name);
                $nestedData['distributor_name'] = strtoupper($post->distributor_name);
                $nestedData['action'] = "<div class='btn-group btn-group-sm edit-delete'>
                    <button type='button' class='btn btn-default waves-effect deleteBtn' style='float: none;'
                        onclick='deleteButton(this)'>
                        <i class='material-icons'>delete</i>
                    </button>
                </div>
                <div class='btn-group btn-group-sm save-confirm-cancel'>
                    <button type='button' class='btn btn-success waves-effect confirmBtn' style='float: none; display: none;'
                        onclick='actionDelete(this,$post->id)'>
                        <i class='material-icons'>check</i>
                    </button>
                    <button type='button' class='btn bg-red waves-effect cancelBtn' style='float: none; display: none;'
                        onclick='cancelButton(this)'>
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
                DistributorProductList::destroy($request->id);
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
