class ModalRecoPwd extends HTMLElement {
    constructor() {
        super();
    }

    connectedCallback() {
        this.innerHTML = `
            <div id="modal-reco-pwd" class="modal fade">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-primary bg-opacity-10">
                            <h5 class="modal-title">Recommandations mot de passe</h5>
                            <button
                                type="button"
                                class="btn-close"
                                data-bs-dismiss="modal"
                                aria-label="Close"
                            >
                                <span aria-hidden="true"></span>
                            </button>
                        </div>
                        <div class="modal-body d-flex flex-row">
                            <div id="modal-body">
                                <span>Selon préconisation de l'ANSSI (Agence National de la Sécurité des Systèmes d'Infomation :</span><br><br>
                                <p class="fst-italic">
                                    - minimum 12 caractères<br>
                                    - au moins une Majuscule/minuscule<br>
                                    - des chiffres<br>
                                    - des caractères spéciaux (!@%#&*/$)
                                </p>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button
                                type="button"
                                class="btn btn-primary rounded px-2 py-1"
                                data-bs-dismiss="modal">
                                OK
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }
}

customElements.define("modal-reco-pwd", ModalRecoPwd);

let modalRecoPwd = function () {
    $("#modal-reco-pwd").modal("show");
}
