<?php

session_start();

spl_autoload_register(function ($classe) {
    require '../../Entity/' . $classe . '.php';
});

require_once "../../Entity/HTML/LoadTechHTML.php";


$db = new Database();
$GLOBALS['Database'] = $db->connexion();


if (isset($_SESSION['id']) && !empty($_SESSION['id'])) {
    $check = Security::check_security();
    if ($check === 'technicien') {

        switch ($_POST['request']) {

            case 'autorisationCT':
                $msg = "";
                $status = 1;
                echo json_encode(array('msg' => $msg, 'status' => $status));
                break;

            case 'display_check_list':
                $html = LoadTechHTML::checklist_content();
                echo json_encode($html);
                break;

            case 'validationCT' :
                error_log(1);
                $id_intervention = $_POST['id_intervention'];
                $msg = 'Intervention en cours de validation...';
                $report = json_decode($_POST['tab_checkbox'], true);
                $CT = new Intervention($id_intervention);
                $car_user = new Vehicle($CT->getId_vehicle());
                $user = new User($CT->getId_user());
                $user_hash = $user->getHash();
                $mail = new Mailing();
                $pv_pdf = new PDF();
                $notif = new Notification();
                $notify = $notif->check_if_notify($CT->getId_user());

                if (sizeof($report) == 0) {
                    $next_control = $CT->getTime_slot() + 63097119;
                    $CT->setState(2);
                    $car_user->setNext_control($next_control);
                    $car_user->setNotified(0);
                    $car_user->update();
                    $pdf_template = $pv_pdf->pv($car_user, $CT, $user);
                    $CT->setPv(Security::encrypt($pv_pdf->generate_pdf($pdf_template, $user_hash), $user_hash));
                    $mail_template = $mail->getCT_OK($user, $CT, $car_user);
                } else {
                    $CT->setState(3);
                    $CT->setReport(json_encode($report));
                    $pdf_template = $pv_pdf->pv($car_user, $CT, $user);
                    $CT->setPv(Security::encrypt($pv_pdf->generate_pdf($pdf_template, $user_hash), $user_hash));
                    $mail_template = $mail->getCT_KO($user, $CT, $car_user);
                }
                $CT->update();

                //Add Job mail in Queue tables
                if ($notify['pv']) {
                    $mail->setFinished_Job($user, $mail_template);
                }

                //Add Job Sms in Queue table
                if ($notify['finished']) {
                    $sms = new SMS(0);
                    $dataSMS = ["car" => $car_user, "user" => $user];
                    $sms->setSMS_JobFinish($dataSMS);
                }

                //Add traces in BDD
                $traces = new Trace(0);
                $traces->setTracesIN(Security::decrypt($_SESSION['id'], false), 'validate', 'intervention');

                echo json_encode(array("msg" => $msg));
                break;

            case 'load_check_list':
                $html = '';
                $id_inter = $_POST['intervention'];
                $data = Intervention::info_checklist($id_inter);
                $tech = new User($data['num_tech']);
                $html = LoadTechHTML::checklist_info($data, $tech);
                $html_inter = 'Intervention n° ' . $id_inter;

                echo json_encode(array("html" => $html, "html_inter" => $html_inter, "id_inter" => $id_inter));

                break;
        }
    } else {
        $msg = "Accès interdit";
        $status = 0;
        session_destroy();
        echo json_encode(array('msg' => $msg, 'status' => $status));
    }
} else {
    $msg = "Vous n'êtes pas authentifié";
    $status = 0;
    echo json_encode(array('msg' => $msg, 'status' => $status));
}
        
