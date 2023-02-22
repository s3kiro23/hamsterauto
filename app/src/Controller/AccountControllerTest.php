<?php
session_start();

spl_autoload_register(function ($classe) {
    require '../Entity/' . $classe . '.php';
});

$db = new Database();
$GLOBALS['Database'] = $db->connexion();

switch ($_POST['request']) {

    case 'profil_content' :
        $user = new User(Security::decrypt($_SESSION['id'], false));
        echo json_encode(array("login" => $user->getEmail_user(),
            "lastname" => $user->getLastname_user(),
            "firstname" => $user->getFirstname_user(),
            "phone" => $user->getPhone_user(),
            "adress" => $user->getAdress_user(),
            "a2f" => $user->getA2f(),
            "image" => $user->getImg_profile(),
        ));

        break;

    case 'modify' :
        $data = json_decode($_POST['values'], true);
        $init_control = new Control();
        $check = $init_control->check_fields($data);

        if ($check['status'] == 0) {
            $msg = $check['msg'];
            $status = $check['status'];
        } else {
            $status = 1;
            $msg = 'Les modifications ont bien été prises en compte!';
            $user = new User(Security::decrypt($_SESSION['id'], false));
            $user->setEmail_user($data['inputLogin']);
            $user->setLastname_user($data['inputNom']);
            $user->setFirstname_user($data['inputPrenom']);
            $user->setPhone_user($data['inputTel']);
            $user->setAdress_user($data['inputAddr']);
            $user->update();

            //Add traces in BDD
            $traces = new Trace(0);
            $traces->setTracesIN(Security::decrypt($_SESSION['id'], false), 'modify', 'account');
        }

        echo json_encode(array("status" => $status, "msg" => $msg));
        break;

    case 'disableAccount' :
        $msg = "Erreur";
        $id = Security::decrypt($_SESSION['id'], false);
        if (isset($id) && !empty($id)) {
            $user = new User($id);
            $notif = new Notification();
            $notification_user = $notif->check_if_notify($id);
            $notif->uncheck_notification($notification_user, $id);
            $car_check = count(User::check_cars($id, ""));
            $rdv_check = count(User::check_rdv($id, ""));
            $user->disable($car_check, $rdv_check);

            //Add traces in BDD
            $traces = new Trace(0);
            $traces->setTracesIN($id, 'disabled', 'account');

            $msg = 'Success';
        }
        echo json_encode(array('msg' => $msg));
        break;

    case 'activationA2F':
        $status = 0;
        $msg = "Désactivation 2FA réussie !";
        $id = Security::decrypt($_SESSION['id'], false);
        $user = new User($id);

        //Add traces in BDD
        $traces = new Trace(0);

        if (!$user->getA2f()) {
            $user->setA2f(1);
            $status = 1;
            $msg = "Activation 2FA réussie !";
            $traces->setTracesIN($id, 'a2f-enabled', 'security');
        } else {
            $user->setA2f(0);
            $traces->setTracesIN($id, 'a2f-disabled', 'security');
        }
        $user->update();

        echo json_encode(array("status" => $status, "msg" => $msg));
        break;
}



