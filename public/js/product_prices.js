$(document).ready(function () {
    $('#table-distribtor-product').dataTable({
        aoColumnDefs: [
            { visible: false, aTargets: [9] },
            { bSortable: false, aTargets: [2, 4, 5, 8] },
            { bSearchable: false, aTargets: [0, 2, 3, 4, 5, 8, 9] },
            {
                targets: [2, 3, 8],
                className: "text-center",
            },
        ],
        bInfo: false,
        order: [[9, 'desc']],
        lengthChange: false,
        // searching: false
        processing: true,
        serverSide: true,
        ajax: {
            url: "productPrices/show",
            dataType: "JSON",
            type: "POST"
        },
        columns: [
            { data: "product_name" },
            { data: "distributor_name" },
            { data: "total_product" },
            { data: "stock_product" },
            { data: "purchase_price" },
            { data: "selling_price" },
            { data: "profit" },
            { data: "delivery_date" },
            { data: "action" },
            { data: "id" },
        ]
    });
    $('.total_product').numeric({ decimal: false, negative: false });
    // $('.selling_price').numeric({ decimal: false, negative: false });
})

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
        trObj.find(".editInput.purchase_price").val('');
        trObj.find(".editInput.selling_price").val('');
        trObj.find(".editInput.purchase_price_ori").val('');
        trObj.find(".editInput.selling_price_ori").val('');
        var d = new Date();
        var strDate = d.getDate() + "/" + (d.getMonth() + 1) + "" + d.getFullYear();
        trObj.find(".editInput.delivery_date").val(strDate);
    }
}

function saveButton(obj, ID, choice = 'update') {
    var trObj = $(obj).closest("tr");
    var inputData = $(obj).closest("tr").find(".varInput").serialize();
    $.ajax({
        url: 'productPrices/' + choice,
        type: 'POST',
        dataType: "json",
        data: inputData + '&id=' + ID,
        success: function (response) {
            if (response.status == 'ok') {
                if (choice == 'update') {
                    trObj.find(".editSpan.product_name").text(response.product_name);
                    trObj.find(".editSpan.total_product").text(response.total_product);
                    trObj.find(".stock_product").text(response.stock_product);
                    trObj.find(".profit").text("Rp " + addPeriod(response.profit));
                    trObj.find(".editSpan.purchase_price").text("Rp " + response.purchase_price);
                    trObj.find(".editSpan.selling_price").text("Rp " + response.selling_price);
                    trObj.find(".editSpan.delivery_date").text(response.format_delivery_date);
                    trObj.find(".editInput.delivery_date").val(response.delivery_date);
                    trObj.find(".editInput").hide();
                    trObj.find(".editSpan").show();
                    trObj.find(".edit-delete").show();
                    trObj.find(".saveBtn").hide();
                    trObj.find(".cancelBtn").hide();
                } else {
                    $('#ig_checkbox_0').prop('checked', false);
                    trObj.find(".editInput.total_product").val('');
                    trObj.find(".editInput.purchase_price").val('');
                    trObj.find(".editInput.selling_price").val('');
                    trObj.find(".editInput.purchase_price_ori").val('');
                    trObj.find(".editInput.selling_price_ori").val('');
                    $('#table-distribtor-product').dataTable().api().ajax.reload();
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
        url: 'productPrices/destroy',
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
        url: 'productPrices/detail',
        type: 'POST',
        dataType: "json",
        data: { id: ID },
        success: function (response) {
            trObj.find(".purchase_price").val(addPeriod(response.purchase_price));
            trObj.find(".purchase_price_hidden").val(response.purchase_price);
            trObj.find(".selling_price").val(addPeriod(response.selling_price));
            trObj.find(".selling_price_hidden").val(response.selling_price);
        }
    });
}

function hai() {
    $.ajax({
        url: "productPrices/selectpicker",
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
                options += '<option value="' + obj.id + '" data-subtext="' + obj.distributor + '">' + obj.product + '</option>';
            });
            select.empty();
            select.html(options);
            select.selectpicker('refresh');
        }
    });
}