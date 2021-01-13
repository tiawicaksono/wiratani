$(document).ready(function () {
    $('#bs_datepicker_range_container').datepicker({
        format: 'dd/mm/yyyy',
        autoclose: true,
        container: '#bs_datepicker_range_container'
    });
    fill_datatable()
    $("#btnFilter").click(function () {
        fill_datatable()
    })

})

function fill_datatable() {
    var fromDate = $("#dari_tgl").val();
    var toDate = $("#sampai_tgl").val();
    $('#table-distribtor-product').dataTable({
        // dom: 'Bfrtip',   
        aoColumnDefs: [
            { bSortable: false, aTargets: [4, 5, 6, 7] },
            {
                targets: [4],
                className: "text-center",
            },
        ],
        buttons: [
            'copy', 'excel'
        ],
        destroy: true,
        bInfo: false,
        lengthMenu: [[25, 50, -1], [25, 50, "All"]],
        searching: false,
        processing: true,
        serverSide: true,
        ajax: {
            url: "listtransaction",
            dataType: "json",
            type: "POST",
            data: {
                from_date: fromDate,
                to_date: toDate
            }
        },
        drawCallback: function (settings) {
            $('#total_product_price').html(settings.json.total_product_price);
            $('#total_selling_price').html(settings.json.total_selling_price);
            $('#total_profit').html(settings.json.total_profit);
        },
        columns: [
            { data: "no_kuitansi" },
            { data: "sales_date" },
            { data: "distributor_name" },
            { data: "product_name" },
            { data: "qty" },
            { data: "basic_selling_price" },
            { data: "total_selling_price" },
            { data: "profit" }
        ]
    });
}

function downloadSalesTransaction(urlAct) {
    var dari_tgl = $('#dari_tgl').val();
    var sampai_tgl = $('#sampai_tgl').val();
    window.location.href = urlAct + "?dari_tgl=" + dari_tgl + "&sampai_tgl=" + sampai_tgl;
    return false;
}