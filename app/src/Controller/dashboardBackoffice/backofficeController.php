<?php 
session_start();
$currentTime = time();
if(!isset($_SESSION['id']) || $currentTime > $_SESSION['expire']) {
    $status = 2;
    $msg = 'Nécessite une authentification, retour à la page de connexion';
    session_unset();
    session_destroy();
    echo json_encode(array('msg' => $msg, 'status' => $status));
}else {
   

require_once '../../Controller/shared.php';
spl_autoload_register(function ($classe) {
    require '../../Entity/' . $classe . '.php';
});

$db = new Database();
$GLOBALS['Database'] = $db->connexion();

    switch ($_POST['request']) {

        case 'toHomeTech' :
            $msg = "";
            echo json_encode(1);
            break;

        case 'next_day_rdv':
            $status = 0;
            $state = 0;
            $off7 = ($_POST['page'] - 1) * 5;
            $html = '';
            $htmlVide = HTML::listeVide();
            $nbr_of_rdv = ControleTech::countRdv($state, $_POST['nextDate']);
            $totalPages = ceil($nbr_of_rdv / 5);
            $paginationHoldNext = "";
            $tab_cars = ControleTech::checkRdvNextDays($off7, $state, $_POST['nextDate']);
            foreach ($tab_cars as $car) {
                $html .= HTML::loadInterventions($car['id_controle'], $car['time_slot'], $car['nom_marque'], $car['nom_modele'], $car['immat_vehicule'], $_POST['nextDate']);
                $status = 1;
            }
            for ($i = 1; $i <= $totalPages; $i++) {
                $paginationHoldNext .= HTML::rdvPages($i, $i, $state);
            }
            echo json_encode(array("status" => $status, "msg" => $html, "msg2" => $htmlVide, "paginationHoldNext" => $paginationHoldNext));
            break;

        case 'previous_day_rdv':
            $status = 0;
            $state = 0;
            $off7 = ($_POST['page'] - 1) * 5;
            $html = '';
            $htmlVide = HTML::listeVide();
            $nbr_of_rdv = ControleTech::countRdv($state, $_POST['previousDate']);
            $totalPages = ceil($nbr_of_rdv / 5);
            $paginationHoldPrevious = "";
            $tab_cars = ControleTech::checkRdvPreviousDays($off7, $state, $_POST['previousDate']);
            foreach ($tab_cars as $car) {
                $html .= HTML::loadInterventions($car['id_controle'], $car['time_slot'], $car['nom_marque'], $car['nom_modele'], $car['immat_vehicule'], $_POST['previousDate']);
                $status = 1;
            }
            for ($i = 1; $i <= $totalPages; $i++) {
                $paginationHoldPrevious .= HTML::rdvPages($i, $i, $state);
            }
            echo json_encode(array("status" => $status, "msg" => $html, "msg2" => $htmlVide, "paginationHoldPrevious" => $paginationHoldPrevious));
            break;

        case 'pageRefresh':
            $status = 0;
            $state = 0;
            $off7 = ($_POST['page'] - 1) * 5;
            $html = '';
            $paginationHoldRefresh = "";
            $nbr_of_rdv = ControleTech::countRdv($state, $_POST['currentDate']);
            $totalPages = ceil($nbr_of_rdv / 5);
            $tab_cars = ControleTech::checkRdvPreviousDays($off7, $state, $_POST['currentDate']);
            foreach ($tab_cars as $car) {
                $html .= HTML::loadInterventions($car['id_controle'], $car['time_slot'], $car['nom_marque'], $car['nom_modele'], $car['immat_vehicule'], $_POST['currentDate']);
                $status = 1;
            }
            for ($i = 1; $i <= $totalPages; $i++) {
                $paginationHoldRefresh .= HTML::rdvPages($i, $i, $state);
            }
            echo json_encode(array("status" => $status, "msg" => $html, 'paginationHoldRefresh' => $paginationHoldRefresh));
            break;

        case 'generate_date_BO':
            setlocale(LC_TIME, "fr_FR", "French");
            $currentDate = date('d-m-Y'); // Date du jour
            $html_slot = "";
            $tab_reserved = [];
            if (empty($_POST['currentDate'])) {
                $timeSettings = Setting::getSettings();
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
                $html_day = HTML::generateDateBackOffice(utf8_encode(strftime("%A %d %B %G", strtotime($currentDate))), strtotime($currentDate));
                /*Génération des créneaux horaires dispo | date courante uniquement */
                $tab_available = ControleTech::generateSlotAvailable($currentDate);
            } else {
                /*Génération du jour en cours*/
                $updatedDate = utf8_encode(strftime("%A %d %B %G", $_POST['currentDate']));
                $html_day = HTML::generateDateBackOffice($updatedDate, $_POST['currentDate']);
            }
            echo json_encode(array("html_day" => $html_day));
            break;

        
        case 'prise_en_charge':
            $status = 1;
            $user = new User(decrypt($_SESSION['id'], false));
            $idUser = $user->getId_user();
            $idControle = $_POST['idControle'];
            $msg = 'Véhicule pris en charge';
            echo json_encode(array("status" => $status, "msg" => $msg, "num_tech" => $idUser));
            break;

        case 'basculer_intervention':
            $msg = "Véhicule pris en charge";
            $idControle = $_POST['idControle'];
            $idTech = $_POST['idTech'];
            $requete = "UPDATE `controle_tech` SET `state`='" . mysqli_real_escape_string($GLOBALS['Database'], '1') . "', 
                    `num_tech`='" . mysqli_real_escape_string($GLOBALS['Database'], $idTech) . "' 
                    WHERE `id_controle`='" . mysqli_real_escape_string($GLOBALS['Database'], $idControle) . "'";
            error_log($requete);
            $result = mysqli_query($GLOBALS['Database'], $requete) or die;
            echo json_encode(array('msg' => $msg));
            break;

        case 'switch_toHold':
            $msg = "Retour en liste d'attente";
            $ct = new ControleTech($_POST['idRdv']);
            $ct->setState(0);
            $ct->setNum_tech(0);
            $ct->update();
            echo json_encode(array("msg" => $msg));
            break;

        default :
            echo json_encode(1);
            break;
    }
}

