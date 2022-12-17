/*Doc Ready DEBUT*/
$(function () {
    $("#file").on("change", checkType);
});

/*Doc Ready FIN*/

/*Fonctions de gestion d'une fiche véhicule DEBUT*/
function uploadCG() {
    let data = new FormData($("form")[0]);

    $.ajax({
        url: "../src/Controller/uploadController.php",
        type: "POST",
        dataType: "JSON",
        enctype: "multipart/form-data",
        data: data,
        cache: false,
        contentType: false,
        processData: false,

        success: function (response) {
            $("#modalCG").modal("hide");
            Swal.fire({
                position: "center",
                icon: "success",
                class: "iziToast-bold",
                title: response["msg"],
                showConfirmButton: false,
                timer: 1500,
            });
        },
        error: function () {
            console.log("errorUpload");
        },
    });
}

/*Vérification du type de fichier upload DEBUT*/
function checkType() {
    let file = this.files[0];
    let fileType = file.type;
    let match = [
        "application/pdf",
        "application/msword",
        "application/vnd.ms-office",
        "image/jpeg",
        "image/png",
        "image/jpg",
    ];
    if (
        !(
            fileType === match[0] ||
            fileType === match[1] ||
            fileType === match[2] ||
            fileType === match[3] ||
            fileType === match[4] ||
            fileType === match[5]
        )
    ) {
        alert(
            "Désolé, seulement les fichiers aux formats DOC, PDF, JPG, JPEG, & PNG sont autorisés pour l'upload."
        );
        $("#file").val("");
        return false;
    }
}

/*Vérification du type de fichier upload FIN*/