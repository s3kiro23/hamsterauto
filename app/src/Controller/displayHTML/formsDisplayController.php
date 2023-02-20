<?php

use HTML\FormHTML;

session_start();

spl_autoload_register(function ($classe) {
    require '../../Entity/' . $classe . '.php';
});

require "../../Entity/HTML/FormHTML.php";

$db = new Database();
$GLOBALS['Database'] = $db->connexion();

switch ($_POST['request']) {

    case 'formAddRDV' :

        $car_owned = User::check_cars(Security::decrypt($_SESSION['id'], false), null);
        $html_car = '<option value="">-</option>';
        foreach ($car_owned as $car) {
            $html_car .= '<option value="' . Security::encrypt($car['id_vehicle'], true) . '">' . $car['registration'] . '</option>';
            $status = 1;
        }
        $html = FormHTML::addRDV($html_car);

        echo json_encode(array('html' => $html));

        break;

    case 'formAddCar':

        $html_brand = '<option value="">-</option>';
        $list_brand = Vehicle::check_brands();
        foreach ($list_brand as $brand) {
            $html_brand .= '<option class="" value="' . $brand['id_brand'] . '">' . $brand['brand_name'] . '</option>';
        }

        $html = FormHTML::addCar($html_brand);

        echo json_encode(array('html' => $html));
        break;

    case 'formModifyCar':
        $data = [];
        $car = new Vehicle(Security::decrypt($_POST['idCar'], true));
        $brand_object = new Brand($car->getId_brand());
        $model_object = new Model($car->getId_model());
        $data['brand'] = '<option value="' . $brand_object->getId_brand() . '">' . $brand_object->getBrand_name() . '</option>';
        $data['model'] = '<option value="' . $model_object->getId_model() . '">' . $model_object->getModel_name() . '</option>';
        $data['registration'] = $car->getRegistration();
        $data['fuel'] = $car->getFuel();
        $data['year'] = $car->getFirst_release();
        $data['idCar'] = $_POST['idCar'];

        $list_brand = Vehicle::check_brands();
        foreach ($list_brand as $brand) {
            if ($brand['brand_name'] != $brand_object->getBrand_name()) {
                $data['brand'] .= '<option class="" value="' . $brand['id_brand'] . '">' . $brand['brand_name'] . '</option>';
            }
        }

        $list_model = Vehicle::check_models($brand_object->getId_brand());
        foreach ($list_model as $model) {
            if ($model['model_name'] != $model_object->getModel_name()) {
                $data['model'] .= '<option value="' . $model['id_model'] . '">' . $model['model_name'] . '</option>';
                $status = 1;
            }
        }

        $html = FormHTML::modifyCar($data);

        echo json_encode(array('html' => $html, 'data' => $data));
        break;
}


