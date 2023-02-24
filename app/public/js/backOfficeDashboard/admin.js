$(function () {
	adminIndex();
	$('[data-toggle="tooltip"]').tooltip();
	$(".switchLogoAdmin").on("click", switchLogoAdmin);
	$("#inputTel").intlTelInput({
		preferredCountries: ["fr", "gb"],
		utilsScript: "/vendor/jackocnr/intl-tel-input/build/js/utils.js",
		initialCountry: "fr",
		placeholder: "",
		geoIpLookup: function (success, failure) {
			$.get("https://ipinfo.io", function () {}, "jsonp").always(function (
				resp
			) {
				const countryCode = resp && resp.country ? resp.country : "fr";
				success(countryCode);
			});
		},
	});
});
//
//
//
function switchLogoAdmin() {
	if (
		$(".logo").attr("src") === "/public/assets/img/hamsterauto-unscreen.gif"
	) {
		$(".logo").attr("src", "/public/assets/img/hamsterautoNuit-unscreen.gif");
	} else if (
		$(".logo").attr("src") === "/public/assets/img/hamsterautoNuit-unscreen.gif"
	) {
		$(".logo").attr("src", "/public/assets/img/hamsterauto-unscreen.gif");
	}
}

//-----------Fonction check password strength---------------//
function Strength(password) {
	let i = 0;
	if (password.length > 6) {
		i++;
	}
	if (password.length >= 10) {
		i++;
	}
	if (/[A-Z]/.test(password)) {
		i++;
	}
	if (/[0-9]/.test(password)) {
		i++;
	}
	if (/[A-Za-z0-8]/.test(password)) {
		i++;
	}
	return i;
}

function checkStrength() {
	let password = $("#inputPassword").val();
	let strength = Strength(password);
	if (!password) {
		$("#passwordContainer").removeClass("weak");
	} else if (strength <= 2) {
		$("#passwordContainer").addClass("weak");
		$("#passwordContainer").removeClass("medium");
		$("#passwordContainer").removeClass("strong");
	} else if (strength >= 2 && strength <= 4) {
		$("#passwordContainer").removeClass("weak");
		$("#passwordContainer").addClass("medium");
		$("#passwordContainer").removeClass("strong");
	} else {
		$("#passwordContainer").removeClass("weak");
		$("#passwordContainer").removeClass("medium");
		$("#passwordContainer").addClass("strong");
	}
}
//----------------------------------------------------------//

let intervalRdv = null;
let intervalWip = null;

function reloadRdv() {
	intervalRdv = setInterval(adminRdv, 10000);
	intervalWip = setInterval(adminRdvWip, 10000);
}
function clearIntervals() {
	clearInterval(intervalRdv);
	clearInterval(intervalWip);
}

function loadAdmin() {
	sweetToast();
	btnToTop();
	// -----------------OFF--------------------------------
	$("#searchType").off("change");
	$("#searchisActive").off("change");
	$("#searchName").off("keyup");
	$("#searchFirstName").off("keyup");
	$("#searchTel").off("keyup");
	$("#searchMail").off("keyup");
	$("#searchImmat").off("keyup");
	$("#searchAdress").off("keyup");
	$(".showPassword").off("click");
	// -----------------ON----------------------------------
	$("#sessionEnding").on("click", sessionEnding);
	$("#searchName").on("keyup", adminUsers);
	$("#searchFirstName").on("keyup", adminUsers);
	$("#searchTel").on("keyup", adminUsers);
	$("#searchMail").on("keyup", adminUsers);
	$("#searchAdress").on("keyup", adminUsers);
	$("#searchType").on("change", adminUsers);
	$("#searchisActive").on("change", adminUsers);
	$("#searchImmat").on("keyup", adminRdv);
	$(".showPassword").on("click", showPassword);
	$("#inputPassword").on("keyup", checkStrength);
}
//
//

