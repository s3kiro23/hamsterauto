$(function () {
    load();
    $("#file").on("change", checkType);
    $('.switchLogo').on("click", switchLogo);
    // setInterval(() => {
    //     let currentPage = $('#pagesMyArchives').find('.active').children().html();
    //     loadUserIntervention();
    //     loadUserArchives(currentPage);
    // }, 5000);
});

function load() {
    sweetToast();
    generateNavbar();
    loadUserCars();
    loadUserIntervention();
    loadUserArchives(1);
    $("#formAddCar").on("click", formAddCar);
    $("#formAddRDV").on("click", formAddRDV);
    $('[data-toggle="tooltip"]').tooltip();
}

let intervalRdv = null;
let intervalHist = null;

function reloadRdv(){
    intervalRdv = setInterval(loadUserIntervention, 10000);
    intervalHist = setInterval(loadUserArchives, 10000);
}
function clearIntervals(){
    clearInterval(intervalRdv);
    clearInterval(intervalHist);
}

/*Affichage des véhicules/RDV/Historiques par user + pagination DEBUT*/
let loadUserCars = function () {
    $.ajax({
        url: "/src/Controller/DisplayHTML/TablesClientDisplayController.php",
        dataType: "JSON",
        type: "POST",
        data: {
            request: "loadCarsRecap",
            type : "cars",
        },
        success: function (response) {
            $("#mycars").html(response["htmlCar"]);
            $('.addCG').on("click", modalCG);
            $('.modifyCar').on("click", modalModifyCar);
            $('.deleteCar').on("click", deleteCar);
            dataTableClient('car');
        },
        error: function () {
        },
    });
}

let loadUserIntervention = function () {
    $.ajax({
        url: "/src/Controller/DisplayHTML/TablesClientDisplayController.php",
        dataType: "JSON",
        type: "POST",
        data: {
            request: "loadCarsRecap",
            type : "rdv",
        },
        success: function (response) {
            $("#myrdv").html(response["htmlRDV"]);
            dataTableClient('rdv');
        },
        error: function () {
        },
    });
}

let loadUserArchives = function (page) {
    $.ajax({
        url: "/src/Controller/DisplayHTML/TablesClientDisplayController.php",
        dataType: "JSON",
        type: "POST",
        data: {
            request: "loadCarsRecap",
            type : "archives",
            page: page,
        },
        success: function (response) {
            $("#myarchives").html(response["htmlArchives"]);
            $("#history-size-start").html(response["nbrOfArchives"]["current"]);
            $("#history-size-end").html(response["nbrOfArchives"]["total"]);
            $("#pagesMyArchives").html(response["paginationMyArchives"]);
            $("#pageMyA" + page).addClass("active");
        },
        error: function () {
        },
    });
}

/*Affichage des véhicules/RDV/Historiques par user + pagination FIN*/

/*Fonctions de gestion fiche véhicule DEBUT*/
function formAddCar() {
    $.ajax({
        url: "/src/Controller/DisplayHTML/FormsDisplayController.php",
        dataType: "JSON",
        type: "POST",
        data: {
            request: "formAddCar",
        },
        success: function (response) {
            modalAddCar(response);
            $('#selectMarque').select2();
            $('#selectedModel').select2();
        },
        error: function () {
        },
    });
}

function selectedCar() {
    $.ajax({
        url: "/src/Controller/DisplayHTML/BrandsDisplayController.php",
        dataType: "JSON",
        type: "POST",
        data: {
            request: "modelsLoad",
            brand: $('#selectMarque').val()
        },
        success: function (response) {
            $("#selectedModel").html(response['html_model'])
        },
        error: function () {
        },
    });
}

let addCar = function () {
    let tabValues = {};
    $('input[name=optionsCarbu]:checked').val() ? tabValues['fuel'] = $('input[name=optionsCarbu]:checked').val() : tabValues['fuel'] = "";
    tabValues['registration'] = $('#inputImmatOld').val();
    if ($('input[name=radioImmat]:checked').val() === "newImmat") {
        tabValues['registration'] = $('#inputImmatNew').val();
    }
    $('.field').each(
        function () {
            tabValues[$(this).attr('id')] = $(this).val();
        });
    Swal.fire({
        title: 'Confirmez-vous l\'ajout de ce véhicule?',
        text: "",
        imageUrl: '/public/assets/img/swalicons/interro.png',
        imageWidth: 100,
        showCancelButton: true,
        confirmButtonColor: '#62C462',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Oui',
        cancelButtonText: 'Annuler',
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '/src/Controller/CarController.php',
                dataType: 'JSON',
                type: 'POST',
                data: {
                    request: 'addCar',
                    data: JSON.stringify(tabValues)
                },
                success: function (response) {
                    if (response['status'] === 1) {
                        toastMixin.fire({
                            animation: true,
                            title: response["msg"]
                        });
                        $("#modalFormCar").modal("hide");
                        $('#tab-car').DataTable().clear().destroy();
                        loadUserCars();
                    } else {
                        toastMixin.fire({
                            animation: true,
                            title: response["msg"],
                            icon: "error",
                        });
                    }
                },
                error: function () {
                }
            })
        }
    })
};

