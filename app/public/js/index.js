$(function () {
    sweetToast()
    marquesLoad();
    generateDate();
    IntlTelInput();
    $('.switchLogo').off("click")
    $('.switchLogo').on("click", switchLogo)
    $('#to-mentions').on("click", modalMentions)
    $('#to-cgu').on("click", modalCGU)
    $(".form-control").on("change", checkField);
    $("#selectMarque").on("change", modelesLoad);
    $("#to_signIn").on("click", toSignIn);
    $("#reload").on("click", reload);
    $('[data-toggle="tooltip"]').tooltip();
    $('input[name=radioImmat]').on("click", selectedImmatFormat);
    $(".accordion-button").on("click", function (event) {
        if ($(this).attr("id") == 1) {
            setTimeout(() => {
                $(window).scrollTop($("#inputEmail").position().top);
            }, "200");
        } else if ($(this).attr("id") == 2) {
            setTimeout(() => {
                $(window).scrollTop($("#inputAnnee").position().top);
            }, "200");
        } else if ($(this).attr("id") == 3) {
            setTimeout(() => {
                $(window).scrollTop($("#reserved").position().top);
            }, "200");
        }
    });

    $('.changeStep').on('click', function (event) {

        //Block content
        let hello = $('#bootstrap-wizard-tabHello')
        let step0 = $('#bootstrap-wizard-tab0');
        let step1 = $('#bootstrap-wizard-tab1');
        let step2 = $('#bootstrap-wizard-tab2');

        //Button
        let prevStep = $('#prev-btn')
        let nextStep = $('#next-btn')

        //Header/Footer wizard
        let formTitle = $('.form-title');
        let navWizard = $('.nav-wizard');
        let footerWizard = $('.card-footer');
        let idStep = $("#idStep");
        let carStep = $("#carStep");
        let slotStep = $("#slotStep");

        if (event.target.id === "new-btn"){
            formTitle.attr('hidden', 1);
            navWizard.removeAttr('hidden');
            footerWizard.removeAttr('hidden');
            hello.removeClass('active')
            step0.addClass('active show')
            $('#prev-btn').attr('hidden', 1);
        } else if(event.target.id === "next-btn"){
            if (step0.hasClass('active')){
                step0.removeClass('active show')
                idStep.attr('aria-selected','false')
                idStep.removeClass('active')
                step1.addClass('active show')
                carStep.attr('aria-selected','true');
                carStep.addClass('active show')
                prevStep.removeAttr('hidden');
            } else if (step1.hasClass('active show')) {
                step1.removeClass('active show')
                carStep.attr('aria-selected','false')
                carStep.removeClass('active')
                step2.addClass('active show')
                slotStep.attr('aria-selected','true');
                slotStep.addClass('active show')
                setTimeout(() => {
                    nextStep.attr('type', 'submit')
                    nextStep.html('Réserver!')
                }, 100);
            }
        } else {
            if (step1.hasClass('active show')){
                step1.removeClass('active show');
                carStep.attr('aria-selected','false');
                carStep.removeClass('active');
                step0.addClass('active show')
                idStep.attr('aria-selected','true')
                idStep.addClass('active')
                prevStep.attr('hidden', 1);
            } else if (step2.hasClass('active show')){
                step2.removeClass('active show');
                slotStep.attr('aria-selected','false');
                slotStep.removeClass('active');
                step1.addClass('active show')
                carStep.attr('aria-selected','true')
                carStep.addClass('active')
                nextStep.html('Suivant <span class="fas fa-chevron-right ms-2" data-fa-transform="shrink-3"></span>')
                nextStep.attr('type', 'button')
            }
        }
    })
});

function switchLogo(){
    if ($('#logoIndex').attr('src') === "../public/assets/img/animated-icons/hamsterauto-unscreen.gif"){
        $('#logoIndex').attr('src',"../public/assets/img/animated-icons/hamsterautoNuit-unscreen.gif")
    }else if ($('#logoIndex').attr('src') === "../public/assets/img/animated-icons/hamsterautoNuit-unscreen.gif"){
        $('#logoIndex').attr('src', "../public/assets/img/animated-icons/hamsterauto-unscreen.gif")
    }
}