let callApiMatmut = function () {
	Swal.fire({
		title:
			"Cette action peut prendre un certains temps, voulez-vous continuer ?",
		text: "",
		imageUrl: "/public/assets/img/swalicons/interro.png",
		imageWidth: 100,
		showCancelButton: true,
		confirmButtonColor: "#4BBF73",
		cancelButtonColor: "#d33",
		confirmButtonText: "Oui",
		cancelButtonText: "Annuler",
	}).then((result) => {
		if (result.isConfirmed) {
			Swal.fire({
				title: "Récupération des informations véhicules en cours...",
				imageUrl: "/public/assets/img/swalicons/spinner.gif",
				imageWidth: 220,
				imageHeight: 220,
				allowEscapeKey: false,
				allowOutsideClick: false,
				showCancelButton: false,
				showConfirmButton: false,
			});
			$.ajax({
				url: "/src/Controller/DashboardBackoffice/AdminController.php",
				dataType: "JSON",
				type: "POST",
				data: {
					request: "launch_api_sync",
				},
				success: function (response) {
					if (response["status"] === 1) {
						Swal.fire({
							position: "center",
							title: response["msg"],
							html:
								response["brands_msg"] +
								"<br><br>" +
								response["models_msg"] +
								"<br><br>" +
								response["totalTime"],
							icon: "success",
							confirmButtonColor: "#4BBF73",
						});
					} else {
						Swal.fire({
							position: "center",
							title: response["msg"],
							html: response["unfilled"] + "<br><br>" + response["totalTime"],
							icon: "success",
							confirmButtonColor: "#4BBF73",
						});
					}

					adminIndex();
				},
				error: function () {
					console.log("errorAPI");
				},
			});
		}
	});
};

function exportCSV() {
	$.ajax({
		url: "/src/Controller/DashboardBackoffice/AdminController.php",
		dataType: "json",
		type: "POST",
		data: {
			request: "export",
			name: $("#searchName").val(),
			adress: $("#searchAdress").val(),
			firstName: $("#searchFirstName").val(),
			phone: $("#searchTel").val(),
			mail: $("#searchMail").val(),
			type: $("#searchType").val(),
			active: $("#searchisActive").val(),
		},
		success: function (response) {
			download(response);
		},
	});
}

function download(elem) {
	$.ajax({
		url: elem.url,
		method: "GET",
		xhrFields: {
			responseType: "blob",
		},
		success: function (data) {
			var a = document.createElement("a");
			var url = window.URL.createObjectURL(data);
			a.href = url;
			a.download = elem.name;
			document.body.append(a);
			a.click();
			a.remove();
			window.URL.revokeObjectURL(url);
		},
	});
}

