$(document).ready(function () {
    // console.log('customize connect');
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    uploadNM();
    customDateNM();
    $(".date-picker").flatpickr({
        dateFormat: 'd/m/Y',
        enableTime: false,
    });

});

// $(document).ready(function() {
//     $('input.specialCode').each(function() {
//         var $input = $(this);
//         var value = $input.val();
//         $(this).val(encryptData(value)); // Encrypt the value
//         $input.hide();
//         $('<span>')
//             .addClass('specialCode-span')
//             .text(value)
//             .insertAfter($input);
//         console.log(encryptData(value))
//     });

//     // For dynamically opened modals, you may want to handle future elements:
//     $(document).on('shown.bs.modal', function() {
//         $('input.specialCode').each(function() {
//             var $input = $(this);
//             if (!$input.next().hasClass('specialCode-span')) {
//                 var value = $input.val();
//                 $(this).val(encryptData(value)); // Encrypt the value
//                 $input.hide();
//                 $('<span>')
//                     .addClass('specialCode-span')
//                     .text(value)
//                     .insertAfter($input);
//             }
//         });
//     });
// });

function encryptData(text) {
    var passphrase = "N3211M1305"; // must match PHP key

    var encrypted = CryptoJS.AES.encrypt(text, passphrase).toString();

    return encrypted;
}

$(".time-picker").flatpickr({
    noCalendar: true,
    dateFormat: "H:i",
    enableTime: true,
    time_24hr: true,
});

$(".datetime-picker").flatpickr({
    dateFormat: 'dd/mm/yyyy hh:ii',
    enableTime: true,
    time_24hr: true,
});

$("body").delegate(".yearPicker", "focusin", function () {
    $(this).datepicker({
        format: "yyyy",
        viewMode: "years",
        minViewMode: "years",
        autoclose: true, //to close picker once year is selected
    });
});

$('.readonly').each(function () {
    if ($(this).hasClass("date-picker") || $(this).hasClass("month-picker") || $(this).hasClass("year-picker") || $(this).hasClass("date-picker-ymd") || $(this).hasClass("date-picker-dmy")) {
        $(this).datepicker('remove');
        $(this).attr('readonly', 'readonly');
    } else if ($(this).hasClass("select2") || $(this).hasClass("global-select2")) {
        $(this).select2({ readonly: true });
    } else {
        $(this).attr('readonly', 'readonly');
    }
});

function dmy_to_ymd(date) {
    var d = new Date(date.split("/").reverse().join("-"));
    var dd = d.getDate();
    var mm = d.getMonth() + 1;
    var yy = d.getFullYear();
    var newdate = yy + "-" + mm + "-" + dd;
    return newdate;
}

function convertdate(date, newformat) {
    var date = new Date(date);
    var newDate = date.toString(newformat);
    return newDate;
}

function get_month(monthNumber) {
    var months = [
        'January',
        'February',
        'March',
        'April',
        'May',
        'June',
        'July',
        'August',
        'September',
        'October',
        'November',
        'December'
    ];
    return months[monthNumber - 1];
}

function number_format(x, decimals, decPoint, thousandsSep) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    // decimals = Math.abs(decimals) || 0;
    // number = parseFloat(number);

    // if (!decPoint || !thousandsSep) {
    //     decPoint = '.';
    //     thousandsSep = ',';
    // }

    // var roundedNumber = Math.round(Math.abs(number) * ('1e' + decimals)) + '';
    // var numbersString = decimals ? (roundedNumber.slice(0, decimals * -1) || 0) : roundedNumber;
    // var decimalsString = decimals ? roundedNumber.slice(decimals * -1) : '';
    // var formattedNumber = "";

    // while (numbersString.length > 3) {
    //     formattedNumber += thousandsSep + numbersString.slice(-3)
    //     numbersString = numbersString.slice(0, -3);
    // }

    // if (decimals && decimalsString.length === 1) {
    //     while (decimalsString.length < decimals) {
    //         decimalsString = decimalsString + decimalsString;
    //     }
    // }

    // return (number < 0 ? '-' : '') + numbersString + formattedNumber + (decimalsString ? (decPoint + decimalsString) : '');
}