let connect = function () {
    $.ajax({
        url: "../src/Controller/index/loginController.php",
        dataType: "JSON",
        type: "POST",
        data: {
            request: "connexion",
            login: $("#inputLogin").val(),
            password: $("#inputPassword").val(),
        },
        success: function (response) {
            if (response["status"] === 0) {
                toastMixin.fire({
                    position: 'top',
                    animation: true,
                    title: response["msg"],
                    icon: 'error',
                });
            } else if (response["status"] === 2) {
                toastMixin.fire({
                    position: 'top',
                    animation: true,
                    title: response["msg"],
                    icon: 'error',
                });
                $("#content-request").html(response["contentPwdLogin"]);
            } else if (response["status"] === 3) {
                toastMixin.fire({
                    position: 'top',
                    animation: true,
                    title: response["msg"]
                });
                $("#content-request").html(response["contentPwdLogin"]);
            } else {
                toastMixin.fire({
                    position: 'top',
                    animation: true,
                    title: response["msg"]
                });
                if (response["typeUser"] === "technicien") {
                    $('#signIn').prop('disabled', true);
                    setTimeout(() => {
                        window.location.replace("back-office.html");
                    }, 1500);
                } else {
                    $('#signIn').prop('disabled', true);
                    setTimeout(() => {
                        window.location.replace("client-dashboard.html");
                    }, 1500);
                }
            }
        },
        error: function () {
            console.log("errID");
        },
    });
};
let smsVerif = function () {
    $.ajax({
        url: "../src/Controller/index/loginController.php",
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
                        window.location.replace("back-office.html");
                    }, 1500);
                } else {
                    setTimeout(() => {
                        window.location.replace("client-dashboard.html");
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

let toSignIn = function () {
    let timerInterval;
        Swal.fire({
            title: "Redirection vers la page d'inscription",
            imageUrl: '../public/assets/img/swalicons/spinner.gif',
            imageWidth: 220,
            imageHeight: 220,
            allowEscapeKey: false,
            allowOutsideClick: false,
            showCancelButton: false,
            showConfirmButton: false,
            timer: 2000,
            didOpen: () => {
                Swal.showLoading();
                const b = Swal.getHtmlContainer().querySelector("b");
                timerInterval = setInterval(() => {
                    b.textContent = Swal.getTimerLeft();
                }, 100);
            },
            willClose: () => {
                clearInterval(timerInterval);
            },
        }).then((result) => {
            /* Read more about handling dismissals below */
            if (result.dismiss === Swal.DismissReason.timer) {
                console.log("I was closed by the timer");
            }
        });
        setTimeout(() => {
            window.location.replace("sign-in.html");
        }, 2000);  
};
let newRDVHomePage = function () {
    let $civilite = "";
    let $selectedCivilite = $('input[name=optionsCivilite]:checked').val();
    if ($selectedCivilite) {
        $civilite = $selectedCivilite;
    }
    let $fuel = "";
    let $selectedFuel = $('input[name=optionsCarbu]:checked').val();
    if ($selectedFuel) {
        $fuel = $selectedFuel;
    }
    let $slot = "";
    let $selectedSlot = $("input[name=timeSlot]:checked").attr("id");
    if ($selectedSlot) {
        $slot = $selectedSlot;
    }
    let $immat = "";
    let selectedFormatPlate = $('input[name=radioImmat]:checked').val();
    if (selectedFormatPlate === "newImmat") {
        $immat = $('#inputImmatNew').val();
    } else {
        $immat = $('#inputImmatOld').val();
    }
    Swal.fire({
        title: "Confirmez-vous la demande de rendez-vous ?",
        text: "",
        imageUrl: '../public/assets/img/swalicons/interro.png',
        imageWidth: 100,
        showCancelButton: true,
        confirmButtonColor: "#4BBF73",
        cancelButtonColor: "#d33",
        confirmButtonText: "Oui",
        cancelButtonText: "Annuler",
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "../src/Controller/index/newRdvHomePage.php",
                dataType: "JSON",
                type: "POST",
                data: {
                    request: "newRDVHomePage",
                    civilite: $civilite,
                    nom: $("#inputNom").val(),
                    prenom: $("#inputPrenom").val(),
                    tel: $("#inputTel").val(),
                    email: $("#inputEmail").val(),
                    immat: $immat,
                    marque: $("#selectMarque").val(),
                    modele: $("#selectModele").val(),
                    carburant: $fuel,
                    annee: $("#inputAnnee").val(),
                    newsletter: $("#newsletter").is(":checked"),
                    creneau: $slot,
                },
                success: function (response) {
                    let doneBlock = $('#bootstrap-wizard-tab4');
                    let step2Block = $('#bootstrap-wizard-tab2');
                    let slotStep = $("#slotStep");
                    let doneStep = $("#doneStep");
                    let prevStep = $('#prev-btn')
                    let nextStep = $('#next-btn')
                    if (response["status"] === 1) {

                        /*Swal.fire({
                            title: 'Votre rendez-vous est confirmé !',
                            width: 600,
                            confirmButtonColor: "#4BBF73",
                            confirmButtonText: "OK!",

                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.replace("index.html");
                            }
                        });*/
                        step2Block.removeClass('active show')
                        doneBlock.addClass('active show')
                        doneStep.attr('aria-selected','true');
                        doneStep.addClass('active')
                        slotStep.removeClass('active')
                        slotStep.attr('aria-selected','false')
                        $('.renew').removeClass('d-none')
                        nextStep.attr('hidden','true')
                        prevStep.attr('hidden','true')
                        $('.pager').addClass('justify-content-center')
                    } else {
                        Swal.fire({
                            title: "Erreur",
                            text: response["msg"],
                            icon: "error",
                            showCancelButton: true,
                            showConfirmButton: false,
                            cancelButtonColor: "rgba(255, 163, 71, 0.9)",
                            cancelButtonText: "J'essaie encore!",
                        });
                    }
                },

                error: function(jqxhr,textStatus,errorThrown)
                    {
                        console.log(jqxhr);
                            console.log(textStatus);
                            console.log(errorThrown);
                    }

            });
        }
    });
};