function adminIndex() {
	clearIntervals();
	$.ajax({
		url: "/src/Controller/DashboardBackoffice/AdminController.php",
		dataType: "JSON",
		type: "POST",
		data: {
			request: "display_adminOffice",
		},
		success: function (response) {
			$("#adminOfficeBody").html(response);
			loadAdmin();
		},
		error: function () {
			console.log("errorBO");
		},
	});
}
//
//
function displayFiltreImmatAdmin() {
	$.ajax({
		url: "/src/Controller/DisplayHTML/TablesTechDisplayController.php",
		dataType: "JSON",
		type: "POST",
		data: {
			request: "display_registration",
		},
		success: function (response) {
			$("#filtreImmat").html(response);
			generateDateBO();
		},
		error: function () {
			console.log("errorFiltreImmat");
		},
	});
}
//
//
function displayRdvTab() {
	$.ajax({
		url: "/src/Controller/DashboardBackoffice/AdminController.php",
		dataType: "JSON",
		type: "POST",
		data: {
			request: "display_RDV_tab",
		},
		success: function (response) {
			$("#adminOfficeBody").html(response);
			displayFiltreImmatAdmin();
			setTimeout(() => {
				loadAdmin();
				adminRdv();
				reloadRdv();
			}, 140);
		},
		error: function () {
			console.log("errorAdminRDV1");
		},
	});
}
//
//
function adminRdv() {
	$.ajax({
		url: "/src/Controller/DashboardBackoffice/AdminController.php",
		dataType: "JSON",
		type: "POST",
		data: {
			request: "display_Rdv_wait",
			registration: $("#searchImmat").val(),
			currentDate: $(".currentDate").prop("id"),
		},
		success: function (response) {
			$("#awaitingCarsAdmin").html(response);
			adminRdvWip();
			dataTableAdminRdv();
		},
		error: function () {
			console.log("errorAdminRDV2");
		},
	});
}
//
//
function adminRdvWip() {
	$.ajax({
		url: "/src/Controller/DashboardBackoffice/AdminController.php",
		dataType: "JSON",
		type: "POST",
		data: {
			request: "display_Rdv_wip",
			currentDate: $(".currentDate ").prop("id"),
		},
		success: function (response) {
			$("#wipCarsAdmin").html(response);
			dataTableAdminWip();
		},
		error: function () {
			console.log("errorAdminRDV3");
		},
	});
}
//
//
function switchDayRdv(switchDate) {
	$("#searchImmat").val("");
	let page = 1;
	$.ajax({
		url: "/src/Controller/DashboardBackoffice/BackofficeController.php",
		dataType: "JSON",
		type: "POST",
		data: {
			request: "switch_day_rdv",
			page: page,
			timestamp: switchDate,
			registration: "",
		},
		success: function (response) {
			if (response["html"]) {
				$("#vehiculeAttente").html(response["html"]);
				$("#pagesHold").html(response["paginationHoldNext"]);
				$("#pageH" + page).addClass("active");
				generateDateBO(response["time"]);
				setTimeout(() => {
					dataTableAdminWip();
					dataTableAdminRdv();
				}, 100);
			}
		},
	});
}
//
//
function deleteRdv(rdvId) {
	Swal.fire({
		title: "Confirmez vous la suppression de cette intervention?",
		text: "",
		imageUrl: "/public/assets/img/swalicons/warning.png",
		imageWidth: 100,
		showCancelButton: true,
		cancelButtonText: "Annuler",
		confirmButtonColor: "#62C462",
		cancelButtonColor: "#d33",
		confirmButtonText: "Oui",
	}).then((result) => {
		if (result.isConfirmed) {
			$.ajax({
				url: "/src/Controller/RdvController.php",
				dataType: "JSON",
				type: "POST",
				data: {
					request: "deleteRdv",
					idRdv: rdvId,
				},
				success: function (response) {
					toastMixin.fire({
						animation: true,
						title: "Cette intervention a été annulée",
					});
					displayRdvTab();
				},
				error: function () {
					console.log("PHP");
				},
			});
		}
	});
}
//
//

