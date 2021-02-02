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
            <th style="width:150px !important">Note</th>
            <th style="width:10px !important">Qty</th>
            <th style="width:150px !important">Price</th>
            <th style="width:150px !important">Total</th>
            <th style="width:10px !important">Total</th>
            <th style="width:10px !important">Delivery Date</th>
            <th style="width:250px !important"></th>
        </tr>
    </thead>
    {{-- <tfoot>
        <tr>
            <th colspan="5">TOTAL</th>
            <th id="total"></th>
            <th colspan="3"></th>
        </tr>
    </tfoot> --}}
</table>
<div class="row m-r10">
    <table>
        <tr>
            <td style="width: 75px">HELIOS</td>
            <td style="width: 15px">:</td>
            <td style="width: 105px" align="right"><span id="sumber_helios"></span></td>
        </tr>
        <tr>
            <td>WIRATANI</td>
            <td>:</td>
            <td align="right"><span id="sumber_wiratani"></span></td>
        </tr>
        <tr>
            <td colspan="3">
                -----------------------------------------------------------
                +
            </td>
        </tr>
        <tr>
            <td>TOTAL</td>
            <td>:</td>
            <td align="right"><span id="total_sumber"></span></td>
        </tr>
    </table>
</div>