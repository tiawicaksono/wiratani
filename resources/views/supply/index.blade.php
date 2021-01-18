@extends('layouts.master')

@section('title','SUPPLY')
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
            &nbsp;
        </div>
    </div>
</div>
<div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
    <div class="card">
        <div class="header">
            <h2>SUPPLY</h2>
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
                        <th style="width:80px !important">Price</th>
                        <th style="width:30px !important">Qty</th>
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
@endsection

@section('script')
<script src="/js/supply.js"></script>
@endsection