@extends('layouts.master')

@section('title','ORDER')
@section('style')
<style>
    tr.group,
    tr.group:hover {
        background-color: #ddd !important;
        font-weight: bold;
    }

    thead,
    th {
        text-align: center;
    }

    .bootstrap-tagsinput {
        width: 100% !important;
    }
</style>
@endsection
@section('content')
<div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
    <div class="card">
        <div class="header">
            <h2>FORM INPUT</h2>
        </div>
        <div class="body">
            <form>
                {{ csrf_field() }}
                <div class="form-group">
                    <div class="form-line">
                        <input name="input_date" id="input_date" type="text" class="form-control date mask_date"
                            placeholder="Ex: 14/10/1991" value="{{ date('d/m/Y') }}">
                    </div>
                </div>
                <div class="form-group">
                    <select id="distributor_id" name="distributor_id"
                        class="form-control show-tick editInput distributor_id varInput" data-live-search="true"
                        data-size="3">
                        <option value="">--SELECT DISTRIBUTOR--</option>
                        @foreach($distributor as $getDataDistributor)
                        <option value="{{ $getDataDistributor->id }}">
                            {{ $getDataDistributor->distributor_name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="row clearfix">
                    <div class="align-right m-r-10">
                        <button type="button" class="btn btn-primary waves-effect" onclick="addRow()">
                            <i class="glyphicon glyphicon-plus"></i>
                        </button>
                    </div>
                    <div class="clearfix">
                        <table class="table" id="product_table">
                            <thead>
                                <tr>
                                    <th class="text-center">PRODUCT</th>
                                    <th class="text-center" style="width: 60px">QTY</th>
                                    <th class="text-center">PRICE</th>
                                    <th class="text-center"></th>
                                </tr>
                            </thead>
                            <tbody id="tbody">
                                <tr id="1">
                                    <td class="row-index text-center">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <input type="checkbox" class="filled-in" id="ig_checkbox_1"
                                                    name="ig_checkbox_1">
                                                <label for="ig_checkbox_1"></label>
                                            </span>
                                            <div class="form-line">
                                                <input type="text" class="form-control form_product_code"
                                                    id="form_product_code_1">
                                            </div>
                                        </div>

                                        <input type="hidden" class="form-control form_product_id"
                                            id="form_product_id_1">
                                    </td>
                                    <td class="row-index text-center">
                                        <div class="input-group">
                                            <div class="form-line">
                                                <input type="text" class="form-control qty text-center" id="qty_1">
                                            </div>
                                        </div>
                                    </td>
                                    <td class="row-index text-right">
                                        <div class="input-group">
                                            <div class="form-line">
                                                <input type="text" class="form-control price text-center" id="price_1"
                                                    size="5" onkeyup="priceRow(this)">
                                            </div>
                                        </div>
                                        <input type="hidden" class="form-control price_ori text-center" id="price_1_ori"
                                            value="0">
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-danger remove" type="button">
                                            <i class="material-icons">delete</i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <button type="button" class="btn btn-block btn-lg btn-success waves-effect" onclick="save()">
                            <i class="material-icons">save</i>
                            SAVE
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
    <div class="card">
        <div class="header">
            <h2>ORDER</h2>
        </div>
        <div class="body">
            <div class="row clearfix">
                <div class="col-xs-6">
                    <div class="input-daterange input-group" id="bs_datepicker_range_container">
                        <div class="form-line">
                            <input type="text" class="form-control" id="dari_tgl" placeholder="Date start..."
                                value="1/{{ date('m/Y') }}">
                        </div>
                        <span class="input-group-addon">to</span>
                        <div class="form-line">
                            <input type="text" class="form-control" id="sampai_tgl" placeholder="Date end..."
                                value="{{ date('d/m/Y') }}">
                        </div>
                    </div>
                </div>
                <div class="col-xs-6">
                    <button type="button" id="btnFilter" class="btn btn-success waves-effect">
                        <i class="material-icons">search</i>
                        <span>FILTER</span>
                    </button>
                    {{-- <button type="button" class="btn btn-primary waves-effect"
                                onclick="downloadSalesTransaction('{{ route('report.exportTransaction') }}')">
                    <i class="material-icons">file_download</i>
                    <span>DOWLOAD</span>
                    </button> --}}
                </div>
            </div>
            <table id="table-supply" class="table table-bordered table-striped table-hover dataTable" role="grid">
                <thead>
                    <tr role="row">
                        <th style="width:250px !important">Product Name</th>
                        <th style="width:130px !important">Distributor Name</th>
                        <th style="width:200px !important">Note</th>
                        <th style="width:30px !important">Qty</th>
                        <th style="width:80px !important">Price</th>
                        <th style="width:80px !important">Total</th>
                        <th style="width:100px !important">Delivery Date</th>
                        <th style="width:30px !important">Src</th>
                        <th style="width:125px !important"></th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th colspan="5">TOTAL</th>
                        <th id="total"></th>
                        <th colspan="3"></th>
                    </tr>
                </tfoot>
            </table>
            <div class="row">
                HELIOS&nbsp;&nbsp;: <span id="sumber_helios"></span>
                <br>
                WIRATANI : <span id="sumber_wiratani"></span>
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
<script src="{{ URL::asset('public/js/supply.js') }}"></script>
@endsection