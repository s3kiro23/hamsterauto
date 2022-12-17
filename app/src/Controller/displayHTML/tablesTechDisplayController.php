<?php 
session_start();
$currentTime = time();
if(!isset($_SESSION['id']) || $currentTime > $_SESSION['expire']) {
    $status = 2;
    $msg = 'Nécessite une authentification, retour à la page de connexion';
    session_unset();
    session_destroy();
    echo json_encode(array('msg' => $msg, 'status' => $status));
}else{
    

require_once '../../Controller/shared.php';
require_once '../../Controller/authorization.php';
spl_autoload_register(function ($classe) {
    require '../../Entity/' . $classe . '.php';
});

$db = new Database();
$GLOBALS['Database'] = $db->connexion();

$whoIs = false;
if (isset($_SESSION['id']) && !empty($_SESSION['id'])) {
    $whoIs = new User(decrypt($_SESSION['id'], false));
}
if (!getAuthorizationUser($whoIs)){
    $status = 2;
    session_destroy();
    $msg = "Vous n'êtes pas autorisé à accéder à cette page ! <br> Redirection vers la page de login...";
    echo json_encode(array('msg' => $msg, 'status' => $status));
}else{
    $_SESSION['expire'] = $_SESSION['expire'] + (4*3600);

    switch ($_POST['request']) {

        /*Tableaux backoffice DEBUT*/

        case 'vehiculeAttente':
            setlocale(LC_TIME, "fr_FR", "French");
            if(empty($_POST['currentDate']) or is_null($_POST['currentDate'])){
                $currentDate = strtotime(date("d-m-Y"));
            }else{
                $currentDate =$_POST['currentDate'];
            }
            $html = '';
            $htmlVide = HTML::listeVide();
            $status = 0;
            $state = 0;
            $paginationHold = "";
            $off7 = ($_POST['page'] - 1) * 5;
            $tab_cars = ControleTech::checkAllRdv($off7, $state, $_POST['immat'],$currentDate);
            $nbr_of_rdv = ControleTech::countRdv($state, $currentDate);
            $totalPages = ceil($nbr_of_rdv / 5);
            $timestamp = strtotime(date('d-m-Y'));
            $jourRDV = HTML::generateDateBackOffice(date("d-m-Y"), $timestamp);
            foreach ($tab_cars as $car) {
                $html .= HTML::loadInterventions($car['id_controle'], $car['id_time_slot'], $car['nom_marque'], $car['nom_modele'], $car['immat_vehicule'], $currentDate);
                $status = 1;
            }
            for ($i = 1; $i <= $totalPages; $i++) {
                $paginationHold .= HTML::rdvPages($i, $i, $state);
            }
            echo json_encode(array("status" => $status, "msg" => $html, "msg2" => $htmlVide, "paginationHold" => $paginationHold));
            break;

        case 'interv_en_cours':
            if(empty($_POST['currentDate']) or is_null($_POST['currentDate'])){
                $currentDate = strtotime(date("d-m-Y"));
            }else{
                $currentDate =$_POST['currentDate'];
            }
            $off7 = "";
            $html = '';
            $status = 0;
            $state = 1;
            $htmlVide = HTML::intervVide();
            $paginationInProgress = "";
            if (isset($_POST['page'])) {
                $off7 = ($_POST['page'] - 1) * 5;
            }
            $immat = '';
            $tab_cars = ControleTech::checkAllRdv($off7, $state, $immat,  $currentDate);
            $nbr_of_rdv = ControleTech::countRdv($state, $currentDate);
            $totalPages = ceil($nbr_of_rdv / 5);
            foreach ($tab_cars as $car) {
                $tech = new User($car['id_tech']);
                $html .= HTML::loadInterventionsEnCours($car['id_controle'], $car['id_time_slot'], $tech->getPrenom_user(), $car['nom_marque'], $car['immat_vehicule']);
                $status = 1;
            }
            for ($i = 1; $i <= $totalPages; $i++) {
                $paginationInProgress .= HTML::rdvPages($i, $i, $state);
            }
            echo json_encode(array("status" => $status, "msg" => $html, "msg2" => $htmlVide, "paginationInProgress" => $paginationInProgress));
            break;

        case 'load_termines':
            if(empty($_POST['currentDate']) or is_null($_POST['currentDate'])){
                $currentDate = strtotime(date("d-m-Y"));
            }else{
                $currentDate =$_POST['currentDate'];
            }
            $htmlVide = HTML::listeVideHistory();
            $html = '';
            $state = 2;
            $status = 0;
            $paginationOver = "";
            $off7 = ($_POST['page'] - 1) * 5;
            $immat='';
            $tab_cars = ControleTech::checkAllRdv($off7, $state, $immat,  $currentDate);
            $nbr_of_rdv = ControleTech::countRDV($state, null);
            $totalPages = ceil($nbr_of_rdv / 5);
            foreach ($tab_cars as $car) {
                $status = 1;
                $user = new User($car['id_user']);
                if ($car['state'] == 2) {
                    $html .= HTML::loadTerminesCTOK($car['id_controle'], $user->getNom_user(), $user->getTelephone_user(), $car['immat_vehicule']);
                } elseif ($car['state'] == 3) {
                    $html .= HTML::loadTerminesContreVisite($car['id_controle'], $user->getNom_user(), $user->getTelephone_user(), $car['immat_vehicule']);
                } elseif ($car['state'] == 4) {
                    $html .= HTML::loadTerminesAnnule($car['id_controle'], $user->getNom_user(), $user->getTelephone_user(), $car['immat_vehicule']);
                }
            }
            for ($i = 1; $i <= $totalPages; $i++) {
                $paginationOver .= HTML::rdvPages($i, $i, $state);
            }
            echo json_encode(array("msg" => $html, "paginationOver" => $paginationOver, "status" => $status, "html_vide" => $htmlVide));

            break;

        /*Tableaux backoffice FIN*/
    }
}
}