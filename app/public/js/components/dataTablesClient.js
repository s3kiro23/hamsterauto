function activateButtons() {
	$(".addCG").on("click", modalCG);
	$(".modifyCar").on("click", modalModifyCar);
	$(".deleteCar").on("click", deleteCar);
}

let dataTableCars = $("#tab-car").DataTable({
	pageLength: 3,
	paging: true,
	lengthMenu: [
		[3, 5, 10, 25, 50, 75, 100, -1],
		[3, 5, 10, 25, 50, 75, 100, "All"],
	],
	searching: true,
	retrieve: true,
	destroy: true,
	responsive: {
		details: {
			type: "colomn",
			target: "tr",
		},
	},
	order: [[2, "asc"]],
	columnDefs: [
		{ name: "brand", targets: 0 },
		{ name: "model", targets: 1 },
		{ name: "registration", targets: 2 },
		{ name: "action", targets: 3 },
		{ className: "dt-head-center text-center", targets: [0, 1, 2, 3] },
		{ responsivePriority: 1, targets: 0 },
		{ responsivePriority: 2, targets: -1 },
		{
			targets: [0, 3],
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
		if (settings.oPreviousSearch.sSearch !== '') {
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

let dataTableRdv = $("#tab-rdv").DataTable({
	pageLength: 3,
	lengthMenu: [
		[3, 5, 10, 25, 50, 75, 100, -1],
		[3, 5, 10, 25, 50, 75, 100, "All"],
	],
	searching: true,
	retrieve: true,
	destroy: true,
	responsive: {
		details: {
			type: "colomn",
			target: "tr",
		},
	},
	columnDefs: [
		{ name: "date", targets: 0 },
		{ name: "registration", targets: 1 },
		{ name: "statut", targets: 2 },
		{ name: "action", targets: 3 },
		{ className: "dt-head-center text-center", targets: [0, 1, 2, 3] },
		{ responsivePriority: 1, targets: 0 },
		{ responsivePriority: 2, targets: 2 },
		{
			targets: 3,
			orderable: false,
		},
	],
	processing: false,
	serverSide: true,
	// Ajax call
	ajax: {
		url: "../src/Controller/DashboardClient/ClientRdvController.php",
		type: "POST",
		data: function (d) {
			d.sSearch = $('input[type="search"]').val();
		},
	},
	drawCallback: function (settings) {
		activateButtons();

		//Pagination buttons
		var pageInfo = settings.json;
		var startIndex = pageInfo.start;
		var totalRecords = pageInfo.recordsTotal;
		var filteredRecords = pageInfo.recordsFiltered;

		// Handle case when only one result is returned by the search
		if (settings.oPreviousSearch.sSearch !== '') {
			if (filteredRecords == 1) {
				totalRecords = 1;
			} else {
				totalRecords = filteredRecords;
			}
		}

		//Calcul nb total page
		var currentPage = Math.ceil((startIndex + 1) / pageInfo.length);
		var totalPages = Math.ceil(totalRecords / pageInfo.length);

		//Calcul indices start/end of display elements
		var endIndex = Math.min(startIndex + pageInfo.length - 1, totalRecords - 1);
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
			'<ul class="pagination"><li id="tab-rdv_previous" class="paginate_button page-item previous ' +
			(currentPage == 1 ? "disabled" : "") +
			'"><a aria-controls="tab-rdv" class="cursor-pointer page-link" data-dt-idx="previous" tabindex="0">Previous</a></li>';

		//Generate buttons page
		for (var i = 1; i <= totalPages; i++) {
			pagingControls +=
				'<li class="paginate_button page-item ' +
				(i == currentPage ? "active" : "") +
				'"><a aria-controls="tab-rdv" data-dt-idx=' +
				i +
				' tabindex="0" class="cursor-pointer page-link">' +
				i +
				"</a></li>";
		}

		//Btn next
		pagingControls +=
			'<li id="tab-rdv_next" class="paginate_button page-item next ' +
			(currentPage == totalPages ? "disabled" : "") +
			'"><a class="cursor-pointer page-link" data-dt-idx="next" tabindex="0">Next</a></li></ul>';

		//Write html pagination
		$("#tab-rdv_wrapper .dataTables_paginate").html(pagingControls);
		$("#tab-rdv_wrapper .dataTables_info").html(displayText);

		//Callback for pagination
		$("#tab-rdv_wrapper .pagination li a").on("click", function () {
			var page = $(this).data("dt-idx");
			// Call Ajax with new value
			$("#tab-rdv")
				.DataTable()
				.ajax.url(
					"../src/Controller/DashboardClient/ClientRdvController.php?start=" +
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
