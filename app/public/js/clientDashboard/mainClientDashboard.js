/*Doc Ready DEBUT*/
$(function () {
    load();
    $("#file").on("change", checkType);
    $('.switchLogo').on("click", switchLogo);
    /*setInterval(() => {
        carsRecap(1);
    }, 1000);*/
});

/*Doc Ready FIN*/
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
    carsRecap(1);
    $("#formAddCar").on("click", formAddCar);
    $("#formAddRDV").on("click", formAddRDV);
    $('[data-toggle="tooltip"]').tooltip();
}

/*Affichage des véhicules/RDV/Historiques par user + pagination DEBUT*/
let carsRecap = function (page) {
    $.ajax({
        url: "../src/Controller/displayHTML/tablesClientDisplayController.php",
        dataType: "JSON",
        type: "POST",
        data: {
            request: "loadCarsRecap",
            page: page,
        },
        success: function (response) {
            if (response["html"]) {
                $("#mycars").html(response["html"]);
                $("#noCar").html("");
            } else {
                $("#mycars").html("");
                $("#noCar").html("Aucune voiture dans votre inventaire");
            }
            if (response["htmlRDV"]) {
                $("#myrdv").html(response["htmlRDV"]);
                $("#noRDV").html("");
            } else {
                $("#myrdv").html("");
                $("#noRDV").html("Aucun rendez-vous programmé");
            }
            if (response["htmlHistory"]) {
                $("#myhistory").html(response["htmlHistory"]);
                $("#history-size-start").html(response["nbrOfHistory"]["current"]);
                $("#history-size-end").html(response["nbrOfHistory"]["total"]);
                $("#noHistory").html("");
                $("#pagesMyHistory").html(response["paginationMyHistory"]);
                $("#pageMyH" + page).addClass("active");
            } else {
                $("#myhistory").html("");
                $("#noHistory").html("Aucun historique consultable");
            }
        },
        error: function () {
            console.log('errorClient')
        },
    });
}



/*Fonctions de gestion d'une fiche véhicule DEBUT*/
function formAddCar() {
    $.ajax({
        url: "../src/Controller/displayHTML/formsDisplayController.php",
        dataType: "JSON",
        type: "POST",
        data: {
            request: "formAddCar",
        },
        success: function (response) {
            $("#modalAddCar").modal("show");
            $("#bodyAddCar").html(response['html'])
            $('#selectMarque').on("change", selectedCar)
            $("#inputImmatNew").on("keyup", checkNewValueRegEx)
            $("#inputImmatOld").on("keyup", checkOldValueRegEx)
            $("#inputAnnee").on("keyup", checkOldValueYear)
            $(".form-control").on('change', checkField)
            $('input[name=radioImmat]').on("click", selectedImmatFormat);
        },
        error: function () {
            console.log("errorAddShow");
        },
    });
}
function selectedCar() {
    $.ajax({
        url: "../src/Controller/displayHTML/brandsDisplayController.php",
        dataType: "JSON",
        type: "POST",
        data: {
            request: "modelesLoad",
            marque: $('#selectMarque').val()
        },
        success: function (response) {
            $("#selectModele").html(response['html_model'])
        },
        error: function () {
            console.log("errorSelect");
        },
    });
}
let addCar = function () {
    let $fuel = "";
    let $selectedFuel = $('input[name=optionsCarbu]:checked').val();
    if ($selectedFuel) {
        $fuel = $selectedFuel;
    }
    let $immat = "";
    let selectedFormatPlate = $('input[name=radioImmat]:checked').val();
    if (selectedFormatPlate === "newImmat"){
        $immat = $('#inputImmatNew').val();
    } else {
        $immat = $('#inputImmatOld').val();
    }
    Swal.fire({
        title: 'Confirmez-vous l\'ajout de ce véhicule?',
        text: "",
        imageUrl: '../public/assets/img/swalicons/interro.png',
        imageWidth: 100,
        showCancelButton: true,
        confirmButtonColor: '#62C462',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Oui',
        cancelButtonText: 'Annuler',
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '../src/Controller/carController.php',
                dataType: 'JSON',
                type: 'POST',
                data: {
                    request: 'addCar',
                    immat: $immat,
                    marque: $('#selectMarque').val(),
                    modele: $('#selectModele').val(),
                    carburant: $fuel,
                    annee: $('#inputAnnee').val(),
                },
                success: function (response) {
                    if (response['status'] === 1) {
                        toastMixin.fire({
                            animation: true,
                            title: response["msg"]
                          });
                        $("#modalAddCar").modal("hide");
                        carsRecap(1)
                    } else {
                        toastMixin.fire({
                            animation: true,
                            title: response["msg"],
                            icon: "error",
                          });

                    }
                },
                error: function () {
                    console.log('errorsAddcAr');
                }
            })
        }
    })
};
function deleteCar(id) {
    Swal.fire({
        title: "Confirmez-vous la suppression de ce véhicule?",
        text: "",
        imageUrl: '../public/assets/img/swalicons/warning.png',
        imageWidth: 100,
        showCancelButton: true,
        cancelButtonText: "Annuler",
        confirmButtonColor: "#62C462",
        cancelButtonColor: "#d33",
        confirmButtonText: "Oui",
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "../src/Controller/carController.php",
                dataType: "JSON",
                type: "POST",
                data: {
                    request: "deleteCar",
                    carID: id,
                },
                success: function (response) {
                    toastMixin.fire({
                        animation: true,
                        title: response["msg"]
                      });
                    carsRecap(1)
                },
                error: function () {
                    console.log("CarDelErr");
                },
            });
        }
    });
}

