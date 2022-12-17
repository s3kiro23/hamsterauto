<?php session_start();

require_once '../../Controller/shared.php';
require_once '../../Controller/authorization.php';

spl_autoload_register(function ($classe) {
    require '../../Entity/' . $classe . '.php';
});

$db = new Database();
$GLOBALS['Database'] = $db->connexion();

if (!getAuthorizationAll()) {
    $status = 2;
    session_destroy();
    $msg = "Vous n'êtes pas autorisé à accéder à cette page ! <br> Redirection vers la page de login...";
    echo json_encode(array('msg' => $msg, 'status' => $status));
} else {

    switch ($_POST['request']) {

        /*Tableaux dashboard Client DEBUT*/

        case 'loadCarsRecap':
            setlocale(LC_TIME, "fr_FR", "French");
            $msg = "Success";
            $html = "";
            $htmlRDV = "";
            $htmlHistory = "";
            $statusCar = 1;
            $statusRdv = 0;
            $state = 2;
            $id_vehicule = false;
            $paginationMyHistory = "";
            $count = 1;
            $off7 = ($_POST['page'] - 1) * 5;
            $nbr_of_rdv = User::countHistory(decrypt($_SESSION['id'], false));
            $totalPages = ceil($nbr_of_rdv / 5);
            for ($i = 1; $i <= $totalPages; $i++) {
                $paginationMyHistory .= HTML::historyUserPages($i, $i);
            }
            $tab_userCars = User::checkCars(decrypt($_SESSION['id'], false), $id_vehicule);
            $tab_rdv = User::checkRdv(decrypt($_SESSION['id'], false), null);
            $tab_history = User::checkHistory(decrypt($_SESSION['id'], false), $off7);
            $nbr_of_history = ["current" => count($tab_history), "total" => $nbr_of_rdv];
            foreach ($tab_userCars as $car) {
                $html .= HTML::loadCarsRecap($car['nom_marque'], $car['nom_modele'], $car['immat_vehicule'], $car['id_vehicule']);
            }
            foreach ($tab_rdv as $rdv) {
                $tech = new User($rdv['id_user']);
                $htmlRDV .= HTML::loadRdvRecap(
                    date("d  M  Y", $rdv['id_time_slot']) . " à " . strftime("%H" . "h" . "%M", $rdv['id_time_slot']),
                    $rdv['state'],
                    $rdv['immat_vehicule'],
                    $rdv['id_controle']
                );
            }
            foreach ($tab_history as $history) {
                $tech = new User($history['id_tech']);
                $htmlHistory .= HTML::loadHistory(
                    $history['id_controle'], date("d  M  Y",
                    $history['id_time_slot']),
                    $tech->getPrenom_user(),
                    $history['immat_vehicule'], $history['state']
                );
            }
            echo json_encode(array('statusRdv' => $statusRdv,
                'statusCar' => $statusCar,
                'msg' => $msg,
                'html' => $html,
                "htmlRDV" => $htmlRDV,
                "htmlHistory" => $htmlHistory,
                "paginationMyHistory" => $paginationMyHistory,
                "nbrOfHistory" => $nbr_of_history
            ));
            break;

        /*Tableaux dashboard Client FIN*/

        default :

            echo json_encode(1);

            break;

    }
}