function historyBackNM() {
    if (document.referrer == "") {
        window.location.replace(base_url);
    } else {
        history.back()
    }
}


function imageToDataUri(img, width, height, ext) {
    // create an off-screen canvas
    format = ext;
    var canvas = document.createElement("canvas"),
        ctx = canvas.getContext("2d");

    // set its dimension to target size
    canvas.width = width;
    canvas.height =
        height == "auto" ? width * (img.height / img.width) : height;
    // console.log((height == 'auto' ? width * (img.height / img.width) : height));
    // draw source image into the off-screen canvas:
    if (ext != "svg") {
        ctx.drawImage(
            img,
            0,
            0,
            width,
            height == "auto" ? width * (img.height / img.width) : height
        );
        // encode image to data-uri with base64 version of compressed image
        return canvas.toDataURL();
    } else {
        return img.src;
    }
    // console.log(img.src);
}

// function setLabel($label, text) {
//     $label.html(text);
// }

// function showDocumentTooltip($target, name) {

//     $target.tooltip("dispose");

//     $target.attr("title", `<u><b>${name}</b></u>`);

//     $target.tooltip({
//         html: true,
//         trigger: "hover",
//         container: "body"
//     });

// }

// function showDocumentTooltip($target, name) {

//     $target.tooltip("dispose");

//     $target.attr("title", `<u><b>${name}</b></u>`);

//     $target.tooltip({
//         html: true,
//         trigger: "hover",
//         container: "body"
//     });

// }

// function showImageTooltip($target, image) {

//     $target.tooltip("dispose");

//     $target.attr("title", image);

//     $target.tooltip({
//         html: true,
//         trigger: "hover",
//         container: "body"
//     });

// }

// function clearTooltip($target){

//     $target.tooltip("dispose");

//     $target
//         .removeAttr("title")
//         .removeAttr("data-original-title")
//         .removeAttr("data-bs-title");

// }


function replaceAll(str, find, replace) {
    return str.replace(new RegExp(find, "g"), replace);
}

$("body").on("keypress", ".numberValueonly", function (evt) {
    var charCode = evt.which ? evt.which : event.keyCode;
    thisval = $(this).val();
    if (thisval == "0.00") {
        $(this).val('');
    }
    if (charCode == 46 && thisval.indexOf(".") < 0 && thisval != "") {
        return true;
    } else if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }

    return true;
});

$("body").on("keyup", ".numberValueonly", function (evt) {
    thisval = $(this).val();
    realvalue = thisval.replace(/,/g, "");
    valuewithcomas = addCommas(realvalue);
    $(this).val(valuewithcomas);
});

function addCommas(nStr) {
    nStr += "";
    x = nStr.split(".");
    x1 = x[0];
    x2 = x.length > 1 ? "." + x[1] : "";
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, "$1" + "," + "$2");
    }
    return x1 + x2;
}

$("body").on("keypress", ".numberonly", function (evt) {
    var charCode = evt.which ? evt.which : event.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }

    return true;
});

$("body").on("change", ".numberonly", function (evt) {
    if ($.isNumeric($(this).val()) == false) {
        $(this).val("");
        // $(this).removeAttr('value');
    }
});

