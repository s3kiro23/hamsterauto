class ModalRdvInfo extends HTMLElement {
    constructor() {
        super();
    }

    connectedCallback() {
        this.innerHTML = `
            <div id='modalRdvInfo' class='modal fade'>
                <div class='modal-dialog modal-lg' role='document'>
                    <div class='modal-content'>
                        <div class='modal-header'>
                            <h5 class='modal-title'>Infos client liées à l'inter n°</h5>
                            <span id='modal-rdvID'></span>
                            <button
                                    type='button'
                                    class='btn-close'
                                    data-bs-dismiss='modal'
                                    aria-label='Close'>
                                <span aria-hidden='true'></span>
                            </button>
                        </div>
                        <div class='modal-body d-flex flex-row'>
                            <div class='col-4 d-flex flex-column gap-3' id='modal-body'>
                                <div>
                                    <span class='fw-bold font-italic text-gray-700 white:text-dark'>Nom :</span>
                                    <span id='modal-nom_user' class='pl-4 fst-italic'>Nom client</span>
                                </div>
                                <div>
                                    <span class='fw-bold text-gray-700 white:text-dark'>Prénom :</span>
                                    <span id='modal-prenom_user' class='pl-4 fst-italic'>User ID</span>
                                </div>
                                <div>
                                    <span class='fw-bold text-gray-700 white:text-dark'>Créneau réservé :</span>
                                    <span id='modal-timeslotID' class='pl-4 fst-italic'>Créneau</span>
                                </div>
                                <div>
                                    <span class='fw-bold text-gray-700 white:text-dark'>Téléphone :</span>
                                    <span id='modal-tel_user' class='pl-4 fst-italic'>Tél</span>
                                </div>
                                <div>
                                    <span class='fw-bold text-gray-700 white:text-dark'>Email :</span>
                                    <span id='modal-mail_user' class='pl-4 fst-italic'>Email</span>
                                </div>
                                <div>
                                    <span class='fw-bold text-gray-700 white:text-dark'>RDV confirmé le :</span>
                                    <span id='modal-booked_date' class='pl-4 fst-italic'>Créneau</span>
                                </div>
                            </div>
                            <div class='col d-flex justify-content-center align-items-center' id='modal-CG'></div>
                        </div>
                        <div class='modal-footer'>
                            <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>
                                Fermer
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }
}

customElements.define("modal-rdv-info", ModalRdvInfo);

let modalRdvInfo = function (id) {

    $.ajax({
        url: "../src/Controller/CarController.php",
        dataType: "JSON",
        type: "POST",
        data: {
            request: "showInfo",
            rdvID: id,
        },
        success: function (response) {
            $("#modalRdvInfo").modal("show");
            $("#modal-rdvID").html(response["rdvID"]);
            $("#modal-timeslotID").html(response["timeslotID"]);
            $("#modal-booked_date").html(response["booked_date"]);
            $("#modal-nom_user").html(response["lastname_user"]);
            $("#modal-prenom_user").html(response["firstname_user"]);
            $("#modal-tel_user").html(response["phone_user"]);
            $("#modal-mail_user").html(response["mail_user"]);
            if (!response["CG"]) {
                $("#modal-CG").html("Aucune carte grise n'est associée à ce véhicule.");
            } else {
                $("#modal-CG").html(response["CG"]);
            }
        },
        error: function () {
        },
    });
}