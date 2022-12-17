$(function () {
    checkListLoader();
    $('[data-toggle="tooltip"]').tooltip();
    $('#checkAllbtn').on("click", checkThemAll)
    $('#btn-info-clist').on("click", showInfosCL)
    btnToTop();
});

let showInfosCL = function () {
    $("#modal-clist").modal("show");
}

let checkThemAll = function () {
    if (this.checked) {
        $('.checkBox').each(function () { //loop through each checkbox
            $(this).prop('checked', true); //check
        });
    } else {
        $('.checkBox').each(function () { //loop through each checkbox
            $(this).prop('checked', false); //uncheck
        });
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

function checkListLoader() {
    let intervention = $_GET("intervention");
    $.ajax({
        url: "../src/Controller/dashboardBackoffice/checklistController.php ",
        dataType: "JSON",
        type: "POST",
        data: {
            request: "load_check_list",
            intervention: intervention,
        },
        success: function (response) {
            if (response['status'] === 2) {
                toastMixin.fire({
                    animation: true,
                    icon: "error",
                    width: 500,
                    title: response['msg']
                });
                setTimeout(() => {
                    window.location.replace('index.html')
                }, 3000);
            } else {
                $("#content-modal-clist").html(response["html"]);
                $("#inter-id").html(response["html_inter"]);
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
    const id_controle = $("#numeroInter").html();
    let tab_checkbox = [];
    $('.checkBox').each(
        function () {
            if ($(this).is(':not(:checked)')) {
                tab_checkbox.push($(this).attr('id'));
            }
        });
    Swal.fire({
        title: "Voulez-vous valider cette intervention?",
        text: "",
        imageUrl: '../public/assets/img/swalicons/interro.png',
        imageWidth: 100,
        showCancelButton: true,
        cancelButtonText: "Annuler",
        confirmButtonColor: "#62C462",
        cancelButtonColor: "#d33",
        confirmButtonText: "Oui",
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "../src/Controller/dashboardBackoffice/checklistController.php ",
                dataType: "JSON",
                type: "POST",
                data: {
                    request: "validationCT",
                    tab_checkbox: JSON.stringify(tab_checkbox),
                    id_controle: id_controle,
                },
                success: function (response) {
                    /*Swal.fire({
                        title: response["msg"],
                        imageUrl: '../public/assets/img/swalicons/spinner.gif',
                        imageWidth: 220,
                        imageHeight: 220,
                        allowEscapeKey: false,
                        allowOutsideClick: false,
                        showCancelButton: false,
                        showConfirmButton: false,
                        timer: 2000
                    }).then(() => {*/
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: 'Intervention terminée',
                            showConfirmButton: false,
                            timer: 2000
                        })
                    /*}).then(() => {*/
                        setTimeout(() => {
                            window.location.replace("back-office.html");
                        }, 2000);
                    /*});*/
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
    


