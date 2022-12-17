$(function () {
    generateNavbar();
    generateDate();
    marquesLoad();
    $("#selectMarque").on('change', modelesLoad)
    $(".form-control").on('change', checkField)
    $('input[name=radioImmat]').on("click", selectedImmatFormat);
    $('.changeStep').on('click', function (event) {
        let step1 = $('#bootstrap-wizard-tab1');
        let step2 = $('#bootstrap-wizard-tab2');
        let prevStep = $('#prev-btn')
        let nextStep = $('#next-btn')
        let slotStep = $("#slotStep");
        let slotCar = $("#carStep");

        if(event.target.id === "next-btn"){
            step1.removeClass('active show')
            step2.addClass('active show')
            prevStep.removeAttr('hidden');
            slotStep.attr('aria-selected','true');
            slotStep.addClass('active')
            slotCar.removeClass('active')
            slotCar.attr('aria-selected','false')
            nextStep.html('Réserver!')
            setTimeout(() => {
                nextStep.attr('type', 'submit')
            }, 100);
        } else {
            step2.removeClass('active show')
            step1.addClass('active show')
            slotCar.attr('aria-selected','true')
            slotCar.addClass('active')
            slotStep.removeClass('active')
            slotStep.attr('aria-selected','false')
            prevStep.attr('hidden', 1);
            nextStep.html('Suivant')
            nextStep.attr('type', 'button')
        }
    })
    if ($('#bootstrap-wizard-tab1').hasClass('active')) {
        $('#prev-btn').attr('hidden', 1);
    }
});
toastMixin = Swal.mixin({
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
let newRDVDashboardClient = function () {

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
        title: 'Confirmez vous la demande de rendez-vous ?',
        text: "",
        imageUrl: '../public/assets/img/swalicons/interro.png',
        imageWidth: 100,
        showCancelButton: true,
        confirmButtonColor: '#62C462',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Non',
        confirmButtonText: 'Oui'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '../src/Controller/rdvController.php',
                dataType: 'JSON',
                type: 'POST',
                data: {
                    request: 'newRDVDashboardClient',
                    immat: $immat,
                    marque: $('#selectMarque').val(),
                    modele: $('#selectModele').val(),
                    carburant: $fuel,
                    annee: $('#inputAnnee').val(),
                    creneau: $slot,
                    newsletter: $('#newsletter').is(':checked'),
                },
                success: function (response) {
                    let doneBlock = $('#bootstrap-wizard-tab4');
                    let step2Block = $('#bootstrap-wizard-tab2');
                    let slotStep = $("#slotStep");
                    let doneStep = $("#doneStep");
                    let prevStep = $('#prev-btn')
                    let nextStep = $('#next-btn')
                    if (response['status'] === 1) {
                        /*toastMixin.fire({
                            animation: true,
                            title: 'Le rendez-vous a été pris'
                          });
                        setTimeout(() => {
                            window.location.replace('client-dashboard.html')
                        }, 2000);*/
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
                        toastMixin.fire({
                            animation: true,
                            title: response["msg"],
                            icon: 'error',
                          });
                    }
                },

                error: function () {
                    console.log('errorsnewAppointment');
                }
            })
        }
    })
}



