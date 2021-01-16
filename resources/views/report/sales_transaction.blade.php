@extends('layouts.master')

@section('title','REPORT CASHIER')
@section('style')
@endsection
@section('content')
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <div class="card">
        <div class="header">
            <h2>LIST</h2>
        </div>
        <div class="body">
            <div class="row">
                <div class="col-xs-6">
                    <div class="input-daterange input-group" id="bs_datepicker_range_container">
                        <div class="form-line">
                            <input type="text" class="form-control" id="dari_tgl" placeholder="Date start..."
                                value="{{ date('d/m/Y') }}">
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
                    <button type="button" class="btn btn-primary waves-effect"
                        onclick="downloadSalesTransaction('{{ route('report.exportTransaction') }}')">
                        <i class="material-icons">file_download</i>
                        <span>DOWLOAD</span>
                    </button>
                </div>
            </div>
            <table id="table-distribtor-product" class="table table-bordered table-striped table-hover dataTable"
                role="grid">
                <thead>
                    <tr role="row">
                        <th rowspan="2">Numerator</th>
                        <th rowspan="2">Delivery Date</th>
                        <th rowspan="2">Distributor Name</th>
                        <th rowspan="2">Product Name</th>
                        <th colspan="3" style="text-align: center">Price</th>
                        <th rowspan="2" style="width: 68px;">Omzet</th>
                        <th rowspan="2" style="width: 68px;">Profit</th>
                    </tr>
                    <tr role="row">
                        <th style="width: 38px;">QTY</th>
                        <th style="width: 68px;">Product Price</th>
                        <th style="width: 68px;">Disc Product</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th colspan="6">TOTAL</th>
                        {{-- <th id="total_product_price"></th> --}}
                        <th id="total_discount"></th>
                        <th id="total_selling_price"></th>
                        <th id="total_profit"></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="/js/report/transaction.js"></script>
@endsection