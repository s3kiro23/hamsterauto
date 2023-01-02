$(function () {
    $("#toRequestMail").on("click", toRequestMail);
});

let newPwd = function () {
    $.ajax({
        url: "../src/Controller/index/loginController.php",
        dataType: "JSON",
        type: "POST",
        data: {
            request: "newPwd",
            user: $("#user").val(),
            password: $("#password").val(),
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
                setTimeout(() => {
                    window.location.replace("/");
                }, 1500);
            } else {
                Swal.fire({
                    position: 'top',
                    title: "Non, non, non...",
                    text: response["msg"],
                    icon: "warning",
                    showCancelButton: true,
                    showConfirmButton: false,
                    cancelButtonColor: "#3085d6",
                    cancelButtonText: "Laisse moi encore essayer !",
                });
            }
        },

        error: function () {
            console.log("errNewPwd");
        },
    });
};

let toRequestMail = function () {
    $.ajax({
        url: "../src/Controller/index/recoveryController.php",
        dataType: "JSON",
        type: "POST",
        data: {
            request: "toRequestMail",
        },
        success: function (response) {
            toastMixin.fire({
                animation: true,
                timer: 1500,
                position: 'top',
                title: response["msg"]
            });
            $("#content-request").html(response["contentForgot"]);
            $('#to-mentions').on("click", modalMentions)
        },

        error: function () {
            console.log("errToRequestMail");
        },
    });
};

let genToken = function () {
    $.ajax({
        url: "../src/Controller/index/recoveryController.php",
        dataType: "JSON",
        type: "POST",
        data: {
            request: "genToken",
            mail: $("#email").val(),
        },
        success: function (response) {
            if (response["status"] === 1) {
                toastMixin.fire({
                    animation: true,
                    position: 'top',
                    title: response["msg"]
                });
                /*$('#sendToken').prop("disabled", true);
                setTimeout(() => {
                    window.location.replace("index.html");
                }, 3000);*/
                $("#mail-sending").html(response["htmlMail"]);
                $('#to-mentions').on("click", modalMentions)
                $("#reload").on("click", reload);
            } else {
                toastMixin.fire({
                    animation: true,
                    icon: "error",
                    title: response["msg"]
                });
            }
        },
        error: function () {
            console.log("errGenToken");
        },
    });
};

/*let tokenLink = function () {
    $.ajax({
        url: "../src/Controller/index/recoveryController.php",
        dataType: "JSON",
        type: "POST",
        data: {
            request: "tokenLink",
        },
        success: function () {
            window.location.replace(
                "change-password.html?token=" + $("#tokenLink").html()
            );
        },

        error: function () {
            console.log("errTokenLink");
        },
    });
};*/