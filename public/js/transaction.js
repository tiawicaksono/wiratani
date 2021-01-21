$(function () {
    $("#product_code_1").focus()
    closeDialogTable()
    domo()

    // JIKA DIALOG EASYUI OPEN
    // var dlg = $("#dialogListGridProduct");
    // if (dlg.data('dialog')) {
    //     $(document).bind('keydown', 'esc', function assets() {
    //         closeDialogTable()
    //         return false;
    //     });
    // }
});

$(document).on("keypress", ".product_code", function (e) {
    var code = e.keyCode || e.which;
    if (code == 13) {
        prosesSearch($(this).val(), this)
    }
});

$(document).on("keypress", ".quantity, .discon", function (e) {
    var code = e.keyCode || e.which;
    if (code == 13) {
        addRow();
    }
});

function closeDialogTable() {
    $("#dialogListGridProduct").dialog("close");
}

let rowIdx = 1;
function addRow() {
    rowIdx++;
    $("#tbody").append(
        '<tr id="' +
        rowIdx +
        '">' +
        '<td class="row-index text-center">' +
        '<input type="text" class="form-control product_code" id="product_code_' + rowIdx + '">' +
        '<input type="hidden" class="form-control product_id" id="product_id_' + rowIdx + '"></td>' +
        '<td class="row-index"><span class="product_name" id="product_name_' + rowIdx + '">-</span></td>' +
        '<td class="row-index text-center"><span class="stock" id="stock_' + rowIdx + '">0</span></td>' +
        '<td class="row-index text-right"><span class="unit_price" id="unit_price_' + rowIdx + '"></span>' +
        '<input type="hidden" class="form-control unit_price_ori" id="unit_price_ori_' + rowIdx + '"></td>' +
        '<td class="row-index text-center"><div class="input-group" data-trigger="spinner">' +
        '<span class="input-group-addon">' +
        '<button class="btn btn-default spin-down" data-spin="down" type="button" onclick="calculateUnitPrice(this)">' +
        '<i class="glyphicon glyphicon-minus"></i>' +
        '</button>' +
        "</span>" +
        '<input onkeyup="calculateUnitPrice(this)" type="text" class="form-control text-center quantity" value="1" data-rule="quantity" maxlength="4" size="2" id="qty_' + rowIdx + '" disabled>' +
        '<span class="input-group-addon">' +
        '<button class="btn btn-default spin-up" data-spin="up" type="button" onclick="calculateUnitPrice(this)">' +
        '<i class="glyphicon glyphicon-plus"></i>' +
        "</button>" +
        "</span>" +
        "</div></td>" +
        '<td class="row-index text-right"><input type="text" class="form-control discon text-center" id="discon_' + rowIdx + '" size="5" disabled onkeyup="disconRow(this)">' +
        '<input type="hidden" class="form-control discon_ori" value="0" id="discon_' + rowIdx + '_ori"></td>' +
        '<td class="row-index text-right"><span class="total_price font-bold font-14" id="total_price_' + rowIdx + '"></span>' +
        '<input type="hidden" class="form-control total_price_ori" id="total_price_' + rowIdx + '_ori"></td>' +
        '<td class="text-center">' +
        '<button class="btn btn-danger remove" type="button">' +
        '<i class="material-icons">delete</i></button></td></tr>'
    );
    $("#product_code_" + rowIdx).focus();
    var script = document.createElement("script");
    script.src = "/wiratani/public/js/master/jquery.spinner.min.js";
    script.type = "text/javascript";
    document.getElementsByTagName("head")[0].appendChild(script);
}

$("#product_table").on("click", ".remove", function () {
    $(this)
        .closest("tr")
        .remove();
    calculateTagihan();
});

