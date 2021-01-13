$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
    }
});

$(document).ready(function () {
    var groupColumns = 2;
    $('#table-distribtor-products').dataTable({
        "sDom": '<"row view-filter"<"col-sm-12"<"pull-left"l><"pull-right"f><"clearfix">>>t<"row view-pager"<"col-sm-12"<"text-center"ip>>>',
        "aoColumnDefs": [
            { targets: [3], "className": "text-center", "width": "70px" },
            { "visible": false, "aTargets": [groupColumns] },
            { "bSortable": false, "aTargets": [0, 1, 2, 3] },
        ],
        'order': [[groupColumns, 'asc']],
        "displayLength": 10,
        "lengthChange": false,
        "bInfo": false,
        "drawCallback": function (settings) {
            var api = this.api();
            var rows = api.rows({ page: 'current' }).nodes();
            var last = null;
            api.column(groupColumns, { page: 'current' }).data().each(function (group, i) {
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
            "url": "distributorProduct/show",
            "dataType": "json",
            "type": "POST"
        },
        "columns": [
            { "data": "product_code" },
            { "data": "product_name" },
            { "data": "distributor_name" },
            { "data": "action" },
        ]
    });



    /**
     * SEARCHABLE MULTISELECT
     */
    $('#optgroup').multiSelect({
        selectableHeader: "<input type='text' class='form-control search-input' autocomplete='off'>",
        selectionHeader: "<input type='text' class='form-control search-input' autocomplete='off'>",
        afterInit: function (ms) {
            var that = this,
                $selectableSearch = that.$selectableUl.prev(),
                $selectionSearch = that.$selectionUl.prev(),
                selectableSearchString = '#' + that.$container.attr('id') + ' .ms-elem-selectable:not(.ms-selected)',
                selectionSearchString = '#' + that.$container.attr('id') + ' .ms-elem-selection.ms-selected';

            that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
                .on('keydown', function (e) {
                    if (e.which === 40) {
                        that.$selectableUl.focus();
                        return false;
                    }
                });

            that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
                .on('keydown', function (e) {
                    if (e.which == 40) {
                        that.$selectionUl.focus();
                        return false;
                    }
                });
        },
        afterSelect: function () {
            this.qs1.cache();
            this.qs2.cache();
        },
        afterDeselect: function () {
            this.qs1.cache();
            this.qs2.cache();
        }
    });
})

function saveButton() {
    var inputData = $('#optgroup').find('option:selected').map(function () {
        return $(this).val()
    }).get()
    var distributorId = $('#distributor_id').find('option:selected').val();
    $.ajax({
        url: 'distributorProduct/store',
        type: 'POST',
        dataType: "json",
        data: { arrData: inputData, distributor_id: distributorId },
        success: function (response) {
            if (response.status == 'ok') {
                $('option', $('#optgroup')).each(function (element) {
                    $(this).removeAttr('selected').prop('selected', false);
                });
                $("#optgroup").multiSelect('refresh');
                $('#table-distribtor-products').dataTable().api().ajax.reload();
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
    $('.category_name').selectpicker('refresh');
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
        trObj.find('#product_name').val('');
        trObj.find("#product_name").tagsinput('removeAll');
    }
}


function actionDelete(obj, ID) {
    var trObj = $(obj).closest("tr");
    $.ajax({
        url: 'distributorProduct/destroy',
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