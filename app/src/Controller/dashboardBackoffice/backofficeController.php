<?php

session_start();

require $_SERVER['DOCUMENT_ROOT']."/src/Entity/Setting.php";
Setting::autoload();

$db = new Database();
$GLOBALS['Database'] = $db->connexion();

switch ($_POST['request']) {

    case 'display_backoffice':
        $html = LoadTechHTML::tabStructure();
        echo json_encode(array('html' => $html));

        break;

    case 'toHomeTech' :
        $msg = "";
        echo json_encode(1);
        break;

    case 'switch_day_rdv':
        $timestamp = Security::decrypt($_POST['timestamp'], false);
        $state = 0;
        $off7 = ($_POST['page'] - 1) * 5;
        $html = "<em style='font-size: x-large'>Aucun véhicule en attente</em>";
        $paginationHoldNext = PaginationHTML::dashTechPagination(0, $timestamp);
        $tab_cars = Intervention::check_rdv_switch_days($off7, $state, $timestamp);
        if (!empty($tab_cars)) {
            $html = "";
            foreach ($tab_cars as $car) {
                $html .= LoadTechHTML::pending($car['id_intervention'], $car['time_slot'], $car['brand_name'], $car['model_name'], $car['registration'], $timestamp);
            }
        }
        echo json_encode(array("html" => $html, "paginationHoldNext" => $paginationHoldNext, "time" => $timestamp));
        break;

    case 'generate_date_BO':
        $currentDate = Convert::current_date(); // Date du jour
        $html_day = GenerateDateHTML::dashTech(
            Convert::date_to_fullFR(),
            strtotime($currentDate)
        );
        if ($_POST['currentDate'] != 0) {
            $html_day = GenerateDateHTML::dashTech(
                Convert::date_to_fullFR($_POST['currentDate']),
                $_POST['currentDate']
            );
        }
        echo json_encode(array("html_day" => $html_day));
        break;


    case 'prise_en_charge':
        $status = 1;
        $user = new User(Security::decrypt($_SESSION['id'], false));
        $idUser = $user->getId_user();
        $msg = 'Véhicule pris en charge';
        echo json_encode(array("status" => $status, "msg" => $msg, "num_tech" => $idUser));
        break;

    case 'basculer_intervention':
        $msg = "Véhicule pris en charge";
        $intervention = new Intervention(Security::decrypt($_POST['idControle'], false));
        $user = new User($intervention->getId_user());
        $notif = new Notification();
        $checkNotify = $notif->check_if_notify($user->getId_user());
        if ($checkNotify['car']) {
            $sms = new SMS(0);
            $sms->setSMS_JobSupport($user, $intervention);
        }
        $intervention->setState(1);
        $intervention->setNum_tech($_POST['idTech']);
        $intervention->update();

        //Add traces in BDD
        $traces = new Trace(0);
        $traces->setTracesIN(Security::decrypt($_SESSION['id'], false), 'support', 'intervention');

        echo json_encode(array('msg' => $msg));
        break;

    case 'switch_toHold':
        $msg = "Retour en liste d'attente";
        $ct = new Intervention(Security::decrypt($_POST['idRdv'], false));
        $ct->setState(0);
        $ct->setNum_tech(0);
        $ct->update();

        //Add traces in BDD
        $traces = new Trace(0);
        $traces->setTracesIN(Security::decrypt($_SESSION['id'], false), 'hold', 'intervention');

        echo json_encode(array("msg" => $msg));
        break;

    default :
        echo json_encode(1);
        break;
}