let showInfoCar = function (id) {
    $.ajax({
        url: "../src/Controller/carController.php",
        dataType: "JSON",
        type: "POST",
        data: {
            request: "showInfoCar",
            carID: id,
        },
        success: function (response) {
            $("#modalCar").modal("show");
            $("#modal-Marque").html(response["marque"]);
            $("#modal-Modele").html(response["modele"]);
            $("#modal-Immat").html(response["immat"]);
            $("#modal-Annee").html(response["annee"]);
            $("#modal-Carburant").html(response["carburant"]);
            $("#modal-Infos").html(response["infos"]);
            if (!response["carteGrise"]) {
                $("#modal-CG").html("Aucune carte grise n'est associée à ce véhicule.");
            } else {
                $("#modal-CG").html(response["carteGrise"]);
            }
        },
        error: function () {
            console.log("errorShow");
        },
    });
};

let modalCG = function (id) {
    $.ajax({
        url: '../src/Controller/dashboardClient/clientController.php',
        dataType: "JSON",
        type: "POST",
        data: {
            request: "modalUploadCG",
            carID: id,
        },
        success: function () {
            $("#modalCG").modal("show");
            $("#modal-carID").html(id);
        },
        error: function () {
            console.log("errorShow");
        },
    });
};
/*Fonctions de gestion d'une fiche véhicule FIN*/

/*---------------------------------------------------------------------*/