//
//
function displayUsersTab() {
	clearIntervals();
	$.ajax({
		url: "/src/Controller/DashboardBackoffice/AdminController.php",
		dataType: "JSON",
		type: "POST",
		data: {
			request: "display_users_tab",
		},
		success: function (response) {
			$("#adminOfficeBody").html(response);
			adminUsers();
		},
		error: function () {
			console.log("errorBO");
		},
	});
}
//
//
function refreshAdminUsers() {
	displayUsersTab();
}
//
//
function adminUsers() {
	$.ajax({
		url: "/src/Controller/DashboardBackoffice/AdminController.php",
		dataType: "JSON",
		type: "POST",
		data: {
			request: "display_users",
			name: $("#searchName").val(),
			adress: $("#searchAdress").val(),
			firstName: $("#searchFirstName").val(),
			phone: $("#searchTel").val(),
			mail: $("#searchMail").val(),
			type: $("#searchType").val(),
			active: $("#searchisActive").val(),
		},
		success: function (response) {
			$("#adminUsersTab").html(response);
			loadAdmin();
			dataTableAdminUsers();
			$("#button-csv").on("click", exportCSV);
		},
		error: function () {
			console.log("errorUser");
		},
	});
}
//
//
function inactivateUser(id) {
	$.ajax({
		url: "/src/Controller/DashboardBackoffice/AdminController.php",
		dataType: "JSON",
		type: "POST",
		data: {
			request: "inactivate_user",
			id: id,
		},
		success: function () {
			// adminUsers();
			swal.fire({
				title: "Compte désactivé",
			});
			refreshAdminUsers();
		},
		error: function () {
			console.log("errorBO");
		},
	});
}
//
//
function activateUser(id) {
	$.ajax({
		url: "/src/Controller/DashboardBackoffice/AdminController.php",
		dataType: "JSON",
		type: "POST",
		data: {
			request: "activate_user",
			id: id,
		},
		success: function () {
			// adminUsers();
			swal.fire({
				title: "Compte activé",
			});
			refreshAdminUsers();
		},
		error: function () {
			console.log("errorBO");
		},
	});
}
//
//
function addUserAdmin() {
	let tab_fields_modal = {};
	tab_fields_modal[$("input[name=civilite]:checked").attr("name")] = $(
		"input[name=civilite]:checked"
	).val();
	tab_fields_modal["typeAccount"] = $(
		"input[name=optionTypeAdmin]:checked"
	).val();
	$(".modal-add").each(function () {
		tab_fields_modal[$(this).attr("id")] = $(this).val();
	});
	$.ajax({
		url: "/src/Controller/DashboardBackoffice/AdminController.php",
		dataType: "JSON",
		type: "POST",
		data: {
			request: "add_user",
			values: JSON.stringify(tab_fields_modal),
		},
		success: function (response) {
			if (response["status"] === 1) {
				Swal.fire({
					position: "center",
					icon: "success",
					title: response["msg"],
					showConfirmButton: false,
					timer: 1500,
				});
				adminUsers();
			} else {
				Swal.fire({
					position: "center",
					icon: "error",
					title: response["msg"],
					showConfirmButton: true,
					timer: 1500,
				});
			}
		},
		error: function () {},
	});
}
//
//
function modalProfilAdmin(id) {
	let tab_fields = {};
	$(".data").each(function () {
		tab_fields[$(this).attr("id")] = $(this).html();
	});
	$("#modal-profil").modal("show");
	$("#userId").val(id);
	$("#inputLogin").val(tab_fields["profile_login" + id]);
	$("#inputNom").val(tab_fields["profile_nom" + id]);
	$("#inputPrenom").val(tab_fields["profile_prenom" + id]);
	$("#inputAddr").html(tab_fields["profile_addr" + id]);
	$("#inputTel").val(tab_fields["profile_tel" + id]);
}
//
//
function modifyUserAdmin() {
	let userId = $("#userId").val();
	let tab_fields_modal = {};
	$(".data-modal").each(function () {
		tab_fields_modal[$(this).attr("id")] = $(this).val();
	});
	$.ajax({
		url: "/src/Controller/DashboardBackoffice/AdminController.php",
		dataType: "JSON",
		type: "POST",
		data: {
			request: "modifyUser",
			values: JSON.stringify(tab_fields_modal),
			id: userId,
		},
		success: function (response) {
			if (response["status"] === 1) {
				Swal.fire({
					position: "center",
					icon: "success",
					title: response["msg"],
					showConfirmButton: false,
					timer: 1500,
				});
				adminUsers();
			} else {
				Swal.fire({
					position: "center",
					icon: "error",
					title: response["msg"],
					showConfirmButton: true,
					timer: 1500,
				});
			}
		},
		error: function () {},
	});
}
//
//
function displayBanTab() {
	clearIntervals();
	$.ajax({
		url: "/src/Controller/DashboardBackoffice/AdminController.php",
		dataType: "JSON",
		type: "POST",
		data: {
			request: "display_ban_tab",
		},
		success: function (response) {
			$("#adminOfficeBody").html(response);
			displayBanUsers();
		},
		error: function () {
			console.log("errorBO");
		},
	});
}
//
//
function displayBanUsers() {
	$.ajax({
		url: "/src/Controller/DashboardBackoffice/AdminController.php",
		dataType: "JSON",
		type: "POST",
		data: {
			request: "display_ban_users",
		},
		success: function (response) {
			$("#userBans").html(response);
			dataTableBans();
		},
		error: function () {
			console.log("errorUser");
		},
	});
}
//
//
function debanUser(id) {
	$.ajax({
		url: "/src/Controller/DashboardBackoffice/AdminController.php",
		dataType: "JSON",
		type: "POST",
		data: {
			request: "deban_user",
			userId: id,
		},
		success: function (response) {
			$("#userBans").html(response);
			Swal.fire({
				position: "center",
				icon: "success",
				title: response,
				showConfirmButton: false,
				timer: 1500,
			});
			displayBanTab();
		},
		error: function () {
			console.log("errorUser");
		},
	});
}
//
//
function displayAdminArchives() {
	clearIntervals();
	$.ajax({
		url: "/src/Controller/DashboardBackoffice/AdminController.php",
		dataType: "JSON",
		type: "POST",
		data: {
			request: "display_admin_archives",
		},
		success: function (response) {
			$("#adminOfficeBody").html(response);
			adminArchives();
		},
		error: function () {
			console.log("errorBO");
		},
	});
}
//
//
function adminArchives() {
	$.ajax({
		url: "/src/Controller/DashboardBackoffice/AdminController.php",
		dataType: "JSON",
		type: "POST",
		data: {
			request: "admin_archives",
		},
		success: function (response) {
			$("#archivesTab").html(response);
			dataTableAdminArchives();
		},
		error: function () {
			console.log("errorBO");
		},
	});
}
//
//
function showContreVisiteAdmin(id) {
	$.ajax({
		url: "/src/Controller/DashboardBackoffice/AdminController.php",
		dataType: "JSON",
		type: "POST",
		data: {
			request: "show_contre_visite",
			rdvID: id,
		},
		success: function (response) {
			modalCVisite(response);
		},
		error: function () {
			console.log("errorShow");
		},
	});
}
//
//
function displayLogs() {
	clearIntervals();
	$.ajax({
		url: "/src/Controller/DashboardBackoffice/AdminController.php",
		dataType: "JSON",
		type: "POST",
		data: {
			request: "display_logs_tab",
		},
		success: function (response) {
			$("#adminOfficeBody").html(response);
			showLogs();
		},
		error: function () {
			console.log("errorLogs");
		},
	});
}
//
//
function showLogs() {
	adminLogs();
}
//
//
function adminLogs() {
	$.ajax({
		url: "/src/Controller/DashboardBackoffice/AdminController.php",
		dataType: "JSON",
		type: "POST",
		data: {
			request: "admin_logs",
		},
		success: function (response) {
			$("#logsTab").html(response);
			dataTableLogs();
		},
		error: function () {
			console.log("errorShow");
		},
	});
}
//
//
function generateDateBO(timestampID = 0) {
	$.ajax({
		url: "/src/Controller/DashboardBackoffice/BackofficeController.php",
		dataType: "JSON",
		type: "POST",
		data: {
			request: "generate_date_BO",
			currentDate: timestampID,
		},
		success: function (response) {
			$("#dateDuJour").html(response["html_day"]["currentDay"]);
			$(".btnBack").html(response["html_day"]["btnBack"]);
			$(".btnNext").html(response["html_day"]["btnNext"]);
			$(".btnPrevious").html(response["html_day"]["btnPrevious"]);
			adminRdv();
			loadAdmin();
		},
		error: function () {
			console.log("errordayCases");
		},
	});
}
//
//

