$(document).ready(function () {
    loadEvent();
    vehicule_attente(1);
    loadIntervEnCours(1);
    loadTermines(1);
    generateDateBO();
    // $('[data-toggle="tooltip"]').tooltip();
    /*setInterval(() => {
        vehicule_attente(1);
        loadIntervEnCours(1);
        loadTermines(1);
    }, 3000);*/
    
});

function loadEvent(){
    $("#searchImmat").off('keyup');

    $("#searchImmat").on('keyup', loadVehiculeEnAttente);
   

}


let loadVehiculeEnAttente = function () {
    vehicule_attente(1);
}
// Gestion des tableaux Backoffice DEBUT
let count = 0;

function vehicule_attente(page) {
    $.ajax({
        url: "../src/Controller/displayHTML/tablesTechDisplayController.php",
        dataType: "JSON",
        type: "POST",
        data: {
            request: "vehiculeAttente",
            page: page,
            immat: $('#searchImmat').val(),
            currentDate: $('.currentDate ').attr('id'),
        },
        success: function (response) {
            $("#noAttente").html();
            if (response["status"] === 1) {
                $("#vehiculeAttente").html(response["msg"]);
                $("#pagesHold").html(response["paginationHold"]);
                $("#pageH" + page).addClass("active");
                $("#noAttente").empty();
                // loadEvent();
            } else if (response["status"] === 2) {
                toastMixin.fire({
                    animation: true,
                    icon: "error",
                    width: 500,
                    title: response['msg']
                });
                setTimeout(() => {
                    window.location.replace('/')
                }, 3000);
            } else {
                $("#noAttente").html(response["msg2"]);
                $("#pagesHold").html("");
                $("#vehiculeAttente").html("");
              
            }
        },
        error: function () {
            console.log("errorCarHold")
        },
    });
}
function nextDayRdv(page) {
    var immatriculation = "";
    $('#searchImmat').val("");
    $.ajax({
        url: "../src/Controller/dashboardBackoffice/backofficeController.php",
        dataType: "JSON",
        type: "POST",
        data: {
            request: "next_day_rdv",
            page: page,
            nextDate: $('.nextDate').attr('id'),
            immat: immatriculation,
            
        },
        success: function (response) {
            if (response["status"] === 1) {
                $("#vehiculeAttente").html(response["msg"]);
                $("#pagesHold").html(response["paginationHoldNext"]);
                $("#pageH" + page).addClass("active");
                $("#noAttente").empty();
                // loadEvent();
            } else {
                $("#noAttente").html(response["msg2"]);
                $("#pagesHold").html("");
                $("#vehiculeAttente").html("");
            }
        },
        
    });
}
function previousDayRdv(page) {
    var immatriculation = "";
    $('#searchImmat').val("");
    $.ajax({
        url: "../src/Controller/dashboardBackoffice/backofficeController.php",
        dataType: "JSON",
        type: "POST",
        data: {
            request: "previous_day_rdv",
            page: page,
            previousDate: $('.previousDate').attr('id'),
            immat: immatriculation,
        },
        success: function (response) {
            if (response["status"] === 1) {
                $("#vehiculeAttente").html(response["msg"]);
                $("#pagesHold").html(response["paginationHoldPrevious"]);
                $("#pageH" + page).addClass("active");
                $("#noAttente").empty();
                loadEvent();
                
            } else {
                $("#noAttente").html(response["msg2"]);
                $("#pagesHold").html(response["paginationHoldPrevious"]);
                $("#vehiculeAttente").html("");
            }
        },
    });
}
function loadIntervEnCours(page) {
    $.ajax({
        url: "../src/Controller/displayHTML/tablesTechDisplayController.php",
        dataType: "JSON",
        type: "POST",
        data: {
            request: "interv_en_cours",
            page: page,
            currentDate: $('.currentDate ').attr('id'),
        },
        success: function (response) {
            if (response["status"] === 1) {
                $("#noInterv").html();
                $("#interventionTab").html(response["msg"]);
                $("#pagesInProgress").html(response["paginationInProgress"]);
                $("#pageP" + page).addClass("active");
              
            } else {
                $("#noInterv").html(response["msg2"]);
                $("#interventionTab").html("");
                $("#pagesInProgress").html(response["paginationInProgress"]);
            }
        },
        error: function () {
            console.log("PHP");
        },
    });
}

function loadTermines(page) {
    $.ajax({
        url: "../src/Controller/displayHTML/tablesTechDisplayController.php",
        dataType: "JSON",
        type: "POST",
        data: {
            request: "load_termines",
            page: page,
            count: count,
            currentDate: $('.currentDate ').attr('id'),
        },
        success: function (response) {
            if (response["status"] === 1) {
                $("#noHistory").html("");
                $("#vehiculesTermines").html(response["msg"]);
                $("#pagesOver").html(response["paginationOver"]);
                $("#pageO" + page).addClass("active");
              
            } else {
                $("#noHistory").html(response["html_vide"]);
                $("#vehiculesTermines").html("");
                $("#pagesOver").html("");
            }
        },
        error: function () {
            console.log("PHP");
        },
    });
}
// Gestion des tableaux Backoffice FIN

// Gestion des dates DEBUT
let generateDateBO = function (timestampID = null) {
    $.ajax({
        url: "../src/Controller/dashboardBackoffice/backofficeController.php",
        dataType: "JSON",
        type: "POST",
        data: {
            request: "generate_date_BO",
            currentDate: timestampID,
        },
        success: function (response) {
            $("#dateDuJour").html(response["html_day"]['daycase2']);
            $(".btnBack").html(response["html_day"]['btnBack']);
            $(".btnNext").html(response["html_day"]['btnNext']);
            $(".btnPrevious").html(response["html_day"]['btnPrevious']);
        },
        error: function () {
            console.log("errordayCases");
        },
    });
};

function pageRefresh(page) {
    $.ajax({
        url: "../src/Controller/dashboardBackoffice/backofficeController.php",
        dataType: "JSON",
        type: "POST",
        data: {
            request: "pageRefresh",
            page: page,
            currentDate: $('.currentDate').attr('id'),
        },
        success: function (response) {
            $("#vehiculeAttente").html(response["msg"]);
            $("#pagesHold").html(response["paginationHoldRefresh"]);
            $("#pageH" + page).addClass("active");
         
        },
    });
}
// Gestion des dates FIN
