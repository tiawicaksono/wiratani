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
    var groupColumnProduct = 6;
    $('#table-supply').dataTable({
        sDom: '<"row view-filter"<"col-sm-12"<"pull-left"l><"pull-right"f><"clearfix">>>t<"row view-pager"<"col-sm-12"<"text-center"ip>>>',
        aoColumnDefs: [
            { visible: false, aTargets: [6] },
            { bSortable: false, aTargets: [2, 3, 4, 5, 6, 7, 8] },
            {
                targets: [4, 7, 8],
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
            url: "supply/show",
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
            { data: 'price' },
            { data: 'qty' },
            { data: 'total' },
            { data: 'delivery_date' },
            { data: 'source' },
            { data: 'action' }
        ]
    });

    // $('#table-supply tbody').on('click', 'tr.group', function () {
    //     var currentOrder = table.order()[0];
    //     if (currentOrder[0] === groupColumn && currentOrder[1] === 'asc') {
    //         table.order([groupColumn, 'desc']).draw();
    //     }
    //     else {
    //         table.order([groupColumn, 'asc']).draw();
    //     }
    // });
}

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
        url: 'stockOpname/' + choice,
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

// =======================================================

function actionDelete(obj, ID) {
    var trObj = $(obj).closest("tr");
    $.ajax({
        url: 'stockOpname/destroy',
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
function selectInput(obj) {
    var subtext = $(obj).find('option:selected').data("subtext");
    var trObj = $(obj).closest("tr");
    trObj.find(".distributor_name").text(subtext);
    ID = $(obj).find('option:selected').val();
    $.ajax({
        url: 'stockOpname/detail',
        type: 'POST',
        dataType: "json",
        data: { id: ID },
        success: function (response) {
            trObj.find(".stock_product").html(response.stock);
        }
    });
}

function price(obj) {
    if (!$(obj).val()) {
        var n = ""
    } else {
        var n = parseInt(
            $(obj)
                .val()
                .replace(/\D/g, ""),
            10
        )
    }
    $(obj).val(n.toLocaleString("id"))
}

function priceRow(obj) {
    let id = obj.id
    price(obj)
    if (!$(obj).val()) {
        $("#" + id + "_ori").val(0)
    } else {
        $("#" + id + "_ori").val(
            $(obj)
                .val()
                .replace(/\.(\d\d)$/, ".$1")
                .replace(".", "")
        )
    }
}

function reloadProduct() {
    $.ajax({
        url: "stockOpname/selectpicker",
        type: 'post',
        beforeSend: function () {
            showlargeloader();
        },
        success: function (response) {
            hidelargeloader();
            var jsonData = JSON.stringify(response);
            var options = '';
            var select = $('#product_id');
            $.each(JSON.parse(jsonData), function (idx, obj) {
                options += '<option value="' + obj.id + '" data-subtext="' + obj.distributor + ' (' + obj.stock_product + ')' + '">' + obj.product + '</option>';
            });
            select.empty();
            select.html(options);
            select.selectpicker('refresh');
        }
    });
}