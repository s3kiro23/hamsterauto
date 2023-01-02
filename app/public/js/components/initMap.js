$(function () {
    captcha();
    $('#to-mentions').on("click", modalMentions)
    $('#to-cgu').on("click", modalCGU)
    $(".form-control").on("change", checkField);
    $('#to_logIn').on('click', to_logIn);
});


function initMap(callback) {
    $.ajax({
        url: "../src/Controller/contactUsController.php",
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
                "info": data['contentBIA']
            },
            "aflo_aja": {
                "lat": data['coordinates'].aflo_aja.lat,
                "lng": data['coordinates'].aflo_aja.lng,
                "info": data['contentAJA']
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

window.initMap = initMap;

let contactUS = function () {
    let tabInput = [];
    $('.form-control').each(function () { //loop through each checkbox
        tabInput.push($(this).val());
    });
    $.ajax({
        url: '../src/Controller/contactUsController.php',
        dataType: 'JSON',
        type: 'POST',
        data: {
            request: 'contact-form',
            tabInput: JSON.stringify(tabInput),
            captcha: $('#captcha').html(),
            rgpd: $('#rgpd').prop('checked')
        },
        success: function (response) {
            if (response['status'] === 1) {
                $('.sending').html(response['msg'])
                $('#to_logIn').on('click', to_logIn);
            } else {
                Swal.fire({
                    position: 'top',
                    icon: 'error',
                    title: response['msg'],
                    showConfirmButton: false,
                    timer: 2500
                })
            }
        },
        error: function () {
            console.log(3);
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
    window.location.replace('/')
};


