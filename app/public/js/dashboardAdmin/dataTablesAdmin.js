// START DataTables Admin Intervention //

let dataTableAdminRdv = function () {
	$("#tab-rdv-admin").DataTable({
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
			{ className: "dt-head-center", targets: [0, 1, 2, 3, 4, 5, 6, 7] },
			{ responsivePriority: 1, targets: 2 },
			{ responsivePriority: 2, targets: 5 },
			{ responsivePriority: 3, targets: 6 },
			{
				targets: [0, 5, 7],
				orderable: false,
			},
		],
		processing: false,
		serverSide: true,
		// Ajax call
		ajax: {
			url: "../src/Controller/DashboardClient/ClientCarController.php",
			type: "POST",
			data: function (d) {
				d.sSearch = $('input[type="search"]').val();
			},
			// success: function (data){
			//     console.log(data)
			// }
		},
		drawCallback: function (settings) {
			activateButtons();

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
				'<ul class="pagination"><li id="tab-car_previous" class="paginate_button page-item previous ' +
				(currentPage == 1 ? "disabled" : "") +
				'"><a aria-controls="tab-car" class="cursor-pointer page-link" data-dt-idx="previous" tabindex="0">Previous</a></li>';

			//Generate buttons page
			for (var i = 1; i <= totalPages; i++) {
				pagingControls +=
					'<li class="paginate_button page-item ' +
					(i == currentPage ? "active" : "") +
					'"><a aria-controls="tab-car" data-dt-idx=' +
					i +
					' tabindex="0" class="cursor-pointer page-link">' +
					i +
					"</a></li>";
			}

			//Btn next
			pagingControls +=
				'<li id="tab-car_next" class="paginate_button page-item next ' +
				(currentPage == totalPages ? "disabled" : "") +
				'"><a class="cursor-pointer page-link" data-dt-idx="next" tabindex="0">Next</a></li></ul>';

			//Write html pagination
			$("#tab-car_wrapper .dataTables_paginate").html(pagingControls);
			$("#tab-car_wrapper .dataTables_info").html(displayText);

			//Callback for pagination
			$("#tab-car_wrapper .pagination li a").on("click", function () {
				var page = $(this).data("dt-idx");
				// Call Ajax with new value
				$("#tab-car")
					.DataTable()
					.ajax.url(
						"../src/Controller/DashboardClient/ClientCarController.php?start=" +
							page
					)
					.load();
			});
		},
		language: {
			sEmptyTable: "Aucun rendez-vous",
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
};
// END DataTables Admin Intervention //

// START DataTables Admin Logs //
let dataTableLogs = function () {
	$("#tab-logs").DataTable({
		searching: true,
		pageLength: 5,
		pagingType: "full",
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
		columns: [{ type: "text" }, { type: "text" }, { type: "num" }],
		order: [[2, "desc"]],
		columnDefs: [
			{ className: "dt-head-center", targets: [0, 1, 2] },
			{ responsivePriority: 1, targets: 0 },
			{ responsivePriority: 2, targets: -1 },
		],
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
};

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
		var endIndex = Math.min(startIndex + filteredRecords - 1, totalRecords - 1);
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
			(currentPage == totalPages ? "disabled" : "") +
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
					"../src/Controller/DashboardAdmin/UserController.php?start=" +
						page
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
			sSortDescending: ": activer pour trier la colonne par ordre décroissant",
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
// END DataTables Admin Logs //

// START DataTables Admin Bans //
let dataTableBans = function () {
	$("#tab-bans").DataTable({
		searching: true,
		pageLength: 7,
		lengthMenu: [
			[7, 10, 25, 50, 75, 100, -1],
			[7, 10, 25, 50, 75, 100, "All"],
		],
		retrieve: true,
		responsive: {
			details: {
				type: "colomn",
				target: "tr",
			},
		},
		columns: [{ type: "text" }, { type: "text" }, { type: "num" }],
		order: [[2, "desc"]],
		columnDefs: [
			{ className: "dt-head-center", targets: [0, 1, 2, 3] },
			{ responsivePriority: 1, targets: 0 },
			{ responsivePriority: 2, targets: -1 },
			{
				targets: [0, 1, 2, 3],

				orderable: false,
			},
		],
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
};

let dataTableAdminArchives = function () {
	$("#tab-admin-archives").DataTable({
		searching: true,
		pageLength: 7,
		lengthMenu: [
			[7, 10, 25, 50, 75, 100, -1],
			[7, 10, 25, 50, 75, 100, "All"],
		],
		retrieve: true,
		responsive: {
			details: {
				type: "colomn",
				target: "tr",
			},
		},
		columnDefs: [
			{ className: "dt-center", targets: [0, 1, 2, 3, 4] },
			{ responsivePriority: 1, targets: 0 },
			{ responsivePriority: 2, targets: -1 },
		],
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
};
// END DataTables Admin Bans //
