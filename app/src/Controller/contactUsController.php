<?php

use HTML\ContactHTML;

spl_autoload_register(function ($classe) {
    require '../Entity/' . $classe . '.php';
});

require "../Entity/HTML/ContactHTML.php";

$db = new Database();
$GLOBALS['db'] = $db->connexion();

switch ($_POST['request']) {

    case 'contact-form':
        $data = json_decode($_POST['tabInput'], true);
        $status = 1;
        $msg = ContactHTML::messageHamster();
        $init_control = new Control();
        $check = $init_control->check_fields($data);

        if ($check['status'] == 0) {
            $msg = $check['msg'];
            $status = $check['status'];
        } else {
            //Add Job mail in Queue table
            $mail = new Mailing();
            $mail->setContact_Job($data);
        }

        echo json_encode(array("status" => $status, "msg" => $msg));
        break;

    case 'getTimes':

        $dataBIA = [];
        $dataAJA = [];
        $times = new Setting(1);
        $opening = date("H:i", $times->getStart_time_am());
        $close = date("H:i", $times->getEnd_time_pm());
        $days = ["Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi"];
        $coordinates = json_decode($times->getCoordinates(), true);
        $html_content = "";
        foreach ($days as $day) {
            $html_content .= $day . ' ' . $opening . ' – ' . $close . '<br>';
        }
        $content = ContactHTML::mapContent($coordinates, $html_content);

        echo json_encode(array("content" => $content, "coordinates" => $coordinates));
        break;

    default :

        echo json_encode(1);
        break;

}
