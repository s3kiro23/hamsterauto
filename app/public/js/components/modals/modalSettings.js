class ModalSettings extends HTMLElement {
	constructor() {
		super();
	}

	connectedCallback() {
		this.innerHTML = `
            <div id="modalSettings" class="modal fade">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-primary bg-opacity-10">
                            <h5 class="modal-title-settings">Notifications</h5>
                            <button type="button"
                                    class="btn-close"
                                    data-bs-dismiss="modal"
                                    aria-label="Close">
                                <span aria-hidden="true"></span>
                            </button>
                        </div>
                        <div class="modal-body d-flex flex-column gap-3">
                        <span class="test"></span>
                            <div class="d-flex flex-column gap-2">
                                <h4>SMS</h4>
                                <div class="form-check form-switch">
                                    <input class="notif form-check-input hover-bnt-switch"
                                           name="btn-rdv" type="checkbox"
                                           role="switch" id="btn-rdv">
                                    <label class="form-check-label" for="btn-rdv">Me rappeler mes prochains rendez-vous</label>
                                </div>
                                <div class="form-check form-switch">
                                        <input class="notif form-check-input hover-bnt-switch"
                                               name="btn-confirmed" type="checkbox"
                                               role="switch" id="btn-confirmed">
                                        <label class="form-check-label" for="btn-confirmed">Me notifier quand un rendez-vous est confirmé</label>
                                    </div>
                                <div class="form-check form-switch">
                                    <input class="notif form-check-input hover-bnt-switch"
                                           name="btn-car" type="checkbox"
                                           role="switch" id="btn-car">
                                    <label class="form-check-label" for="btn-car">Me notifier quand mes véhicules sont pris en charge</label>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="notif form-check-input hover-bnt-switch"
                                           name="btn-finished" type="checkbox"
                                           role="switch" id="btn-finished">
                                    <label class="form-check-label" for="btn-finished">Me notifier quand le contrôle d'un véhicule est terminé</label>
                                </div>
                            </div>
                            <hr>
                            <div class="d-flex flex-column gap-2">
                                <h4>Mailing</h4>
                                <div class="form-check form-switch">
                                    <input class="notif form-check-input hover-bnt-switch"
                                           name="btn-deleted" type="checkbox"
                                           role="switch" id="btn-deleted">
                                    <label class="form-check-label" for="btn-deleted">Me notifier quand un rendez-vous est supprimé</label>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="notif form-check-input hover-bnt-switch"
                                           name="btn-pv" type="checkbox"
                                           role="switch" id="btn-pv">
                                    <label class="form-check-label" for="btn-pv">Me transmettre si mon contrôle est validé, le procès-verbal</label>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="notif form-check-input hover-bnt-switch"
                                           name="btn-control" type="checkbox"
                                           role="switch" id="btn-control">
                                    <label class="form-check-label" for="btn-control">Me rappeler mes prochains contrôles technique</label>
                                </div>
                            </div>
                            <hr>
                            <div class="mb-3">
                                <button class="btn btn-outline-primary cursor-pointer fw-bold" type="button" id="check-all-list"></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
	}
}

customElements.define("modal-settings", ModalSettings);

let notificationManager = function () {
	$.ajax({
		url: "../src/Controller/DashboardClient/ClientController.php",
		dataType: "JSON",
		type: "POST",
		data: {
			request: "notificationManager",
		},
		success: function (response) {
			$("#check-all-list").html("S'abonner à toutes les notifications");
			$("#modalSettings").modal("show");
			$(".notif").prop("checked", false);
			const types = [
				"rdv",
				"confirmed",
				"deleted",
				"finished",
				"car",
				"control",
				"pv",
			];
			types.forEach(function (property) {
				if (response[property]) {
					$("#btn-" + property).prop("checked", true);
					$("#check-all-list").html("Se désabonner de toutes les notifications");
				}
			});
			$(".form-check-input").off("click").on("click", notificationModify);
		},
		error: function () {
			console.log("errorHeader");
		},
	});
};

let notificationModify = function (values) {
	let tabTypes;
	Array.isArray(values) && values.length !== 0
		? (tabTypes = values)
		: (tabTypes = this.id);
	$.ajax({
		url: "../src/Controller/DashboardClient/ClientController.php",
		dataType: "JSON",
		type: "POST",
		data: {
			request: "notificationModify",
			values: tabTypes,
		},
		success: function (response) {
			let checkboxes = $(".notif");
			if (atleastOneChecked(checkboxes)) {
				$("#check-all-list").html("Se désabonner de toutes les notifications");
			} else {
				$("#check-all-list").html("S'abonner à toutes les notifications");
			}
		},
		error: function () {
			console.log("errorNotif");
		},
	});
};

function atleastOneChecked(checkboxes) {
	return checkboxes.is(":checked");
}

let checkThemAll = function () {
	let checkboxes = $(".notif");
	let isChecked = atleastOneChecked(checkboxes);

	let tabTypes = [];

	if ($("#check-all-list").html() === "S'abonner à toutes les notifications") {
		checkboxes.not(":checked").each(function () {
			tabTypes.push(this.id);
		});
		checkboxes.not(":checked").prop("checked", true);
	} else {
		checkboxes.filter(":checked").each(function () {
			tabTypes.push(this.id);
		});
		checkboxes.filter(":checked").prop("checked", false);
	}

	let newLabel = isChecked
		? "S'abonner à toutes les notifications"
		: "Se désabonner de toutes les notifications";
	$("#check-all-list").html(newLabel);
	notificationModify(tabTypes);
};
