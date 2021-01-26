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
    closeDialogTable()
})

function closeDialogTable() {
    $("#dialogListGridProduct").dialog("close");
}

$(document).on("keypress", ".form_product_code", function (e) {
    var code = e.keyCode || e.which;
    if (code == 13) {
        prosesSearch($(this).val(), this)
    }
});

$(document).on("keypress", ".price", function (e) {
    var code = e.keyCode || e.which;
    if (code == 13) {
        addRow();
    }
});

function fill_datatable() {
    var fromDate = $("#dari_tgl").val();
    var toDate = $("#sampai_tgl").val();
    var groupColumnProduct = 6;
    $('#table-supply').dataTable({
        sDom: '<"row view-filter"<"col-sm-12"<"pull-left"l><"pull-right"f><"clearfix">>>t<"row view-pager"<"col-sm-12"<"text-center"ip>>>',
        aoColumnDefs: [
            { visible: false, aTargets: [6] },
            { bSortable: false, aTargets: [2, 3, 4, 5, 6, 7, 8] },
            {
                targets: [3, 7, 8],
                className: "text-center",
            },
        ],
        order: [[groupColumnProduct, 'asc']],
        destroy: true,
        bInfo: false,
        drawCallback: function (settings) {
            var api = this.api();
            var rows = api.rows({ page: 'current' }).nodes();
            var last = null;
            api.column(groupColumnProduct, { page: 'current' }).data().each(function (group, i) {
                if (last !== group) {
                    $(rows).eq(i).before(
                        '<tr class="group"><td colspan="8">' + group + '</td></tr>'
                    );
                    last = group;
                }
            });
            $('#total').html(settings.json.total);
            $('#sumber_helios').html(settings.json.total_helios);
            $('#sumber_wiratani').html(settings.json.total_wiratani);
        },
        lengthMenu: [[25, 50, -1], [25, 50, "All"]],
        searching: false,
        processing: true,
        serverSide: true,
        ajax: {
            url: 'supply/show',
            dataType: "JSON",
            type: "POST",
            data: {
                from_date: fromDate,
                to_date: toDate
            }
        },
        columns: [
            { data: 'product_name' },
            { data: 'distributor_name' },
            { data: 'note' },
            { data: 'qty' },
            { data: 'price' },
            { data: 'total' },
            { data: 'delivery_date' },
            { data: 'source' },
            { data: 'action' }
        ]
    });
}

function prosesSearch(text_category, this_textbox) {
    let distributor = $('#distributor_id').val()
    $.ajax({
        url: "supply/search",
        type: "POST",
        data: {
            search: text_category,
            distributor: distributor
        },
        dataType: "JSON",
        beforeSend: function () {
            showlargeloader();
        },
        success: function (data) {
            hidelargeloader();
            if (data.count_data > 1) {
                loadDialogTable(text_category, distributor, this_textbox);
            } else if (data.count_data == 1) {
                closeDialogTable()
                $(this_textbox)
                    .closest("tr")
                    .find(".form_product_code")
                    .val(data.product_name);
                $(this_textbox)
                    .closest("tr")
                    .find(".form_product_id")
                    .val(data.id);
                $(this_textbox)
                    .closest("tr")
                    .find(".qty")
                    .focus()
            } else {
                $(this_textbox)
                    .closest("tr")
                    .find(".form_product_id")
                    .val(0);
                swal({
                    closeOnEsc: false,
                    title: "Data not found",
                    type: "error"
                    // text: "Here's a custom image."
                });
            }
        },
        error: function () {
            $(this_textbox)
                .closest("tr")
                .find(".form_product_id")
                .val(0);
            swal({
                closeOnEsc: false,
                title: "Data not found",
                type: "error"
                // text: "Here's a custom image."
            });
            hidelargeloader();
            return false;
        }
    });
}

