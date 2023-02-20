$(function () {
    sweetToast();
    $(".form-control").on("change", checkField);
    $(".form-control").on("focusin", placeholderAnimation);
    $(".form-control").on("focusout", placeholderAnimation);
})

let connect = function () {
    let access = $('.accessPath').prop('id');
    $.ajax({
        url: "../src/Controller/Index/LoginController.php",
        dataType: "JSON",
        type: "POST",
        data: {
            request: "connexion",
            login: $("#inputLogin").val(),
            password: $("#inputPasswdLogin").val(),
            accessPath: access,
        },
        success: function (response) {
            if (response["status"] === 0) {
                toastMixin.fire({
                    animation: true,
                    title: response["msg"],
                    icon: 'error',
                });
                setTimeout(() => {
                    window.location.replace(response['url']);
                }, 1500);
            } else if (response["status"] === 2) {
                toastMixin.fire({
                    animation: true,
                    title: response["msg"],
                    icon: 'error',
                });
                $("#content-request").html(response["contentPwdLogin"]);
                $(".form-control").on("change", checkField);
            } else if (response["status"] === 3) {
                toastMixin.fire({
                    animation: true,
                    title: response["msg"],
                    icon: 'warning',
                });
                $("#content-request").html(response["contentPwdLogin"]);
            } else {
                toastMixin.fire({
                    animation: true,
                    title: response["msg"]
                });
                if (response["typeUser"] === "technicien") {
                    $('#login').prop('disabled', true);
                    setTimeout(() => {
                        window.location.replace("dashboards/technicien");
                    }, 1500);
                }
                if (response["typeUser"] === "client") {
                    $('#login').prop('disabled', true);
                    setTimeout(() => {
                        window.location.replace("dashboards/client");
                    }, 1500);
                }
                if (response["typeUser"] === "admin") {
                    $('#login').prop('disabled', true);
                    setTimeout(() => {
                        window.location.replace("administration");
                    }, 1500);
                }
            }
        },
        error: function () {
        },
    });
};

let smsVerif = function () {
    $.ajax({
        url: "../src/Controller/Index/LoginController.php",
        dataType: "JSON",
        type: "POST",
        data: {
            request: "sub_sms",
            sms_verif: $("#sms_verif").val(),
        },
        success: function (response) {
            if (response["status"] === 1) {
                Swal.fire({
                    position: 'top',
                    icon: "success",
                    title: response["msg"],
                    showConfirmButton: false,
                    timer: 1500,
                });
                if (response["type"] === "technicien") {
                    setTimeout(() => {
                        window.location.replace("dashboards/technicien");
                    }, 1500);
                } else {
                    setTimeout(() => {
                        window.location.replace("dashboards/client");
                    }, 1500);
                }
            } else {
                Swal.fire({
                    title: "Erreur",
                    text: response["msg"],
                    imageUrl: '../public/assets/img/swalicons/warning.png',
                    imageWidth: 100,
                    showCancelButton: true,
                    showConfirmButton: false,
                    cancelButtonColor: "#3085d6",
                    cancelButtonText: "Retry!",
                });
            }
        },
        error: function () {
            console.log("errSms_verif");
        },
    });
};

let reload = function () {
    location.reload(true);
}

