@extends('layouts.master')

@section('title','CASHIER')
@section('style')
<style>
    .datagrid-cell {
        font-size: 14px;
    }

    .datagrid-header .datagrid-cell span {
        font-weight: bold;
        font-size: 16px;
    }
</style>
@endsection
@section('content')
{{-- PENJUALAN BARANG --}}
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="card">
        <div class="body">
            <div class="row clearfix">
                <div class="align-right">
                    <button type="button" class="btn btn-primary waves-effect" onclick="addRow()">
                        <i class="material-icons">add</i>
                        <span>ADD PRODUCT</span>
                    </button>
                </div>
                <div class="col-md-12">
                    <table class="table" id="product_table">
                        <thead>
                            <tr>
                                <th class="text-center">BARCODE</th>
                                <th class="text-center">PRODUCT</th>
                                <th class="text-center">STOCK</th>
                                <th class="text-center">PRICE</th>
                                <th class="text-center">QTY</th>
                                <th class="text-center">DISCOUNT (@)</th>
                                <th class="text-center">SUB-TOTAL</th>
                                <th class="text-center"></th>
                            </tr>
                        </thead>
                        <tbody id="tbody">
                            <tr id="1">
                                <td class="row-index text-center">
                                    <input type="text" class="form-control product_code" id="product_code_1">
                                    <input type="hidden" class="form-control product_id" id="product_id_1">
                                </td>
                                <td class="row-index">
                                    <span class="product_name" id="product_name_1">-</span>
                                </td>
                                <td class="row-index text-center">
                                    <span class="stock" id="stock_1">-</span>
                                </td>
                                <td class="row-index text-right">
                                    <span class="unit_price" id="unit_price_1">-</span>
                                    <input type="hidden" class="form-control unit_price_ori" id="unit_price_ori_1">
                                </td>
                                <td class="row-index text-center">
                                    <div class="input-group" data-trigger="spinner">
                                        <span class="input-group-addon">
                                            <button class="btn btn-default spin-down" data-spin="down" type="button"
                                                onclick="calculateUnitPrice(this)">
                                                <i class="glyphicon glyphicon-minus"></i>
                                            </button>
                                        </span>
                                        <input onkeyup="calculateUnitPrice(this)" type="text"
                                            class="form-control text-center quantity" value="1" data-rule="quantity"
                                            maxlength="4" size="2" id="qty_1" disabled>
                                        <span class="input-group-addon">
                                            <button class="btn btn-default spin-up" data-spin="up" type="button"
                                                onclick="calculateUnitPrice(this)">
                                                <i class="glyphicon glyphicon-plus"></i>
                                            </button>
                                        </span>
                                    </div>
                                </td>
                                <td class="row-index text-center">
                                    <input type="text" class="form-control discon text-center" id="discon_1" size="5"
                                        disabled onkeyup="disconRow(this)">
                                    <input type="hidden" class="form-control discon_ori text-center" id="discon_1_ori"
                                        value="0">
                                </td>
                                <td class="row-index text-right">
                                    <span class="total_price font-bold font-14" id="total_price_1">-</span>
                                    <input type="hidden" class="form-control total_price_ori" id="total_price_1_ori">
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-danger remove" type="button">
                                        <i class="material-icons">delete</i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="card" style="background-color: #AED6F1">
        <div class=" body" style="padding: 10px !important">
            <div class="align-right font-35 font-bold">
                Total :
                <span id="sum">
                    {{ Helpers::MoneyFormat(0) }}
                </span>
                {{ Form::hidden('total_tagihan', '0', array('id' => 'total_tagihan')) }}
            </div>
        </div>
    </div>
</div>

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding:0px !important">
    <div class="col-lg-4 col-md-4 col-sm-1 col-xs-1"></div>
    <div class="col-lg-8 col-md-8 col-sm-11 col-xs-11">
        <div class="card">
            <div class="body">
                <div class="col-md-6 form-horizontal">
                    <div class="row clearfix">
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-7 form-control-label">
                            <label for="email_address_2">Discount (F1)</label>
                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-6 col-xs-5">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="discon_grand"
                                        onkeyup="disconGrand(this)">
                                    <input type="hidden" class="form-control" id="discon_grand_ori" value="0">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row clearfix">
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-7 form-control-label">
                            <label for="password_2">Total</label>
                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-6 col-xs-5">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control font-bold font-18" id="grand_total" disabled
                                        value="Rp 0">
                                    <input type="hidden" class="form-control" id="grand_total_ori" disabled value="0">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 form-horizontal">
                    <div class="row clearfix">
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-7 form-control-label">
                            <label for="email_address_2">Cash (F2)</label>
                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-6 col-xs-5">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="bayar" onkeyup="bayar(this)">
                                    <input type="hidden" class="form-control" id="bayar_ori" value="0">
                                </div>
                                <small><code><b>Ctrl+c</b> = get same <b>Total</b></code></small>
                            </div>
                        </div>
                    </div>

                    <div class="row clearfix">
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-7 form-control-label">
                            <label for="password_2">Change</label>
                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-6 col-xs-5">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control font-bold font-18" id="kembalian" disabled
                                        value="Rp 0">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row clearfix">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        <button type="button" class="btn btn-block btn-lg bg-amber waves-effect">
                            <i class="material-icons">refresh</i>
                            CANCEL (F9)
                        </button>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        <button type="button" class="btn btn-block btn-lg btn-success waves-effect" onclick="save()">
                            <i class="material-icons">save</i>
                            SAVE (F4)
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL --}}
<div id="dialogListGridProduct" class="easyui-dialog" title="Lis Data Product" style="width:620px; height:440px"
    data-options="
    iconCls: 'icon-save',
    autoOpen: false,
    noheader: true,
	shadow:true,
    border:true,
    modal:true,
    resizable: true,
    buttons: [{
        text:'Close',
        iconCls:'icon-cancel',
        handler:function(){
            closeDialogTable();
        }
    }]">
    <table id="listGridProduct" style="width: 605px; height: 380px"></table>
</div>
@endsection

@section('script')
<script src="{{ URL::asset('public/js/transaction.js') }}"></script>
@endsection