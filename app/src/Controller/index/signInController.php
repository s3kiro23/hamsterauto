<?php
session_start();
require_once __DIR__ . '/../shared.php';

spl_autoload_register(function ($classe) {
    require '../../Entity/' . $classe . '.php';
});

$db = new Database();
$GLOBALS['db'] = $db->connexion();

switch ($_POST['request']) {

    case 'captcha' :
        $get_captcha = captcha();
        echo json_encode(array('get_captcha' => $get_captcha));
        break;

    case 'to_logIn' :
        $msg = "Redirection vers la page de connexion en cours!";
        echo json_encode(array("msg" => $msg));
        break;


    case 'signIn' :
        $status = 1;
        $msg = "Votre compte a été créé avec succès !";
        $user = User::checkUser($_POST['email']);

        if (checkField()) {
            $status = 0;
            $msg = checkField();
        } else if (!checkPassword($_POST['passwd'], $_POST['passwd2'])) {
            $status = 0;
            $msg = "Les mots de passe ne correspondent pas !";
        } else if (!checkCaptcha($_POST['checkCap'], $_POST['captcha'])) {
            $status = 0;
            $msg = "Les captcha ne correspondent pas !";
        } else if ($user) {
            $status = 0;
            $msg = "Le login existe déjà!";
        }
        /*        else if (!checkPasswdLenght($_POST['passwd'])){
                    $status = 0;
                    $msg = "Condition de création du mot de passe non remplies!";
                }*/

        if ($status == 1) {
            $currenPwdExp = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m"), date("d") + 1, date("Y")));
            $client = User::create(
                $_POST['civilite'],
                $_POST['prenom'],
                $_POST['nom'],
                $_POST['email'],
                $_POST['tel'],
                $_POST['passwd'],
                "client",
                $currenPwdExp,
                User::random_hash(),
            );

            //Add Job in queue
            $mail = new Mailing();
            $mailTemplate = $mail->getSign_Up($_POST['email'], $_POST['civilite'], $_POST['nom']);
            $queued = new Queued(0);
            $queued->setType("mail");
            $queued->setTemplate(json_encode($mailTemplate));
            $queued->create();

            //Add traces in BDD
            $traces = new Traces(0);
            $traces->setId_user($client);
            $traces->setType('account');
            $traces->setAction('new');
            $traces->create();
        }


        echo json_encode(array("status" => $status, "msg" => $msg));

        break;
}