function displaySettings() {
	clearIntervals();
	$.ajax({
		url: "/src/Controller/DashboardBackoffice/AdminController.php",
		dataType: "JSON",
		type: "POST",
		data: {
			request: "display_settings",
		},
		success: function (response) {
			$("#adminOfficeBody").html(response);
			showSettings();
		},
		error: function () {},
	});
}
function showSettings() {
	$.ajax({
		url: "/src/Controller/DashboardBackoffice/AdminController.php",
		dataType: "JSON",
		type: "POST",
		data: {
			request: "show_settings",
		},
		success: function (response) {
			$("#settingsTab").html(response);
		},
		error: function () {},
	});
}
function updateHour(contextHour) {
	let newtimeH = $("#openHour").val();
	let newtimeM = $("#openMin").val();
	let slotHour = "start_time_am";
	if (contextHour == "close") {
		newtimeH = $("#closeHour").val();
		newtimeM = $("#closeMin").val();
		slotHour = "end_time_pm";
	}
	Swal.fire({
		title: "Voulez-vous changer l'horaire?",
		text: "",
		imageUrl: "/public/assets/img/swalicons/interro.png",
		imageWidth: 100,
		showCancelButton: true,
		cancelButtonText: "Annuler",
		confirmButtonColor: "#62C462",
		cancelButtonColor: "#d33",
		confirmButtonText: "Oui",
	}).then((result) => {
		if (result.isConfirmed) {
			$.ajax({
				url: "/src/Controller/DashboardBackoffice/AdminController.php",
				dataType: "JSON",
				type: "POST",
				data: {
					request: "update_hour",
					slot: slotHour,
					newTimeH: newtimeH,
					newTimeM: newtimeM,
					context: contextHour,
				},
				success: function () {
					showSettings();
				},
				error: function () {
					console.log("errorSettings");
				},
			});
		}
	});
}

