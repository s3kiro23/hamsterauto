class ModalCVisite extends HTMLElement {
    constructor() {
        super();
    }

    connectedCallback() {
        this.innerHTML = `
            <div id="modalContreVisite" class="modal fade">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-primary bg-opacity-10">
                            <h5 class="modal-title">Procès-verbal du CT n°</h5>
                            <span id="modal-contreID"></span>
                            <button
                                    type="button"
                                    class="btn-close"
                                    data-bs-dismiss="modal"
                                    aria-label="Close"
                            >
                                <span aria-hidden="true"></span>
                            </button>
                        </div>
                        <div class="modal-body d-flex flex-column gap-2">
                            <div>
                              <span class="fw-bold font-italic text-gray-700 white:text-dark"
                              >
                              </span>
                                <div id="rapportInter"></div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button
                                    type="button"
                                    class="btn btn-secondary rounded px-2 py-1"
                                    data-bs-dismiss="modal"
                            >
                                Fermer
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }
}

customElements.define("modal-cvisite", ModalCVisite);

let modalCVisite = function (response){
    $("#modalContreVisite").modal("show");
    $("#modal-contreID").html(response["rdvID"]);
    $("#rapportInter").html(response["rapport"]);
}