$(function () {
    getAuthorization();
    sweetToast();
    $(".form-control").on("change", checkField);
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
    let tabInput = {};
    tabInput['token'] = $_GET('token');
    $('.field').each(
        function () {
            if (tabInput['token'] !== 'pwd-modify' && this.id !== 'old-password') {
                tabInput[this.id] = $(this).val();
            } else if (tabInput['token'] === 'pwd-modify') {
                tabInput[this.id] = $(this).val();
            }
        });
    $.ajax({
        url: '/src/Controller/Index/RecoveryController.php',
        dataType: 'JSON',
        type: 'POST',
        data: {
            request: 'modify_password',
            tabInput: JSON.stringify(tabInput),
        },
        success: function (response) {
            if (response['status'] === 1) {
                $('#btn-modify').prop('disabled', true);
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
            } else if (response['status'] === 0) {
                toastMixin.fire({
                    position: 'top',
                    animation: true,
                    icon: "error",
                    width: 500,
                    title: response['msg'],
                    timer: 3000
                });
            } else if (response["status"] === 2) {
                toastMixin.fire({
                    animation: true,
                    icon: "warning",
                    position: 'top',
                    width: 500,
                    title: response["msg"]
                });
                setTimeout(() => {
                    window.location.replace("/");
                }, 3000);
            }
        },
        error: function () {
        }
    });
}

let toClientDash = function () {
    window.location.replace('/dashboards/client')
}

let toTechDash = function () {
    window.location.replace('/dashboards/technicien')
}

let historyBack = function () {
    history.back()
}



