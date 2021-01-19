"use strict"

$(window).on("load", function () {
    //MASK
    $('.mask_date').inputmask('dd/mm/yyyy', { placeholder: '__/__/____' });
    // $('.date_max_today').datepicker({
    //     endDate: "today",
    //     format: 'dd/mm/yyyy',
    //     // daysOfWeekDisabled: [0, 7],
    //     autoclose: true,
    // });

    // $('.btn-forget').on('click', function (e) {
    //     e.preventDefault();
    //     $('.form-items', '.form-content').addClass('hide-it');
    //     $('.form-sent', '.form-content').addClass('show-it');
    // });
    // $('.btn-tab-next').on('click', function (e) {
    //     e.preventDefault();
    //     $('.nav-tabs .nav-item > .active').parent().next('li').find('a').trigger('click');
    // });
    $('#optgroup').multiSelect({ selectableOptgroup: true });
});

$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
    }
});

// $(function () {
//     $('.js-basic-example').DataTable({
//         responsive: true
//     });
// });

$(".toggle-password").click(function () {
    $(this).toggleClass("fa-eye-slash fa-eye");
    var input = $("#password-field");
    if (input.attr("type") == "password") {
        input.attr("type", "text");
    } else {
        input.attr("type", "password");
    }
});

function showlargeloader() {
    $("#overlay").css('display', 'block');
    $("#popup").css('display', 'block');
    $("#popup").fadeIn(500);
}

function hidelargeloader() {
    $("#overlay").fadeOut(500);
    $("#popup").fadeOut(500);
}

function addPeriod(nStr) {
    nStr += "";
    let x = nStr.split(".");
    let x1 = x[0];
    let x2 = x.length > 1 ? "." + x[1] : "";
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, "$1" + "." + "$2");
    }
    return x1 + x2;
}

function price(obj) {
    if (!$(obj).val()) {
        var n = ""
    } else {
        var n = parseInt(
            $(obj)
                .val()
                .replace(/[^,\d]/g, ""),
            10
        )
    }
    $(obj).val(n.toLocaleString("id"))
    // var number_string = $(obj).val().replace(/[^,\d]/g, "").toString(),
    //     split = number_string.split(","),
    //     sisa = split[0].length % 3,
    //     rupiah = split[0].substr(0, sisa),
    //     ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    // // tambahkan titik jika yang di input sudah menjadi angka ribuan
    // if (ribuan) {
    //     var separator = sisa ? "." : "";
    //     rupiah += separator + ribuan.join(".");
    // }

    // rupiah = split[1] != undefined ? rupiah + "," + split[1] : rupiah;
    // $(obj).val(rupiah);
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
                .replace(/[^,\d]/g, "")
                .replace(".", "")
        )
    }
}