function modifySession(typeUser) {
	let newSessionDuration = $("#userSession").val();
	if (typeUser == "internal") {
		newSessionDuration = $("#internalSession").val();
	}
	Swal.fire({
		title: "Voulez-vous changer la durée de session?",
		text: "",
		imageUrl: "/public/assets/img/swalicons/interro.png",
		imageWidth: 100,
		showCancelButton: true,
		cancelButtonText: "Annuler",
		confirmButtonColor: "#62C462",
		cancelButtonColor: "#d33",
		confirmButtonText: "Oui",
	}).then((result) => {
		if (result.isConfirmed) {
			$.ajax({
				url: "/src/Controller/DashboardBackoffice/AdminController.php",
				dataType: "JSON",
				type: "POST",
				data: {
					request: "session_update",
					sessionDuration: newSessionDuration,
					context: typeUser,
				},
				success: function () {
					showSettings();
				},
				error: function () {
					console.log("errorSettings");
				},
			});
		}
	});
}

function changeLifts() {
	let nbLifts = $("#nbLifts").val();
	Swal.fire({
		className: "swalWarning",
		title: "Voulez-vous changer le nombre de ponts disponibles?",
		text: "",
		imageUrl: "/public/assets/img/swalicons/interro.png",
		imageWidth: 100,
		showCancelButton: true,
		cancelButtonText: "Annuler",
		confirmButtonColor: "#62C462",
		cancelButtonColor: "#d33",
		confirmButtonText: "Oui",
	}).then((result) => {
		if (result.isConfirmed) {
			$.ajax({
				url: "/src/Controller/DashboardBackoffice/AdminController.php",
				dataType: "JSON",
				type: "POST",
				data: {
					request: "change_lifts",
					lifts: nbLifts,
				},
				success: function () {},
				error: function () {
					console.log("errorSettings");
				},
			});
			showSettings();
		}
	});
}
function updateSlot() {
	let newtimeH = $("#slotDurationHour").val();
	let newtimeM = $("#slotDurationMin").val();
	Swal.fire({
		title: "Voulez-vous changer la durée des créneaux?",
		text: "",
		imageUrl: "/public/assets/img/swalicons/interro.png",
		imageWidth: 100,
		showCancelButton: true,
		cancelButtonText: "Annuler",
		confirmButtonColor: "#62C462",
		cancelButtonColor: "#d33",
		confirmButtonText: "Oui",
	}).then((result) => {
		if (result.isConfirmed) {
			$.ajax({
				url: "/src/Controller/DashboardBackoffice/AdminController.php",
				dataType: "JSON",
				type: "POST",
				data: {
					request: "update_slot",
					newTimeH: newtimeH,
					newTimeM: newtimeM,
				},
				success: function () {
					showSettings();
				},
				error: function () {
					console.log("errorSettings");
				},
			});
		}
	});
}
