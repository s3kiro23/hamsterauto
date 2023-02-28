
let dataTableAdminRdv = function () {
    $('#tab-rdv-admin').DataTable({
        
        searching: false,
        "pageLength": 5,
        "lengthMenu": [[5, 10, 25, 50, 75, 100, -1], [5, 10, 25, 50, 75, 100, "All"]],
        retrieve: true,
        responsive: {
            details: {
                type: 'colomn',
                target: 'tr'
            }
        },
        columnDefs: [
            { className: "dt-head-center", targets: [0,1,2,3,4,5,6,7] },
            {responsivePriority: 1, targets: 2},
            {responsivePriority: 2, targets: 5},
            {responsivePriority: 3, targets: 6},
            {
                "targets": [0,5,7],
                "orderable": false
            }
        ],
        language: {
            "sEmptyTable": "Aucun rendez-vous",
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

let dataTableLogs = function () {
    $('#tab-logs').DataTable({
        searching: true,
        "pageLength": 5,
        "pagingType": "full",
        "lengthMenu": [[7, 10, 25, 50, 75, 100, -1], [7, 10, 25, 50, 75, 100, "All"]],
        retrieve: true,
        responsive: {
            details: {
                type: 'colomn',
                target: 'tr'
            }
        },
        columns: [
            {type:"text"},  
            {type:"text"},
            {type:"num"}         
        ],
        order:[[2,'desc']],
        columnDefs: [
            { className: "dt-head-center", targets: [0,1,2] },
            {responsivePriority: 1, targets: 0},
            {responsivePriority: 2, targets: -1},
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

let dataTableAdminUsers = function () {

    $('#tab-admin-users').DataTable({
        searching: false,
        "pageLength": 5,
        "lengthMenu": [[5, 10, 25, 50, 75, 100, -1], [5, 10, 25, 50, 75, 100, "All"]],
        retrieve: true,
        responsive: {
            details: {
                type: 'colomn',
                target: 'tr'
            }
        },
        columnDefs: [
            { className: "dt-center", targets: [0,1,2,3,4,5,6,7,8] },
            {responsivePriority: 1, targets: 0},
            {responsivePriority: 2, targets: 8},
            {responsivePriority: 3, targets: 7},
            {
                "targets": [2,3,4,6,7,8],
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

let dataTableBans = function () {
    $('#tab-bans').DataTable({
        
        searching: true,
        "pageLength": 7,
        "lengthMenu": [[7, 10, 25, 50, 75, 100, -1], [7, 10, 25, 50, 75, 100, "All"]],
        retrieve: true,
        responsive: {
            details: {
                type: 'colomn',
                target: 'tr'
            }
        },
        columns: [
            {type:"text"},  
            {type:"text"},
            {type:"num"}         
        ],
        order:[[2,'desc']],
        columnDefs: [
            { className: "dt-head-center", targets: [0,1,2,3] },
            {responsivePriority: 1, targets: 0},
            {responsivePriority: 2, targets: -1},
            {
                "targets": [0,1,2,3],
                
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

let dataTableAdminArchives = function () {
    $('#tab-admin-archives').DataTable({
        searching: true,
        "pageLength": 7,
        "lengthMenu": [[7, 10, 25, 50, 75, 100, -1], [7, 10, 25, 50, 75, 100, "All"]],
        retrieve: true,
        responsive: {
            details: {
                type: 'colomn',
                target: 'tr'
            }
        },
        columnDefs: [
            { className: "dt-center", targets: [0,1,2,3,4] },
            {responsivePriority: 1, targets: 0},
            {responsivePriority: 2, targets: -1},
            {
                "targets": [0,1,2,3,4],
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

