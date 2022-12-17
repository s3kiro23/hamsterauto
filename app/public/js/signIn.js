$(function () {
    captcha();
    $('#to-cgu').on("click", modalCGU)
    $('#to-mentions').on("click", modalMentions)
    $('#to_logIn').on('click', to_logIn);
    $(".form-control").on("change", checkField);
    $('#inputTel').intlTelInput({
        preferredCountries: ["fr", "gb"],
        utilsScript: "../vendor/jackocnr/intl-tel-input/build/js/utils.js",
        initialCountry: "fr",
        geoIpLookup: function (success, failure) {
            $.get("https://ipinfo.io", function () {
            }, "jsonp").always(function (resp) {
                const countryCode = (resp && resp.country) ? resp.country : "fr";
                success(countryCode);
            });
        },
    });
    /*$('#inputTel').on("countrychange", fetchDialCountry)*/
});
/*let fetchDialCountry = function () {
    let input = $("#inputTel");
    let countryData = input.intlTelInput("getSelectedCountryData");
    $('#inputTel').val("+" + countryData.dialCode)
}*/

let signIn = function () {
    let $civilite = "";
    let $selectedCivilite = $('input[name=optionsCivilite]:checked').val();
    if ($selectedCivilite) {
        $civilite = $selectedCivilite;
    }
    $.ajax({
        url: '../src/Controller/index/signInController.php',
        dataType: 'JSON',
        type: 'POST',
        data: {
            request: 'signIn',
            civilite: $civilite,
            nom: $('#inputNom').val(),
            prenom: $('#inputPrenom').val(),
            tel: $('#inputTel').val(),
            email: $('#inputEmail').val(),
            passwd: $('#inputPassword').val(),
            passwd2: $('#inputPassword2').val(),
            checkCap: $('#captcha_verif').val(),
            captcha: $('#captcha').html(),
        },
        success: function (response) {
            if (response['status'] === 0) {
                console.log('error');
                Swal.fire({
                    title: 'Erreur',
                    text: response['msg'],
                    icon: 'warning',
                    showCancelButton: true,
                    showConfirmButton: false,
                    cancelButtonColor: '#3085d6',
                    cancelButtonText: 'Retry!'
                });
            } else {
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: response['msg'],
                    showConfirmButton: false,
                    timer: 1500
                });
                console.log('Success');
                setTimeout(() => {
                    window.location.replace("index.html");
                }, 1600);

            }

        },

        error: function () {
            console.log('errorSign');
        }

    });
}

function captcha() {
    $.ajax({
        url: '../src/Controller/index/signInController.php',
        dataType: 'JSON',
        type: 'POST',
        data: {
            request: 'captcha',
        },
        success: function (response) {
            $("#captcha").html(response['get_captcha']);
        },
        error: function () {
            console.log(3);
        }
    });
}

let to_logIn = function () {
            let timerInterval
            Swal.fire({
                title: "Redirection vers la page de connexion",
                imageUrl: '../public/assets/img/swalicons/spinner.gif',
                imageWidth: 220,
                imageHeight: 220,
                allowEscapeKey: false,
                allowOutsideClick: false,
                showCancelButton: false,
                showConfirmButton: false,
                timer: 2000,
                didOpen: () => {
                    Swal.showLoading()
                    const b = Swal.getHtmlContainer().querySelector('b')
                    timerInterval = setInterval(() => {
                        b.textContent = Swal.getTimerLeft()
                    }, 100)
                },
                willClose: () => {
                    clearInterval(timerInterval)
                }
            }).then((result) => {
                /* Read more about handling dismissals below */
                if (result.dismiss === Swal.DismissReason.timer) {
                    console.log('I was closed by the timer')
                }
            })
            setTimeout(() => {
                window.location.replace('index.html')
            }, 2000);
};