function discon(obj) {
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

function disconRow(obj) {
    let id = obj.id;
    discon(obj)
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
    calculateUnitPrice(obj)
}

function disconGrand(obj) {
    discon(obj)
    if (!$(obj).val()) {
        $("#discon_grand_ori").val(0)
        calculateTagihan()
    } else {
        $("#discon_grand_ori").val(
            $(obj)
                .val()
                .replace(/\.(\d\d)$/, ".$1")
                .replace(".", "")
        )
        let total_tagihan = parseInt($("#total_tagihan").val())
        let grandDiscon = parseInt($("#discon_grand_ori").val())
        sum = total_tagihan - grandDiscon
        $("#grand_total").val("Rp " + addPeriod(sum))
        $("#grand_total_ori").val(sum)
        // $("#total_tagihan").val(sum)
        $("#sum").html("Rp " + addPeriod(sum))
    }
}

function calculateTagihan() {
    var sum = 0;
    let discon_grand
    $('.total_price_ori').each(function () {
        if (this.value) {
            sum += parseFloat(this.value);
        }
    });
    if (!$("#discon_grand_ori").val()) {
        discon_grand = parseInt(0)
    } else {
        discon_grand = parseInt($("#discon_grand_ori").val())
    }
    $("#total_tagihan").val(sum - discon_grand)
    $("#sum").html("Rp " + addPeriod(sum - discon_grand))
    $("#grand_total").val("Rp " + addPeriod(sum - discon_grand))
    $("#grand_total_ori").val(sum - discon_grand)
}

function calculateUnitPrice(obj) {
    var spin = $(obj).data("spin");
    let sum = 0
    let stock = parseInt($(obj)
        .closest("tr")
        .find(".stock")
        .html());
    let unitPrice = $(obj)
        .closest("tr")
        .find(".unit_price_ori")
        .val();
    let qty = parseInt($(obj)
        .closest("tr")
        .find(".quantity")
        .val());
    let discon = parseInt($(obj)
        .closest("tr")
        .find(".discon_ori")
        .val());
    if (spin == 'up') {
        qty += parseInt(1)
    } else if (spin == 'down') {
        qty -= parseInt(1)
    }
    if (!qty) {
        qty = parseInt(1);
    }
    if (qty > stock) {
        $(obj)
            .closest("tr")
            .find(".quantity")
            .val(stock);
        $(obj)
            .closest("tr")
            .find(".spin-up")
            .prop("disabled", true);
        qty = stock
    } else {
        $(obj)
            .closest("tr")
            .find(".spin-up")
            .prop("disabled", false);
    }
    sum = (qty * unitPrice) - (qty * discon);
    $(obj)
        .closest("tr")
        .find(".total_price_ori")
        .val(sum);
    $(obj)
        .closest("tr")
        .find(".total_price")
        .text("Rp " + addPeriod(sum));
    calculateTagihan();
}

function bayar(obj) {
    discon(obj)
    if (!$(obj).val()) {
        $("#bayar_ori").val(0)
        $("#kembalian").val("Rp 0")
    } else {
        $("#bayar_ori").val(
            $(obj)
                .val()
                .replace(/\.(\d\d)$/, ".$1")
                .replace(".", "")
        )
        let bayar = parseInt($("#bayar_ori").val())
        let grand_total = parseInt($("#grand_total_ori").val())
        kembalian = bayar - grand_total
        $("#kembalian").val("Rp " + addPeriod(kembalian))
    }
}

function prosesSearch(text_category, this_textbox) {
    $.ajax({
        url: "search",
        type: "POST",
        data: {
            search: text_category
        },
        dataType: "JSON",
        beforeSend: function () {
            showlargeloader();
        },
        success: function (data) {
            hidelargeloader();
            if (data.count_data > 1) {
                loadDialogTable(text_category, this_textbox);
            } else if (data.count_data == 1) {
                closeDialogTable()
                $(this_textbox)
                    .closest("tr")
                    .find(".unit_price_ori")
                    .val(data.selling_price_ori);
                $(this_textbox)
                    .closest("tr")
                    .find(".total_price_ori")
                    .val(data.selling_price_ori);
                $(this_textbox)
                    .closest("tr")
                    .find(".product_code")
                    .val(data.barcode);
                $(this_textbox)
                    .closest("tr")
                    .find(".product_id")
                    .val(data.id);
                $(this_textbox)
                    .closest("tr")
                    .find(".product_name")
                    .text(data.product_name);
                $(this_textbox)
                    .closest("tr")
                    .find(".stock")
                    .text(data.stock_product);
                $(this_textbox)
                    .closest("tr")
                    .find(".unit_price")
                    .text(data.selling_price);
                $(this_textbox)
                    .closest("tr")
                    .find(".total_price")
                    .text(data.selling_price);
                $(this_textbox)
                    .closest("tr")
                    .find(".discon")
                    .prop("disabled", false);
                $(this_textbox)
                    .closest("tr")
                    .find(".quantity")
                    .prop("disabled", false);
                $(this_textbox)
                    .closest("tr")
                    .find(".quantity")
                    .focus();
                // addRow()
                calculateTagihan();
            } else {
                swal({
                    closeOnEsc: false,
                    title: "Data not found",
                    type: "error"
                    // text: "Here's a custom image."
                });
            }
        },
        error: function () {
            hidelargeloader();
            return false;
        }
    });
}

function loadDialogTable(text_category, this_textbox) {
    $("#dialogListGridProduct").dialog("open");
    $("#dialogListGridProduct").dialog("center");
    $("#listGridProduct").datagrid({
        url: "list",
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
                field: "barcode",
                title: "Product Code",
                width: 130,
                sortable: true
            },
            {
                field: "product_name",
                width: 200,
                title: "Product Name",
                sortable: true
            },
            {
                field: "stock_product",
                width: 70,
                title: "Stock",
                sortable: false,
                halign: "center",
                align: "center"
            },
            {
                field: "purchase_price",
                width: 120,
                title: "Price",
                sortable: false
            }]
        ],
        onBeforeLoad: function (params) {
            params.search = text_category;
        },
        onLoadError: function () {
            return false;
        },
        onLoadSuccess: function () { }
    });

    function buttonSelectAcuan(value) {
        var button = '<button type="button" class="btn btn-primary waves-effect" onclick="selectProduct(\'' + value + '\',\'' + this_textbox.id + '\')"><i class="material-icons">check_circle</i></button>';
        return button;
    }
}

