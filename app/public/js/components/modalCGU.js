class ModalCGU extends HTMLElement {
    constructor() {
        super();
    }

    connectedCallback() {
        this.innerHTML = `
            <div id="modal-cgu" class="modal fade">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-primary bg-opacity-10">
                            <h5 class="modal-title">CGU - Traitement des données personnelles</h5>
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
                                <div>
                                    <p>
                                        Les données personnelles sont traitées par l'entreprise Protechnologies Systems. <br>
                                        Les messages électroniques envoyés et les adresses électroniques utilisées pour l'envoi d'informations complémentaires sont susceptibles d'être conservées. <br><br>
                                        Lorsqu'un utilisateur effectue une réservation ou une demande de contact, il donne son accord pour le traitement de ses informations en cochant la case précisant cette mention.<br>
                                        Les informations recueillies pourront faire l'objet d'un traitement informatique destiné à vous contacter. <br><br>
                                        Conformément à la loi « informatique et libertés » du 6 janvier 1978 modifiée en 2004, vous bénéficiez d'un droit d'accès et de rectification aux informations qui vous concernent, 
                                        que vous pouvez exercer en vous adressant par courrier postal à Protechnologies Systems, 10 avenue Henri Zanaroli, 74600 Seynod.<br><br>
                                        Si vous estimez que vos droits concernant le traitement des données n'ont pas été respectés, vous avez la possibilité de déposer plainte auprès de la CNIL. <br>
                                        Vous pouvez également, pour des motifs légitimes, vous opposer au traitement des données vous concernant.
                                    </p>
                                </div>
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

customElements.define("modal-cgu", ModalCGU);

let modalCGU = function (){
    $("#modal-cgu").modal("show");
}