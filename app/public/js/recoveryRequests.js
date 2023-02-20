$(function () {
    $("#toRequestMail").on("click", toRequestMail);
});

let newPwd = function () {
    let tabInput = {};
    $('.field').each(function () {
        tabInput[this.id] = $("#" + this.id).val();
    });
    $.ajax({
        url: "../src/Controller/Index/LoginController.php",
        dataType: "JSON",
        type: "POST",
        data: {
            request: "newPwd",
            tabInput: JSON.stringify(tabInput)
        },
        success: function (response) {
            if (response["status"] === 1) {
                $('#newPwd').prop('disabled', true);
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
        },
    });
};

let toRequestMail = function () {
    $.ajax({
        url: "../src/Controller/Index/RecoveryController.php",
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
            $('#to-mentions').on("click", modalMentions);
            $(".form-control").on("change", checkField);
            $(".form-control").on("click", placeholderAnimation);
            $(".form-control").on("focusout", placeholderAnimation);
        },

        error: function () {
        },
    });
};

let genToken = function () {
    $.ajax({
        url: "../src/Controller/Index/RecoveryController.php",
        dataType: "JSON",
        type: "POST",
        data: {
            request: "genToken",
            mail: $("#inputEmail").val(),
        },
        success: function (response) {
            if (response["status"] === 1) {
                toastMixin.fire({
                    animation: true,
                    position: 'top',
                    title: response["msg"]
                });
                $("#mail-sending").html(response["htmlMail"]);
                $('#to-mentions').on("click", modalMentions)
                $("#reload").on("click", reload);
            } else {
                toastMixin.fire({
                    animation: true,
                    icon: "warning",
                    position: 'top',
                    width: 500,
                    title: response["msg"]
                });
            }
        },
        error: function () {
        },
    });
};