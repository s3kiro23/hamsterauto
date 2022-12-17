class ModalProfil extends HTMLElement {
    constructor() {
        super();
    }

    connectedCallback() {
        this.innerHTML = `
            <div id="modal-profil" class="modal fade">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-primary bg-opacity-10">
                            <h5 class="modal-title">Modification de vos informations</h5>
                            <span id="modal-rdvID"></span>
                            <button
                                type="button"
                                class="btn-close"
                                data-bs-dismiss="modal"
                                aria-label="Close"
                            >
                                <span aria-hidden="true"></span>
                            </button>
                        </div>
                        <form action="javascript:modify();">
                            <div class="modal-body d-flex flex-row">
                                <div id="modal-body">
                                    <div class="row gap-3">
                                        <div class="col-12 d-flex flex-column">
                                            <label class="fw-bold">Email / login</label>
                                            <input id="modal-mail_user" type="text" class="data-modal text-muted ps-2">
                                        </div>
                                        <div class="col-6">
                                            <div class="d-flex flex-column">
                                                <label class="fw-bold">Nom</label>
                                                <input id="modal-nom_user" type="text" class="data-modal text-muted ps-2">
                                            </div>
                                            <div class="d-flex flex-column my-2">
                                                <label class="fw-bold">Prénom</label>
                                                <input id="modal-prenom_user" type="text" class="data-modal text-muted ps-2">
                                            </div>
                                            <div class="d-flex flex-column">
                                                <label class="fw-bold">Téléphone</label>
                                                <input id="modal-tel_user" type="tel" placeholder="" class="data-modal text-muted ps-5">
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="d-flex flex-column">
                                                <label class="fw-bold">Adresse</label>
                                                <textarea id="modal-addr" class="data-modal text-muted ps-2"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button
                                    type="button"
                                    class="btn btn-outline-secondary border-0 rounded px-2 py-1"
                                    data-bs-dismiss="modal"
                                >
                                    Annuler
                                </button>
                                <button
                                    type="submit"
                                    class="btn btn-primary rounded px-2 py-1"
                                    data-bs-dismiss="modal"
                                >
                                    Enregistrer
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        `;
    }
}

customElements.define("modal-profil", ModalProfil);
