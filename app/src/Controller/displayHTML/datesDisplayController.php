<?php session_start();
require_once '../../Controller/shared.php';

spl_autoload_register(function ($classe) {
    require '../../Entity/' . $classe . '.php';
});

$db = new Database();
$GLOBALS['Database'] = $db->connexion();

switch ($_POST['request']) {

    case 'generate_date':
        setlocale(LC_TIME, "fr_FR", "French");
        $currentDate = date('d-m-Y');
        $updatedDate = (utf8_encode(strftime("%A %d %B %G", strtotime($currentDate))));
        $settings = Setting::getSettings();
        $html_slot = "";
        $jourFerme = 'dimanche';
        $tab_reserved = [];
        $tech = 4;

        if (empty($_POST['currentDate'])) {
            if (strstr($updatedDate, $jourFerme)) {
                $tab_available = [];
                $html_slot = "Nous sommes fermés";
            } else {
                $tab_available = ControleTech::generateSlotAvailable(($currentDate));
            }
            /*Récupération des créneaux réservés en BDD*/
            $timeSlotCheck = ControleTech::checkTimeSlotReserved(strtotime($currentDate));
            if ($timeSlotCheck) {
                for ($a = 0; $a <= count($timeSlotCheck) - 1; $a++) {
                    if ((int)$timeSlotCheck[$a]['time_slot'] > strtotime($currentDate)) {
                        $tab_reserved[] = (int)$timeSlotCheck[$a]['time_slot'];
                    }
                }
            }
            /*Génération du jour en cours*/
            $html_day = HTML::generateDate(utf8_encode(strftime("%A %d %B %G", strtotime($currentDate))), strtotime($currentDate));
        } else {
            /*Récupération des créneaux réservés en BDD*/
            $timeSlotCheck = ControleTech::checkTimeSlotReserved($_POST['currentDate']);
            if ($timeSlotCheck) {
                for ($a = 0; $a <= count($timeSlotCheck) - 1; $a++) {
                    if ((int)$timeSlotCheck[$a]['time_slot'] > strtotime($currentDate)) {
                        $tab_reserved[] = (int)$timeSlotCheck[$a]['time_slot'];
                    }
                }
            }
            /*Génération du jour en cours*/
            $updatedDate = (utf8_encode(strftime("%A %d %B %G", $_POST['currentDate'])));
            $dayOff = explode(" ", $updatedDate);
            $html_day = HTML::generateDate($updatedDate, $_POST['currentDate']);
            /*Génération des créneaux horaires dispo | date courante uniquement */
            if (strstr($updatedDate, $jourFerme) !== false) {
                $tab_available = [];
                $html_slot = "Nous sommes fermés";
            } else {
                $tab_available = ControleTech::generateSlotUpdate($_POST['currentDate']);
            }
        }
        /*Comparaison des slots disponible avec ceux réservés et suppression des match*/
        foreach (array_count_values($tab_reserved) as $timeSlot => $item) {
            if (array_count_values($tab_reserved)[$timeSlot] == $settings['nb_lifts']) {
                $tab_available = array_diff($tab_available, (array)$timeSlot);
            }
        }
        /*Génération des slots disponible suite comparaison des tab_available et reserved*/
        foreach ($tab_available as $item) {
            $html_slot .= HTML::timeSlot($item, date("H:i", $item));
        }
        if (empty($html_slot))
            $html_slot = "Aucun créneau horaire dispo";
        echo json_encode(array("html_day" => $html_day, "html_slot" => $html_slot));
        break;

    default :
        echo json_encode(1);
        break;

}