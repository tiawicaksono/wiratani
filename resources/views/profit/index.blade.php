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
                <div class="col-lg-9 col-md-12 col-sm-12 col-xs-12">
                    <div class="row">
                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
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
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    @include('profit.profit_list')
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    @include('profit.withdraw')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="{{ URL::asset('public/js/profit.js') }}"></script>
@endsection