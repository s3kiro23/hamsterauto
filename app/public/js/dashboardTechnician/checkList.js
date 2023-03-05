$(function () {
    allowCheckList();
});

function loadEvent(){
    $('[data-toggle="tooltip"]').tooltip();
    $('#checkAllbtn').on("click", checkThemAll)
    $('#btn-info-clist').on("click", showInfosCL)
    btnToTop();
}

let toastMixin =
    Swal.mixin({
        toast: true,
        icon: 'success',
        title: 'General Title',
        animation: false,
        position: 'center',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });

let showInfosCL = function () {
    $("#modal-clist").modal("show");
}

let checkThemAll = function () {
    if (this.checked) {
        $('.checkBox').each(function () { //loop through each checkbox
            $(this).prop('checked', true); //check
        });
        $('.label-check-all-btn').html('Tout décocher');
    } else {
        $('.checkBox').each(function () { //loop through each checkbox
            $(this).prop('checked', false); //uncheck
        });
        $('.label-check-all-btn').html('Tout cocher');
    }
}

function $_GET(param) {
    let vars = {};
    window.location.href.replace(location.hash, "").replace(
        /[?&]+([^=&]+)=?([^&]*)?/gi, // regexp
        function (m, key, value) {
            // callback
            vars[key] = value !== undefined ? value : "";
        }
    );
    if (param) {
        return vars[param] ? vars[param] : null;
    }
    return vars;
}

function allowCheckList() {
    $.ajax({
        url: "/src/Controller/DashboardTechnician/ChecklistController.php ",
        dataType: "JSON",
        type: "POST",
        data: {
            request: "autorisationCT",
        },
        success: function (response) {
            if (response["status"] === 1) {
                displayCheckList();
            } else {
                toastMixin.fire({
                    animation: true,
                    title: response["msg"],
                    icon: 'error',
                });
                setTimeout(() => {
                    window.location.replace("/");
                }, 1500); 
            } 
        },
        error: function () {
            console.log("errorAllowCT")
        },
    }); 
}

function displayCheckList(){
    $.ajax({
        url: "/src/Controller/DashboardTechnician/ChecklistController.php ",
        dataType: "JSON",
        type: "POST",
        data: {
            request: "display_check_list",
        },
        success: function (response) {
            $("#checklistContent").html(response);
                checkListLoader();
        },
        error: function () {
            console.log("errorDisplayCT")
        },
    }); 

}


function checkListLoader() {
    let intervention = $_GET("intervention");
    let interID = $(".inter-id");
    $.ajax({
        url: "/src/Controller/DashboardTechnician/ChecklistController.php ",
        dataType: "JSON",
        type: "POST",
        data: {
            request: "load_check_list",
            intervention: intervention,
        },
        success: function (response) {
            if (response['status'] === 0) {
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

                $("#content-modal-clist").html(response["html"]);
                interID.html(response["html_inter"]);
                interID.attr('id', response["id_inter"]);
                loadEvent();
            }
        },
        error: function () {
            console.log('errorCheckLoader')
        },
    });
}

// _____________________________________________________________

// controle technique ok

function validationCT() {
    let tab_checkbox = {};
    $('.checkBox').each(
        function () {
            if ($(this).is(':not(:checked)')) {
                tab_checkbox[$(this).attr('id')] = $('label[for="' + $(this).attr('id') + '"]').html();
            }
        });
    Swal.fire({
        title: "Voulez-vous valider cette intervention?",
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
                url: "/src/Controller/DashboardTechnician/ChecklistController.php ",
                dataType: "JSON",
                type: "POST",
                data: {
                    request: "validationCT",
                    tab_checkbox: JSON.stringify(tab_checkbox),
                    id_intervention: $(".inter-id").attr('id'),
                },
                success: function (response) {
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: 'Intervention terminée',
                            showConfirmButton: false,
                            timer: 2000
                        })
                        setTimeout(() => {
                            window.location.replace("/dashboards/technicien");
                        }, 2000);
                },
                error: function (response) {
                    toastMixin.fire({
                        animation: true,
                        title: response["msg"],
                        icon: "error",
                    });
                },
            });
        }
    });
}
    


