<?php
session_start();
$currentTime = time();
if (!isset($_SESSION['id'])) {
    $status = 2;
    $msg = 'Nécessite une authentification, retour à la page de connexion';
    session_unset();
    session_destroy();
    echo json_encode(array('msg' => $msg, 'status' => $status));
} elseif ($currentTime > $_SESSION['expire']) {
    $status = 2;
    $msg = 'Session expirée, retour à la page principale';
    session_unset();
    session_destroy();
    echo json_encode(array('msg' => $msg, 'status' => $status));
} else {

    require_once '../../Controller/shared.php';
    spl_autoload_register(function ($classe) {
        require '../../Entity/' . $classe . '.php';
    });

    $db = new Database();
    $GLOBALS['db'] = $db->connexion();

    switch ($_POST['request']) {

        /*Cases de redirection liens header DEBUT*/
        case 'generateNavbar' :
            $status = 1;
            $user = new User(decrypt($_SESSION['id'], false));
            $userInfo = [
                "firstName" => $user->getPrenom_user(),
                "lastName" => $user->getNom_user(),
                "image" => $user->getImg_profile()
            ];
            $navbarHTML = HTML::navBarType($user);
            echo json_encode(array("navbarHTML" => $navbarHTML, "status" => $status, "userInfo" => $userInfo));
            break;

        case 'to_login' :
            $msg = "Redirection vers la page de connexion";
            echo json_encode(array("msg" => $msg));
            break;

        case 'modalUploadCG':
            $msg = "test1";
            $_SESSION['carID'] = encrypt($_POST['carID'], false);
            echo json_encode(array("msg" => $msg));
            break;

        case 'session_ending_soon':
            $tempsActuel = time();
            $msg = '';
            $statut = 0;
            if ($tempsActuel >=  ($_SESSION['expire'] - 60 )){
                $msg = 'Etes-vous toujours là ?';
                $statut = 1;
            }
            echo json_encode(array("msg" => $msg, "statut" => $statut));
            break;
    }
}

