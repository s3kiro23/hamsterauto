$(function () {
    generateNavbar();
    load();
    $(".data_modify").on("click", formProfil);
    $("#disable").on("click", disableAccount);
    $("#btnA2f").on("click", activationA2F);
    $("#file").on("change", checkType);
});

/*Vérification du type de fichier upload DEBUT*/
function checkType() {
    let file = this.files[0];
    let fileType = file.type;
    let match = [
        "image/jpeg",
        "image/png",
        "image/jpg",
    ];
    if (
        !(
            fileType === match[0] ||
            fileType === match[1] ||
            fileType === match[2]
        )
    ) {
        alert(
            "Désolé, seulement les fichiers aux formats JPG, JPEG, & PNG sont autorisés pour l'upload."
        );
        $("#file").val("");
        return false;
    }
}

/*Vérification du type de fichier upload FIN*/

function load() {
    $.ajax({
        url: "../src/Controller/AccountController.php",
        dataType: "JSON",
        type: "POST",
        data: {
            request: "profil_content",
        },
        success: function (response) {
            let badgeA2F = $('.badge-a2f');
            generateNavbar();
            $("#profile_login").html(response["login"]);
            $("#profile_tel").html(response["phone"]);
            $("#profile_addr").html(response["adress"]);
            $("#profile_nom").html(response["lastname"]);
            $("#profile_prenom").html(response["firstname"]);
            $('.img_profile').attr("src", response["image"]);
            if (response['a2f'] == 1) {
                $("#btnA2f").prop("checked", true);
                badgeA2F.removeClass('bg-danger text-danger');
                badgeA2F.addClass('bg-success text-success');
                badgeA2F.html('Active');
            } else {
                $("#btnA2f").prop("checked", false);
                badgeA2F.addClass('bg-danger text-danger');
                badgeA2F.removeClass('bg-success text-success');
                badgeA2F.html('Inactive');
            }
            $("#inputTel").intlTelInput({
                preferredCountries: ["fr", "gb"],
                utilsScript: "/vendor/jackocnr/intl-tel-input/build/js/utils.js",
                initialCountry: "fr",
                geoIpLookup: function (success, failure) {
                    $.get("https://ipinfo.io", function () {
                    }, "jsonp").always(function (resp) {
                        const countryCode = (resp && resp.country) ? resp.country : "fr";
                        success(countryCode);
                    });
                },
            });
        },
        error: function () {
        },
    });
}

let uploadImgProfile = function () {
    let data = new FormData($("form")[1]);

    $.ajax({
        url: "../src/Controller/ProfileUploadController.php",
        type: "POST",
        dataType: "JSON",
        enctype: "multipart/form-data",
        data: data,
        cache: false,
        contentType: false,
        processData: false,

        success: function (response) {
            $("#modalCG").modal("hide");
            Swal.fire({
                position: "center",
                icon: "success",
                class: "iziToast-bold",
                title: response["msg"],
                showConfirmButton: false,
                timer: 1500,
            });
            load();
            generateNavbar();
        },
        error: function () {
        },
    });
}

let modify = function () {
    let tab_fields_modal = {};
    $(".data-modal").each(
        function () {
            tab_fields_modal[$(this).attr('id')] = $(this).val();
        }
    );
    $.ajax({
        url: "../src/Controller/AccountController.php",
        dataType: "JSON",
        type: "POST",
        data: {
            request: "modify",
            values: JSON.stringify(tab_fields_modal),
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
                load();
            } else {
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: response['msg'],
                    showConfirmButton: true,
                    timer: 1500
                });
            }
        },
        error: function () {
        },
    });
};

let disableAccount = function () {
    Swal.fire({
        title: "Etes-vous sûr!?",
        text: "La désactivation est permanente!",
        imageUrl: '../public/assets/img/swalicons/warning.png',
        imageWidth: 100,
        showCancelButton: true,
        confirmButtonColor: "#4BBF73",
        cancelButtonColor: "#d33",
        cancelButtonText: "Non, j'ai peur",
        confirmButtonText: "Oui, désactive le!",
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire(
                "Désactivé!",
                "Votre compte à bien été désactivé.",
                "success",
                $.ajax({
                    url: "../src/Controller/AccountController.php",
                    dataType: "JSON",
                    type: "POST",
                    data: {
                        request: "disableAccount",
                    },
                    success: function (response) {
                    },
                    error: function () {
                    },
                })
            );
            setTimeout(() => {
                window.location.href = "/";
            }, 2300);
        }
    });
};

let formProfil = function () {
    let tab_fields = {};
    $(".data").each(
        function () {
            tab_fields[$(this).attr('id')] = $(this).html();
        }
    );
    $('#modal-profil').modal("show");
    $("#inputLogin").val(tab_fields['profile_login']);
    $("#inputNom").val(tab_fields['profile_nom']);
    $("#inputPrenom").val(tab_fields['profile_prenom']);
    $("#inputAddr").html(tab_fields['profile_addr']);
    $(".form-control").on("change", checkField);
    $("#inputTel").val(tab_fields['profile_tel']);
}

let activationA2F = function () {
    Swal.fire({
        title: "Action sur la double authentification par SMS",
        text: "êtes-vous sûr?",
        imageUrl: '../public/assets/img/swalicons/warning.png',
        imageWidth: 100,
        showCancelButton: true,
        confirmButtonColor: "#4BBF73",
        cancelButtonColor: "#d33",
        cancelButtonText: "Non, j'ai peur !",
        confirmButtonText: "Oui, vas-y !",
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "../src/Controller/AccountController.php",
                dataType: "JSON",
                type: "POST",
                data: {
                    request: "activationA2F",
                },
                success: function (response) {
                    let badgeA2F = $('.badge-a2f');
                    if (response['status'] === 1) {
                        $("#btnA2f").prop("checked", true);
                        badgeA2F.removeClass('bg-danger text-danger')
                        badgeA2F.addClass('bg-success text-success')
                        badgeA2F.html('Active')
                    } else {
                        $("#btnA2f").prop("checked", false);
                        badgeA2F.addClass('bg-danger text-danger')
                        badgeA2F.removeClass('bg-success text-success')
                        badgeA2F.html('Inactive')
                    }
                    Swal.fire({
                        title: response['msg'],
                        confirmButtonColor: "#4BBF73",
                    });
                },
                error: function () {
                    console.log("delError");
                },
            })
        }
    });
}

