<?php 
session_start();

date_default_timezone_set("Europe/Paris");

require_once __DIR__.'/../shared.php';

spl_autoload_register(function ($classe) {
    require '../../Entity/' . $classe . '.php';
});

$db = new Database();
$GLOBALS['Database'] = $db->connexion();

switch ($_POST['request']) {

    case 'checkField':
        // error_log($_POST['fieldVal']);
        $msg = "";
        $status = 1;
        if (isset($_POST['field']) && $_POST['field'] == 'inputNom') {
            // error_log('NomErr1');
            if (empty($_POST['fieldVal'])) {
                // error_log('NomErr2');
                $msg = "Veuillez renseigner votre nom!";
                $status = 0;
            }
        }
        if (isset($_POST['field']) && $_POST['field'] == 'inputLogin') {
            // error_log('NomErr1');
            if (!checkMail($_POST['fieldVal'])) {
                // error_log('mail');
                $msg = "Veuillez renseigner un e-mail conforme!";
                $status = 0;
            } else if (empty($_POST['fieldVal'])) {
                $msg = "Veuillez renseigner votre login!";
                $status = 0;
            }
        }
        if (isset($_POST['field']) && $_POST['field'] == 'inputPassword') {
            // error_log('NomErr1');
            if (empty($_POST['fieldVal'])) {
                // error_log('NomErr2');
                $msg = "Veuillez renseigner votre mot de passe!";
                $status = 0;
            }
        }
        if (isset($_POST['field']) && $_POST['field'] == 'inputPrenom') {
            if (empty($_POST['fieldVal'])) {
                $msg = "Veuillez renseigner votre prénom!";
                $status = 0;
            }
        }
        if (isset($_POST['field']) && $_POST['field'] == 'inputTel') {
            if (empty($_POST['fieldVal'])) {
                $msg = "Veuillez indiquer un numéro de téléphone!";
                $status = 0;
            }
            if (!checkTel($_POST['fieldVal'])) {
                // error_log('Tel12');
                $msg = "Veuillez renseigner un numéro de téléphone valide!";
                $status = 0;
            }
        }
        if (isset($_POST['field']) && $_POST['field'] == 'inputEmail') {
            if (!checkMail($_POST['fieldVal'])) {
                // error_log('mail');
                $msg = "Veuillez renseigner un e-mail valide!";
                $status = 0;
            }
            if (empty($_POST['fieldVal'])) {
                $msg = "Veuillez renseigner un e-mail!";
                $status = 0;
            }
        }
        if (isset($_POST['field']) && $_POST['field'] == 'inputAddr') {
            if (empty($_POST['fieldVal'])) {
                $msg = "Veuillez renseigner une adresse!";
                $status = 0;
            }
        }
        if (isset($_POST['field']) && $_POST['field'] == 'inputCP') {
            if (empty($_POST['fieldVal'])) {
                $msg = "Veuillez indiquer un code postal!";
                $status = 0;
            } else if (!checkCP($_POST['fieldVal'])) {
                $msg = "Veuillez indiquer un code postal valide!";
                $status = 0;
            }
        }
        if (isset($_POST['field']) && $_POST['field'] == 'inputVille') {
            if (empty($_POST['fieldVal'])) {
                $msg = "Veuillez indiquer votre ville!";
                $status = 0;
            }
        }
        if (isset($_POST['field']) && $_POST['field'] == 'captcha_verif') {
            if (empty($_POST['fieldVal'])) {
                $msg = "Captcha obligatoire!";
                $status = 0;
            }
        }
        if (isset($_POST['field']) && $_POST['field'] == 'inputImmatNew' || $_POST['field'] == 'inputImmatOld') {
            error_log($_POST['field']);
            if (!checkImmat($_POST['fieldVal'])) {
                $msg = "Veuillez renseigner une immatriculation conforme!";
                $status = 0;
            }
            if (empty($_POST['fieldVal'])) {
                $msg = "Veuillez renseigner une immatriculation!";
                $status = 0;
            }
        }
        if (isset($_POST['field']) && $_POST['field'] == 'inputAnnee') {
            if (!checkYear($_POST['fieldVal'])) {
                $msg = "Veuillez renseigner une année valide!";
                $status = 0;
            }
            if (empty($_POST['fieldVal'])) {
                $msg = "Veuillez renseigner une année de 1ère mise en circulation!";
                $status = 0;
            }
        }
        // error_log(json_encode($_POST));
        echo json_encode(array("status" => $status, "msg" => $msg));
        break;


    case 'to_login' :
        $msg = "Redirection vers la page de connexion!";
        echo json_encode(array("msg" => $msg));
        break;

    
}
