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

	// Close menu monile on clickOut
	$(document).on("click", function (e) {
		var navbarToggler = $(".navbar-toggler");
		if (
			!navbarToggler.is(e.target) &&
			navbarToggler.has(e.target).length === 0
		) {
			navbarToggler.addClass("collapsed");
			navbarToggler.attr("aria-expanded", "true");
			$(".navbar-collapse").removeClass("show");
		}
	});
});
//
//
//
function switchLogoAdmin() {
	if (
		$(".logo").attr("src") === "../public/assets/img/hamsterauto-unscreen.gif"
	) {
		$(".logo").attr("src", "../public/assets/img/hamsterautoNuit-unscreen.gif");
	} else if (
		$(".logo").attr("src") ===
		"../public/assets/img/hamsterautoNuit-unscreen.gif"
	) {
		$(".logo").attr("src", "../public/assets/img/hamsterauto-unscreen.gif");
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

function reloadRdv() {
	intervalRdv = setInterval(adminRdv, 10000);
}
function clearIntervals() {
	clearInterval(intervalRdv);
}

function loadAdmin() {
	sweetToast();
	btnToTop();
}
//
//
//----------------- START Admin API functions -----------------//

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
				url: "/src/Controller/DashboardAdmin/AdminController.php",
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
//
//
//----------------- END Admin API functions -----------------//
//
//
//----------------- Start Export CSV functions -----------------//

function exportInterCSV() {
	$.ajax({
		url: "/src/Controller/DashboardAdmin/AdminController.php",
		dataType: "json",
		type: "POST",
		data: {
			request: "export_intervention",
			filter: $("#tab-rdv-admin_filter input[type='search']").val(),
		},
		success: function (response) {
			download(response);
		},
	});
}

function exportUserCSV() {
	let values = {};
	$(".filtreAd").each(function () {
		values[$(this).attr("id")] = $(this).val();
	});
	$.ajax({
		url: "/src/Controller/DashboardAdmin/AdminController.php",
		dataType: "json",
		type: "POST",
		data: {
			request: "export_user",
			tabValues: JSON.stringify(values),
		},
		success: function (response) {
			download(response);
		},
	});
}

function exportArchivesCSV() {
	$.ajax({
		url: "/src/Controller/DashboardAdmin/AdminController.php",
		dataType: "json",
		type: "POST",
		data: {
			request: "export_archives",
		},
		success: function (response) {
			download(response);
		},
	});
}

function exportLogsCSV() {
	$.ajax({
		url: "/src/Controller/DashboardAdmin/AdminController.php",
		dataType: "json",
		type: "POST",
		data: {
			request: "export_logs",
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
//
//
//----------------- END Export CSV functions -----------------//
//
//
//----------------- Start Admin Rdv functions -----------------//

function adminIndex() {
	clearIntervals();
	$.ajax({
		url: "/src/Controller/DashboardAdmin/AdminController.php",
		dataType: "JSON",
		type: "POST",
		data: {
			request: "display_adminOffice",
		},
		success: function (response) {
			$("#adminOfficeBody").html(response);
			loadAdmin();
			$(".current-breadcrumb").html("");
		},
		error: function () {
			console.log("errorBO");
		},
	});
}
//
//
//
//
function displayRdvTab() {
	$.ajax({
		url: "/src/Controller/DashboardAdmin/AdminController.php",
		dataType: "JSON",
		type: "POST",
		data: {
			request: "display_RDV_tab",
		},
		success: function (response) {
			$("#adminOfficeBody").html(response);
			$(".current-breadcrumb").html("interventions");
			$(".button-csv").on("click", exportInterCSV);
			let dataTableAdminRdv = $("#tab-rdv-admin").DataTable({
				searching: true,
				pageLength: 5,
				lengthMenu: [
					[5, 10, 25, 50, 75, 100, -1],
					[5, 10, 25, 50, 75, 100, "All"],
				],
				retrieve: true,
				responsive: {
					details: {
						type: "colomn",
						target: "tr",
					},
				},
				order: [[2, "asc"]],
				columnDefs: [
					{ name: "info", targets: 0 },
					{ name: "inter", targets: 1 },
					{ name: "date", targets: 2 },
					{ name: "brand", targets: 3 },
					{ name: "model", targets: 4 },
					{ name: "registration", targets: 5 },
					{ name: "state", targets: 6 },
					{ name: "delete", targets: 7 },
					{ className: "dt-center", targets: [0, 1, 2, 3, 4, 5, 6, 7] },
					{ responsivePriority: 1, targets: 2 },
					{ responsivePriority: 2, targets: 6 },
					{ responsivePriority: 3, targets: 7 },
					{
						targets: [0, 3, 4, 7],
						orderable: false,
					},
				],
				processing: false,
				serverSide: true,
				// Ajax call
				ajax: {
					url: "../src/Controller/DashboardAdmin/InterventionController.php",
					type: "POST",
					// success: function (data){
					//     console.log(data)
					// }
				},
				drawCallback: function (settings) {
					$(".deleteRdvTech").on("click", deleteRdv);

					//Pagination buttons
					var pageInfo = settings.json;
					var startIndex = pageInfo.start;
					var totalRecords = pageInfo.recordsTotal;
					var filteredRecords = pageInfo.recordsFiltered;

					// Handle case when only one result is returned by the search
					if (settings.oPreviousSearch.sSearch !== "") {
						if (filteredRecords == 1) {
							totalRecords = 1;
						} else {
							totalRecords = filteredRecords;
						}
					}

					//Calculate number of total pages
					var currentPage = Math.ceil((startIndex + 1) / pageInfo.length);
					var totalPages = Math.ceil(totalRecords / pageInfo.length);

					//Calculate indices start/end of display elements
					var endIndex = Math.min(
						startIndex + filteredRecords - 1,
						totalRecords - 1
					);
					var displayStart = startIndex + 1;
					var displayEnd = endIndex + 1;

					//Display output
					var displayText =
						"Affichage de l'élément " +
						displayStart +
						" à " +
						displayEnd +
						" sur " +
						filteredRecords +
						" éléments (filtré à partir de " +
						pageInfo.recordsTotal +
						" éléments au total)";

					//Btn previous
					var pagingControls =
						'<ul class="pagination"><li id="tab-rdv-admin_previous" class="paginate_button page-item previous' +
						(currentPage == 1 ? " disabled" : "") +
						'"><a aria-controls="tab-rdv-admin" class="cursor-pointer page-link" data-dt-idx="previous" tabindex="0">Précédent</a></li>';

					//Generate buttons page
					for (var i = 1; i <= totalPages; i++) {
						pagingControls +=
							'<li class="paginate_button page-item ' +
							(i == currentPage ? "active" : "") +
							'"><a aria-controls="tab-rdv-admin" data-dt-idx=' +
							i +
							' tabindex="0" class="cursor-pointer page-link">' +
							i +
							"</a></li>";
					}

					//Btn next
					pagingControls +=
						'<li id="tab-rdv-admin_next" class="paginate_button page-item next ' +
						(currentPage == totalPages || totalPages == 0 ? "disabled" : "") +
						'"><a class="cursor-pointer page-link" data-dt-idx="next" tabindex="0">Suivant</a></li></ul>';

					//Write html pagination
					$("#tab-rdv-admin_wrapper .dataTables_paginate").html(pagingControls);
					$("#tab-rdv-admin_wrapper .dataTables_info").html(displayText);

					//Callback for pagination
					$("#tab-rdv-admin_wrapper .pagination li a").on("click", function () {
						var page = $(this).data("dt-idx");
						var previousPage = currentPage - 1;
						var nextPage = currentPage + 1;
						// Call Ajax with new value
						$("#tab-rdv-admin")
							.DataTable()
							.ajax.url(
								"../src/Controller/DashboardAdmin/InterventionController.php?start=" +
									(page == "next"
										? nextPage
										: page == "previous"
										? previousPage
										: page)
							)
							.load();
					});
				},
				language: {
					sEmptyTable: "Aucunes données n'est disponible",
					sInfo: "Affichage de l'élément _START_ à _END_ sur _TOTAL_ éléments",
					sInfoEmpty: "Affichage de l'élément 0 à 0 sur 0 élément",
					sInfoFiltered: "(filtré à partir de _MAX_ éléments au total)",
					sInfoPostFix: "",
					sInfoThousands: ",",
					sLengthMenu: "Afficher _MENU_ éléments",
					sLoadingRecords: "Chargement...",
					sProcessing: "Traitement...",
					sSearch: "Rechercher :",
					searchPlaceholder: "immatriculation",
					sZeroRecords: "Aucun élément correspondant trouvé",
					oPaginate: {
						sFirst: "Premier",
						sLast: "Dernier",
						sNext: "Suivant",
						sPrevious: "Précédent",
					},
					oAria: {
						sSortAscending:
							": activer pour trier la colonne par ordre croissant",
						sSortDescending:
							": activer pour trier la colonne par ordre décroissant",
					},
					select: {
						rows: {
							_: "%d lignes sélectionnées",
							0: "Aucune ligne sélectionnée",
							1: "1 ligne sélectionnée",
						},
					},
				},
			});
			setInterval(() => {
				dataTableAdminRdv.ajax.reload();
			}, 3000);
		},
		error: function () {
			console.log("errorAdminRDV1");
		},
	});
}
//
//

function refreshAdminRdv() {
	let dataTableAdminRdv = $("#tab-rdv-admin").DataTable({
		searching: true,
		pageLength: 5,
		lengthMenu: [
			[5, 10, 25, 50, 75, 100, -1],
			[5, 10, 25, 50, 75, 100, "All"],
		],
		retrieve: true,
		responsive: {
			details: {
				type: "colomn",
				target: "tr",
			},
		},
		columnDefs: [
			{ name: "lastname", targets: 0 },
			{ name: "firstname", targets: 1 },
			{ name: "adress", targets: 2 },
			{ name: "phone", targets: 3 },
			{ name: "email", targets: 4 },
			{ name: "type", targets: 5 },
			{ name: "active", targets: 6 },
			{ className: "dt-center", targets: [0, 1, 2, 3, 4, 5, 6, 7, 8] },
			{ responsivePriority: 1, targets: 0 },
			{ responsivePriority: 2, targets: 8 },
			{ responsivePriority: 3, targets: 7 },
			{
				targets: [2, 3, 4, 6, 7, 8],
				orderable: false,
			},
		],
		processing: false,
		serverSide: true,
		// Ajax call
		ajax: {
			url: "../src/Controller/DashboardAdmin/InterventionController.php",
			type: "POST",
			// success: function (data){
			//     console.log(data)
			// }
		},
		drawCallback: function (settings) {
			$(".showPassword").off("click").on("click", showPassword);
			$("#inputPassword").on("keyup", checkStrength);
			$("#button-csv").on("click", exportUserCSV);

			//Pagination buttons
			var pageInfo = settings.json;
			var startIndex = pageInfo.start;
			var totalRecords = pageInfo.recordsTotal;
			var filteredRecords = pageInfo.recordsFiltered;

			// Handle case when only one result is returned by the search
			if (settings.oPreviousSearch.sSearch !== "") {
				if (filteredRecords == 1) {
					totalRecords = 1;
				} else {
					totalRecords = filteredRecords;
				}
			}

			//Calculate number of total pages
			var currentPage = Math.ceil((startIndex + 1) / pageInfo.length);
			var totalPages = Math.ceil(totalRecords / pageInfo.length);

			//Calculate indices start/end of display elements
			var endIndex = Math.min(
				startIndex + filteredRecords - 1,
				totalRecords - 1
			);
			var displayStart = startIndex + 1;
			var displayEnd = endIndex + 1;

			//Display output
			var displayText =
				"Affichage de l'élément " +
				displayStart +
				" à " +
				displayEnd +
				" sur " +
				filteredRecords +
				" éléments (filtré à partir de " +
				pageInfo.recordsTotal +
				" éléments au total)";

			//Btn previous
			var pagingControls =
				'<ul class="pagination"><li id="tab-rdv-admin_previous" class="paginate_button page-item previous' +
				(currentPage == 1 ? " disabled" : "") +
				'"><a aria-controls="tab-rdv-admin" class="cursor-pointer page-link" data-dt-idx="previous" tabindex="0">Précédent</a></li>';

			//Generate buttons page
			for (var i = 1; i <= totalPages; i++) {
				pagingControls +=
					'<li class="paginate_button page-item ' +
					(i == currentPage ? "active" : "") +
					'"><a aria-controls="tab-rdv-admin" data-dt-idx=' +
					i +
					' tabindex="0" class="cursor-pointer page-link">' +
					i +
					"</a></li>";
			}

			//Btn next
			pagingControls +=
				'<li id="tab-rdv-admin_next" class="paginate_button page-item next ' +
				(currentPage == totalPages || totalPages == 0 ? "disabled" : "") +
				'"><a class="cursor-pointer page-link" data-dt-idx="next" tabindex="0">Suivant</a></li></ul>';

			//Write html pagination
			$("#tab-rdv-admin_wrapper .dataTables_paginate").html(pagingControls);
			$("#tab-rdv-admin_wrapper .dataTables_info").html(displayText);

			//Callback for pagination
			$("#tab-rdv-admin_wrapper .pagination li a").on("click", function () {
				var page = $(this).data("dt-idx");
				var previousPage = currentPage - 1;
				var nextPage = currentPage + 1;
				// Call Ajax with new value
				$("#tab-rdv-admin")
					.DataTable()
					.ajax.url(
						"../src/Controller/DashboardAdmin/InterventionController.php?start=" +
							(page == "next"
								? nextPage
								: page == "previous"
								? previousPage
								: page)
					)
					.load();
			});
		},
		language: {
			sEmptyTable: "Aucunes données n'est disponible",
			sInfo: "Affichage de l'élément _START_ à _END_ sur _TOTAL_ éléments",
			sInfoEmpty: "Affichage de l'élément 0 à 0 sur 0 élément",
			sInfoFiltered: "(filtré à partir de _MAX_ éléments au total)",
			sInfoPostFix: "",
			sInfoThousands: ",",
			sLengthMenu: "Afficher _MENU_ éléments",
			sLoadingRecords: "Chargement...",
			sProcessing: "Traitement...",
			sSearch: "Rechercher :",
			sZeroRecords: "Aucun élément correspondant trouvé",
			oPaginate: {
				sFirst: "Premier",
				sLast: "Dernier",
				sNext: "Suivant",
				sPrevious: "Précédent",
			},
			oAria: {
				sSortAscending: ": activer pour trier la colonne par ordre croissant",
				sSortDescending:
					": activer pour trier la colonne par ordre décroissant",
			},
			select: {
				rows: {
					_: "%d lignes sélectionnées",
					0: "Aucune ligne sélectionnée",
					1: "1 ligne sélectionnée",
				},
			},
		},
	});

	dataTableAdminRdv.ajax.reload();
}
//
//
function deleteRdv() {
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
					idRdv: $(this).data("id"),
				},
				success: function (response) {
					toastMixin.fire({
						animation: true,
						title: "Cette intervention a été annulée",
					});
					refreshAdminRdv();
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
//----------------- END Admin Rdv functions -----------------//
//
//
//----------------- Start Admin Users functions -----------------//

function displayUsersTab() {
	$.ajax({
		url: "/src/Controller/DashboardAdmin/AdminController.php",
		dataType: "JSON",
		type: "POST",
		data: {
			request: "display_users_tab",
		},
		success: function (response) {
			$("#adminOfficeBody").html(response);
			$(".current-breadcrumb").html("utilisateurs");
			let dataTableAdminUsers = $("#tab-admin-users").DataTable({
				searching: true,
				pageLength: 5,
				lengthMenu: [
					[5, 10, 25, 50, 75, 100, -1],
					[5, 10, 25, 50, 75, 100, "All"],
				],
				retrieve: true,
				responsive: {
					details: {
						type: "colomn",
						target: "tr",
					},
				},
				columnDefs: [
					{ name: "lastname", targets: 0 },
					{ name: "firstname", targets: 1 },
					{ name: "adress", targets: 2 },
					{ name: "phone", targets: 3 },
					{ name: "email", targets: 4 },
					{ name: "type", targets: 5 },
					{ name: "active", targets: 6 },
					{ className: "dt-center", targets: [0, 1, 2, 3, 4, 5, 6, 7, 8] },
					{ responsivePriority: 1, targets: 0 },
					{ responsivePriority: 2, targets: 8 },
					{ responsivePriority: 3, targets: 7 },
					{
						targets: [2, 3, 4, 6, 7, 8],
						orderable: false,
					},
				],
				processing: false,
				serverSide: true,
				// Ajax call
				ajax: {
					url: "../src/Controller/DashboardAdmin/UserController.php",
					type: "POST",
					// success: function (data){
					//     console.log(data)
					// }
				},
				drawCallback: function (settings) {
					$(".showPassword").off("click").on("click", showPassword);
					$("#inputPassword").on("keyup", checkStrength);
					$("#button-csv").on("click", exportUserCSV);

					//Pagination buttons
					var pageInfo = settings.json;
					var startIndex = pageInfo.start;
					var totalRecords = pageInfo.recordsTotal;
					var filteredRecords = pageInfo.recordsFiltered;

					// Handle case when only one result is returned by the search
					if (settings.oPreviousSearch.sSearch !== "") {
						if (filteredRecords == 1) {
							totalRecords = 1;
						} else {
							totalRecords = filteredRecords;
						}
					}

					//Calculate number of total pages
					var currentPage = Math.ceil((startIndex + 1) / pageInfo.length);
					var totalPages = Math.ceil(totalRecords / pageInfo.length);

					//Calculate indices start/end of display elements
					var endIndex = Math.min(
						startIndex + filteredRecords - 1,
						totalRecords - 1
					);
					var displayStart = startIndex + 1;
					var displayEnd = endIndex + 1;

					//Display output
					var displayText =
						"Affichage de l'élément " +
						displayStart +
						" à " +
						displayEnd +
						" sur " +
						filteredRecords +
						" éléments (filtré à partir de " +
						pageInfo.recordsTotal +
						" éléments au total)";

					//Btn previous
					var pagingControls =
						'<ul class="pagination"><li id="tab-admin-users_previous" class="paginate_button page-item previous' +
						(currentPage == 1 ? " disabled" : "") +
						'"><a aria-controls="tab-admin-users" class="cursor-pointer page-link" data-dt-idx="previous" tabindex="0">Précédent</a></li>';

					//Generate buttons page
					for (var i = 1; i <= totalPages; i++) {
						pagingControls +=
							'<li class="paginate_button page-item ' +
							(i == currentPage ? "active" : "") +
							'"><a aria-controls="tab-admin-users" data-dt-idx=' +
							i +
							' tabindex="0" class="cursor-pointer page-link">' +
							i +
							"</a></li>";
					}

					//Btn next
					pagingControls +=
						'<li id="tab-admin-users_next" class="paginate_button page-item next ' +
						(currentPage == totalPages || totalPages == 0 ? "disabled" : "") +
						'"><a class="cursor-pointer page-link" data-dt-idx="next" tabindex="0">Suivant</a></li></ul>';

					//Write html pagination
					$("#tab-admin-users_wrapper .dataTables_paginate").html(
						pagingControls
					);
					$("#tab-admin-users_wrapper .dataTables_info").html(displayText);

					//Callback for pagination
					$("#tab-admin-users_wrapper .pagination li a").on(
						"click",
						function () {
							var page = $(this).data("dt-idx");
							var previousPage = currentPage - 1;
							var nextPage = currentPage + 1;
							// Call Ajax with new value
							$("#tab-admin-users")
								.DataTable()
								.ajax.url(
									"../src/Controller/DashboardAdmin/UserController.php?start=" +
										(page == "next"
											? nextPage
											: page == "previous"
											? previousPage
											: page)
								)
								.load();
						}
					);
				},
				language: {
					sEmptyTable: "Aucunes données n'est disponible",
					sInfo: "Affichage de l'élément _START_ à _END_ sur _TOTAL_ éléments",
					sInfoEmpty: "Affichage de l'élément 0 à 0 sur 0 élément",
					sInfoFiltered: "(filtré à partir de _MAX_ éléments au total)",
					sInfoPostFix: "",
					sInfoThousands: ",",
					sLengthMenu: "Afficher _MENU_ éléments",
					sLoadingRecords: "Chargement...",
					sProcessing: "Traitement...",
					sSearch: "Rechercher :",
					sZeroRecords: "Aucun élément correspondant trouvé",
					oPaginate: {
						sFirst: "Premier",
						sLast: "Dernier",
						sNext: "Suivant",
						sPrevious: "Précédent",
					},
					oAria: {
						sSortAscending:
							": activer pour trier la colonne par ordre croissant",
						sSortDescending:
							": activer pour trier la colonne par ordre décroissant",
					},
					select: {
						rows: {
							_: "%d lignes sélectionnées",
							0: "Aucune ligne sélectionnée",
							1: "1 ligne sélectionnée",
						},
					},
				},
			});
			// Apply the search
			let pos = 1;
			dataTableAdminUsers.columns().every(function () {
				const that = this;

				$(".filtreAd" + pos).on("change", function () {
					if (that.search() !== this.value) {
						that.search(this.value).draw();
					}
				});
				pos++;
			});
			var searchField = $("div.dataTables_filter");
			// Cacher le champ de recherche
			searchField.hide();

			setInterval(() => {
				dataTableAdminUsers.ajax.reload();
			}, 3000);
		},
		error: function () {
			console.log("errorBO");
		},
	});
}
//
//
function refreshAdminUsers() {
	let dataTableAdminUsers = $("#tab-admin-users").DataTable({
		searching: false,
		pageLength: 5,
		lengthMenu: [
			[5, 10, 25, 50, 75, 100, -1],
			[5, 10, 25, 50, 75, 100, "All"],
		],
		retrieve: true,
		responsive: {
			details: {
				type: "colomn",
				target: "tr",
			},
		},
		columnDefs: [
			{ name: "lastname", targets: 0 },
			{ name: "firstname", targets: 1 },
			{ name: "type", targets: 5 },
			{ className: "dt-center", targets: [0, 1, 2, 3, 4, 5, 6, 7, 8] },
			{ responsivePriority: 1, targets: 0 },
			{ responsivePriority: 2, targets: 8 },
			{ responsivePriority: 3, targets: 7 },
			{
				targets: [2, 3, 4, 6, 7, 8],
				orderable: false,
			},
		],
		processing: false,
		serverSide: true,
		// Ajax call
		ajax: {
			url: "../src/Controller/DashboardAdmin/UserController.php",
			type: "POST",
			// success: function (data){
			//     console.log(data)
			// }
		},
		drawCallback: function (settings) {
			$(".showPassword").off("click").on("click", showPassword);
			$("#inputPassword").on("keyup", checkStrength);
			$("#button-csv").on("click", exportUserCSV);

			//Pagination buttons
			var pageInfo = settings.json;
			var startIndex = pageInfo.start;
			var totalRecords = pageInfo.recordsTotal;
			var filteredRecords = pageInfo.recordsFiltered;

			// Handle case when only one result is returned by the search
			if (settings.oPreviousSearch.sSearch !== "") {
				if (filteredRecords == 1) {
					totalRecords = 1;
				} else {
					totalRecords = filteredRecords;
				}
			}

			//Calculate number of total pages
			var currentPage = Math.ceil((startIndex + 1) / pageInfo.length);
			var totalPages = Math.ceil(totalRecords / pageInfo.length);

			//Calculate indices start/end of display elements
			var endIndex = Math.min(
				startIndex + filteredRecords - 1,
				totalRecords - 1
			);
			var displayStart = startIndex + 1;
			var displayEnd = endIndex + 1;

			//Display output
			var displayText =
				"Affichage de l'élément " +
				displayStart +
				" à " +
				displayEnd +
				" sur " +
				filteredRecords +
				" éléments (filtré à partir de " +
				pageInfo.recordsTotal +
				" éléments au total)";

			//Btn previous
			var pagingControls =
				'<ul class="pagination"><li id="tab-admin-users_previous" class="paginate_button page-item previous ' +
				(currentPage == 1 ? "disabled" : "") +
				'"><a aria-controls="tab-admin-users" class="cursor-pointer page-link" data-dt-idx="previous" tabindex="0">Précédent</a></li>';

			//Generate buttons page
			for (var i = 1; i <= totalPages; i++) {
				pagingControls +=
					'<li class="paginate_button page-item ' +
					(i == currentPage ? "active" : "") +
					'"><a aria-controls="tab-admin-users" data-dt-idx=' +
					i +
					' tabindex="0" class="cursor-pointer page-link">' +
					i +
					"</a></li>";
			}

			//Btn next
			pagingControls +=
				'<li id="tab-admin-users_next" class="paginate_button page-item next ' +
				(currentPage == totalPages || totalPages == 0 ? "disabled" : "") +
				'"><a class="cursor-pointer page-link" data-dt-idx="next" tabindex="0">Suivant</a></li></ul>';

			//Write html pagination
			$("#tab-admin-users_wrapper .dataTables_paginate").html(pagingControls);
			$("#tab-admin-users_wrapper .dataTables_info").html(displayText);

			//Callback for pagination
			$("#tab-admin-users_wrapper .pagination li a").on("click", function () {
				var page = $(this).data("dt-idx");
				// Call Ajax with new value
				$("#tab-admin-users")
					.DataTable()
					.ajax.url(
						"../src/Controller/DashboardAdmin/UserController.php?start=" + page
					)
					.load();
			});
		},
		language: {
			sEmptyTable: "Aucunes données n'est disponible",
			sInfo: "Affichage de l'élément _START_ à _END_ sur _TOTAL_ éléments",
			sInfoEmpty: "Affichage de l'élément 0 à 0 sur 0 élément",
			sInfoFiltered: "(filtré à partir de _MAX_ éléments au total)",
			sInfoPostFix: "",
			sInfoThousands: ",",
			sLengthMenu: "Afficher _MENU_ éléments",
			sLoadingRecords: "Chargement...",
			sProcessing: "Traitement...",
			sSearch: "Rechercher :",
			sZeroRecords: "Aucun élément correspondant trouvé",
			oPaginate: {
				sFirst: "Premier",
				sLast: "Dernier",
				sNext: "Suivant",
				sPrevious: "Précédent",
			},
			oAria: {
				sSortAscending: ": activer pour trier la colonne par ordre croissant",
				sSortDescending:
					": activer pour trier la colonne par ordre décroissant",
			},
			select: {
				rows: {
					_: "%d lignes sélectionnées",
					0: "Aucune ligne sélectionnée",
					1: "1 ligne sélectionnée",
				},
			},
		},
	});

	dataTableAdminUsers.ajax.reload();
}
//
//
//
//
function inactivateUser(id) {
	$.ajax({
		url: "/src/Controller/DashboardAdmin/AdminController.php",
		dataType: "JSON",
		type: "POST",
		data: {
			request: "inactivate_user",
			id: id,
		},
		success: function () {
			Swal.fire({
				position: "center",
				icon: "info",
				title: "Compte désactivé",
				showConfirmButton: false,
				timer: 1500,
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
		url: "/src/Controller/DashboardAdmin/AdminController.php",
		dataType: "JSON",
		type: "POST",
		data: {
			request: "activate_user",
			id: id,
		},
		success: function () {
			Swal.fire({
				position: "center",
				icon: "success",
				title: "Compte activé",
				showConfirmButton: false,
				timer: 1500,
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
		url: "/src/Controller/DashboardAdmin/AdminController.php",
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
				refreshAdminUsers();
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
function modalProfilAdmin(id, event) {
	let tr = $(event).closest("tr");
	let tab_fields = {
		profile_login: tr.find("td:nth-child(5)").text(),
		profile_nom: tr.find("td:nth-child(1)").text(),
		profile_prenom: tr.find("td:nth-child(2)").text(),
		profile_tel: tr.find("td:nth-child(4)").text(),
		profile_addr: tr.find("td:nth-child(3)").text(),
	};
	$("#modal-profil").modal("show");
	$("#userId").val(id);
	$("#inputLogin").val(tab_fields["profile_login"]);
	$("#inputNom").val(tab_fields["profile_nom"]);
	$("#inputPrenom").val(tab_fields["profile_prenom"]);
	$("#inputTel").val(tab_fields["profile_tel"]);
	$("#inputAddr").html(tab_fields["profile_addr"]);
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
		url: "/src/Controller/DashboardAdmin/AdminController.php",
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
				refreshAdminUsers();
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
//----------------- END Admin Rdv functions -----------------//
//
//
//----------------- Start Admin Ban functions -----------------//

function displayBanTab() {
	clearIntervals();
	$.ajax({
		url: "/src/Controller/DashboardAdmin/AdminController.php",
		dataType: "JSON",
		type: "POST",
		data: {
			request: "display_ban_tab",
		},
		success: function (response) {
			$("#adminOfficeBody").html(response);
			$(".current-breadcrumb").html("comptes bannis");
			let dataTableAdminBan = $("#tab-bans").DataTable({
				searching: true,
				pageLength: 5,
				lengthMenu: [
					[5, 10, 25, 50, 75, 100, -1],
					[5, 10, 25, 50, 75, 100, "All"],
				],
				retrieve: true,
				responsive: {
					details: {
						type: "colomn",
						target: "tr",
					},
				},
				order: [[2, "desc"]],
				columnDefs: [
					{ name: "login", targets: 0 },
					{ name: "ip", targets: 1 },
					{ name: "date", targets: 2 },
					{ name: "action", targets: 3 },
					{ className: "dt-center", targets: [0, 1, 2, 3] },
					{ responsivePriority: 1, targets: 0 },
					{ responsivePriority: 2, targets: 3 },
					{
						targets: [3],
						orderable: false,
					},
				],
				processing: false,
				serverSide: true,
				// Ajax call
				ajax: {
					url: "../src/Controller/DashboardAdmin/BanController.php",
					type: "POST",
					// success: function (data){
					//     console.log(data)
					// }
				},
				drawCallback: function (settings) {
					//Pagination buttons
					var pageInfo = settings.json;
					var startIndex = pageInfo.start;
					var totalRecords = pageInfo.recordsTotal;
					var filteredRecords = pageInfo.recordsFiltered;

					// Handle case when only one result is returned by the search
					if (settings.oPreviousSearch.sSearch !== "") {
						if (filteredRecords == 1) {
							totalRecords = 1;
						} else {
							totalRecords = filteredRecords;
						}
					}

					//Calculate number of total pages
					var currentPage = Math.ceil((startIndex + 1) / pageInfo.length);
					var totalPages = Math.ceil(totalRecords / pageInfo.length);

					//Calculate indices start/end of display elements
					var endIndex = Math.min(
						startIndex + filteredRecords - 1,
						totalRecords - 1
					);
					var displayStart = startIndex + 1;
					var displayEnd = endIndex + 1;

					//Display output
					var displayText =
						"Affichage de l'élément " +
						displayStart +
						" à " +
						displayEnd +
						" sur " +
						filteredRecords +
						" éléments (filtré à partir de " +
						pageInfo.recordsTotal +
						" éléments au total)";

					//Btn previous
					var pagingControls =
						'<ul class="pagination"><li id="tab-bans_previous" class="paginate_button page-item previous' +
						(currentPage == 1 ? " disabled" : "") +
						'"><a aria-controls="tab-bans" class="cursor-pointer page-link" data-dt-idx="previous" tabindex="0">Précédent</a></li>';

					//Generate buttons page
					for (var i = 1; i <= totalPages; i++) {
						pagingControls +=
							'<li class="paginate_button page-item ' +
							(i == currentPage ? "active" : "") +
							'"><a aria-controls="tab-bans" data-dt-idx=' +
							i +
							' tabindex="0" class="cursor-pointer page-link">' +
							i +
							"</a></li>";
					}

					//Btn next
					pagingControls +=
						'<li id="tab-bans_next" class="paginate_button page-item next ' +
						(currentPage == totalPages || totalPages == 0 ? "disabled" : "") +
						'"><a class="cursor-pointer page-link" data-dt-idx="next" tabindex="0">Suivant</a></li></ul>';

					//Write html pagination
					$("#tab-bans_wrapper .dataTables_paginate").html(pagingControls);
					$("#tab-bans_wrapper .dataTables_info").html(displayText);

					//Callback for pagination
					$("#tab-bans_wrapper .pagination li a").on("click", function () {
						var page = $(this).data("dt-idx");
						var previousPage = currentPage - 1;
						var nextPage = currentPage + 1;
						// Call Ajax with new value
						$("#tab-bans")
							.DataTable()
							.ajax.url(
								"../src/Controller/DashboardAdmin/BanController.php?start=" +
									(page == "next"
										? nextPage
										: page == "previous"
										? previousPage
										: page)
							)
							.load();
					});
				},
				language: {
					sEmptyTable: "Aucunes données n'est disponible",
					sInfo: "Affichage de l'élément _START_ à _END_ sur _TOTAL_ éléments",
					sInfoEmpty: "Affichage de l'élément 0 à 0 sur 0 élément",
					sInfoFiltered: "(filtré à partir de _MAX_ éléments au total)",
					sInfoPostFix: "",
					sInfoThousands: ",",
					sLengthMenu: "Afficher _MENU_ éléments",
					sLoadingRecords: "Chargement...",
					sProcessing: "Traitement...",
					sSearch: "Rechercher :",
					sZeroRecords: "Aucun élément correspondant trouvé",
					oPaginate: {
						sFirst: "Premier",
						sLast: "Dernier",
						sNext: "Suivant",
						sPrevious: "Précédent",
					},
					oAria: {
						sSortAscending:
							": activer pour trier la colonne par ordre croissant",
						sSortDescending:
							": activer pour trier la colonne par ordre décroissant",
					},
					select: {
						rows: {
							_: "%d lignes sélectionnées",
							0: "Aucune ligne sélectionnée",
							1: "1 ligne sélectionnée",
						},
					},
				},
			});
		},
		error: function () {
			console.log("errorBO");
		},
	});
}

function refreshAdminBans() {
	let dataTableAdminBan = $("#tab-bans").DataTable({
		searching: true,
		pageLength: 5,
		lengthMenu: [
			[5, 10, 25, 50, 75, 100, -1],
			[5, 10, 25, 50, 75, 100, "All"],
		],
		retrieve: true,
		responsive: {
			details: {
				type: "colomn",
				target: "tr",
			},
		},
		order: [[2, "asc"]],
		columnDefs: [
			{ name: "info", targets: 0 },
			{ name: "inter", targets: 1 },
			{ name: "date", targets: 2 },
			{ name: "brand", targets: 3 },
			{ name: "model", targets: 4 },
			{ name: "registration", targets: 5 },
			{ name: "state", targets: 6 },
			{ name: "delete", targets: 7 },
			{ className: "dt-center", targets: [0, 1, 2, 3, 4, 5, 6, 7] },
			{ responsivePriority: 1, targets: 2 },
			{ responsivePriority: 2, targets: 6 },
			{ responsivePriority: 3, targets: 7 },
			{
				targets: [0, 3, 4, 7],
				orderable: false,
			},
		],
		processing: false,
		serverSide: true,
		// Ajax call
		ajax: {
			url: "../src/Controller/DashboardAdmin/BanController.php",
			type: "POST",
			// success: function (data){
			//     console.log(data)
			// }
		},
		drawCallback: function (settings) {
			$(".deleteRdvTech").on("click", deleteRdv);

			//Pagination buttons
			var pageInfo = settings.json;
			var startIndex = pageInfo.start;
			var totalRecords = pageInfo.recordsTotal;
			var filteredRecords = pageInfo.recordsFiltered;

			// Handle case when only one result is returned by the search
			if (settings.oPreviousSearch.sSearch !== "") {
				if (filteredRecords == 1) {
					totalRecords = 1;
				} else {
					totalRecords = filteredRecords;
				}
			}

			//Calculate number of total pages
			var currentPage = Math.ceil((startIndex + 1) / pageInfo.length);
			var totalPages = Math.ceil(totalRecords / pageInfo.length);

			//Calculate indices start/end of display elements
			var endIndex = Math.min(
				startIndex + filteredRecords - 1,
				totalRecords - 1
			);
			var displayStart = startIndex + 1;
			var displayEnd = endIndex + 1;

			//Display output
			var displayText =
				"Affichage de l'élément " +
				displayStart +
				" à " +
				displayEnd +
				" sur " +
				filteredRecords +
				" éléments (filtré à partir de " +
				pageInfo.recordsTotal +
				" éléments au total)";

			//Btn previous
			var pagingControls =
				'<ul class="pagination"><li id="tab-bans_previous" class="paginate_button page-item previous' +
				(currentPage == 1 ? " disabled" : "") +
				'"><a aria-controls="tab-bans" class="cursor-pointer page-link" data-dt-idx="previous" tabindex="0">Précédent</a></li>';

			//Generate buttons page
			for (var i = 1; i <= totalPages; i++) {
				pagingControls +=
					'<li class="paginate_button page-item ' +
					(i == currentPage ? "active" : "") +
					'"><a aria-controls="tab-bans" data-dt-idx=' +
					i +
					' tabindex="0" class="cursor-pointer page-link">' +
					i +
					"</a></li>";
			}

			//Btn next
			pagingControls +=
				'<li id="tab-bans_next" class="paginate_button page-item next ' +
				(currentPage == totalPages ? "disabled" : "") +
				'"><a class="cursor-pointer page-link" data-dt-idx="next" tabindex="0">Suivant</a></li></ul>';

			//Write html pagination
			$("#tab-bans_wrapper .dataTables_paginate").html(pagingControls);
			$("#tab-bans_wrapper .dataTables_info").html(displayText);

			//Callback for pagination
			$("#tab-bans_wrapper .pagination li a").on("click", function () {
				var page = $(this).data("dt-idx");
				var previousPage = currentPage - 1;
				var nextPage = currentPage + 1;
				// Call Ajax with new value
				$("#tab-bans")
					.DataTable()
					.ajax.url(
						"../src/Controller/DashboardAdmin/BanController.php?start=" +
							(page == "next"
								? nextPage
								: page == "previous"
								? previousPage
								: page)
					)
					.load();
			});
		},
		language: {
			sEmptyTable: "Aucunes données n'est disponible",
			sInfo: "Affichage de l'élément _START_ à _END_ sur _TOTAL_ éléments",
			sInfoEmpty: "Affichage de l'élément 0 à 0 sur 0 élément",
			sInfoFiltered: "(filtré à partir de _MAX_ éléments au total)",
			sInfoPostFix: "",
			sInfoThousands: ",",
			sLengthMenu: "Afficher _MENU_ éléments",
			sLoadingRecords: "Chargement...",
			sProcessing: "Traitement...",
			sSearch: "Rechercher :",
			searchPlaceholder: "immatriculation",
			sZeroRecords: "Aucun élément correspondant trouvé",
			oPaginate: {
				sFirst: "Premier",
				sLast: "Dernier",
				sNext: "Suivant",
				sPrevious: "Précédent",
			},
			oAria: {
				sSortAscending: ": activer pour trier la colonne par ordre croissant",
				sSortDescending:
					": activer pour trier la colonne par ordre décroissant",
			},
			select: {
				rows: {
					_: "%d lignes sélectionnées",
					0: "Aucune ligne sélectionnée",
					1: "1 ligne sélectionnée",
				},
			},
		},
	});

	dataTableAdminBan.ajax.reload();
}
//
//
//
//
function debanUser(id) {
	$.ajax({
		url: "/src/Controller/DashboardAdmin/AdminController.php",
		dataType: "JSON",
		type: "POST",
		data: {
			request: "deban_user",
			userId: id,
		},
		success: function (response) {
			toastMixin.fire({
				position: "center",
				animation: true,
				title: response,
				icon: "success",
			});
			refreshAdminBans();
		},
		error: function () {
			console.log("errorUser");
		},
	});
}
//
//
//----------------- END Admin Ban functions -----------------//
//
//
//----------------- Start Admin Archives functions -----------------//

function displayAdminArchives() {
	clearIntervals();
	$.ajax({
		url: "/src/Controller/DashboardAdmin/AdminController.php",
		dataType: "JSON",
		type: "POST",
		data: {
			request: "display_admin_archives",
		},
		success: function (response) {
			$("#adminOfficeBody").html(response);
			$(".current-breadcrumb").html("archives");
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
		url: "/src/Controller/DashboardAdmin/AdminController.php",
		dataType: "JSON",
		type: "POST",
		data: {
			request: "admin_archives",
		},
		success: function (response) {
			$("#archivesTab").html(response);
			dataTableAdminArchives();
			$("#button-csv").on("click", exportArchivesCSV);
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
		url: "/src/Controller/DashboardAdmin/AdminController.php",
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
//----------------- Start Admin Archives functions -----------------//
//
//
//----------------- Start Admin Logs functions -----------------//

function displayLogs() {
	clearIntervals();
	$.ajax({
		url: "/src/Controller/DashboardAdmin/AdminController.php",
		dataType: "JSON",
		type: "POST",
		data: {
			request: "display_logs_tab",
		},
		success: function (response) {
			$("#adminOfficeBody").html(response);
			$(".current-breadcrumb").html("surveillance");
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
	$("#button-csv").on("click", exportLogsCSV);
}
//
//
function adminLogs() {
	$.ajax({
		url: "/src/Controller/DashboardAdmin/AdminController.php",
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
//----------------- END Admin Logs functions -----------------//
//
//
//----------------- Start Admin Settings functions -----------------//

function displaySettings() {
	clearIntervals();
	$.ajax({
		url: "/src/Controller/DashboardAdmin/AdminController.php",
		dataType: "JSON",
		type: "POST",
		data: {
			request: "display_settings",
		},
		success: function (response) {
			$("#adminOfficeBody").html(response);
			$(".current-breadcrumb").html("paramétrage");
			showSettings();
		},
		error: function () {},
	});
}
function showSettings() {
	$.ajax({
		url: "/src/Controller/DashboardAdmin/AdminController.php",
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
				url: "/src/Controller/DashboardAdmin/AdminController.php",
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
				url: "/src/Controller/DashboardAdmin/AdminController.php",
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
				url: "/src/Controller/DashboardAdmin/AdminController.php",
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
				url: "/src/Controller/DashboardAdmin/AdminController.php",
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
//
//
//----------------- Start Admin Settings functions -----------------//
