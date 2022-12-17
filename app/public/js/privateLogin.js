$(function () {
    sweetToast()
    $('#to-mentions').on("click", modalMentions)
    $('#to-cgu').on("click", modalCGU)
    $(".form-control").on("change", checkField);
    $('[data-toggle="tooltip"]').tooltip();
});

let private_connect = function () {
    $.ajax({
        url: "../src/Controller/index/privateLogin.php",
        dataType: "JSON",
        type: "POST",
        data: {
            request: "connexion_private",
            login: $("#inputLogin").val(),
            password: $("#inputPassword").val(),
        },
        success: function (response) {
            if (response["status"] === 0) {
                
                toastMixin.fire({
                    animation: true,
                    title: response["msg"],
                    icon: 'error',
                    
                });setTimeout(() => {
                    window.location.replace("index.html");
                }, 1500);
            } else if (response["status"] === 2) {
                toastMixin.fire({
                    animation: true,
                    title: response["msg"],
                    icon: 'error',
                });
                $("#content-request").html(response["contentPwdLogin"]);
            } else if (response["status"] === 3) {
                toastMixin.fire({
                    animation: true,
                    title: response["msg"]
                });
                $("#content-request").html(response["contentPwdLogin"]);
            } else {
                toastMixin.fire({
                    animation: true,
                    title: response["msg"]
                });
                if (response["typeUser"] === "technicien") {
                    setTimeout(() => {
                        window.location.replace("back-office.html");
                    }, 1500);
                } 
            }
        },
        error: function () {
            console.log("errID");
        },
    });
};