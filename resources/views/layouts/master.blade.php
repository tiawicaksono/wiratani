<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <!-- Favicon-->
    <link rel="icon" href="{{ asset('images/ico.ico') }}" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="/css/app.css">
    <link rel="stylesheet" type="text/css" href="/css/main.css">
    @yield('style')
</head>

<body class="theme-red ls-closed">
    <!-- Page Loader -->
    <div class="page-loader-wrapper">
        <div class="loader">
            <div class="preloader">
                <div class="spinner-layer pl-red">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
            </div>
            <p>Please wait...</p>
        </div>
    </div>
    <!-- #END# Page Loader -->

    <!-- Search Bar -->
    <div class="search-bar">
        <div class="search-icon">
            <i class="material-icons">search</i>
        </div>
        <input type="text" placeholder="START TYPING...">
        <div class="close-search">
            <i class="material-icons">close</i>
        </div>
    </div>
    <!-- #END# Search Bar -->
    <!-- Top Bar -->
    @include('layouts.topbar')
    <!-- #Top Bar -->
    <section>
        <!-- Left Sidebar -->
        {{ HMenu::renderContent() }}
        {{-- @include('layouts.left_sidemenu') --}}
        <!-- #END# Left Sidebar -->
        <!-- Right Sidebar -->
        @include('layouts.right_sidebar')
        <!-- #END# Right Sidebar -->
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row clearfix">
                @yield('content')
            </div>
        </div>
    </section>

    <!-- Overlay For Sidebars -->
    <div style="display: none;" id="overlay"></div>
    <div style="display: none;" id="popup"></div>
    <!-- #END# Overlay For Sidebars -->
</body>
<script src="/js/master/jquery.js"></script>
<script src="/js/master/bootstrap.js"></script>
<script src="/js/master/bootstrap-select.min.js"></script>
<script src="/js/master/sweetalert.min.js"></script>
<script src="/js/master/jquery.spinner.min.js"></script>
<script src="/js/master/jquery.slimscroll.js"></script>
<script src="/js/master/waves.min.js"></script>
<script src="/js/master/jquery.cookie.js"></script>
<script src="/js/master/jquery.hotkeys.js"></script>
<script src="/js/master/jquery-datatable/jquery.dataTables.js"></script>
<script src="/js/master/jquery-datatable/dataTables.bootstrap.js"></script>
<script src="/js/master/jquery-datatable/export/dataTables.buttons.min.js"></script>
<script src="/js/master/jquery-datatable/export/buttons.flash.min.js"></script>
<script src="/js/master/jquery-datatable/export/jszip.min.js"></script>
<script src="/js/master/jquery-datatable/export/pdfmake.min.js"></script>
<script src="/js/master/jquery-datatable/export/vfs_fonts.js"></script>
<script src="/js/master/jquery-datatable/export/buttons.html5.min.js"></script>
<script src="/js/master/jquery-datatable/export/buttons.print.min.js"></script>

<script src="/js/master/jquery.inputmask.bundle.js"></script>
<script src="/js/master/bootstrap-datepicker.min.js"></script>
<script src="/js/master/jquery.numeric.js"></script>
<script src="/js/master/jquery.multi-select.js"></script>
<script src="/js/easyui/jquery.easyui.min.js"></script>
<script src="/js/easyui/datagrid-scrollview.js"></script>
<script src="/js/app.js"></script>
<script src="/js/main.js"></script>
@yield('script')

</html>