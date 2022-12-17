$(function () {
    $("#inputImmatNew").on("keyup", checkNewValueRegEx)
    $("#inputImmatOld").on("keyup", checkOldValueRegEx)
    $("#inputAnnee").on("keyup", checkOldValueYear)
});
let oldvalue = "";

/*REGEX IMMAT DEBUT*/
function checkNew(s) {
    let toks = s.split('-');
    switch (toks.length) {
        case 3:
            if (!/^[A-Za-z]{0,2}$/.test(toks[2].trim())) return false;
        case 2:
            if (!/^\d{0,3}$/.test(toks[1].trim())) return false;
        case 1:
            return /^[A-Za-z]{0,2}$/.test(toks[0].trim());
        default:
            return false;
    }
}

let checkNewValueRegEx = function () {
    if (!checkNew(this.value)) {
        this.value = oldvalue;
    } else {
        oldvalue = this.value = this.value.toUpperCase();
    }
}

function checkOld(s) {
    let toks = s.split('-');
    switch (toks.length) {
        case 3:
            if (!/^\d?[A-Za-z0-9]{0,2}$/.test(toks[2].trim())) return false;
            /*if (!/^\d{0,3}$/.test(toks[2].trim())) return false;*/
        case 2:
            if (!/^[A-Za-z]{0,3}$/.test(toks[1].trim())) return false;
        case 1:
            return /^\d{0,4}$/.test(toks[0].trim());
        default:
            return false;
    }
}

let checkOldValueRegEx = function () {
    if (!checkOld(this.value)) {
        this.value = oldvalue;
    } else {
        oldvalue = this.value = this.value.toUpperCase();
    }
}

/*REGEX IMMAT FIN*/

function checkYear(s) {
    let toks = s;
    switch (toks.length) {
        case 4:
            if (!/^\d{0,4}$/.test(toks[1].trim())) return false;
        case 3:
            if (!/^\d{0,4}$/.test(toks[1].trim())) return false;
        case 2:
            if (!/^\d{0,2}$/.test(toks[1].trim())) return false;
        case 1:
            return /^[1-2]{0,2}$/.test(toks[0].trim());
        default:
            return false;
    }
}

let checkOldValueYear = function () {
    if (!checkYear(this.value)) {
        this.value = oldvalue;
    } else {
        oldvalue = this.value
    }
}

let checkField = function () {

    let $field = this.id;
    let $fieldVal = $("#" + $field).val();

    $.ajax({

        url: '../src/Controller/checkFieldController.php',
        dataType: 'JSON',
        type: 'POST',
        data: {
            request: 'checkField',
            field: $field,
            fieldVal: $fieldVal
        },
        success: function (response) {
            let field = $('#' + $field);
            if (response['status'] === 1) {
                if (field.hasClass('is-invalid')) {
                    field.removeClass('is-invalid')
                }
                if (field.attr('id') !== 'inputImmatNew' && field.attr('id') !== 'inputImmatOld') {
                    field.addClass('is-valid');
                }
            } else {
                if (field.attr('id') !== 'inputImmatNew' && field.attr('id') !== 'inputImmatOld') {
                    field.addClass('is-invalid')
                    field.next().html(response['msg']);
                }
            }
        },
        error: function () {
        }
    });
}