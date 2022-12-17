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
    require '../../Entity/' . $classe . '.php';
});
require_once '../shared.php';
$db = new Database();
$GLOBALS['Database'] = $db->connexion();

switch ($_POST['request']) {

    case 'formAddRDV' :

        $car_owned = User::checkCars(decrypt($_SESSION['id'], false), null);
        $html_car = '<option value="">-Sélectionner un véhicule-</option>';
        foreach ($car_owned as $car) {
            $html_car .= '<option value="' . $car['id_vehicule'] . '">' . $car['immat_vehicule'] . '</option>';
            $status = 1;
        }
        $html = HTML::formAddRDV($html_car);

        echo json_encode(array('html' => $html));

        break;

    case 'formAddCar':

        $html_marque = '<option value="">-Marque-</option>';
        $list_marque = Vehicule::checkMarques();
        foreach ($list_marque as $marque) {
            $html_marque .= '<option class="" value="' . $marque['id_marque'] . '">' . $marque['nom_marque'] . '</option>';
        }

        $html = HTML::formAddCar($html_marque);

        echo json_encode(array('html' => $html));
        break;
    }}

