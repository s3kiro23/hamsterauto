class ModalFormCar extends HTMLElement {
    constructor() {
        super();
    }

    connectedCallback() {
        this.innerHTML = `
            <div id="modalFormCar" class="modal fade">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-primary bg-opacity-10">
                            <h5 class="modal-title">Ajout d'un nouveau véhicule</h5>
                            <span id="modal-addID" data-id="" hidden></span>
                            <button
                                    type="button"
                                    class="btn-close"
                                    data-bs-dismiss="modal"
                                    aria-label="Close">
                                <span aria-hidden="true"></span>
                            </button>
                        </div>
                        <div class="modal-body d-flex flex-column gap-2">
                            <form id="bodyFormCar" action="javascript:addCar();" method="POST">
                                Erreur, veuillez recharger la page
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }
}

customElements.define("modal-form-car", ModalFormCar);

let modalModifyCar = function () {
    $.ajax({
        url: "../src/Controller/DisplayHTML/FormsDisplayController.php",
        dataType: "JSON",
        type: "POST",
        data: {
            request: "formModifyCar",
            idCar: $(this).data("id")
        },
        success: function (response) {
            $("#modalFormCar").modal("show");
            $("#bodyFormCar").html(response['html'])
            $(".modal-title").html('Modification de votre véhicule :')
            $("#modal-addID").attr('data-id', response['data']['idCar'])
            $("#inputYear").val(response['data']['year'])
            $("#inputImmatNew").val(response['data']['registration'])
            $("#bodyFormCar").attr('action', "javascript:modifyCar();")
            $('#selectMarque').on("change", selectedCar)
            $('#selectMarque').select2();
            $('#selectedModel').select2();
            $('select:not(.normal)').each(function () {
                $(this).select2({
                    dropdownParent: $(this).parent()
                });
            });
            $("#inputImmatNew").on("keyup", checkNewValueRegEx)
            $("#inputImmatOld").on("keyup", checkOldValueRegEx)
            $("#inputYear").on("keyup", checkOldValueYear)
            $(".form-control").on('change', checkField)
            $('input[name=radioImmat]').on("click", selectedImmatFormat);
        },
        error: function () {
        },
    });
}

let modalAddCar = function (response) {
    $("#modalFormCar").modal("show");
    $("#bodyFormCar").html(response['html'])
    $('#selectMarque').on("change", selectedCar)
    $("#inputImmatNew").on("keyup", checkNewValueRegEx)
    $("#inputImmatOld").on("keyup", checkOldValueRegEx)
    $("#inputYear").on("keyup", checkOldValueYear)
    $(".form-control").on('change', checkField)
    $(".form-control").on("click", placeholderAnimation);
    $(".form-control").on("focusout", placeholderAnimation);
    $('input[name=radioImmat]').on("click", selectedImmatFormat);
}