let modifyCar = function () {
    let modifyValues = {};
    modifyValues['fuel'] = $('input[name=optionsCarbu]:checked').val();
    modifyValues['registration'] = $('#inputImmatOld').val();
    if ($('input[name=radioImmat]:checked').val() === "newImmat") {
        modifyValues['registration'] = $('#inputImmatNew').val();
    }
    $('.field').each(
        function () {
            modifyValues[$(this).attr('id')] = $(this).val();
        });
    Swal.fire({
        title: 'Confirmez-vous la modification de ce véhicule?',
        text: "",
        imageUrl: '/public/assets/img/swalicons/interro.png',
        imageWidth: 100,
        showCancelButton: true,
        confirmButtonColor: '#62C462',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Oui',
        cancelButtonText: 'Annuler',
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '/src/Controller/CarController.php',
                dataType: 'JSON',
                type: 'POST',
                data: {
                    request: 'modifyCar',
                    idCar: $('#modal-addID').attr('data-id'),
                    data: JSON.stringify(modifyValues)
                },
                success: function (response) {
                    if (response['status'] === 1) {
                        toastMixin.fire({
                            animation: true,
                            title: response["msg"]
                        });
                        $("#modalFormCar").modal("hide");
                        $('#tab-car').DataTable().clear().destroy();
                        loadUserCars();
                    } else {
                        toastMixin.fire({
                            animation: true,
                            title: response["msg"],
                            icon: "error",
                        });
                    }
                },
                error: function () {
                }
            })
        }
    })
};

let deleteCar = function () {
    Swal.fire({
        title: "Confirmez-vous la suppression de ce véhicule?",
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
                url: "/src/Controller/CarController.php",
                dataType: "JSON",
                type: "POST",
                data: {
                    request: "deleteCar",
                    carID: $(this).data("id"),
                },
                success: function (response) {
                    let currentPage = $('#pagesMyArchives').find('.active').children().html();
                    if (currentPage == undefined){
                        currentPage = 1;
                    }
                    toastMixin.fire({
                        animation: true,
                        title: response["msg"]
                    });
                    if (response['status'] === 1){
                        $('#tab-car').DataTable().clear().destroy();
                        $('#tab-rdv').DataTable().clear().destroy();
                        loadUserCars();
                        loadUserIntervention();
                        loadUserArchives(currentPage);
                    } else {
                        $('#tab-car').DataTable().clear().destroy();
                        loadUserCars();
                        loadUserArchives(currentPage);
                    }
                },
                error: function () {
                },
            });
        }
    });
}

/*Fonctions de gestion fiche véhicule FIN*/

/*---------------------------------------------------------------------*/

/*Fonctions de gestion rdv DEBUT*/
let formAddRDV = function () {
    $.ajax({
        url: "/src/Controller/DisplayHTML/FormsDisplayController.php",
        dataType: "JSON",
        type: "POST",
        data: {
            request: "formAddRDV",
        },
        success: function (response) {
            modalFormAddRDV(response);
        },
        error: function () {
        },
    });
};

let addRDV = function () {
    let tabValuesRdv = {};
    tabValuesRdv['timeSlot'] = $("input[name=timeSlot]:checked").attr("id");
    tabValuesRdv['carID'] = $('#selectCars').val();
    Swal.fire({
        title: 'Confirmez-vous la demande de rendez-vous ?',
        text: "",
        imageUrl: '/public/assets/img/swalicons/interro.png',
        imageWidth: 100,
        showCancelButton: true,
        confirmButtonColor: '#62C462',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Oui',
        cancelButtonText: "Annuler",
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '/src/Controller/RdvController.php',
                dataType: 'JSON',
                type: 'POST',
                data: {
                    request: 'newRDVDashboardClient',
                    data: JSON.stringify(tabValuesRdv)
                },
                success: function (response) {
                    if (response['status'] === 1) {
                        toastMixin.fire({
                            animation: true,
                            title: response["msg"]
                        });
                        $('#tab-rdv').DataTable().clear().destroy();
                        $("#modalAddRDV").modal("hide");
                        loadUserIntervention();
                    } else {
                        toastMixin.fire({
                            animation: true,
                            title: response["msg"],
                            icon: "error",
                        });
                    }
                },
                error: function (jqxhr, textStatus, errorThrown) {
                    console.log(jqxhr);
                    console.log(textStatus);
                    console.log(errorThrown);
                }
            })
        }
    })
};

function deleteRdvUser(ctId) {
    Swal.fire({
        title: "Voulez-vous annuler ce rendez-vous?",
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
                    idRdv: ctId,
                },
                success: function (response) {
                    let currentPage = $('#pagesMyArchives').find('.active').children().html();
                    if (currentPage == undefined){
                        currentPage = 1;
                    }
                    toastMixin.fire({
                        animation: true,
                        title: response["msg"]
                    });
                    $('#tab-rdv').DataTable().clear().destroy();
                    loadUserIntervention();
                    loadUserArchives(currentPage);
                },
                error: function () {
                },
            });
        }
    });
}



/*Fonctions de gestion RDV FIN*/

  






