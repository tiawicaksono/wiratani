$(document).ready(function () {
    var groupColumnProduct = 0;
    $('#table-products').dataTable({
        "sDom": '<"row view-filter"<"col-sm-12"<"pull-left"l><"pull-right"f><"clearfix">>>t<"row view-pager"<"col-sm-12"<"text-center"ip>>>',
        "aoColumnDefs": [
            { targets: [2], "className": "text-center", "width": "148px" },
            { "visible": false, "aTargets": [3] },
            { "bSortable": false, "aTargets": [0, 1, 2, 3] },
            { "bSearchable": false, "aTargets": [2, 3] }
        ],
        'order': [[groupColumnProduct, 'asc']],
        "displayLength": 10,
        "lengthChange": false,
        "bInfo": false,
        "drawCallback": function (settings) {
            var api = this.api();
            var rows = api.rows({ page: 'current' }).nodes();
            var last = null;
            api.column(groupColumnProduct, { page: 'current' }).data().each(function (group, i) {
                if (last !== group) {
                    $(rows).eq(i).before(
                        '<tr class="group"><td colspan="4">' + group + '</td></tr>'
                    );
                    last = group;
                }
            });
        },
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "product/show",
            "dataType": "json",
            "type": "POST"
        },
        "columns": [
            { "data": "product_category_id" },
            { "data": "product_name" },
            { "data": "action" },
            { "data": "category_name" },
        ]
    });
})

function saveButtonNewProduct(obj, urlAct) {
    var trObj = $(obj).closest("tr");
    var inputData = $(obj).closest("tr").find(".varInput").serialize();
    $.ajax({
        url: urlAct,
        type: 'POST',
        dataType: "json",
        data: inputData,
        success: function (response) {
            if (response.status == 'ok') {
                trObj.find('#product_name').val('');
                trObj.find("#product_name").tagsinput('removeAll');
                $('#table-products').dataTable().api().ajax.reload();
                $.each(response.obj, function (key, dataValue) {
                    dtValue = dataValue.split('|');
                    $('#optgroup').multiSelect('addOption', { value: key, text: dtValue[0], index: 0, nested: dtValue[1] });
                });
                $("#optgroup").multiSelect('refresh');
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

function saveButtonProduct(obj, ID) {
    var trObj = $(obj).closest("tr");
    var inputData = $(obj).closest("tr").find(".varInput").serialize();
    $.ajax({
        url: 'product/update',
        type: 'POST',
        dataType: "json",
        data: inputData + '&id=' + ID,
        success: function (response) {
            if (response.status == 'ok') {
                trObj.find(".editSpan.category_name").val(response.product_category_id);
                trObj.find(".editSpan.category_name_text").text(response.category_name);
                trObj.find(".editSpan.product_name").text(response.product_name);
                trObj.find(".editSpan.product_name").val(response.product_name);

                trObj.find(".editInput").hide();
                trObj.find(".editSpan").show();
                trObj.find(".edit-delete").show();
                trObj.find(".saveBtn").hide();
                trObj.find(".cancelBtn").hide();
                $("#optgroup").multiSelect('refresh');
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

function actionDeleteProduct(obj, ID) {
    var trObj = $(obj).closest("tr");
    $.ajax({
        url: 'product/destroy',
        type: 'POST',
        dataType: "json",
        data: { id: ID },
        success: function (response) {
            if (response.status == 'ok') {
                trObj.remove();
                trObj.find(".edit-delete").show();
                trObj.find(".confirmBtn").hide();
                trObj.find(".cancelBtn").hide();
                $("#optgroup option[value=\"" + ID + "\"]").remove();
                $("#optgroup").multiSelect('refresh');
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