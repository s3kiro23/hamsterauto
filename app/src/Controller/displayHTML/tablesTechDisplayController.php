<?php

use HTML\GenerateDateHTML;
use HTML\LoadTechHTML;
use HTML\PaginationHTML;

session_start();

spl_autoload_register(function ($classe) {
    require '../../Entity/' . $classe . '.php';
});

require "../../Entity/HTML/LoadTechHTML.php";
require "../../Entity/HTML/PaginationHTML.php";
require "../../Entity/HTML/GenerateDateHTML.php";


$db = new Database();
$GLOBALS['Database'] = $db->connexion();

$check = Security::check_security();

if ($check != 'client') {

    switch ($_POST['request']) {

        /*Tableaux backoffice DEBUT*/

        case 'loadInterventionRecap' :
            setlocale(LC_TIME, "fr_FR", "French");
            if (empty($_POST['currentDate']) or is_null($_POST['currentDate'])) {
                $current_date = strtotime(date("d-m-Y"));
            } else {
                $current_date = $_POST['currentDate'];
            }

            $registration = isset($_POST['registration']) ? $registration = $_POST['registration'] : "";

            if ($_POST['type'] == "awaiting") {
                $timestamp = strtotime(date('d-m-Y'));
                $tab_awaiting = Intervention::check_all_rdv(PaginationHTML::off7($_POST['page']), 0, $registration, $current_date);
                $html_awaiting = $tab_awaiting ? "" : "<em style='font-size: x-large;'>Aucun véhicule en attente</em>";
                $pagination_awaiting = PaginationHTML::dashTechPagination(0, $current_date);
                foreach ($tab_awaiting as $awaiting) {
                    $html_awaiting .= LoadTechHTML::pending(
                        Security::encrypt($awaiting['id_intervention'], false),
                        $awaiting['time_slot'],
                        $awaiting['brand_name'],
                        $awaiting['model_name'],
                        $awaiting['registration'],
                        $current_date
                    );
                }
                echo json_encode(array(
                    'htmlAwaiting' => $html_awaiting,
                    'paginationAwaiting' => $pagination_awaiting,
                    'user' => $_SESSION['typeUser']
                ));
            }

            if ($_POST['type'] == "inprogress") {
                $pagination_in_progress = PaginationHTML::dashTechPagination(1, $current_date);
                $tab_in_progress = Intervention::check_all_rdv(PaginationHTML::off7($_POST['page']), 1, null, $current_date);
                $html_in_progress = $tab_in_progress ? "" : "<em style='font-size: x-large'>Pas d'interventions en cours</em>";
                foreach ($tab_in_progress as $in_progress) {
                    $tech = new User($in_progress['num_tech']);
                    $html_in_progress .= LoadTechHTML::inProgress(
                        $in_progress['id_intervention'],
                        $in_progress['time_slot'],
                        $tech->getFirstname_user(),
                        $in_progress['brand_name'],
                        $in_progress['registration']
                    );
                }
                echo json_encode(array(
                    'htmlInProgress' => $html_in_progress,
                    'paginationInProgress' => $pagination_in_progress,
                ));
            }

            if ($_POST['type'] == "archives") {
                $pagination_archives = PaginationHTML::dashTechPagination(2, $current_date);
                $tab_archives = Intervention::check_all_rdv(PaginationHTML::off7($_POST['page']), 2, null, $current_date);
                $html_archives = $tab_archives ? "" : "<em style='font-size: x-large'>Aucun historique disponible</em>";
                foreach ($tab_archives as $archive) {
                    $user = new User($archive['id_user']);
                    $html_archives .= LoadTechHTML::techHistory(
                        $archive['id_archive'],
                        $user->getLastname_user(),
                        $user->getPhone_user(),
                        $archive['registration'],
                        $archive['state']
                    );
                }
                echo json_encode(array(
                    'htmlTechHistory' => $html_archives,
                    'paginationTechHistory' => $pagination_archives,
                    'typeUser' => $_SESSION['typeUser']
                ));
            }

            break;

        case 'display_registration':
            $html = LoadTechHTML::filtre_registration();
            echo json_encode($html);
            break;

        case 'autorisation':
            $msg = "";
            $status = 1;
            echo json_encode(array('msg' => $msg, 'status' => $status));
            break;

        /*Tableaux backoffice FIN*/
    }
} else {
    session_destroy();
    $msg = "Accès interdit";
    $status = 0;
    echo json_encode(array('msg' => $msg, 'status' => $status));
}
