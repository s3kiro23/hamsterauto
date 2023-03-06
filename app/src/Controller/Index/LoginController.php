<?php

session_start();

require $_SERVER['DOCUMENT_ROOT'] . "/src/Entity/Setting.php";
Setting::autoload();

$db = new Database();
$GLOBALS['Database'] = $db->connexion();

switch ($_POST['request']) {
    case 'connexion':
        $status = 1;
        $msg = "Connexion réussie!";
        $content_pwd_login = "";
        $type_user = "";
        $url = "";
        $user = User::check_user($_POST['login']);
        if (!$user) {
            $status = 0;
            $msg = "Cet e-mail n'existe pas!";
        } else {
            if ($user['is_active'] == 0) {
                $status = 0;
                $msg = "Ce compte est désactivé, <br> Veuillez contactez l'administrateur";
            } else {
                $log = LoginAttempt::check_log($user['id_user']);
                if ($log >= 3) {
                    $status = 0;
                    $msg = "Compte bloqué!";
                } else {
                    if (password_verify($_POST['password'], $user['password_user'])) {
                        $date_jour = date("Y-m-d H:i:s");
                        if ($user['pwdExp_user'] > $date_jour) {
                            if ($user['a2f']) {
                                $status = 3;
                                $state = "a2f";
                                $msg = 'Double authentification en cours';
                                $_SESSION['id'] = Security::encrypt($user['id_user'], false);
                                $content_pwd_login = RequestHTML::secondAuth();
                                if (User::count_sms($user['id_user']) < 1) {
                                    //Add Job Sms in Queue table
                                    $sms = new SMS(0);
                                    $sms->setSMS_JobA2F($_SESSION['id']);
                                } else {
                                    $msg = 'Un code SMS est déjà en cours de validation pour ce compte!';
                                }
                            }
                            if (($user['type'] === 'technicien' && $_POST['accessPath'] == 'private') or
                                ($user['type'] === 'client' && $_POST['accessPath'] === 'index') or ($user['type'] === 'admin')
                            ) {
                                $type_user = Security::create_session($user);
                            } else {
                                $status = 0;
                                $msg = "Accès interdit";
                                $url = "/";
                                if ($user['type'] === 'technicien') {
                                    $url = "acces-prive";
                                }
                            }
                        } else {
                            $msg = "Mot de passe expiré! Merci d'en créer un nouveau'";
                            $status = 2;
                            $content_pwd_login = RequestHTML::newPwd();
                        }
                    } else {
                        $status = 0;
                        $msg = "La combinaison de cet email et ce mot de passe n'existe pas !";
                        $data = [
                            'id_user' => $user['id_user'],
                            'mail' => $user['email_user'],
                            'remote_ip' => $_SERVER["REMOTE_ADDR"]
                        ];
                        LoginAttempt::create($data);
                    }
                }
            }
        }
        echo json_encode(array("status" => $status, "msg" => $msg, "contentPwdLogin" => $content_pwd_login, "typeUser" => $type_user, "url" => $url));
        break;

    case 'newPwd':
        $status = 1;
        $msg = "Mise à jour réussie!";
        $current_pwd_exp = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m"), date("d") + 1, date("Y")));
        $data = json_decode($_POST['tabInput'], true);
        $user = User::check_user($data['inputEmail']);
        $init_control = new Control();
        $check = $init_control->check_fields($data);

        if ($check['status'] == 0) {
            $msg = $check['msg'];
            $status = $check['status'];
        } else {
            if (!$user) {
                $status = 0;
                $msg = "Cet e-mail n'existe pas!";
            } else if (password_verify($data['inputPassword'], $user['password_user'])) {
                $status = 0;
                $msg = "Le nouveau mot de passe ne peut être identique à l'ancien!";
            } else {
                $current_user = new User($user['id_user']);
                $current_user->setPassword_user($data['inputPassword']);
                $current_user->setPwdExp_user($current_pwd_exp);
                $current_user->update();
                $_SESSION['id'] = Security::encrypt($user['id_user'], false);

                //Add traces in BDD
                $traces = new Trace(0);
                $traces->setTracesIN(Security::decrypt($_SESSION['id'], false), 'update', 'password');
            }
        }
        echo json_encode(array("status" => $status, "msg" => $msg));
        break;

    case 'sub_sms':
        $status = 0;
        $msg = "Ce code SMS a expiré ou n'est pas valide!";
        $object_sms = new SMS(0);
        $object_sms->check_expiration();
        $check_input_sms = User::check_sms_code(Security::decrypt($_SESSION['id'], false), $_POST['sms_verif']);
        $user = new User(Security::decrypt($_SESSION['id'], false));


        if ($check_input_sms) {
            $status = 1;
            $msg = "Double authentification validée!";
            $_SESSION['auth'] = true;
            $_SESSION['start'] = time();
            //valeur a changer pour le temps de session( x * nbre de secondes)
            $_SESSION['expire'] = $_SESSION['start'] + (1 * 1800);

            //Add traces in BDD
            $traces = new Trace(0);
            $traces->setTracesIN(Security::decrypt($_SESSION['id'], false), 'logged', 'session');
            User::update_sms(Security::decrypt($_SESSION['id'], false));
        }
        echo json_encode(array("status" => $status, "msg" => $msg, "type" => $user->getType()));
        break;

    case 'to_clientForm':
        $msg = "Redirection vers la page du formulaire...";
        echo json_encode(array("msg" => $msg));
        break;


    case 'captcha':
        $check = new Control();
        $get_captcha = $check->captcha();
        echo json_encode(array('get_captcha' => $get_captcha));
        break;

    case 'session_extend':
        $msg = Session::session_extend();
        echo json_encode($msg);
        break;

    case 'session_ending':
        $data = Session::session_ending();
        echo json_encode(array("msg" => $data['msg'], "status" => $data['status']));
        break;

    case 'logout':
        $status = $_SESSION['typeUser'];
        $traces = new Trace(0);
        $traces->setTracesIN(Security::decrypt($_SESSION['id'], false), 'logout', 'session');
        $_SESSION = array();
        session_destroy();
        unset($_SESSION);
        echo json_encode(array('status' => $status));
        break;

    case 'session_ending_soon':
        $data = Session::session_ending_soon();
        echo json_encode(array("msg" => $data['msg'], "status" => $data['status']));
        break;


    default:
        echo json_encode(1);
        break;
}
