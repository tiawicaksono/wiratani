@extends('layouts.master')

@section('title','STOCK OPNAME')
@section('content')
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <div class="card">
        <div class="header">
            <h2>STOCK OPNAME</h2>
        </div>
        <div class="body">
            <table class="table table-bordered table-striped table-hover dataTable table-responsive" role="grid">
                <thead>
                    <tr role="row">
                        <th>Product Name</th>
                        <th>Distributor Name</th>
                        <th style="text-align: center">Stock</th>
                        <th style="text-align: center">Qty</th>
                        <th>Note</th>
                        <th>Delivery Date</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr role="row" class="odd" id="0">
                        <td style="width:250px !important">
                            <div style="float: left; width:250px">
                                <select id="product_id" name="product_id"
                                    class="form-control show-tick editInput product_id varInput" data-live-search="true"
                                    data-size="3" onchange="selectInput(this)">
                                    @foreach($listProduct as $getDataProduct)
                                    <option value="{{ $getDataProduct->id }}"
                                        data-subtext="{{ $getDataProduct->distributor_name.' ('.$getDataProduct->stock_product.')' }}">
                                        {{ $getDataProduct->product_name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div style="float: left">
                                <i style="padding:5px !important" class="material-icons btn-primary waves-effect"
                                    onclick="reloadProduct()">refresh</i>
                            </div>
                        </td>
                        <td style="width:131px !important">
                            <span class="distributor_name">-</span>
                        </td>
                        <td style="width:25px !important; text-align: center">
                            <span class="stock_product">0</span>
                        </td>
                        <td style="width:50px !important; text-align: center">
                            <input class="editInput qty form-control input-sm varInput" type="text" name="qty" value=""
                                style="width:50px">
                        </td>
                        <td style="width:300px !important">
                            <input class="editInput note form-control input-sm varInput" type="text" name="note"
                                value="">
                        </td>
                        <td style=" width:85px !important">
                            <input class="editInput input_date form-control input-sm varInput mask_date date_max_today"
                                type="text" name="input_date" value="{{ date('d/m/Y') }}" style="width:85px">
                        </td>
                        <td class="text-center" style="width:70px !important">
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
            <table id="table-stock-opname" class="table table-bordered table-striped table-hover dataTable" role="grid">
                <thead>
                    <tr role="row">
                        <th style="width:250px !important">Product Name</th>
                        <th style="width:130px !important">Distributor Name</th>
                        <th style="width:50px !important">Qty</th>
                        <th style="width:300px !important">Note</th>
                        <th style="width:100px !important">Delivery Date</th>
                        <th style="width:85px !important"></th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Product Name</th>
                        <th>Distributor Name</th>
                        <th>Qty</th>
                        <th>Note</th>
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
<script src="{{ URL::asset('public/js/stock_opname.js') }}"></script>
@endsection