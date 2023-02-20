class ModalCG extends HTMLElement {
    constructor() {
        super();
    }

    connectedCallback() {
        this.innerHTML = `
            <div id="modalCG" class="modal fade">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-primary bg-opacity-10">
                            <h5 class="modal-title">Import d'une carte grise</h5>
                            <button type="button"
                                    class="btn-close"
                                    data-bs-dismiss="modal"
                                    aria-label="Close">
                                <span aria-hidden="true"></span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <span id="modal-carID" hidden data-id=""></span>
                            <form id="uploadForm"
                                  action="javascript:uploadCG();"
                                  method="POST"
                                  class="mt-4 flex flex-column">
                                <input name="file"
                                       value="test"
                                       type="file"
                                       id="file"
                                       class="mb-3"/>
                                <input id="submitBtn"
                                       class="d-flex justify-content-center btn bg-primary rounded text-white p-2 w-100 mt-5"
                                       type="submit"
                                       value="Upload"/>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }
}

customElements.define("modal-cg", ModalCG);

let modalCG = function (){
    $.ajax({
        url: '../src/Controller/DashboardClient/ClientController.php',
        dataType: "JSON",
        type: "POST",
        data: {
            request: "modalUploadCG",
            carID: $(this).data("id")
        },
        success: function () {
            $("#modalCG").modal("show");
        },
        error: function () {
        },
    });
}