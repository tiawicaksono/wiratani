@extends('layouts.master')

@section('title','PRICE LIST')
@section('style')
<style>
    /* .datagrid-cell {
        font-size: 14px;
    }

    .datagrid-header .datagrid-cell span {
        font-weight: bold;
        font-size: 16px;
    } */
</style>
@endsection
@section('content')
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <div class="card">
        <div class="header">
            <h2>PRICE LIST</h2>
        </div>
        <div class="body">
            <table class="table table-bordered table-striped table-hover dataTable table-responsive" role="grid">
                <thead>
                    <tr role="row">
                        {{-- <th class="sorting_asc" aria-sort="ascending">Product Name</th> --}}
                        <th>Product Name</th>
                        <th>Distributor Name</th>
                        <th class="text-center">Qty</th>
                        <th>Purchasing Price</th>
                        <th>Price</th>
                        <th>Delivery Date</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr role="row" class="odd" id="0">
                        <td style="width:235px !important">
                            <div style="float: left; width:90%">
                                <select id="product_id" name="product_id"
                                    class="form-control show-tick editInput product_id varInput" data-live-search="true"
                                    data-size="3" onchange="selectInput(this)">
                                    @foreach($listProduct as $getDataProduct)
                                    <option value="{{ $getDataProduct->id }}"
                                        data-subtext="{{ $getDataProduct->distributor_name }}">
                                        {{ $getDataProduct->product_name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div style="float: left">
                                <i style="padding:5px !important" class="material-icons btn-primary waves-effect"
                                    onclick="hai()">refresh</i>
                            </div>
                        </td>
                        <td style="width:120px !important">
                            <span class="distributor_name">{{ $distributor }}</span>
                        </td>
                        <td style="width:50px !important">
                            <center><input class="editInput total_product form-control input-sm varInput text-center"
                                    type="text" name="total_product" value="" style="width:50px"></center>
                        </td>
                        <td style="width:130px !important">
                            <input class="editInput purchase_price form-control input-sm" type="text" value=""
                                style="width:100%" onkeyup="priceRow(this)" id="purchase_price_0">
                            <input id="purchase_price_0_ori" type="hidden" name="purchase_price"
                                class="form-control text-center varInput purchase_price_hidden" value="0">
                        </td>
                        <td style="width:130px !important">
                            <input class="editInput selling_price form-control input-sm varInput" type="text" value=""
                                style="width:100%" onkeyup="priceRow(this)" id="selling_price_0">
                            <input id="selling_price_0_ori" type="hidden" name="selling_price"
                                class="form-control text-center varInput selling_price_hidden" value="0">
                        </td>
                        <td style="width:77px !important">
                            <input
                                class="editInput delivery_date form-control input-sm varInput mask_date date_max_today"
                                type="text" name="delivery_date" value="{{ date('d/m/Y') }}" style="width:105px">
                        </td>
                        <td class="text-center" style="width:100px !important">
                            <div class="btn-group btn-group-sm save-confirm-cancel">
                                <button type="button" class="btn btn-success waves-effect saveBtn" style="float: none;"
                                    onclick="saveButton(this,0,'store')">
                                    <i class="material-icons">save</i>
                                </button>
                                <button type="button" class="btn bg-red waves-effect cancelBtn" style="float: none;"
                                    onclick="cancelButton(this,'insert')">
                                    <i class="material-icons">refresh</i>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <table id="table-distribtor-product" class="table table-bordered table-striped table-hover dataTable"
                role="grid">
                <thead>
                    <tr role="row">
                        <th>Product Name</th>
                        <th>Distributor Name</th>
                        <th>Qty</th>
                        <th>Stock</th>
                        <th>Purchase Price</th>
                        <th>Selling Price</th>
                        <th>Profit</th>
                        <th>Delivery Date</th>
                        <th></th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Product Name</th>
                        <th>Distributor Name</th>
                        <th>Qty</th>
                        <th>Stock</th>
                        <th>Purchase Price</th>
                        <th>Selling Price</th>
                        <th>Profit</th>
                        <th>Delivery Date</th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ URL::asset('public/js/product_prices.js') }}"></script>
@endsection