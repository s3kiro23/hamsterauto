<?php

use HTML\GenerateDateHTML;

session_start();

spl_autoload_register(function ($classe) {
    require '../../Entity/' . $classe . '.php';
});

require "../../Entity/HTML/GenerateDateHTML.php";

$db = new Database();
$GLOBALS['Database'] = $db->connexion();

switch ($_POST['request']) {

    case 'generate_date':
        $current_date = Convert::current_date();
        $current_date_to_fullFR = Convert::date_to_fullFR();
        $settings = Setting::get_settings();
        $html_slot = "";
        $closed_day = 'dimanche';
        $tab_reserved = [];
        $current_year = Convert::check_year($_POST['currentDate']);
        $dateDropper_format = date('m-d-Y');

        if (empty($_POST['currentDate'])) {
            if (strstr($current_date_to_fullFR, $closed_day)) {
                $tab_available = [];
                $html_slot = "Nous sommes fermés";
            } else {
                $tab_available = Intervention::generate_slot_available(($current_date));
            }
            /*Récupération des créneaux réservés en BDD*/
            $timeslot_check = Intervention::check_timeslot_reserved(strtotime($current_date));
            if ($timeslot_check) {
                for ($a = 0; $a <= count($timeslot_check) - 1; $a++) {
                    if ((int)$timeslot_check[$a]['time_slot'] > strtotime($current_date)) {
                        $tab_reserved[] = (int)$timeslot_check[$a]['time_slot'];
                    }
                }
            }
            /*Génération du jour en cours*/
            $html_day = GenerateDateHTML::formClient($current_date_to_fullFR, strtotime($current_date));
        } else {
            /*Récupération des créneaux réservés en BDD*/
            $timeslot_check = Intervention::check_timeslot_reserved($_POST['currentDate']);
            if ($timeslot_check) {
                for ($a = 0; $a <= count($timeslot_check) - 1; $a++) {
                    if ((int)$timeslot_check[$a]['time_slot'] > strtotime($current_date)) {
                        $tab_reserved[] = (int)$timeslot_check[$a]['time_slot'];
                    }
                }
            }
            /*Génération du jour en cours*/
            $selected_date_to_fullFR = Convert::date_to_fullFR($_POST['currentDate']);
            $html_day = GenerateDateHTML::formClient($selected_date_to_fullFR, $_POST['currentDate']);
            /*Génération des créneaux horaires dispo | date courante uniquement */
            if (strstr($selected_date_to_fullFR, $closed_day) !== false) {
                $tab_available = [];
                $html_slot = "Nous sommes fermés";
            } else {
                $tab_available = Intervention::generate_slot_update($_POST['currentDate']);
            }
            $dateDropper_format = date('m-d-Y', $_POST['currentDate']);
        }
        /*Comparaison des slots disponible avec ceux réservés et suppression des match*/
        $counts = array_count_values($tab_reserved);
        foreach ($counts as $timeslot => $count) {
            if ($count == $settings['nb_lift']) {
                $tab_available = array_diff($tab_available, [$timeslot]);
            }
        }
        /*Génération des slots disponible suite comparaison des tab_available et reserved*/
        foreach ($tab_available as $item) {
            $html_slot .= GenerateDateHTML::timeSlot(Security::encrypt($item, ""), date("H:i", $item));
        }

        if (empty($html_slot))
            $html_slot = "Aucun créneau horaire dispo";

        echo json_encode(array("html_day" => $html_day, "html_slot" => $html_slot, "currentYear" => $current_year, "dateDropperFormat" => $dateDropper_format));

        break;

    default :
        echo json_encode(1);
        break;

}
