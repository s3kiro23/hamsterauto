<?php 
session_start();
require_once __DIR__.'/../shared.php';

spl_autoload_register(function ($classe) {
    require '../../Entity/' . $classe . '.php';
});

$db = new Database();
$GLOBALS['Database'] = $db->connexion();

switch ($_POST['request']) {

    case 'toRequestMail':
        $msg = "Demande de nouveau mot de passe en cours!";
        $contentForgot = HTML::toRequestMail();
        echo json_encode(array("msg" => $msg, "contentForgot" => $contentForgot));
        break;

    case 'genToken':
        $status = 0;
        $msg = "Cet e-mail n'existe pas!";
        $contentToken = '';
        $token = '';
        $user = '';
        $html_mail = '';
        if (isset($_POST['mail']) && !empty($_POST['mail'])) {
            $user = User::checkUser($_POST['mail']);
            if ($user) {
                $hash = User::request($user[0]['id_user']);
                $checkToken = User::checkRequest($hash);
                $token = $checkToken['hash'];
                $status = 1;
                $msg = "Token généré avec succès ! Un e-mail a été envoyé.";

                //Add Job mail in Queue table
                $userMail = new User($user[0]['id_user']);
                $mail = new Mailing();
                $mailTemplate = $mail->getToken($userMail, $token);
                $queued = new Queued(0);
                $queued->setType("mail");
                $queued->setTemplate(json_encode($mailTemplate));
                $queued->create();
/*                $contentToken = HTML::genToken();*/
                $html_mail = HTML::mailSending($userMail->getEmail_user());
            }
        }
        echo json_encode(array("status" => $status, "msg" => $msg, "user" => $user, "htmlMail" => $html_mail));
        break;

    case 'tokenLink':
        $msg = "Token validé!";
        echo json_encode(array("msg" => $msg));
        break;

    case 'modify_password' :
        $status = 1;
        $type = "";
        $userType ="";
        $msg = "Votre mot de passe a été modifié !";
        $currenPwdExp = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m"), date("d") + 1, date("Y")));
        $token = $_POST['token'];

        $traces = new Traces(0);
        $traces->setType('password');
        $traces->setAction('recovery');

        if($token == "pwd-modify"){
            $user = new User(decrypt($_SESSION['id'], false));
            if (!password_verify($_POST['oldPassword'], $user->getPassword_user())){
                $status = 0;
                $msg = "L'ancien mot de passe ne correspond pas!";
            } else if (!checkPassword($_POST['password'], $_POST['password2'])) {
                $status = 0;
                $msg = "Les mots de passe ne correspondent pas!";
            } else {
                $user->setPassword_user($_POST['password']);
                $user->setPwdExp_user($currenPwdExp);
                $user->update();
                $type = "profile";
                $userType = $user->getType();

                //Add traces in BDD
                $traces->setId_user(decrypt($_SESSION['id'], false));
                $traces->create();
            }
        } else {
            if (!checkPassword($_POST['password'], $_POST['password2'])) {
                $status = 0;
                $msg = "Les mots de passe ne correspondent pas!";
            } else if (isset($token) && !empty($token)) {
                $checkHash = User::checkRequest($token);
                if (!$checkHash) {
                    $status = 0;
                    $msg = "Ce token n'est plus valide!";
                } else if ($token == $checkHash['hash']) {
                    $user_hash = $checkHash['hash'];
                    $user = new User($checkHash['id_user']);
                    $user->setPassword_user($_POST['password']);
                    $user->setPwdExp_user($currenPwdExp);
                    $user->update();
                    User::updateRequest($checkHash['id_user']);

                    //Add traces in BDD
                    $traces->setId_user($checkHash['id_user']);
                    $traces->create();
                    $type = "request";
                }
            }
        }
        echo json_encode(array("status" => $status, "msg" => $msg, "type" => $type));
        break;
}