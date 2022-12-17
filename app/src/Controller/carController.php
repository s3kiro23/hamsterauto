<?php 
session_start();
$currentTime = time();
if(!isset($_SESSION['id']) || $currentTime > $_SESSION['expire']) {
    $status = 2;
    $msg = 'Nécessite une authentification, retour à la page de connexion';
    session_unset();
    session_destroy();
    echo json_encode(array('msg' => $msg, 'status' => $status));
}else {


spl_autoload_register(function ($classe) {
    require '../Entity/' . $classe . '.php';
});
require_once 'shared.php';

$db = new Database();
$GLOBALS['db'] = $db->connexion();

switch ($_POST['request']) {

    case 'showInfo':
        setlocale(LC_TIME, "fr_FR", "French");
        $CG = "";
        $current_CT = new ControleTech($_POST['rdvID']);
        $carFile = new Upload($current_CT->getId_vehicule());  //Upload::checkFile($current_CT->getId_vehicule());
        $car = new Vehicule($current_CT->getId_vehicule());
        $owner = new User($current_CT->getId_user());
        if ($carFile->checkFile()) {
            $CG = $car->getCG($carFile->getFile_name(), $owner->getHash());
        }
        $bookedConvert = strtotime($current_CT->getBooked_date());

        echo json_encode(array(
            "rdvID" => $_POST['rdvID'],
            "timeslotID" => utf8_encode(strftime("%A %d %B %G", $current_CT->getTime_slot())) . " à " . strftime("%H" . "h" . "%M", $current_CT->getTime_slot()),
            "booked_date" => utf8_encode(strftime("%A %d %B %G", $current_CT->getTime_slot())) . " à " . strftime("%H" . "h" . "%M", $bookedConvert),
            "nom_user" => $owner->getNom_user(),
            "prenom_user" => $owner->getPrenom_user(),
            "tel_user" => $owner->getTelephone_user(),
            "mail_user" => $owner->getEmail_user(),
            "CG" => $CG
        ));

        break;

    case 'showInfoCar':

        $CG = "";
        $msg = "test";
        $user = new User(decrypt($_SESSION['id'], false));
        $check_currentCar = User::checkCars(decrypt($_SESSION['id'], false), $_POST['carID']);
        $carFile = new Upload($_POST['carID']);
        $car = new Vehicule($_POST['carID']);
        if ($carFile->checkFile()) {
            $CG = $car->getCG($carFile->getFile_name(), $user->getHash());
        }

        echo json_encode(array(
            "marque" => $check_currentCar[0]['nom_marque'],
            "modele" => $check_currentCar[0]['nom_modele'],
            "immat" => $check_currentCar[0]['immat_vehicule'],
            "annee" => $check_currentCar[0]['annee_vehicule'],
            "carburant" => $check_currentCar[0]['carburant_vehicule'],
            "carteGrise" => $CG,
            "infos" => $check_currentCar[0]['infos_vehicule'],
        ));
        break;

    case 'addCar':
        $msg = "Véhicule enregistré !";
        $status = 1;
        $carCheck = Vehicule::checkImmat($_POST['immat']);
        if (checkField()) {
            $status = 0;
            $msg = checkField();
        } else {
            if ($carCheck) {
                $msg = 'Un véhicule existe déjà avec cette immatriculation! Nous contactons la police...';
                $status = 0;
            } else {
                Vehicule::newVehicule(
                    decrypt($_SESSION['id'], false),
                    $_POST['modele'],
                    $_POST['immat'],
                    $_POST['annee'],
                    $_POST['carburant'],
                    true
                );

                //Add traces in BDD
                $traces = new Traces(0);
                $traces->setId_user(decrypt($_SESSION['id'], false));
                $traces->setType('car');
                $traces->setAction('new');
                $traces->create();
            }
        }

        echo json_encode(array("msg" => $msg, 'status' => $status));

        break;

    case 'deleteCar':
        $msg = "Suppression du véhicule...";
        $car = new Vehicule($_POST['carID']);
        $car->setOwned(0);
        $car->update();

        //Add traces in BDD
        $traces = new Traces(0);
        $traces->setId_user(decrypt($_SESSION['id'], false));
        $traces->setType('car');
        $traces->setAction('delete');
        $traces->create();

        echo json_encode(array("msg" => $msg));

        break;
    }}

