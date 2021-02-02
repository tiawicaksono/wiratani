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
            @include('supply.form')
        </div>
    </div>
</div>
<div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
    <div class="card">
        <div class="header">
            <h2>ORDER</h2>
        </div>
        <div class="body">
            @include('supply.list')
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