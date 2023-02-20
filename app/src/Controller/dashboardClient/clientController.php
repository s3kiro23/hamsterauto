<?php

use HTML\MenuHTML;

require "../../Entity/HTML/MenuHTML.php";

session_start();

if (!isset($_SESSION['id'])) {
    $status = 2;
    $msg = "Vous n'êtes pas authentifié";
    session_unset();
    session_destroy();
    echo json_encode(array('msg' => $msg, 'status' => $status));
} else {

    spl_autoload_register(function ($classe) {
        require '../../Entity/' . $classe . '.php';
    });

    $db = new Database();
    $GLOBALS['db'] = $db->connexion();

    switch ($_POST['request']) {

            /*Cases de redirection liens header DEBUT*/
        case 'generateNavbar':
            $status = 1;
            $user = new User(Security::decrypt($_SESSION['id'], false));
            $user_info = [
                "firstName" => $user->getFirstname_user(),
                "lastName" => $user->getLastname_user(),
                "image" => $user->getImg_profile()
            ];
            $navbar_HTML = MenuHTML::navBarType($user);
            echo json_encode(array("navbarHTML" => $navbar_HTML, "status" => $status, "userInfo" => $user_info));
            break;

        case 'notificationManager':
            $id = Security::decrypt($_SESSION['id'], false);
            $notif = new Notification();
            $result = $notif->check_if_notify($id);

            echo json_encode($result);
            break;

        case 'notificationModify':
            $id = Security::decrypt($_SESSION['id'], false);
            $user = new User($id);
            $notif = new Notification();
            $notification_types = [
                'btn-rdv' => 'next_rdv',
                'btn-confirmed' => 'confirmed_rdv',
                'btn-deleted' => 'deleted_rdv',
                'btn-finished' => 'finished_rdv',
                'btn-car' => 'car_support',
                'btn-control' => 'next_control',
                'btn-pv' => 'send_pv',
            ];

            if (is_array($_POST['values'])) {
                foreach ($_POST['values'] as $id_value) {
                    if (isset($notification_types[$id_value])) {
                        $notification_list = json_decode($notif->getData($notification_types[$id_value]), true);
                        if (!in_array($id, $notification_list)) {
                            $notification_list[] = $id;
                        } else {
                            unset($notification_list[array_search($id, $notification_list)]);
                        }
                        $notif->setData($notification_types[$id_value], json_encode($notification_list));
                        $notif->update();
                    }
                }
            } elseif (isset($notification_types[$_POST['values']])) {
                $notification_list = json_decode($notif->getData($notification_types[$_POST['values']]), true);
                if (!in_array($id, $notification_list)) {
                    $notification_list[] = $id;
                } else {
                    unset($notification_list[array_search($id, $notification_list)]);
                }
                $notif->setData($notification_types[$_POST['values']], json_encode($notification_list));
                $notif->update();
            }
            //Add traces in BDD
            $traces = new Trace(0);
            $traces->setTracesIN($id, 'change', 'notification');

            echo json_encode(1);
            break;


        case 'to_login':
            $msg = "Redirection vers la page de connexion";
            echo json_encode(array("msg" => $msg));
            break;

        case 'modalUploadCG':
            $_SESSION['carID'] = $_POST['carID'];
            echo json_encode(1);
            break;
    }
}
