$(function () {
    captcha();
    $('#to-mentions').on("click", modalMentions)
    $('#to-cgu').on("click", modalCGU)
    $(".form-control").on("change", checkField);
    $(".form-control").on("focusin", placeholderAnimation);
    $(".form-control").on("focusout", placeholderAnimation);
    $('#to_logIn').on('click', to_logIn);
    
});


function configMap(callback) {
    $.ajax({
        url: "/src/Controller/ContactUsController.php",
        dataType: "JSON",
        type: "POST",
        data: {
            request: "getTimes",
        },
    }).done(function (data) {
        let villes = {
            "aflo_bia": {
                "lat": data['coordinates'].aflo_bia.lat,
                "lng": data['coordinates'].aflo_bia.lng,
                "info": data['content']['aflo_bia']
            },
            "aflo_aja": {
                "lat": data['coordinates'].aflo_aja.lat,
                "lng": data['coordinates'].aflo_aja.lng,
                "info": data['content']['aflo_aja']
            },
        }

        const map = new google.maps.Map(document.querySelector("#map"), {
            center: new google.maps.LatLng(42.14019896633072, 9.148053973765355),
            zoom: 8,
        });

        for (let ville in villes) {
            let marker = new google.maps.Marker({
                position: {lat: villes[ville].lat, lng: villes[ville].lng},
                map: map,
                description: {info: villes[ville].info}
            })
            const contentString = marker.description;
            const infowindow = new google.maps.InfoWindow({
                content: contentString.info,
            });
            marker.addListener("click", () => {
                infowindow.open({
                    anchor: marker,
                    map,
                    shouldFocus: false,
                });
            });
        }
    });
}

window.initMap = configMap;

let contactUS = function () {
    let tabInput = {};
    tabInput['captcha'] = $('#captcha').html();
    tabInput['checkRGPD'] = $('#rgpd').prop('checked');
    $('.form-control').each(function () {
        tabInput[$(this).attr('id')] = $(this).val();
    });
    $.ajax({
        url: '/src/Controller/ContactUsController.php',
        dataType: 'JSON',
        type: 'POST',
        data: {
            request: 'contact-form',
            tabInput: JSON.stringify(tabInput),
        },
        success: function (response) {
            if (response['status'] === 1) {
                $('.sending').html(response['msg'])
                $('#to_logIn').on('click', to_logIn);
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
        error: function () {
        }
    });

}

function captcha() {
    $.ajax({
        url: '/src/Controller/Index/SignInController.php',
        dataType: 'JSON',
        type: 'POST',
        data: {
            request: 'captcha',
        },
        success: function (response) {
            $("#captcha").html(response['get_captcha']);
        },
        error: function () {
        }
    });
}

let to_logIn = function () {
    // window.location.replace('index.html')
    history.back()
};


