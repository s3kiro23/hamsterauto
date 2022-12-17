<?php

spl_autoload_register(function ($classe) {
    require '../../Entity/' . $classe . '.php';
});
require_once '../../Controller/shared.php';

$db = new Database();
$GLOBALS['Database'] = $db->connexion();

switch ($_POST['request']) {

    case 'newRDVHomePage' :
        $msg = '<div>Vous allez recevoir prochainement un SMS de confirmation au numéro indiqué.</div>';
        $status = 1;
        $carCheck = Vehicule::checkImmat($_POST['immat']);

        if (checkField()) {
            $status = 0;
            $msg = checkField();
        } else if ($carCheck) {
            $msg = 'Un véhicule existe déjà avec cette immatriculation! Nous contactons la police...';
            $status = 0;
        } else {
            $currenPwdExp = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m"), date("d") + 1, date("Y")));
            $client_tmp = User::create($_POST['civilite'], $_POST['prenom'], $_POST['nom'], $_POST['email'],
                $_POST['tel'], NULL, 'temp', $currenPwdExp, NULL);
            $carID = Vehicule::newVehicule($client_tmp, $_POST['modele'], $_POST['immat'], $_POST['annee'], $_POST['carburant'], 0);
            $ctID = ControleTech::newCT($client_tmp, $_POST['creneau'], $carID, 0);

            //Add traces in BDD
            $traces = new Traces(0);
            $traces->setId_user($client_tmp);
            $traces->setType('intervention');
            $traces->setAction('new');
            $traces->create();

            //Add Job Sms in Queue table
            $dataSMS = ["CT" => $ctID, "car" => $carID, "user" => $client_tmp];
            $sms = new SMS();
            $sms->setSMSJobRDV($dataSMS);
        }

        echo json_encode(array("status" => $status, "msg" => $msg));
        break;

    case 'default' :
        echo json_encode(1);
        break;

}