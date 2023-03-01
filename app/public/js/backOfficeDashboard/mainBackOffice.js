$(function () {
    load();
    $('[data-toggle="tooltip"]').tooltip();
    $('.switchLogo').on("click", switchLogo);
});
//
//
//
function switchLogo(){
    if ($('#logo').attr('src') === "../public/assets/img/hamsterauto-unscreen.gif"){
        $('#logo').attr('src',"../public/assets/img/hamsterautoNuit-unscreen.gif") 
    }else if ($('#logo').attr('src') === "../public/assets/img/hamsterautoNuit-unscreen.gif"){
        $('#logo').attr('src', "../public/assets/img/hamsterauto-unscreen.gif")
    }
}
function load() {
    sweetToast();
    generateNavbar();
    $('[data-toggle="tooltip"]').tooltip();
}

function priseEnCharge(id) {
    Swal.fire({
        className: "swalWarning",
        title: "Voulez-vous prendre en charge cette intervention?",
        text: "",
        imageUrl: '/public/assets/img/swalicons/interro.png',
        imageWidth: 100,
        showCancelButton: true,
        cancelButtonText: "Annuler",
        confirmButtonColor: "#62C462",
        cancelButtonColor: "#d33",
        confirmButtonText: "Oui",
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "/src/Controller/DashboardBackoffice/BackofficeController.php",
                dataType: "JSON",
                type: "POST",
                data: {
                    request: "prise_en_charge",
                    idControle: id,
                },
                success: function (response) {
                    basculerIntervention(id, response['num_tech']);
                    loadAwaiting(1);
                    loadInProgress(1);
                },
                error: function () {
                    console.log("PHP");
                },
            });
        }
    });
}

//
//

//
function basculerIntervention(id, id_tech) {
   
    $.ajax({
        url: "/src/Controller/DashboardBackoffice/BackofficeController.php",
        dataType: "JSON",
        type: "POST",
        data: {
            request: "basculer_intervention",
            idControle: id,
            idTech: id_tech,
        },
        success: function (response) {
            if (response) {
                $("#interventionTab").html(response["msg"]);
                toastMixin.fire({
                    animation: true,
                    title: response["msg"]
                });
            } else {
                alert("ErreurDDDDDD");
            }
            loadAwaiting(1);
            loadInProgress(1);
        },
        error: function () {
            console.log("PHP");
        },
    });
}

//
//
//
function switchToHold(id) {
    Swal.fire({
        className: "swalWarning",
        title: "Voulez-vous remettre ce véhicule en liste d'attente?",
        text: "",
        imageUrl: '/public/assets/img/swalicons/interro.png',
        imageWidth: 100,
        showCancelButton: true,
        cancelButtonText: "Annuler",
        confirmButtonColor: "#62C462",
        cancelButtonColor: "#d33",
        confirmButtonText: "Oui",
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "/src/Controller/DashboardBackoffice/BackofficeController.php",
                dataType: "JSON",
                type: "POST",
                data: {
                    request: "switch_toHold",
                    idRdv: id,
                },
                success: function (response) {
                    toastMixin.fire({
                        animation: true,
                        title: response["msg"]
                    });
                    loadAwaiting(1);
                    loadInProgress(1);
                },
                error: function () {
                    console.log("PHP");
                },
            });
        }
    });
}

//
//
//

function deleteRdv(rdvId) {
    Swal.fire({
        title: "Confirmez vous la suppression de cette intervention?",
        text: "",
        imageUrl: '/public/assets/img/swalicons/warning.png',
        imageWidth: 100,
        showCancelButton: true,
        cancelButtonText: "Annuler",
        confirmButtonColor: "#62C462",
        cancelButtonColor: "#d33",
        confirmButtonText: "Oui",
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "/src/Controller/RdvController.php",
                dataType: "JSON",
                type: "POST",
                data: {
                    request: "deleteRdv",
                    idRdv: rdvId,
                },
                success: function (response) {
                    let currentPage = $('#vehiculesTermines').find('.active').children().html();
                    if (currentPage == undefined){
                        currentPage = 1;
                    }
                    toastMixin.fire({
                        animation: true,
                        title: 'Cette intervention a été annulée',
                    });
                    loadAwaiting(1);
                    loadArchives(currentPage);
                    generateDateBO();
                },
                error: function () {
                    console.log("PHP");
                },
            });
        }
    });
}

let showInfo = function (id) {
    $.ajax({
        url: "/src/Controller/CarController.php",
        dataType: "JSON",
        type: "POST",
        data: {
            request: "showInfo",
            rdvID: id,
        },
        success: function (response) {
            $("#modalRDV").modal("show");
            $("#modal-rdvID").html(response["rdvID"]);
            $("#modal-timeslotID").html(response["timeslotID"]);
            $("#modal-booked_date").html(response["booked_date"]);
            $("#modal-nom_user").html(response["lastname_user"]);
            $("#modal-prenom_user").html(response["firstname_user"]);
            $("#modal-tel_user").html(response["phone_user"]);
            $("#modal-mail_user").html(response["mail_user"]);
            if (!response["CG"]) {
                $("#modal-CG").html("Aucune carte grise n'est associée à ce véhicule.");
            } else {
                $("#modal-CG").html(response["CG"]);
            }
        },
        error: function () {
            console.log("errorShow");
        },
    });
};



//
//
//
