<?php
session_start();
$currentTime = time();
if (!isset($_SESSION['id']) || $currentTime > $_SESSION['expire']) {
    $status = 2;
    $msg = 'Nécessite une authentification, retour à la page de connexion';
    session_unset();
    session_destroy();
    echo json_encode(array('msg' => $msg, 'status' => $status));
} else {


    spl_autoload_register(function ($classe) {
        require '../Entity/' . $classe . '.php';
    });
    require_once '../Controller/shared.php';

    $db = new Database();
    $GLOBALS['Database'] = $db->connexion();


    switch ($_POST['request']) {

        case 'newRDVDashboardClient' :
            $msg = 'Votre rendez-vous est validé';
            $status = 1;
            $userID = decrypt($_SESSION['id'], false);
            $checkCarRDV = "";

            if (checkField()) {
                $status = 0;
                $msg = checkField();
            } else {

                $traces = new Traces(0);
                $traces->setType('intervention');
                $traces->setAction('new');
                $user = new User($userID);
                $sms = new SMS();
                $queued = new Queued(0);
                $queued->setType("sms");

                if (empty($_POST['carID'])) {
                    $carCheck = Vehicule::checkImmat($_POST['immat']);
                    if ($carCheck) {
                        $msg = 'Un véhicule existe déjà avec cette immatriculation!';
                        $status = 0;
                    } else {
                        $carID = Vehicule::newVehicule($userID, $_POST['modele'], $_POST['immat'], $_POST['annee'], $_POST['carburant'], 1);
                        $ctID = ControleTech::newCT($userID, $_POST['creneau'], $carID, 0);

                        //Décommenter pour activer SMS
                        $carUser = new Vehicule($carID);
                        $CT = new ControleTech($ctID);
                        $smsTemplate = $sms->getRDV($user, $carUser, $CT);
                        $queued->setTemplate(json_encode($smsTemplate));

                        //Add traces in BDD
                        $traces->setId_user($userID);
                    }
                } else {
                    $checkCarRDV = User::checkRdv($userID, $_POST['carID']);
                    if ($checkCarRDV) {
                        $msg = 'Une intervention existe déjà pour ce véhicule!';
                        $status = 0;
                    } else {
                        $ctID = ControleTech::newCT($userID, $_POST['creneau'], $_POST['carID'], 0);

                        //Décommenter pour activer SMS
                        $carUser = new Vehicule($_POST['carID']);
                        $CT = new ControleTech($ctID);
                        $smsTemplate = $sms->getRDV($user, $carUser, $CT);
                        $queued->setTemplate(json_encode($smsTemplate));

                        //Add traces in BDD
                        $traces->setId_user($userID);
                    }
                }
                if ($status == 1) {
                    $queued->create();
                    $traces->create();
                }
            }

            echo json_encode(array("status" => $status, "msg" => $msg));

            break;


        case 'deleteRdv':
            $msg = "Votre rendez-vous a bien été annulé !";
            $CT = new ControleTech($_POST['idRdv']);
            $userID = $CT->getId_user();
            $user = new User($userID);
            $carUser = new Vehicule($CT->getId_vehicule());
            $CT->setState(4);
            $CT->setNum_tech(0);
            $CT->update();

            //Add Job mail in Queue table
            $mail = new Mailing();
            $mail_template = $mail->getCT_Canceled($user, $CT, $carUser);
            $queued = new Queued(0);
            $queued->setType("mail");
            $queued->setTemplate(json_encode($mail_template));
            $queued->create();

            //Add traces in BDD
            $traces = new Traces(0);
            $traces->setId_user($userID);
            $traces->setType('intervention');
            $traces->setAction('canceled');
            $traces->create();

            echo json_encode(array("msg" => $msg));
            break;

        case 'show_contre_visite':
            $rapportContreVisite = '';
            $current_CT = new ControleTech($_POST['rdvID']);
            $client = new User($current_CT->getId_user());
            $pathPV = $current_CT->getCarPv($client);
            $rapportContreVisite = "<iframe src='../var/generate/minutes/$pathPV' height='600' class='w-100'></iframe>";

            echo json_encode(array("rdvID" => $_POST['rdvID'], "rapport" => $rapportContreVisite));
            break;

        default :
            echo json_encode(1);
            break;
    }
}
