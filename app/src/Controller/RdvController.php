<?php
session_start();


spl_autoload_register(function ($classe) {
    require '../Entity/' . $classe . '.php';
});

$db = new Database();
$GLOBALS['Database'] = $db->connexion();


switch ($_POST['request']) {

    case 'newRDVDashboardClient':
        $msg = 'Votre rendez-vous est validé';
        $status = 1;
        $user_ID = Security::decrypt($_SESSION['id'], false);
        $check_car_RDV = "";
        $data = json_decode($_POST['data'], true);
        if (!isset($data['carID'])) {
            $carburant = empty($data['fuel']) ? $data['fuel'] = "" : $data['fuel'];
        }
        $creneau = empty($data['timeSlot']) ? $data['timeSlot'] = "" : $data['timeSlot'];
        $init_control = new Control();
        $check = $init_control->check_fields($data);
        $time_slot = Security::decrypt($data['timeSlot'], "");
        $check_timeslots = Security::check_timeslots($time_slot);
        if ($check['status'] == 0) {
            $msg = $check['msg'];
            $status = $check['status'];
        } else {
            $user = new User($user_ID);
            $notif = new Notification();
            $notify = $notif->check_if_notify($user_ID);
            $car_ID = "";
            $ct_ID = "";
            if (!isset($data['carID'])) {
                $car_check = Vehicle::check_registration($data['registration']);
                if ($car_check) {
                    $msg = 'Un véhicule existe déjà avec cette immatriculation!';
                    $status = 0;
                } else {
                    if (!$check_timeslots) {
                        $status = 2;
                        $msg = 'Touche pas au code !';
                    } else {
                        $car_ID = Vehicle::new_vehicle($user_ID, $data['selectedModel'], $data['registration'], $data['inputYear'], $data['fuel'], 1);
                        $ct_ID = Intervention::new_CT($user_ID, Security::decrypt($data['timeSlot'], ""), $car_ID, 0);
                        //set job SMS
                        if ($notify['confirmed']) {
                            $sms = new SMS(0);
                            $data_SMS = [
                                "CT" => $ct_ID,
                                "user" => $user_ID,
                                "car" => $car_ID,
                            ];
                            $sms->setSMS_JobRDV($data_SMS);
                        }
                    }
                }
            } else {
                $car_ID = Security::decrypt($data['carID'], true);
                $check_car_RDV = User::check_rdv($user_ID, $car_ID);
                $car_user = new Vehicle($car_ID);
                if ($check_car_RDV) {
                    $msg = 'Une intervention existe déjà pour ce véhicule!';
                    $status = 0;
                } else if ($car_user->getNext_control() > strtotime(Convert::current_date())) {
                    $msg = 'Le contrôle technique de votre véhicule est déjà à jour!';
                    $status = 0;
                } else {
                    if (!$check || !$time_slot) {
                        $status = 2;
                        $msg = 'Touche pas au code !';
                    } else {
                        $ctID = Intervention::new_CT($user_ID, Security::decrypt($data['timeSlot'], ""), $car_ID, 0);
                        //set job SMS
                        if ($notify['confirmed']) {
                            $sms = new SMS(0);
                            $data_SMS = [
                                "CT" => $ct_ID,
                                "user" => $user_ID,
                                "car" => $car_ID,
                            ];
                            $sms->setSMS_JobRDV($data_SMS);
                        }
                    }
                }
            }

            //Add traces in BDD
            $traces = new Trace(0);
            $traces->setTracesIN($user_ID, 'new', 'intervention');
        }
        echo json_encode(array("status" => $status, "msg" => $msg));
        break;


    case 'deleteRdv':
        $msg = "Votre rendez-vous a bien été annulé !";
        $CT = new Intervention(Security::decrypt($_POST['idRdv'], false));
        $user_ID = $CT->getId_user();
        $user = new User($user_ID);
        $car_user = new Vehicle($CT->getId_vehicle());
        $CT->setState(4);
        $CT->setNum_tech(0);
        $CT->update();
        $notif = new Notification();
        $notify = $notif->check_if_notify($user_ID);
        //Add Job mail in Queue table
        if ($notify['deleted']) {
            $mail = new Mailing();
            $data_mail = [
                "CT" => $CT,
                "user" => $user,
                "car" => $car_user,
            ];
            $mail->setDeleted_CTJob($data_mail);
        }
        //Add traces in BDD
        $traces = new Trace(0);
        $traces->setTracesIN($user_ID, 'canceled', 'intervention');
        echo json_encode(array("msg" => $msg));
        break;

    case 'show_contre_visite':
        $rapport_contre_visite = "Aucun procès-verbal n'est disponible";
        $id_intervention = Security::decrypt($_POST['rdvID'], false);
        $current_archives = Intervention::check_archive($id_intervention);
        $client = new User($current_archives['id_user']);
        if (!empty($current_archives['pv'])) {
            $path_pv = Security::decrypt($current_archives['pv'], $client->getHash());
            $decrypted_file_content = Security::decrypt(file_get_contents("../../var/generate/minutes/" . $path_pv), $client->getHash());
            $encoded_content = base64_encode($decrypted_file_content);
            $rapport_contre_visite = "<iframe src='data:application/pdf;base64, $encoded_content' height='600' class='w-100'></iframe>";
        }
        echo json_encode(array("rdvID" => $id_intervention, "rapport" => $rapport_contre_visite));
        break;

    default:
        echo json_encode(1);
        break;
}