function selectProduct(params, field_id) {
    var explode = field_id.split('_');
    var idx = explode[2];
    $.ajax({
        url: "search",
        type: "POST",
        data: {
            search: params
        },
        dataType: "JSON",
        beforeSend: function () {
            showlargeloader();
        },
        success: function (data) {
            hidelargeloader()
            closeDialogTable()
            $('#unit_price_ori_' + idx).val(data.selling_price_ori)
            $('#total_price_' + idx + '_ori').val(data.selling_price_ori)
            $("#" + field_id).val(params)
            $("#product_id_" + idx).val(data.id)
            $('#product_name_' + idx).text(data.product_name)
            $('#stock_' + idx).text(data.stock_product)
            $('#unit_price_' + idx).text(data.selling_price)
            $('#total_price_' + idx).text(data.selling_price)
            $('#discon_' + idx).prop("disabled", false)
            $('#qty_' + idx).prop("disabled", false);
            $('#qty_' + idx).focus();
            calculateTagihan()
            // addRow()
        },
        error: function () {
            hidelargeloader();
            return false;
        }
    });
}

function save() {
    var arr = [];
    $(".product_id").each(function () {
        var id = $(this).closest("tr").find('.product_id').val()
        var qty = $(this).closest("tr").find('.quantity').val()
        var discon = $(this).closest("tr").find('.discon_ori').val()
        // var price = $(this).closest("tr").find('.total_price_ori').val();
        arr.push({
            id: id,
            qty: qty,
            discon: discon
            // price: price
        });
    });
    let grand_discon = $("#discon_grand_ori").val()
    $.ajax({
        url: "store",
        type: "POST",
        data: { batch: arr, grand_discon: grand_discon },
        async: false,
        beforeSend: function () {
            showlargeloader()
        },
        success: function (data) {
            hidelargeloader()
            cancel()
        },
        error: function () {
            hidelargeloader()
            return false
        }
    });
}

function cancel() {
    $("#discon_grand").val('')
    $("#discon_grand_ori").val(0)
    $("#bayar").val('')
    $("#bayar_ori").val(0)
    $("#grand_total").val("Rp 0")
    $("#grand_total_ori").val(0)
    $("#kembalian").val("Rp 0")
    $("#total_tagihan").val(0)
    $("#sum").html("Rp 0")
    $('#product_table tbody tr').remove()
    addRow()
}

// PERCOBAAN
function domo() {
    $(document).bind('keydown', 'esc', function assets() {
        swal.close()
        closeDialogTable()
        return false;
    });

    $(document).bind('keydown', 'f1', function assets() {
        $("#discon_grand").focus()
        return false;
    });

    $(document).bind('keydown', 'f2', function assets() {
        $("#bayar").focus()
        $grand_total = $("#grand_total_ori").val()
        $("#bayar").val($grand_total)
        $("#bayar_ori").val($grand_total)
        return false;
    });

    $(document).bind('keydown', 'f9', function assets() {
        cancel()
        return false;
    });

    $(document).bind('keydown', 'f4', function assets() {
        save()
        return false;
    });

    $(document).bind('keydown', 'Ctrl+c', function assets() {
        $grand_total = $("#grand_total_ori").val()
        $("#bayar").val($grand_total)
        $("#bayar_ori").val($grand_total)
        return false;
    });
}