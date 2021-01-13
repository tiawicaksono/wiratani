@extends('layouts.master')

@section('title','PRODUCT')
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
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active">
                    <a href="#home_with_icon_title" data-toggle="tab">
                        <i class="material-icons">home</i> DISTRIBUTOR PRODUCT
                    </a>
                </li>
                <li role="presentation">
                    <a href="#profile_with_icon_title" data-toggle="tab">
                        <i class="material-icons">archive</i> PRODUCTS
                    </a>
                </li>
            </ul>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane fade in active" id="home_with_icon_title">
                    @include('distributor_product.distributor_product')
                </div>
                {{-- PRODUCT LIST --}}
                <div role="tabpanel" class="tab-pane fade" id="profile_with_icon_title">
                    @include('distributor_product.product_ori')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="/js/jquery.quicksearch.js"></script>
<script src="/js/bootstrap-tagsinput.js"></script>
<script src="/js/distributor_product.js"></script>
<script src="/js/product.js"></script>
@endsection