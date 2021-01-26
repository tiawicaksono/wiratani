@extends('layouts.master')

@section('title','PROFIT')
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
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <div class="card">
        <div class="body">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="info-box-2 bg-cyan hover-zoom-effect">
                        <div class="icon">
                            <i class="material-icons">equalizer</i>
                        </div>
                        <div class="content">
                            <div class="text">TOTAL SALDO &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <b>
                                    <span id="sum_profit">
                                        Rp {{ number_format($sum_profit, 0, ',', '.') }}
                                    </span>
                                </b>
                            </div>
                            <div class="number">
                                <b>
                                    <span id="sum_sisa_profit">
                                        Rp {{ number_format($sum_sisa_profit, 0, ',', '.') }}
                                    </span>
                                </b>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                    <select name="bulan" id="bulan">
                        @foreach ($longMonth as $key=>$month)
                        <option value="{{ $key+1 }}" {{ ($key+1==date('n'))?'selected':'' }}>{{ $month }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                    <button type="button" id="btnFilter" class="btn btn-success waves-effect">
                        <i class="material-icons">search</i>
                        <span>FILTER</span>
                    </button>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                PROFIT LIST
                            </h2>
                            <ul class="header-dropdown m-r--5">
                                <li class="dropdown">
                                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown"
                                        role="button" aria-haspopup="true" aria-expanded="false">
                                        <i class="material-icons">more_vert</i>
                                    </a>
                                    <ul class="dropdown-menu pull-right">
                                        <li>
                                            <a href="javascript:void(0);" class=" waves-effect waves-block">
                                                Action
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);" class=" waves-effect waves-block">
                                                Another action
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);" class=" waves-effect waves-block">
                                                Something else here
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <div class="body">
                            <table id="table-profit" class="table table-bordered table-striped table-hover dataTable"
                                role="grid">
                                <thead>
                                    <tr role="row">
                                        <th style="width: 30%">Date</th>
                                        <th>Profit</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>TOTAL</th>
                                        <th id="total_profit"></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                WITHDRAW LIST
                            </h2>
                            <ul class="header-dropdown m-r--5">
                                <li class="dropdown">
                                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown"
                                        role="button" aria-haspopup="true" aria-expanded="false">
                                        <i class="material-icons">more_vert</i>
                                    </a>
                                    <ul class="dropdown-menu pull-right">
                                        <li>
                                            <a href="javascript:void(0);" class=" waves-effect waves-block">
                                                Action
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);" class=" waves-effect waves-block">
                                                Another action
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);" class=" waves-effect waves-block">
                                                Something else here
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <div class="body">
                            <ul class="nav nav-tabs" role="tablist">
                                <li role="presentation" class="active">
                                    <a href="#home_with_icon_title" data-toggle="tab">
                                        <i class="material-icons">list</i> LIST
                                    </a>
                                </li>
                                <li role="presentation">
                                    <a href="#profile_with_icon_title" data-toggle="tab">
                                        <i class="material-icons">insert_drive_file</i> FORM
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane fade in active" id="home_with_icon_title">
                                    <table id="table-supply"
                                        class="table table-bordered table-striped table-hover dataTable" role="grid">
                                        <thead>
                                            <tr role="row">
                                                <th style="width:100px !important">Delivery Date</th>
                                                <th style="width:200px !important">Note</th>
                                                <th style="width:30px !important">Qty</th>
                                                <th style="width:80px !important">Price</th>
                                                <th style="width:80px !important">Total</th>
                                                <th style="width:125px !important"></th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th colspan="4">TOTAL</th>
                                                <th id="total_pemakaian"></th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <div role="tabpanel" class="tab-pane fade" id="profile_with_icon_title">
                                    <form>
                                        {{ csrf_field() }}
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input name="input_date" id="input_date" type="text"
                                                    class="form-control date mask_date" placeholder="Ex: 14/10/1991"
                                                    value="{{ date('d/m/Y') }}">
                                            </div>
                                        </div>
                                        <div class="clearfix">
                                            <div class="align-right m-r-10">
                                                <button type="button" class="btn btn-primary waves-effect"
                                                    onclick="addRow()">
                                                    <i class="material-icons">add</i>
                                                </button>
                                            </div>
                                            <div class="clearfix">
                                                <table class="table" id="product_table">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center">NOTE</th>
                                                            <th class="text-center" style="width: 60px">QTY</th>
                                                            <th class="text-center">PRICE</th>
                                                            <th class="text-center"></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="tbody">
                                                        <tr id="1">
                                                            <td class="row-index text-center">
                                                                <input type="text" class="form-control note_pengambilan"
                                                                    id="note_pengambilan_1">
                                                            </td>
                                                            <td class="row-index text-center">
                                                                <input type="text" class="form-control qty text-center"
                                                                    id="qty_1">
                                                            </td>
                                                            <td class="row-index text-right">
                                                                <input type="text"
                                                                    class="form-control price text-center" id="price_1"
                                                                    size="5" onkeyup="priceRow(this)">
                                                                <input type="hidden"
                                                                    class="form-control price_ori text-center"
                                                                    id="price_1_ori" value="0">
                                                            </td>
                                                            <td class="text-right">
                                                                <button class="btn btn-danger remove" type="button">
                                                                    <i class="material-icons">delete</i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <button type="button"
                                                    class="btn btn-block btn-lg btn-success waves-effect"
                                                    onclick="save()">
                                                    <i class="material-icons">save</i>
                                                    SAVE
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="{{ URL::asset('public/js/profit.js') }}"></script>
@endsection