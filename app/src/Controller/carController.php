<?php
session_start();

spl_autoload_register(function ($classe) {
    require '../Entity/' . $classe . '.php';
});

$db = new Database();
$GLOBALS['db'] = $db->connexion();

switch ($_POST['request']) {

    case 'showInfo':
        setlocale(LC_TIME, "fr_FR", "French");
        $rdv_id = Security::decrypt($_POST['rdvID'], false);
        $CG = "";
        $current_CT = new Intervention($rdv_id);
        $carFile = new Upload($current_CT->getId_vehicle());  //Upload::checkFile($current_CT->getId_vehicle());
        $car = new Vehicle($current_CT->getId_vehicle());
        $owner = new User($current_CT->getId_user());
        if ($carFile->check_file()) {
            $CG = $car->get_CG($carFile->getFile_name(), $owner->getHash());
        }
        $bookedConvert = strtotime($current_CT->getBooked_date());

        echo json_encode(array(
            "rdvID" => $rdv_id,
            "timeslotID" => Convert::date_to_fullFR($current_CT->getTime_slot()) . " à " . date("H", $current_CT->getTime_slot()) . "h" . date("i", $current_CT->getTime_slot()),
            "booked_date" => Convert::date_to_fullFR($current_CT->getTime_slot()) . " à " . date("H", $bookedConvert) . "h" . date("i", $bookedConvert),
            "lastname_user" => $owner->getLastname_user(),
            "firstname_user" => $owner->getFirstname_user(),
            "phone_user" => $owner->getPhone_user(),
            "mail_user" => $owner->getEmail_user(),
            "CG" => $CG
        ));

        break;

    case 'addCar':
        $msg = "Véhicule enregistré !";
        $status = 1;
        $data = json_decode($_POST['data'], true);
        $car_check = Vehicle::check_registration($data['registration']);
        $init_control = new Control();
        $check = $init_control->check_fields($data);

        if ($check['status'] == 0) {
            $msg = $check['msg'];
            $status = $check['status'];
        } else {
            if ($car_check) {
                $msg = 'Un véhicule existe déjà avec cette immatriculation! Nous contactons la police...';
                $status = 0;
            } else {
                Vehicle::new_vehicle(
                    Security::decrypt($_SESSION['id'], false),
                    $data['selectedModel'],
                    $data['registration'],
                    $data['inputYear'],
                    $data['fuel'],
                    true
                );

                //Add traces in BDD
                $traces = new Trace(0);
                $traces->setTracesIN(Security::decrypt($_SESSION['id'], false), 'new', 'car');
            }
        }

        echo json_encode(array("msg" => $msg, 'status' => $status));

        break;

    case 'modifyCar':
        $msg = "Véhicule modifié !";
        $status = 1;
        $data = json_decode($_POST['data'], true);
        $car = new Vehicle(Security::decrypt($_POST['idCar'], true));
        $car->setId_model($data['selectedModel']);
        $car->setRegistration($data['registration']);
        $car->setFirst_release($data['inputYear']);
        $car->setFuel($data['fuel']);
        $car->update();

        //Add traces in BDD
        $traces = new Trace(0);
        $traces->setTracesIN(Security::decrypt($_SESSION['id'], false), 'modify', 'car');

        echo json_encode(array("msg" => $msg, 'status' => $status));

        break;

    case 'deleteCar':
        $msg = "Suppression du véhicule...";
        $status = 0;
        $carID = Security::decrypt($_POST['carID'], true);
        $car = new Vehicle($carID);
        $car->setOwned(0);
        $car->update();
        $car_bind_RDV = $car->check_bind_rdv($carID);
        if (!is_null($car_bind_RDV)) {
            $bindRDV = new Intervention($car_bind_RDV['id_intervention']);
            $bindRDV->setState(4);
            $bindRDV->setNum_tech(0);
            $bindRDV->update();
            $status = 1;
        }

        //Add traces in BDD
        $traces = new Trace(0);
        $traces->setTracesIN(Security::decrypt($_SESSION['id'], false), 'delete', 'car');

        echo json_encode(array("msg" => $msg, "status" => $status));

        break;
}

