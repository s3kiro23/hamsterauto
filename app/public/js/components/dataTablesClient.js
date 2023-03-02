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
        // var pageInfo = settings.json;
        // var startIndex = pageInfo.start;
        // var endIndex = startIndex + pageInfo.length - 1;
        // var totalRecords = pageInfo.recordsTotal;
        // var filteredRecords = pageInfo.recordsFiltered;

        // // console.log(startIndex);
        // // console.log(endIndex);
        // // console.log(totalRecords);
        // // console.log(filteredRecords);
        // // console.log(pageInfo.length);

        // var currentPage = Math.ceil((startIndex + 1) / pageInfo.length);
        // var totalPages = Math.ceil(totalRecords / pageInfo.length);
        // // console.log(totalPages);

        // var pagingControls = '<ul class="pagination"><li id="tab-car_previous" class="paginate_button page-item previous ' + (currentPage == 1 ? "disabled" : "") + '"><a href="#" aria-controls="tab-car" class="page-link" data-dt-idx="previous" tabindex="0">Previous</a></li>';

        // for (var i = 1; i <= totalPages; i++) {
        //     pagingControls += '<li class="paginate_button page-item ' + (i == currentPage ? "active" : "") + '"><a href="#" aria-controls="tab-car" data-dt-idx='+ i +' tabindex="0" class="page-link">' + i + '</a></li>';
        // }

        // pagingControls += '<li id="tab-car_next" class="paginate_button page-item next ' + (currentPage == totalPages ? "disabled" : "") + '"><a href="#" class="page-link" data-dt-idx="next" tabindex="0">Next</a></li></ul>';
        
        // $('.dataTables_paginate').html(pagingControls);

        // $('.pagination li a').on('click', function () {
        //     var page = $(this).data('dt-idx');
        //     console.log(page);
        //     // Traitez la valeur de la page ici...
        //     // Effectuez l'appel AJAX avec la nouvelle valeur de la page
        //     $("#tab-car").DataTable().ajax.url('../src/Controller/DashboardClient/ClientCarController.php?start=' + page).load();
        // });

        
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
