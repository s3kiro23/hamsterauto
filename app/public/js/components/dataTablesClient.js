let dataTableClient = function (type) {
    let targetTab;
    type === 'car' ? targetTab = $('#tab-car') : targetTab = $('#tab-rdv');
    targetTab.DataTable({
        "pageLength": 3,
        "lengthMenu": [[3, 5, 10, 25, 50, 75, 100, -1], [3, 5, 10, 25, 50, 75, 100, "All"]],
        retrieve: true,
        responsive: {
            details: {
                type: 'none'
            }
        },
        columnDefs: [
            { className: "dt-head-center", targets: [0,1,2,3] },
            {responsivePriority: 1, targets: 0},
            type === 'car' ? {responsivePriority: 2, targets: -1} : {responsivePriority: 2, targets: 2},
            {
                "targets": 3,
                "orderable": false
            }
        ],
        language: {
            "sEmptyTable": "Aucunes données n'est disponible",
            "sInfo": "Affichage de l'élément _START_ à _END_ sur _TOTAL_ éléments",
            "sInfoEmpty": "Affichage de l'élément 0 à 0 sur 0 élément",
            "sInfoFiltered": "(filtré à partir de _MAX_ éléments au total)",
            "sInfoPostFix": "",
            "sInfoThousands": ",",
            "sLengthMenu": "Afficher _MENU_ éléments",
            "sLoadingRecords": "Chargement...",
            "sProcessing": "Traitement...",
            "sSearch": "Rechercher :",
            "sZeroRecords": "Aucun élément correspondant trouvé",
            "oPaginate": {
                "sFirst": "Premier",
                "sLast": "Dernier",
                "sNext": "Suivant",
                "sPrevious": "Précédent"
            },
            "oAria": {
                "sSortAscending": ": activer pour trier la colonne par ordre croissant",
                "sSortDescending": ": activer pour trier la colonne par ordre décroissant"
            },
            "select": {
                "rows": {
                    "_": "%d lignes sélectionnées",
                    "0": "Aucune ligne sélectionnée",
                    "1": "1 ligne sélectionnée"
                }
            }
        }
    });
}

