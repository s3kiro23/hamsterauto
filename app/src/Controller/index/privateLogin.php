<?php
session_start();
require_once __DIR__ . '/../shared.php';
spl_autoload_register(function ($classe) {
    require '../../Entity/' . $classe . '.php';
});
$db = new Database();
$GLOBALS['db'] = $db->connexion();

switch ($_POST['request']) {
    case 'connexion_private':
        $status = 1;
        $msg = "Connexion réussie!";
        $contentPwdLogin = "";
        $typeUser = "";
        $user = User::checkUser($_POST['login']);
        if (!$user) {
            $status = 0;
            $msg = "Cet e-mail n'existe pas!";
        } else {
            if ($user[0]['is_active'] == 0) {
                $status = 0;
                $msg = "Ce compte est désactivé, <br> Veuillez contactez l'administrateur";
            } else {
                $log = LoginAttempts::checkLog($user[0]['id_user']);
                if ($log >= 3) {
                    $status = 0;
                    $msg = "Compte bloqué!";
                } else {
                    if (password_verify($_POST['password'], $user[0]['password_user'])) {
                        $dateJour = date("Y-m-d H:i:s");
                        if ($user[0]['pwdExp_user'] > $dateJour) {
                            if ($user[0]['a2f']) {
                                $status = 3;
                                $state = "a2f";
                                $msg = 'Double authentification en cours';
                                $_SESSION['id'] = encrypt($user[0]['id_user'], false);
                                $contentPwdLogin = HTML::secondAuth();

                                //Add Job Sms in Queue table
                                $sms = new SMS();
                                $sms->setSMSJobA2F($_SESSION['id']);
                            } else {
                                if ($user[0]['type'] === 'technicien') {
                                    $_SESSION['id'] = encrypt($user[0]['id_user'], false);
                                    $_SESSION['auth'] = true;
                                    $_SESSION['start'] = time();
                                    //valeur a changer pour le temps de session( x * nbre de secondes)
                                    $_SESSION['expire'] = $_SESSION['start'] + (4 * 3600);
                                    $user = new User(decrypt($_SESSION['id'], false));
                                    $typeUser = $user->getType();

                                    //Add traces in BDD
                                    $traces = new Traces(0);
                                    $traces->setId_user(decrypt($_SESSION['id'], false));
                                    $traces->setType('session');
                                    $traces->setAction('logged');
                                    $traces->create();
                                } else {
                                    $status = 0;
                                    $msg = "Accès interdit";
                                }
                            }
                        } else {
                            $msg = "Mot de passe expiré! Merci d'en créer un nouveau'";
                            $status = 2;
                            $contentPwdLogin = HTML::newPwd();
                        }
                    } else {
                        $status = 0;
                        $msg = "Mot de passe incorrect!";
                        $data = [
                            'id_user' => $user[0]['id_user'],
                            'mail' => $user[0]['email_user'],
                            'remote_ip' => $_SERVER["REMOTE_ADDR"]
                        ];
                        LoginAttempts::create($data);
                    }
                }
            }
        }
        echo json_encode(array("status" => $status, "msg" => $msg, "contentPwdLogin" => $contentPwdLogin, "typeUser" => $typeUser));
        break;

    case 'logout_tech':
        $status = 1;
        $msg = "Déconnexion réussie!";
        $user = new User(decrypt($_SESSION['id'], false));
        // write_logs($user->getEmail_user(), 2);
        session_destroy();
        unset($_SESSION);

        echo json_encode(array("status" => $status, "msg" => $msg));

        break;

    default :
        echo json_encode(1);
        break;
}