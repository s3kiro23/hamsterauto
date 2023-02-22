$(function () {
    brandsLoad();
    generateDate();
    IntlTelInput();
    $('.switchLogo').off("click")
    $('.switchLogo').on("click", switchLogo)
    $('#to-mentions').on("click", modalMentions)
    $('#to-cgu').on("click", modalCGU)
    $("#selectMarque").on("change", modelsLoad);
    $("#to_signIn").on("click", toSignIn);
    $("#reload").on("click", reload);
    $('[data-toggle="tooltip"]').tooltip();
    $('input[name=radioImmat]').on("click", selectedImmatFormat);

    // Gestion du wizard sur l'index
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

        if (event.target.id === "new-btn") {
            formTitle.attr('hidden', 1);
            navWizard.removeAttr('hidden');
            footerWizard.removeAttr('hidden');
            hello.removeClass('active')
            step0.addClass('active show')
            $('#prev-btn').attr('hidden', 1);
        } else if (event.target.id === "next-btn") {
            if (step0.hasClass('active')) {
                step0.removeClass('active show')
                idStep.attr('aria-selected', 'false')
                idStep.removeClass('active')
                step1.addClass('active show')
                carStep.attr('aria-selected', 'true');
                carStep.addClass('active show')
                prevStep.removeAttr('hidden');
            } else if (step1.hasClass('active show')) {
                step1.removeClass('active show')
                carStep.attr('aria-selected', 'false')
                carStep.removeClass('active')
                step2.addClass('active show')
                slotStep.attr('aria-selected', 'true');
                slotStep.addClass('active show')
                setTimeout(() => {
                    nextStep.attr('type', 'submit')
                    nextStep.html('Réserver!')
                }, 100);
            }
        } else {
            if (step1.hasClass('active show')) {
                step1.removeClass('active show');
                carStep.attr('aria-selected', 'false');
                carStep.removeClass('active');
                step0.addClass('active show')
                idStep.attr('aria-selected', 'true')
                idStep.addClass('active')
                prevStep.attr('hidden', 1);
            } else if (step2.hasClass('active show')) {
                step2.removeClass('active show');
                slotStep.attr('aria-selected', 'false');
                slotStep.removeClass('active');
                step1.addClass('active show')
                carStep.attr('aria-selected', 'true')
                carStep.addClass('active')
                nextStep.html('Suivant <span class="fas fa-chevron-right ms-2" data-fa-transform="shrink-3"></span>')
                nextStep.attr('type', 'button')
            }
        }
    })
});

function switchLogo() {
    if ($('#logoIndex').attr('src') === "../public/assets/img/animated-icons/hamsterauto-unscreen.gif") {
        $('#logoIndex').attr('src', "../public/assets/img/animated-icons/hamsterautoNuit-unscreen.gif")
    } else if ($('#logoIndex').attr('src') === "../public/assets/img/animated-icons/hamsterautoNuit-unscreen.gif") {
        $('#logoIndex').attr('src', "../public/assets/img/animated-icons/hamsterauto-unscreen.gif")
    }
}

let toSignIn = function () {

    Swal.fire({
        title: "Redirection vers la page d'inscription",
        imageUrl: '/public/assets/img/swalicons/spinner.gif',
        imageWidth: 220,
        imageHeight: 220,
        allowEscapeKey: false,
        allowOutsideClick: false,
        showCancelButton: false,
        showConfirmButton: false,
        timer: 2000,
    })
    setTimeout(() => {
        window.location.replace("/inscription");
    }, 2000);
};

let newRDVHomePage = function () {
    let tabRdvValues = {};
    $('input[name=optionsCarbu]:checked').val() ? tabRdvValues['fuel'] = $('input[name=optionsCarbu]:checked').val() : tabRdvValues['fuel'] = "";
    tabRdvValues['civilite'] = $('input[name=optionsCivilite]:checked').val();
    tabRdvValues['timeSlot'] = $("input[name=timeSlot]:checked").attr("id");
    tabRdvValues['registration'] = $('#inputImmatOld').val();
    if ($('input[name=radioImmat]:checked').val() === "newImmat") {
        tabRdvValues['registration'] = $('#inputImmatNew').val();
    }
    $('.fieldRdv').each(function () {
        tabRdvValues[$(this).attr('id')] = $(this).val();
    });
    Swal.fire({
        title: "Confirmez-vous la demande de rendez-vous ?",
        text: "",
        imageUrl: '/public/assets/img/swalicons/interro.png',
        imageWidth: 100,
        showCancelButton: true,
        confirmButtonColor: "#4BBF73",
        cancelButtonColor: "#d33",
        confirmButtonText: "Oui",
        cancelButtonText: "Annuler",
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "/src/Controller/Index/NewRdvHomePage.php",
                dataType: "JSON",
                type: "POST",
                data: {
                    request: "newRDVHomePage",
                    data: JSON.stringify(tabRdvValues)
                },
                success: function (response) {
                    if (response["status"] === 1) {
                        $('#bootstrap-wizard-tab2').removeClass('active show')
                        $('#bootstrap-wizard-tab4').addClass('active show')
                        $("#doneStep").attr('aria-selected', 'true');
                        $("#doneStep").addClass('active')
                        $("#slotStep").removeClass('active')
                        $("#slotStep").attr('aria-selected', 'false')
                        $('.renew').removeClass('d-none')
                        $('#next-btn').attr('hidden', 'true')
                        $('#prev-btn').attr('hidden', 'true')
                        $('.pager').addClass('justify-content-center')
                    } else if(response["status"] === 0) {
                        Swal.fire({
                            title: "Erreur",
                            text: response["msg"],
                            icon: "error",
                            showCancelButton: true,
                            showConfirmButton: false,
                            cancelButtonColor: "rgba(255, 163, 71, 0.9)",
                            cancelButtonText: "J'essaie encore!",
                        });
                    }else{
                        toastMixin.fire({
                            animation: true,
                            title: response["msg"],
                            icon : "error"
                        });
                        setTimeout(() => {location.reload()}, 1500);
                    }
                },
                error: function (jqxhr, textStatus, errorThrown) {
                    console.log(jqxhr);
                    console.log(textStatus);
                    console.log(errorThrown);
                }
            });
        }
    });
};