function loadDialogTable(text_category, distributor, this_textbox) {
    $("#dialogListGridProduct").dialog("open");
    $("#dialogListGridProduct").dialog("center");
    $("#listGridProduct").datagrid({
        url: "supply/list",
        singleSelect: true,
        selectOnCheck: false,
        checkOnSelect: true,
        pagination: true,
        collapsible: true,
        minimizable: true,
        rownumbers: true,
        striped: true,
        loadMsg: "Loading...",
        method: "POST",
        nowrap: false,
        pageNumber: 1,
        pageSize: 10,
        pageList: [5, 10, 20],
        columns: [
            [{
                field: "id",
                title: "",
                width: 50,
                halign: "center",
                align: "center",
                formatter: buttonSelectAcuan
            },
            {
                field: "product_code",
                title: "Product Code",
                width: 130,
                sortable: true
            },
            {
                field: "product_name",
                width: 200,
                title: "Product Name",
                sortable: true
            }]
        ],
        onBeforeLoad: function (params) {
            params.search = text_category;
            params.distributor = distributor;
        },
        onLoadError: function () {
            return false;
        },
        onLoadSuccess: function () { }
    });

    function buttonSelectAcuan(value) {
        var button = '<button type="button" class="btn btn-info" onclick="selectProduct(\'' + value + '\',\'' + this_textbox.id + '\')"><span class="glyphicon glyphicon-zoom-in "></span></button>';
        return button;
    }
}

function selectProduct(params, field_id) {
    var explode = field_id.split('_')
    var idx = explode[3]
    var distributor = $('#distributor_id').val()
    $.ajax({
        url: "supply/search",
        type: "POST",
        data: {
            search: params,
            distributor: distributor
        },
        dataType: "JSON",
        beforeSend: function () {
            showlargeloader();
        },
        success: function (data) {
            hidelargeloader()
            closeDialogTable()
            $('#form_product_code_' + idx).val(data.product_name)
            $('#form_product_id_' + idx).val(data.id)
            $('#qty_' + idx).focus()
        },
        error: function () {
            hidelargeloader();
            return false;
        }
    });
}

function save() {
    var arr = [];
    $(".form_product_id").each(function () {
        var id = $(this).closest("tr").find('.form_product_id').val()
        var product_code = $(this).closest("tr").find('.form_product_code').val()
        var qty = $(this).closest("tr").find('.qty').val()
        var price = $(this).closest("tr").find('.price_ori').val()
        var source_price = $(this).closest("tr").find('.filled-in:checkbox:checked').val()
        arr.push({
            id: id,
            product_code: product_code,
            qty: qty,
            price: price,
            source_price: source_price
        });
    });
    let input_date = $("#input_date").val()
    let distributor_id = $("#distributor_id :selected").val()
    $.ajax({
        url: "supply/store",
        type: "POST",
        data: {
            _token: $('input[name="_token"]').val(),
            batch: arr,
            input_date: input_date,
            distributor_id: distributor_id,
        },
        async: false,
        beforeSend: function () {
            showlargeloader()
        },
        success: function (data) {
            hidelargeloader()
            $('#product_table tbody tr').remove()
            addRow()
            $('#table-supply').dataTable().api().ajax.reload()
        },
        error: function () {
            hidelargeloader()
            return false
        }
    });
}

// =======================================================
// BUTTON
function deleteButton(obj) {
    var trObj = $(obj).closest("tr");
    //hide delete button
    trObj.find(".edit-delete").hide();

    //show confirm button
    trObj.find(".confirmBtn").show();
    trObj.find(".cancelBtn").show();
}

function editButton(obj) {
    var trObj = $(obj).closest("tr");
    //hide edit span
    trObj.find(".editSpan").hide();
    //show edit input
    trObj.find(".editInput").show();

    //hide edit button
    trObj.find(".edit-delete").hide();

    //show save and cancel button
    trObj.find(".saveBtn").show();
    trObj.find(".cancelBtn").show();
    $('.product_id').selectpicker('refresh');
}

