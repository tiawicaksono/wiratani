<?php

namespace App\Http\Controllers;

use App\Model\Product;
use App\Model\ProductCategory;
use App\Model\view\VProduct;
use Illuminate\Http\Request;

class ProductController extends Controller
{
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
                $category_id = $request->category_name;
                $dtProductCategory = ProductCategory::find($category_id);
                $product_name = explode(',', $request->product_name);
                $output['obj'] = array();
                foreach ($product_name as $key => $value) {
                    $item = Product::firstOrNew(array(
                        'product_category_id' => $category_id,
                        'product_name' => strtoupper($value)
                    ));
                    $item->save();
                    $output['obj'][$item->id] = $value . "|" . $dtProductCategory->category_name;
                }
                $output['status'] = 'ok';
            } catch (\Throwable $th) {
                $output['status'] = 'error';
                echo $th;
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
            try {
                $product_id = $request->id;
                $product_category_id = $request->category_name;
                $product_name = $request->product_name;
                $status = 'ok';
                $product = Product::where('product_name', 'ILIKE', $product_name)
                    ->where('product_category_id', $product_category_id);
                //CEK APAKAH DATA REQUESTSUDAH DIPAKAI? KALAU BELUM MAKA BOLEH UPDATE
                if ($product->exists()) {
                    $dtProduct = $product->where('id', '<>', $product_id)->count();
                    if ($dtProduct != 0) {
                        $status = 'error';
                    } else {
                        Product::edit($product_id, $product_name, $product_category_id);
                    }
                } else {
                    Product::edit($product_id, $product_name, $product_category_id);
                }
                $output['status'] = $status;
                $output['product_category_id'] = $product_category_id;
                $output['product_name'] = strtoupper($product_name);
                $arProductCategory = ProductCategory::find($product_category_id);
                $output['category_name'] = $arProductCategory->category_name;
            } catch (\Throwable $th) {
                $output['status'] = 'error';
                $output['statuss'] = $th;
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
            0 => 'product_category_id',
            1 => 'product_name',
            2 => 'action',
            3 => 'category_name'
        );

        $queryListProduct = VProduct::select('id', 'product_category_id', 'category_name', 'product_name');
        $totalData = $queryListProduct->count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            $posts = VProduct::select('id', 'product_category_id', 'category_name', 'product_name')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');

            $posts =  VProduct::select('id', 'product_category_id', 'category_name', 'product_name')
                ->where('product_name', 'ILIKE', "%{$search}%")
                ->orWhere('category_name', 'ILIKE', "%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            $totalFiltered = VProduct::select('id', 'product_category_id', 'category_name', 'product_name')
                ->where('product_name', 'ILIKE', "%{$search}%")
                ->orWhere('category_name', 'ILIKE', "%{$search}%")
                ->count();
        }

        $data = array();
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $selectOption = '';
                $productCategory = ProductCategory::get();
                foreach ($productCategory as $getDataProductCategory) {
                    $selected = '';
                    if ($getDataProductCategory->id == $post->product_category_id) {
                        $selected = 'selected';
                    }
                    $selectOption .= "<option value='$getDataProductCategory->id' $selected>
                        $getDataProductCategory->category_name
                    </option>";
                }
                $nestedData['product_category_id'] = "<span class='editSpan category_name_text'>" . $post->category_name . "</span>
                <div class='editInput' style='display: none'>
                    <select name='category_name' class='form-control show-tick category_name varInput'
                        data-live-search='true' data-size='3'>$selectOption</select>
                </div>";
                $nestedData['product_name'] = "<span class='editSpan product_name'>$post->product_name</span>
                <input class='editInput product_name form-control input-sm varInput' type='text' name='product_name' value='$post->product_name' 
                style='display: none; width:100%; text-transform:uppercase'>";
                $nestedData['action'] = "<div class='btn-group btn-group-sm edit-delete'>
                <button type='button' class='btn btn-default waves-effect editBtn' style='float: none;'
                    onclick='editButton(this)'>
                    <i class='material-icons'>mode_edit</i>
                </button>
                <button type='button' class='btn btn-default waves-effect deleteBtn' style='float: none;'
                    onclick='deleteButton(this)'>
                    <i class='material-icons'>delete</i>
                </button>
            </div>
            <div class='btn-group btn-group-sm save-confirm-cancel'>
                <button type='button' class='btn btn-success waves-effect saveBtn'
                    style='float: none; display: none;'
                    onclick='saveButtonProduct(this,$post->id)'>
                    <i class='material-icons'>save</i>
                </button>
                <button type='button' class='btn btn-success waves-effect confirmBtn'
                    style='float: none; display: none;' onclick='actionDeleteProduct(this,$post->id)'>
                    <i class='material-icons'>check</i>
                </button>
                <button type='button' class='btn bg-red waves-effect cancelBtn' style='float: none; display: none;'
                    onclick='cancelButton(this)'>
                    <i class='material-icons'>refresh</i>
                </button>
            </div>";
                $nestedData['category_name'] = strtoupper($post->category_name);
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
                Product::destroy($request->id);
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
