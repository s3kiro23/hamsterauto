class ModalAddRDV extends HTMLElement {
    constructor() {
        super();
    }

    connectedCallback() {
        this.innerHTML = `
            <div id="modalAddRDV" class="modal fade">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-primary bg-opacity-10">
                            <h5 class="modal-title-rdv">Nouveau rendez-vous</h5>
                            <span id="modal-addRdvId"></span>
                            <button
                                    type="button"
                                    class="btn-close"
                                    data-bs-dismiss="modal"
                                    aria-label="Close">
                                <span aria-hidden="true"></span>
                            </button>
                        </div>
                        <div class="modal-body d-flex flex-column gap-2">
                            <form id="bodyAddRDV" action="javascript:addRDV();" method="POST">
                                Erreur, veuillez recharger la page
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }
}

customElements.define("modal-form-rdv", ModalAddRDV);

let modalFormAddRDV = function (response) {
    $("#modalAddRDV").modal("show");
    $("#bodyAddRDV").html(response['html'])
    generateDate();
}