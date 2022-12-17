// Affichage des dates et créneaux dans les formulaires DEBUT
let generateDate = function (timestampID = null) {
    $.ajax({
        url: "../src/Controller/displayHTML/datesDisplayController.php",
        dataType: "JSON",
        type: "POST",
        data: {
            request: "generate_date",
            currentDate: timestampID,
        },
        success: function (response) {
            $("#rdvContainer").html(response["html_day"]);
            $("#panel").html(response["html_slot"]);
            $('#datepicker').datepicker({
                /*dateFormat: "DD dd MM yy",*/
                /*dateFormat: "yy/mm/dd",*/
                dayNamesMin: ["Di", "Lu", "Ma", "Me", "Je", "Ve", "Sa"],
                dayNames: ["Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi"],
                monthNames: ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"],
                minDate: 0,
                beforeShowDay: function (date) {
                    let day = date.getDay();
                    return [(day !== 0), ''];
                },
                onSelect: function (date) {
                    function toTimestamp(strDate) {
                        let datum = Date.parse(strDate);
                        return datum / 1000;
                    }

                    /*alert(toTimestamp(date));*/
                    changeDate(toTimestamp(date))
                },
            });
        },
        error: function () {
            console.log("errordayCases");
        },
    });
};
function changeDate(timestamp) {
    generateDate(timestamp);
}
// Affichage des dates et créneaux dans les formulaires FIN
// 
// 
// Affichage des marques et modèles dans les formulaires DEBUT
let marquesLoad = function () {
    $.ajax({
        url: "../src/Controller/displayHTML/brandsDisplayController.php",
        dataType: "JSON",
        type: "POST",
        data: {
            request: "marquesLoad",
        },
        success: function (response) {
            $("#selectMarque").html(response["html_marque"]);
        },
        error: function () {
            console.log("error");
        },
    });
};

let modelesLoad = function () {
    $.ajax({
        url: "../src/Controller/displayHTML/brandsDisplayController.php",
        dataType: "JSON",
        type: "POST",
        data: {
            request: "modelesLoad",
            marque: $("#selectMarque").val(),
        },
        success: function (response) {
            $("#selectModele").html(response["html_model"]);
        },
        error: function () {
            console.log("error");
        },
    });
};
// Affichage des marques et modèles dans les formulaires FIN


// Choix du format immatriculation DEBUT
let selectedImmatFormat = function () {
    let inputPlateNew = $('#inputImmatNew');
    let inputPlateOld = $('#inputImmatOld')
    let format = $('input[name=radioImmat]:checked').val();
    oldvalue = "";
    if (format === "newImmat") {
        $('#licence-plate-old').prop("hidden", true);
        $('#licence-plate-new').prop("hidden", false);
        inputPlateNew.removeClass('is-valid');
    } else {
        $('#licence-plate-old').prop("hidden", false);
        $('#licence-plate-new').prop("hidden", true);
        inputPlateOld.removeClass('is-valid')
    }
}
// Choix du format immatriculation FIN


// Choix Pays télephone DEBUT
let IntlTelInput = function () {
    $('#inputTel').intlTelInput({
        preferredCountries: ["fr", "gb"],
        utilsScript: "../vendor/jackocnr/intl-tel-input/build/js/utils.js",
        initialCountry: "fr",
        geoIpLookup: function (success, failure) {
            $.get("https://ipinfo.io", function () {
            }, "jsonp").always(function (resp) {
                const countryCode = (resp && resp.country) ? resp.country : "fr";
                success(countryCode);
            });
        },
    });
}
// Choix Pays télephone FIN