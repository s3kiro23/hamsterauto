<?php

spl_autoload_register(function ($classe) {
    require '../../Entity/' . $classe . '.php';
});

$db = new Database();
$GLOBALS['Database'] = $db->connexion();

switch ($_POST['request']) {

    case 'newRDVHomePage' :
        $msg = 'Vous allez recevoir prochainement un SMS de confirmation au numéro indiqué.';
        $status = 1;
        $data = json_decode($_POST['data'], true);
        $car_check = Vehicle::check_registration($data['registration']);
        $civilite = empty($data['civilite']) ? $data['civilite'] = "" : $data['civilite'];
        $carburant = empty($data['fuel']) ? $data['fuel'] = "" : $data['fuel'];
        $creneau = empty($data['timeSlot']) ? $data['timeSlot'] = "" : $data['timeSlot'];
        $init_control = new Control();
        $check = $init_control->check_fields($data);

        if ($check['status'] == 0) {
            $msg = $check['msg'];
            $status = $check['status'];
        } else if ($car_check) {
            $msg = 'Un véhicule existe déjà avec cette registrationriculation! Nous contactons la police...';
            $status = 0;
        } else {
            $current_pwd_exp = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m"), date("d") + 1, date("Y")));
            $time_slot = Security::decrypt($data['timeSlot'],"");
            $check = Security::check_timeslots($time_slot);
            if(!$check){
                $status = 2;
                $msg = 'Touche pas au code !';
            }else{
                $client_tmp = User::create($civilite, $data['inputPrenom'], $data['inputNom'], $data['inputEmail'],
                $data['inputTel'], NULL, 'temp', $current_pwd_exp, NULL);
                $car_ID = Vehicle::new_vehicle($client_tmp, $data['selectedModel'], $data['registration'], $data['inputYear'], $carburant, 0);
                $ct_ID = Intervention::new_CT($client_tmp, $time_slot, $car_ID, 0);


                //Add traces in BDD
                $traces = new Trace(0);
                $traces->setTracesIN($client_tmp, 'new', 'intervention');

                //Add Job Sms in Queue table
                $data_SMS = ["CT" => $ct_ID, "car" => $car_ID, "user" => $client_tmp];
                $sms = new SMS(0);
                $sms->setSMS_JobRDV($data_SMS);
            }
        }

        echo json_encode(array("status" => $status, "msg" => $msg));
        break;

    case 'default' :
        echo json_encode(1);
        break;

}