function customDateNM() {
    $(".custom-flat-picker").each(function (key, val) {
        var id = $(this).attr("id");
        var type = typeof $(this).attr("cfp-type") !== "undefined"
            ? $(this).attr("cfp-type") != ""
                ? $(this).attr("cfp-type")
                : 'date'
            : 'date';
        var minDate = typeof $(this).attr("cfp-minDate") !== "undefined"
            ? $(this).attr("cfp-minDate") != ""
                ? $(this).attr("cfp-minDate")
                : false
            : false;
        var defaultDate = typeof $(this).attr("cfp-default") !== "undefined"
            ? $(this).attr("cfp-default") != ""
                ? $(this).attr("cfp-default")
                : null
            : false;
        var useTime = typeof $(this).attr("cfp-showTime") !== "undefined"
            ? $(this).attr("cfp-showTime") != ""
                ? true
                : false
            : false;

        // var wrapp =
        //     typeof $(this).attr("cfp-wrap") !== "undefined"
        //         ? $(this).attr("cfp-wrap") != ""
        //             ? true
        //             : false
        //         : false;

        var dateFormat = 'd/m/Y';
        if (type == 'date') {
            dateFormat = 'd/m/Y';
        };



        window['date' + id] = $("#" + id).flatpickr({
            dateFormat: dateFormat,
            enableTime: useTime,
            minDate: minDate,
            defaultDate: defaultDate,
            // wrap: wrapp,
            onChange: function (selectedDates, dateStr) {
                var autoSetTarget = $('#' + id).attr("cfp-autosettarget") || false;
                var autoSetValue = $('#' + id).attr("cfp-autosetvalue") || false;
                const $target = $(autoSetTarget);
                if ($target.length === 0) {
                    return;
                }

                let valueToSet = dateStr;

                // Custom value logic
                if (autoSetValue === 'day1NextYear' && selectedDates.length) {
                    const nextYear = selectedDates[0].getFullYear() + 1;
                    const jan1 = new Date(nextYear, 0, 1); // Jan 1 next year
                    valueToSet = jan1;
                }

                // Set value on target
                const fp = $target[0]?._flatpickr;

                if (fp) {
                    fp.setDate(valueToSet, true); // true = trigger onChange
                    // Ensure the input shows the correct value (especially if readonly)
                    const formatted = fp.formatDate(fp.selectedDates[0], fp.config.dateFormat);
                    $target.val(formatted);
                } else {
                    // If not flatpickr-enabled, set manually
                    const formatted = valueToSet instanceof Date
                        ? valueToSet.toLocaleDateString('en-GB') // or custom format
                        : valueToSet;
                    $target.val(formatted);
                }

                $(autoSetTarget).attr('cfp-default', formatted);
            },
            onOpen: function (selectedDates, dateStr, instance) {
                const $input = $("#" + id);
                const isReadOnly = $input.attr("close") !== undefined && $input.attr("close") !== "";

                if (isReadOnly) {
                    instance.close(); // Immediately close the calendar to prevent opening
                    return;
                }

                var newminDate = typeof $('#' + id).attr("cfp-minDate") !== "undefined"
                    ? $('#' + id).attr("cfp-minDate") != ""
                        ? $('#' + id).attr("cfp-minDate")
                        : false
                    : false;
                eval('date' + id).set('minDate', newminDate);

            }
        });
    });
}

function valueParseFloat(values) {
    valuee = values.toString().replace(/,/g, "");

    return parseFloat(valuee);
}
//endform ajax

$(document).ready(function () {
    $(document).on("paste", ".numberValueonly", function (e) {
        e.preventDefault();
    });
    $(document).on("focus focusout load", ".numberValueonly", function (e) {
        var currentValue = $(this).val();
        if (currentValue == "") {
            $(this).val("0.00");
        }
    });

    var nVToastr = toastr;
    var numberValueToastr = {
        closeButton: true,
        debug: false,
        newestOnTop: false,
        progressBar: false,
        positionClass: "toast-top-right",
        preventDuplicates: true,
        onclick: null,
        showDuration: "0",
        hideDuration: "0",
        timeOut: "0",
        extendedTimeOut: "0",
        showEasing: "swing",
        hideEasing: "linear",
        showMethod: "fadeIn",
        hideMethod: "fadeOut",
    };
    $(document).on("focus keypress", ".numberValueonly", function (e) {
        thethis = $(this);
        setTimeout(function () {
            showNumberValueInfo(thethis)
        }, 1000);
    });
    $(document).on("keypress", ".numberValueonly", function (e) {
        thethis = $(this);
        showNumberValueInfo(thethis);
    });

    $(document).on("focusout", ".numberValueonly", function (e) {
        nVToastr.clear();
    });
    function showNumberValueInfo(thethis) {
        var inputRules = thethis.attr("inputRules");
        var placeHolder = thethis.attr("placeholder");
        var text =
            "1. `paste` feature is disabled. <br/>2. only numbers and dot (.) are accepted. ";
        var titleText = "";
        if (typeof placeHolder !== "undefined") {
            titleText = placeHolder;
        }
        if (typeof inputRules !== "undefined") {
            text = inputRules;
        }
        nVToastr.warning(
            text,
            (titleText != "" ? "`" + titleText + "` " : "") + "Input Rules",
            numberValueToastr
        );
    }
});