function cancelButton(obj, choice = 'update') {
    var trObj = $(obj).closest("tr");
    if (choice == 'update') {
        //hide delete button
        trObj.find(".edit-delete").show();

        //show confirm button
        trObj.find(".saveBtn").hide();
        trObj.find(".confirmBtn").hide();
        trObj.find(".cancelBtn").hide();

        //hide edit span
        trObj.find(".editSpan").show();
        //show edit input
        trObj.find(".editInput").hide();
    } else {
        trObj.find(".editInput.total_product").val('');
        trObj.find(".stock_product").text('0');
        trObj.find(".editInput.purchase_price").val('');
        trObj.find(".editInput.selling_price").val('');
        trObj.find(".editInput.purchase_price_ori").val('');
        trObj.find(".editInput.selling_price_ori").val('');
        var d = new Date();
        var strDate = d.getDate() + "/" + (d.getMonth() + 1) + "" + d.getFullYear();
        trObj.find(".editInput.input_date").val(strDate);
    }
}

function saveButton(obj, ID, choice = 'update') {
    var trObj = $(obj).closest("tr");
    var inputData = $(obj).closest("tr").find(".varInput").serialize();
    $.ajax({
        url: choice,
        type: 'POST',
        dataType: "json",
        data: inputData + '&id=' + ID,
        success: function (response) {
            if (response.status == 'ok') {
                if (choice == 'update') {
                    trObj.find(".editSpan.product_name").text(response.product_name);
                    trObj.find(".qty").text(response.qty);
                    trObj.find(".note").text(response.note);
                    trObj.find(".editSpan.input_date").text(response.format_input_date);
                    trObj.find(".editInput.input_date").val(response.input_date);

                    trObj.find(".editInput").hide();
                    trObj.find(".editSpan").show();
                    trObj.find(".edit-delete").show();
                    trObj.find(".saveBtn").hide();
                    trObj.find(".cancelBtn").hide();
                } else {
                    trObj.find(".editInput.qty").val('');
                    trObj.find(".stock_product").text('0');
                    trObj.find(".editInput.note").val('');
                    $('#table-supply').dataTable().api().ajax.reload();
                }

            } else {
                swal({
                    closeOnEsc: true,
                    title: "error",
                    type: "error"
                });
            }
        }
    });
}

function actionDelete(obj, ID) {
    var trObj = $(obj).closest("tr");
    $.ajax({
        url: 'supply/destroy',
        type: 'POST',
        dataType: "json",
        data: { id: ID },
        success: function (response) {
            if (response.status == 'ok') {
                trObj.remove();
                trObj.find(".edit-delete").show();
                trObj.find(".confirmBtn").hide();
                trObj.find(".cancelBtn").hide();
            } else {
                trObj.find(".edit-delete").show();
                trObj.find(".confirmBtn").hide();
                trObj.find(".cancelBtn").hide();
                swal({
                    closeOnEsc: false,
                    title: response.msg,
                    type: "error"
                    // text: "Here's a custom image."
                });
            }
        }
    });
}

// =======================================================
let rowIdx = 1;
function addRow() {
    rowIdx++;
    $("#tbody").append(
        '<tr id="' +
        rowIdx +
        '">' +
        '<td class="row-index text-center">' +
        '<input type="checkbox" class="filled-in" id="ig_checkbox' + rowIdx + '" style="float: right; width:5%"><label for="ig_checkbox' + rowIdx + '"></label>' +
        '<input type="text" class="form-control form_product_code" id="form_product_code_' + rowIdx + '" style="float: right; width:85%">' +
        '<input type="hidden" class="form-control form_product_id" id="form_product_id_' + rowIdx + '"></td>' +
        '<td class="row-index text-center"><input type="text" class="form-control qty text-center" id="qty_' + rowIdx + '"></td>' +
        '<td class="row-index text-right">' +
        '<input type="text" class="form-control price text-center" id="price_' + rowIdx + '" size="5" onkeyup="priceRow(this)">' +
        '<input type="hidden" class="form-control price_ori text-center" id="price_' + rowIdx + '_ori" value="0">' +
        '</td>' +
        '<td class="text-center">' +
        '<button class="btn btn-danger remove" type="button">' +
        '<i class="material-icons">delete</i></button></td></tr>'
    );
    $("#form_product_code_" + rowIdx).focus();
}

$("#product_table").on("click", ".remove", function () {
    $(this)
        .closest("tr")
        .remove();
});