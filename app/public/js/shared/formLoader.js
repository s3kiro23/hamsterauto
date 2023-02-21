// Affichage des dates et créneaux dans les formulaires DEBUT
function JoursFeries(an) {
    let JourAn = new Date(an, "00", "01").toLocaleDateString("en")
    let FeteTravail = new Date(an, "04", "01").toLocaleDateString("en")
    let Victoire1945 = new Date(an, "04", "08").toLocaleDateString("en")
    let FeteNationale = new Date(an, "06", "14").toLocaleDateString("en")
    let Assomption = new Date(an, "07", "15").toLocaleDateString("en")
    let Toussaint = new Date(an, "10", "01").toLocaleDateString("en")
    let Armistice = new Date(an, "10", "11").toLocaleDateString("en")
    let Noel = new Date(an, "11", "25").toLocaleDateString("en")
    let SaintEtienne = new Date(an, "11", "26").toLocaleDateString("en")

    let G = an % 19
    let C = Math.floor(an / 100)
    let H = (C - Math.floor(C / 4) - Math.floor((8 * C + 13) / 25) + 19 * G + 15) % 30
    let I = H - Math.floor(H / 28) * (1 - Math.floor(H / 28) * Math.floor(29 / (H + 1)) * Math.floor((21 - G) / 11))
    let J = (an * 1 + Math.floor(an / 4) + I + 2 - C + Math.floor(C / 4)) % 7
    let L = I - J
    let MoisPaques = 3 + Math.floor((L + 40) / 44)
    let JourPaques = L + 28 - 31 * Math.floor(MoisPaques / 4)
    let Paques = new Date(an, MoisPaques - 1, JourPaques).toLocaleDateString("en")
    let VendrediSaint = new Date(an, MoisPaques - 1, JourPaques - 2).toLocaleDateString("en")
    let LundiPaques = new Date(an, MoisPaques - 1, JourPaques + 1).toLocaleDateString("en")
    let Ascension = new Date(an, MoisPaques - 1, JourPaques + 39).toLocaleDateString("en")
    let Pentecote = new Date(an, MoisPaques - 1, JourPaques + 49).toLocaleDateString("en")
    let LundiPentecote = new Date(an, MoisPaques - 1, JourPaques + 50).toLocaleDateString("en")

    return "'" +
        JourAn + "," +
        FeteTravail + "," +
        Victoire1945 + "," +
        FeteNationale + "," +
        FeteNationale + "," +
        Assomption + "," +
        Toussaint + "," +
        Armistice + "," +
        Noel + "," +
        SaintEtienne + "," +
        Paques + "," +
        LundiPaques + "," +
        VendrediSaint + "," +
        Ascension + "," +
        Pentecote + "," +
        LundiPentecote
}

function getDaysInYear(year, day) {
    let d = new Date(year, 0), dates = "";
    d.setDate(d.getDate() + (7 + day - d.getDay()) % 7);
    while (d.getFullYear() === year) {
        dates = dates + new Date(d).toLocaleDateString("en") + ",";
        d.setDate(d.getDate() + 7);
    }

    return dates.replace(/.$/,"'");
}

let generateDate = function (timestampID = null) {
    $.ajax({
        url: "/src/Controller/DisplayHTML/DatesDisplayController.php",
        dataType: "JSON",
        type: "POST",
        data: {
            request: "generate_date",
            currentDate: timestampID,
        },
        success: function (response) {
            $("#rdvContainer").html(response["html_day"]);
            $("#panel").html(response["html_slot"]);
            let jourFeries = JoursFeries(response["currentYear"]);
            let allSunday = getDaysInYear(parseInt(response["currentYear"]), 7);

            $('#date-input').dateDropper({
                format: 'l d F Y',
                lang: 'fr',
                theme: 'date-dropper',
                largeOnly: true,
                modal: true,
                minYear: 2023,
                maxYear: 2080,
                lock: 'from',
                disabledDays: jourFeries + allSunday,
                defaultDate: response["dateDropperDateFormat"],
                eventSelector: 'click',
                onChange: function (res) {
                    // console.log(res)
                    // console.log(getDaysInYear(res.date.Y, 7));
                    // console.log(JoursFeries(res.date.Y));
                    changeDate(res.date.U);
                }
            });
        },
        error: function () {
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
let brandsLoad = function () {
    $.ajax({
        url: "/src/Controller/DisplayHTML/BrandsDisplayController.php",
        dataType: "JSON",
        type: "POST",
        data: {
            request: "brandsLoad",
        },
        success: function (response) {
            $("#selectMarque").html(response["html_brand"]);
        },
        error: function () {
        },
    });
};

let modelsLoad = function () {
    $.ajax({
        url: "/src/Controller/DisplayHTML/BrandsDisplayController.php",
        dataType: "JSON",
        type: "POST",
        data: {
            request: "modelsLoad",
            brand: $("#selectMarque").val(),
        },
        success: function (response) {
            $("#selectedModel").html(response["html_model"]);
        },
        error: function () {
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
        inputPlateOld.val(null);
        $('#licence-plate-old').prop("hidden", true);
        $('#licence-plate-new').prop("hidden", false);
        inputPlateNew.removeClass('is-valid');
    } else {
        inputPlateNew.val(null);
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
        utilsScript: "/vendor/jackocnr/intl-tel-input/build/js/utils.js",
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