/*Fonctions de gestion d'un rdv DEBUT*/
let formAddRDV = function () {
    $.ajax({
        url: "../src/Controller/displayHTML/formsDisplayController.php",
        dataType: "JSON",
        type: "POST",
        data: {
            request: "formAddRDV",
        },
        success: function (response) {
            $("#modalAddRDV").modal("show");
            $("#bodyAddRDV").html(response['html'])
            generateDate();
        },
        error: function () {
            console.log("errorFormRDV");
        },
    });
};
let addRDV = function () {
    let $slot = "";
    let $selectedSlot = $("input[name=timeSlot]:checked").attr("id");
    if ($selectedSlot) {
        $slot = $selectedSlot;
    }
    let $car = "";
    let $selectedCar = $('#selectCars').val();
    if ($selectedCar) {
        $car = $selectedCar;
    }
    Swal.fire({
        title: 'Confirmez-vous la demande de rendez-vous ?',
        text: "",
        imageUrl: '../public/assets/img/swalicons/interro.png',
        imageWidth: 100,
        showCancelButton: true,
        confirmButtonColor: '#62C462',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Oui',
        cancelButtonText: "Annuler",
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '../src/Controller/rdvController.php',
                dataType: 'JSON',
                type: 'POST',
                data: {
                    request: 'newRDVDashboardClient',
                    carID: $car,
                    creneau: $slot,
                },
                success: function (response) {
                    if (response['status'] === 1) {

                        toastMixin.fire({
                            animation: true,
                            title: response["msg"]
                          });
                        $("#modalAddRDV").modal("hide");
                        carsRecap(1)

                    } else {
                        toastMixin.fire({
                            animation: true,
                            title: response["msg"],
                            icon: "error",
                          });

                    }
                },

                error: function(jqxhr,textStatus,errorThrown)
                {
                    console.log(jqxhr);
                    console.log(textStatus);
                    console.log(errorThrown);
                }
            })
        }
    })
};
function deleteRdvUser(id) {
    Swal.fire({
        title: "Voulez-vous annuler ce rendez-vous?",
        text: "",
        imageUrl: '../public/assets/img/swalicons/warning.png',
        imageWidth: 100,
        showCancelButton: true,
        cancelButtonText: "Annuler",
        confirmButtonColor: "#62C462",
        cancelButtonColor: "#d33",
        confirmButtonText: "Oui",
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "../src/Controller/rdvController.php",
                dataType: "JSON",
                type: "POST",
                data: {
                    request: "deleteRdv",
                    idRdv: id,
                },
                success: function (response) {
                    /*Swal.fire({
                        title: 'Annulation en cours...',
                        text: 'Hamster fait au plus vite, merci pour votre patience...',
                        imageUrl: '../public/assets/img/swalicons/spinner.gif',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        imageWidth: 220,
                        imageHeight: 220,
                        showCancelButton: false,
                        showConfirmButton: false,
                        timer: 2000
                    }).then(() => {*/
                        toastMixin.fire({
                            animation: true,
                            title: response["msg"]
                          });
                 /*   })*/
                    carsRecap(1);
                },
                error: function () {
                    console.log("PHP");
                },
            });
        }
    });
}

function showContreVisite(id) {
    $.ajax({
        url: "../src/Controller/rdvController.php",
        dataType: "JSON",
        type: "POST",
        data: {
            request: "show_contre_visite",
            rdvID: id,
        },
        success: function (response) {
            $("#modalContreVisite").modal("show");
            $("#modal-contreID").html(response["rdvID"]);
            $("#rapportInter").html(response["rapport"]);
        },
        error: function () {
            console.log("errorShow");
        },
    });
}
/*Fonctions de gestion d'un RDV FIN*/

  
function sessionEndingSoon() {
    $.ajax({
        url: "../src/Controller/dashBoardClient/clientController.php",
        dataType: "JSON",
        type: "POST",
        data: {
            request: "session_ending_soon", 
        },
        success: function (response) {
           if (response['statut'] === 1){
            // clearInterval(sessionTimer)
            Swal.fire({
                title: response['msg'],
                text: "",
                imageUrl: '../public/assets/img/swalicons/warning.png',
                imageWidth: 100,
                showCancelButton: true,
                cancelButtonText: "Non",
                confirmButtonColor: "#62C462",
                cancelButtonColor: "#d33",
                confirmButtonText: "Oui",
            }).then((result) => {
                if (result.isConfirmed) {
                   sessionExtend();
                }
                else {
                    sessionEnding();
                }
            });
           }
        },
        error: function () {
            console.log("errorSession102");
        },
    });
}

function sessionExtend(){
    $.ajax({
        url: "../src/Controller/index/loginController.php",
        dataType: "JSON",
        type: "POST",
        data: {
            request: "session_extend",
        },
        success: function (response) {
            toastMixin.fire({
                animation: true,
                title: response["msg"]
              });
          
        },
        error: function () {
            console.log("sessionError101");
        },
    });
}
function sessionEnding(){
    $.ajax({
        url: "../src/Controller/index/loginController.php",
        dataType: "JSON",
        type: "POST",
        data: {
            request: "session_ending",
        },
        success: function (response) {
            toastMixin.fire({
                animation: true,
                title: response["msg"]
              });
              location.assign("/")
          
        },
        error: function () {
            console.log("sessionError101");
        },
    });
}





