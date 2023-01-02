$(function () {
    getAuthorization();
    sweetToast();
});

function $_GET(param) {
    const vars = {};
    window.location.href.replace(location.hash, '').replace(
        /[?&]+([^=&]+)=?([^&]*)?/gi, // regexp
        function (m, key, value) { // callback
            vars[key] = value !== undefined ? value : '';
        }
    );

    if (param) {
        return vars[param] ? vars[param] : null;
    }
    return vars;
}

let getAuthorization = function () {
    let token = $_GET('token');
    if (token == null) {
        sweetToast();
        toastMixin.fire({
            animation: true,
            icon: "error",
            width: 500,
            title: "Vous n'êtes pas autorisé à accéder à cette page ! Redirection vers la page de login..."
        });
        $('#btn-modify').prop('disabled', true);
        setTimeout(() => {
            window.location.replace('/')
        }, 3000);
    }
    if (token === "pwd-modify") {
        $(".old_password").prop("hidden", false);
        $("#container-to").prop("hidden", false);
        $('#back').on("click", historyBack)
    }
}

let changePassword = function () {
    let token = $_GET('token');
    $.ajax({
        url: '../src/Controller/index/recoveryController.php',
        dataType: 'JSON',
        type: 'POST',
        data: {
            request: 'modify_password',
            oldPassword: $('#old_password').val(),
            password: $('#password').val(),
            password2: $('#password2').val(),
            token: token
        },
        success: function (response) {
            if (response['status'] === 1) {
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: response['msg'],
                    showConfirmButton: false,
                    timer: 1500
                });
                if (response['type'] == "profile") {
                    if (response['userType'] == "client") {
                        setTimeout(() => {
                            toClientDash();
                        }, 1500);
                    } else {
                        setTimeout(() => {
                            toTechDash();
                        }, 1500);
                    }
                } else {
                    setTimeout(() => {
                        window.location.replace('/')
                    }, 1500);
                }
            } else {
                toastMixin.fire({
                    position: 'top',
                    animation: true,
                    icon: "error",
                    width: 500,
                    title: response['msg'],
                    timer: 1400
                });
            }
        },
        error: function () {
            console.log('errChgPwd')
        }
    });
}

let toClientDash = function () {
    window.location.replace('dashboards/')
}

let toTechDash = function () {
    window.location.replace('dashboards-tech/')
}

let historyBack = function () {
    history.back()
}



