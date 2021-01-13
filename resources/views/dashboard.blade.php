@extends('layouts.master')

@section('title','DASHBOARD')
@section('content')
{{ Helpers::konversiUang(3750) }}
@endsection