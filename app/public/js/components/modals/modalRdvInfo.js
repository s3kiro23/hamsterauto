class ModalRdvInfo extends HTMLElement {
    constructor() {
        super();
    }

    connectedCallback() {
        this.innerHTML = `
            <div id='modalRdvInfo' class='modal fade'>
                <div class='modalRdvInfo modal-dialog modal-lg' role='document'>
                    <div class='modal-content'>
                        <div class='modal-header'>
                            <h5 class='modal-title'>Informations client liées à l'intervention n° <span id='modal-rdvID'></span></h5>
                            
                            <button
                                    type='button'
                                    class='btn-close'
                                    data-bs-dismiss='modal'
                                    aria-label='Close'>
                                <span aria-hidden='true'></span>
                            </button>
                        </div>
                        <div class='modal-body'>
                            <div class="row">
                                <div class='col-12 col-md-6 d-flex flex-column gap-3' id='modal-body'>
                                    <span class='fw-bold font-italic text-gray-700 white:text-dark'>Nom : <span id='modal-nom_user' class='pl-4 fst-italic fw-normal'>Nom client</span></span>
                                    <span class='fw-bold text-gray-700 white:text-dark'>Prénom : <span id='modal-prenom_user' class='pl-4 fst-italic fw-normal'>User ID</span></span>
                                    <span class='fw-bold text-gray-700 white:text-dark'>Créneau réservé : <span id='modal-timeslotID' class='pl-4 fst-italic fw-normal'>Créneau</span></span>
                                    <span class='fw-bold text-gray-700 white:text-dark'>Téléphone : <span id='modal-tel_user' class='pl-4 fst-italic fw-normal'>Tél</span></span>
                                    <span class='fw-bold text-gray-700 white:text-dark'>Email : <span id='modal-mail_user' class='pl-4 fst-italic fw-normal'>Email</span></span>
                                    <span class='fw-bold text-gray-700 white:text-dark'>RDV confirmé le : <span id='modal-booked_date' class='pl-4 fst-italic fw-normal'>Créneau</span></span>
                                </div>
                                <div class='col-12 col-md-6 d-flex justify-content-center align-items-center mt-3 mt-md-0' id='modal-CG'></div>
                            </div>
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

let resizeModalInfo = function () {
	// Permet d'adapter la modal info des rdv au mobile
	if ($(window).width() < 768) {
		$(".modalRdvInfo").removeClass("modal-lg")
	} else {
		$(".modalRdvInfo").addClass("modal-lg");
	}
}

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
            resizeModalInfo();
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