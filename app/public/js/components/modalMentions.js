class ModalMentions extends HTMLElement {
    constructor() {
        super();
    }

    connectedCallback() {
        this.innerHTML = `
            <div id="modal-mentions" class="modal fade">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-primary bg-opacity-10">
                            <h5 class="modal-title">Mentions légales</h5>
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
                                        <b>1) Editeur du site :</b><br>
                                        Dénomination sociale : HAMSTERAUTO SERVICES FRANCE<br>
                                        Forme juridique : Société par Actions Simplifiée<br>
                                        Capital social : 7 €<br>
                                        Siège social : ?<br>
                                        RCS : ?<br>
                                        N° TVA Intracommunautaire : ?<br>
                                        Profession réglementée : Organisateur de l’Epreuve Théorique Générale du Permis de Conduire (Articles L221-4, L221-7 et R221-3-4 à R221-3-17 du Code de la Route)<br>
                                        Autorisation : Arrêté du Ministère de l’Intérieur du 08/02/2017, publié au JO du 18/02/2017<br>
                                        <br>
                                        <b>2) Responsable de la publication :</b><br>
                                        Identité : Team FromScratch<br>
                                        Numéro de téléphone : XX XX XX XX XX <br>
                                        Adresse de courrier électronique : contact@hamsterauto.com<br>
                                        <br>
                                        <b>3) Hébergeur du site : OVH France</b><br>
                                        Siège social : 2 rue Kellermann - 59100 Roubaix - France<br>
                                        RCS Lille Métropole 424 761 419 00045<br>
                                        Code APE 2620Z<br>
                                        SAS au capital de 10 174 560 €<br>
                                        RCS : Lille Métropole – SIRET : 424 761 419 00045<br>
                                        TVA : FR 22 424 761 419<br>
                                        <br>
                                        <b>4) Autres renseignements : </b><br>
                                        N° de déclaration simplifiée CNIL : ?<br>
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

customElements.define("modal-mentions", ModalMentions);

let modalMentions = function () {
    $("#modal-mentions").modal("show");
}
