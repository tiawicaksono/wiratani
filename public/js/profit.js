$(document).ready(function () {
    fill_datatable_withdraw()
    fill_datatable_profit()
    $("#btnFilter").click(function () {
        fill_datatable_profit()
        fill_datatable_withdraw()
    })

})

$(document).on("keypress", ".price", function (e) {
    var code = e.keyCode || e.which;
    if (code == 13) {
        addRow();
    }
});

function fill_datatable_profit() {
    var bulan = $("#bulan").val();
    $('#table-profit').dataTable({
        paging: false,
        displayLength: 50,
        searching: false,
        bInfo: false,
        destroy: true,
        processing: true,
        serverSide: true,
        drawCallback: function (settings) {
            $('#total_profit').html(settings.json.total_profit);
        },
        ajax: {
            url: 'profit/showProfit',
            dataType: "JSON",
            type: "POST",
            data: {
                bulan: bulan
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(textStatus, errorThrown);
            }

        },
        columns: [
            { data: 'sales_date' },
            { data: 'profit' }
        ]
    });
}

function fill_datatable_withdraw() {
    var bulan = $("#bulan").val();
    var groupColumnProduct = 0;
    $('#table-supply').dataTable({
        sDom: '<"row view-filter"<"col-sm-12"<"pull-left"l><"pull-right"f><"clearfix">>>t<"row view-pager"<"col-sm-12"<"text-center"ip>>>',
        aoColumnDefs: [
            { visible: false, aTargets: [0] },
            { bSortable: false, aTargets: [0, 1, 2, 3, 4, 5] },
            {
                targets: [2, 5],
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
                        '<tr class="group"><td colspan="6">' + group + '</td></tr>'
                    );
                    last = group;
                }
            });
            $('#total_pemakaian').html(settings.json.total);
        },
        lengthMenu: [[15, 50, -1], [15, 50, "All"]],
        searching: false,
        processing: true,
        serverSide: true,
        "sDom": 'Rfrtlip',
        ajax: {
            url: 'profit/showWithDraw',
            dataType: "JSON",
            type: "POST",
            data: {
                bulan: bulan,
            }
        },
        columns: [
            { data: 'delivery_date' },
            { data: 'note' },
            { data: 'qty' },
            { data: 'price' },
            { data: 'total' },
            { data: 'action' }
        ]
    });
}

// =======================================================
function save() {
    var arr = [];
    $(".price_ori").each(function () {
        var note_pengambilan = $(this).closest("tr").find('.note_pengambilan').val()
        var qty = $(this).closest("tr").find('.qty').val()
        var price = $(this).closest("tr").find('.price_ori').val()
        arr.push({
            note: note_pengambilan,
            qty: qty,
            price: price,
        });
    });
    let input_date = $("#input_date").val()
    $.ajax({
        url: "profit/store",
        type: "POST",
        data: {
            _token: $('input[name="_token"]').val(),
            batch: arr,
            input_date: input_date
        },
        dataType: "JSON",
        async: false,
        beforeSend: function () {
            showlargeloader()
        },
        success: function (data) {
            hidelargeloader()
            $('#sum_profit').html(data.sum_profit)
            $('#sum_sisa_profit').html(data.sum_sisa_profit)
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

function saveButton(obj, ID) {
    var trObj = $(obj).closest("tr");
    var inputData = $(obj).closest("tr").find(".varInput").serialize();
    $.ajax({
        url: 'profit/update',
        type: 'POST',
        dataType: "json",
        data: inputData + '&id=' + ID,
        success: function (response) {
            if (response.status == 'ok') {
                trObj.find(".total").text("Rp " + addPeriod(response.total));
                trObj.find(".qty").text(response.qty);
                trObj.find(".note_text").text(response.note);
                trObj.find(".price_text").text("Rp " + addPeriod(response.price));
                trObj.find(".editInput").hide();
                trObj.find(".editSpan").show();
                trObj.find(".edit-delete").show();
                trObj.find(".saveBtn").hide();
                trObj.find(".cancelBtn").hide();
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
        url: 'profit/destroy',
        type: 'POST',
        dataType: "JSON",
        data: { id: ID },
        success: function (response) {
            if (response.status == 'ok') {
                $('#sum_profit').html(response.sum_profit)
                $('#sum_sisa_profit').html(response.sum_sisa_profit)
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
        '<input type="text" class="form-control note_pengambilan" id="note_pengambilan_' + rowIdx + '"></td>' +
        '<td class="row-index text-center"><input type="text" class="form-control qty text-center" id="qty_' + rowIdx + '"></td>' +
        '<td class="row-index text-right">' +
        '<input type="text" class="form-control price text-center" id="price_' + rowIdx + '" size="5" onkeyup="priceRow(this)">' +
        '<input type="hidden" class="form-control price_ori text-center" id="price_' + rowIdx + '_ori" value="0">' +
        '</td>' +
        '<td class="text-right">' +
        '<button class="btn btn-danger remove" type="button">' +
        '<i class="material-icons">delete</i></button></td></tr>'
    );
    $("#note_pengambilan_" + rowIdx).focus();
}

$("#product_table").on("click", ".remove", function () {
    $(this)
        .closest("tr")
        .remove();
});