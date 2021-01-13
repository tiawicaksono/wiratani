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