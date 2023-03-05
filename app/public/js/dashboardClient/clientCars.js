
// PAS ENCORE UTILISE!

$(function () {

    login();
    $("#logout").on('click', logout);
    $("#to_home").on('click', toHome);
    $("#to_profil").on('click', toProfil);
    $("#delete").on('click', deleteCar);

});

function login() {

    // console.log(1);
    $.ajax({

        url: '../src/Controller/UserController.php',
        dataType: 'JSON',
        type: 'POST',
        data: {
            request: 'user_login',
        },
        success: function (response) {
            $(".user_login").html(response['login']);
        },
        error: function () {

        }
    });
}


let toProfil = function () {
    $.ajax({
        url: '../src/Controller/CarController.php',
        dataType: 'JSON',
        type: 'POST',
        data: {
            request: 'to_profil',
        },
        success: function (response) {
            window.location.replace('profil.html')
        },
        error: function () {
            console.log('errorAlpha');
        }
    });

}

function deleteCar() {



}


let modify = function () {
    var type = $(this).attr('data-id');
    console.log(type);
    Swal.fire({
        title: 'Nouveau ' + type,
        input: 'text',
        inputAttributes: {
            autocapitalize: 'off'
        },
        showCancelButton: true,
        confirmButtonText: 'Save',
        showLoaderOnConfirm: true,
        preConfirm: (input) => {
            console.log('query_ajax');
            $.ajax({

                url: '../src/Controller/UserController.php',
                dataType: 'JSON',
                type: 'POST',
                data: {
                    request: 'modify',
                    value: input,
                    type_value: type
                },
                success: function (response) {

                },
                error: function () {
                    console.log('moderror')
                }
            });
        },
        allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire(
                'Modification effectuée!',
                'Votre champ a bien été mis à jour.',
                'success',
            )
            load()
        }
    });

}

let logout = function () {

    console.log(1);
    $.ajax({

        url: '../src/Controller/UserController.php',
        dataType: 'JSON',
        type: 'POST',
        data: {
            request: 'logout',
            login: $(".user_login").html()
        },
        success: function (response) {
            toastMixin.fire({
                animation: true,
                title: response["msg"]
              });
            setTimeout(() => {
                window.location.href = "index.html";
            }, 2300);

        },
        error: function () {

        }
    });
}

function modalCard() {

    // console.log(1);
    $.ajax({

        url: '../src/Controller/UserController.php',
        dataType: 'JSON',
        type: 'POST',
        data: {
            request: 'user_login',
        },
        success: function (response) {

            if (response == 1) {
                window.location.href = "index.php";
            } else {
                $(".user_login").html(response['login']);
                $("#tel").html(response['tel']);
                $("#addr").html(response['adresse']);
                $("#user_nom").html(response['nom']);
                $("#user_prenom").html(response['prenom']);
            }
            // console.log(2);

        },
        error: function () {

        }
    });
}

let toHome = function () {
    console.log("tohome");
    $.ajax({
        url: '../src/Controller/UserController.php',
        dataType: 'JSON',
        type: 'POST',
        data: {
            request: 'to_home',
        },
        success: function (response) {
            console.log('to_home');
            window.location.replace('clientDashboard.html')
        },

        error: function () {
            console.log('errhome')
        }
    });
}
