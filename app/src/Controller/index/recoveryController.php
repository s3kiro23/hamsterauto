<?php

session_start();

require $_SERVER['DOCUMENT_ROOT']."/src/Entity/Setting.php";
Setting::autoload();

$db = new Database();
$GLOBALS['Database'] = $db->connexion();

switch ($_POST['request']) {

    case 'toRequestMail':
        $msg = "Demande de nouveau mot de passe en cours!";
        $content_forgot = RequestHTML::toRequestMail();
        echo json_encode(array("msg" => $msg, "contentForgot" => $content_forgot));
        break;

    case 'genToken':
        $status = 0;
        $msg = "Cet e-mail n'existe pas!";
        $content_token = '';
        $token = '';
        $user_check = '';
        $html_mail = '';
        $user = '';
        if (isset($_POST['mail']) && !empty($_POST['mail'])) {
            $user_check = User::check_user($_POST['mail']);
            if ($user_check) {
                $user = new User($user_check['id_user']);
                if (count($user->check_request()) < 1) {
                    $token = $user->request();
                    $status = 1;
                    $msg = "Token généré avec succès ! Un e-mail a été envoyé.";
                    //Add Job mail in Queue table
                    $mail = new Mailing();
                    $mail->setToken_Job($user, $token);
                    $html_mail = RequestHTML::mailSending($user->getEmail_user());
                } else {
                    $msg = 'Un demande de récupération de mot de passe est déjà en cours pour ce compte!';
                }
            }
        }
        echo json_encode(array("status" => $status, "msg" => $msg, "user" => $user, "htmlMail" => $html_mail));
        break;

    case 'tokenLink':
        $msg = "Token validé!";
        echo json_encode(array("msg" => $msg));
        break;

    case 'modify_password' :
        $data = json_decode($_POST['tabInput'], true);
        $status = 1;
        $type = "";
        $user_type = "";
        $msg = "Votre mot de passe a été modifié !";
        $current_pwd_exp = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m"), date("d") + 1, date("Y")));
        $get_token = $data['token'];
        $init_control = new Control();
        $check = $init_control->check_fields($data);
        $traces = new Trace(0);

        if ($get_token == "pwd-modify") {
            $user = new User(Security::decrypt($_SESSION['id'], false));
            if (!password_verify($data['old-password'], $user->getPassword_user())) {
                $status = 0;
                $msg = "L'ancien mot de passe ne correspond pas!";
            } else if ($check['status'] == 0) {
                $msg = $check['msg'];
                $status = $check['status'];
            } else if ($data['inputPassword'] == $data['old-password']){
                $status = 0;
                $msg = "Le nouveau mot de passe ne peut être identique à l'ancien!";
            } else {
                $user->setPassword_user($data['inputPassword']);
                $user->setPwdExp_user($current_pwd_exp);
                $user->update();
                $type = "profile";
                $user_type = $user->getType();

                //Add traces in BDD
                $traces->setTracesIN(Security::decrypt($_SESSION['id'], false), 'recovery', 'password');
            }
        } else {
            if ($check['status'] == 0) {
                $msg = $check['msg'];
                $status = $check['status'];
            } else if (isset($get_token) && !empty($get_token)) {
                $request = new Request(0);
                $request->check_expiration();
                $user_token = User::check_token($get_token);
                if (!$user_token) {
                    $status = 2;
                    $msg = "Votre token n'est plus valide! La modification de votre mot de passe a échoué.";
                } else if ($get_token == $user_token['hash']) {
                    $user_hash = $user_token['hash'];
                    $user = new User($user_token['id_user']);
                    $user->setPassword_user($data['inputPassword']);
                    $user->setPwdExp_user($current_pwd_exp);
                    $user->update();
                    User::update_request($user_token['id_user']);

                    //Add traces in BDD
                    $traces->setTracesIN($user_token['id_user'], 'recovery', 'password');

                    $type = "request";
                }
            }
        }
        echo json_encode(array("status" => $status, "msg" => $msg, "type" => $type, "userType" => $user_type));
        break;
}
