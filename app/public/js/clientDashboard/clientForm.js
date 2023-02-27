$(function () {
    generateNavbar();
    generateDate();
    brandsLoad();
    $("#selectMarque").on('change', modelsLoad)
    $('#selectMarque').select2();
    $('#selectedModel').select2();
    $(".form-control").on('change', checkField)
    $('input[name=radioImmat]').on("click", selectedImmatFormat);
    $('.changeStep').on('click', function (event) {
        let step1 = $('#bootstrap-wizard-tab1');
        let step2 = $('#bootstrap-wizard-tab2');
        let prevStep = $('#prev-btn')
        let nextStep = $('#next-btn')
        let slotStep = $("#slotStep");
        let slotCar = $("#carStep");

        if (event.target.id === "next-btn") {
            step1.removeClass('active show')
            step2.addClass('active show')
            prevStep.removeAttr('hidden');
            slotStep.attr('aria-selected', 'true');
            slotStep.addClass('active')
            slotCar.removeClass('active')
            slotCar.attr('aria-selected', 'false')
            nextStep.html('Réserver!')
            setTimeout(() => {
                nextStep.attr('type', 'submit')
            }, 100);
        } else {
            step2.removeClass('active show')
            step1.addClass('active show')
            slotCar.attr('aria-selected', 'true')
            slotCar.addClass('active')
            slotStep.removeClass('active')
            slotStep.attr('aria-selected', 'false')
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

let newRDVFormClient = function () {
    let tabInput = {};
    tabInput['fuel'] = $('input[name=optionsCarbu]:checked').val();
    $('input[name=optionsCarbu]:checked').val() ? tabInput['fuel'] = $('input[name=optionsCarbu]:checked').val() : tabInput['fuel'] = "";
    tabInput['timeSlot'] = $("input[name=timeSlot]:checked").attr("id");
    tabInput['registration'] = $('#inputImmatOld').val();
    if ($('input[name=radioImmat]:checked').val() === "newImmat") {
        tabInput['registration'] = $('#inputImmatNew').val();
    }
    $('.field').each(
        function () {
            tabInput[$(this).attr('id')] = $(this).val();
        });
    Swal.fire({
        title: 'Confirmez vous la demande de rendez-vous ?',
        text: "",
        imageUrl: '/public/assets/img/swalicons/interro.png',
        imageWidth: 100,
        showCancelButton: true,
        confirmButtonColor: '#62C462',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Non',
        confirmButtonText: 'Oui'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '/src/Controller/RdvController.php',
                dataType: 'JSON',
                type: 'POST',
                data: {
                    request: 'newRDVDashboardClient',
                    data: JSON.stringify(tabInput)
                },
                success: function (response) {
                    let doneBlock = $('#bootstrap-wizard-tab4');
                    let step2Block = $('#bootstrap-wizard-tab2');
                    let slotStep = $("#slotStep");
                    let doneStep = $("#doneStep");
                    let prevStep = $('#prev-btn')
                    let nextStep = $('#next-btn')
                    if (response['status'] === 1) {
                        step2Block.removeClass('active show')
                        doneBlock.addClass('active show')
                        doneStep.attr('aria-selected', 'true');
                        doneStep.addClass('active')
                        slotStep.removeClass('active')
                        slotStep.attr('aria-selected', 'false')
                        $('.renew').removeClass('d-none')
                        nextStep.attr('hidden', 'true')
                        prevStep.attr('hidden', 'true')
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
                }
            })
        }
    })
}



