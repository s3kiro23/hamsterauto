class ModalCar extends HTMLElement {
    constructor() {
        super();
    }

    connectedCallback() {
        this.innerHTML = `
            <div id="modalCar" class="modal fade w-100">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-primary bg-opacity-10">
                            <h5 class="modal-title">Informations du véhicule</h5>
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
                            <div class="col-4 d-flex flex-column gap-3">
                                <div>
                                    <span class="fw-bold font-italic text-gray-700 white:text-dark">Marque :</span>
                                    <span id="modal-Marque" class="pl-4 fst-italic">Marque</span>
                                </div>
                                <div>
                                    <span class="fw-bold text-gray-700 white:text-dark">Modèle :</span>
                                    <span id="modal-Modele" class="pl-4 fst-italic">Modèle</span>
                                </div>
                                <div>
                                    <span class="fw-bold text-gray-700 white:text-dark">Immat :</span>
                                    <span id="modal-Immat" class="pl-4 fst-italic">Immat</span>
                                </div>
                                <div>
                                    <span class="fw-bold text-gray-700 white:text-dark">Année :</span>
                                    <span id="modal-Annee" class="pl-4 fst-italic">Année</span>
                                </div>
                                <div>
                                    <span class="fw-bold text-gray-700 white:text-dark">Carburant :</span>
                                    <span id="modal-Carburant" class="pl-4 fst-italic">Carburant</span>
                                </div>
                                <div>
                                    <span class="fw-bold text-gray-700 white:text-dark">Infos particulières :</span>
                                    <span id="modal-Infos" class="pl-4 fst-italic">Infos</span>
                                </div>
                            </div>
                            <div class="col d-flex justify-content-center align-items-center" id="modal-CG">
                                <!--<span class="fw-bold text-gray-700 white:text-dark">Carte Grise du véhicule :</span>-->
                                <!--<div id="modal-CG" class="bg-primary cg-Size"></div>-->
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

customElements.define("modal-car", ModalCar);

let modalCar = function (){
    $("#modalCar").modal("show");
}