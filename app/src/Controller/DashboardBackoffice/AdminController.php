<?php
session_start();

require $_SERVER['DOCUMENT_ROOT'] . "/src/Entity/Setting.php";
Setting::autoload();

require __DIR__ . '/../../../config/Twig.php';


$db = new Database();
$GLOBALS['Database'] = $db->connexion();

$check = Security::check_security();

if ($check === 'admin') {

    switch ($_POST['request']) {

        case 'export_user':

            echo json_encode(Export::userToCSV(json_decode($_POST['tabValues'], true)));

            break;

        case 'export_archives':

            echo json_encode(Export::archiveToCSV());

            break;

        case 'export_logs':

            echo json_encode(Export::logToCSV());

            break;

        case 'launch_api_sync':
            require __DIR__ . '/../../../script/brands_models/ApiMatmutSync.php';

            $output = majBdd();
            $status = 0;
            if (!empty($output['output']['brands'] || !empty($output['output']['models']))) {
                $brands = $output['output']['brands'];
                $models = $output['output']['models'];
                $brands_msg = count($brands) > 1 ? 'Les marques suivantes ont été ajoutées : ' : 'La marque suivante a été ajoutée : ';
                $brands_msg .= implode(', ', $brands);
                $models_msg = count($models) > 1 ? 'Les modèles suivants ont été ajoutés : ' : 'Le modèle suivant a été ajouté : ';
                $models_msg .= implode(', ', $models) . '.';
                $status = 1;
            }
            $totalTime_msg = 'La requête a été exécuté en ' . $output['totalTime'] . ' secondes';

            echo json_encode(array(
                'msg' => 'Mise à jour de la base de données véhicules terminé !',
                'status' => $status,
                'unfilled' => "Aucune données ajoutées",
                'brands_msg' => $brands_msg,
                'models_msg' => $models_msg,
                'totalTime' => $totalTime_msg
            ));
            break;

        case 'display_adminOffice':
            $date = Convert::date_to_fullFR();
            $userCount = User::count_users();
            $interv = Intervention::count_rdv(0, strtotime(date('d-m-Y')));
            $brands_in_bdd = Brand::count_brands();
            $models_in_bdd = Model::count_models();
            $cars = Vehicle::count_cars();
            $popular_brand = Vehicle::popular_brand();
            if (empty($popular_brand['brand_name'])) {
                $popular_brand['brand_name'] = 'Aucune donnée';
            } else {
                $popular_brand['brand_name'] = $popular_brand['brand_name'];
                $popular_brand['brand_name'] = str_replace(" ", "", $popular_brand['brand_name']);
            }
            $return = $twig->render('admin/admin_index.html.twig', array(
                'date' => $date, 'nbUsers' => $userCount, 'nbInter' => $interv, 'brands' => $brands_in_bdd,
                'models' => $models_in_bdd, 'nbCars' => $cars, 'popularBrandName' => $popular_brand['brand_name'],
                'popularBrandPic' => "../public/assets/img/logo/" . $popular_brand['brand_name'] . ".png"

            ));

            echo json_encode($return);
            break;

        case 'display_RDV_tab':
            $return = $twig->render('intervention/cars_tab_structure.html.twig');
            echo json_encode($return);
            break;

        case 'display_Rdv_wait':
            $registration = $_POST['registration'];
            $current_date = $_POST['currentDate'];
            $Rdv = Intervention::check_rdv_admin(0, $registration, $current_date, "id_user");
            $return = $twig->render('intervention/car_tabs_filler.html.twig', array(
                'Rdvs' => $Rdv,
            ));
            echo json_encode($return);
            break;

        case 'display_Rdv_wip':
            $current_date = $_POST['currentDate'];
            $Rdv = Intervention::check_rdv_admin(1, "", $current_date, "num_tech");
            $return = $twig->render('intervention/car_tabs_wip_filler.html.twig', array(
                'Rdvs' => $Rdv
            ));
            echo json_encode($return);
            break;

        case 'display_users_tab':
            $return = $twig->render('user/users_tab_structure.html.twig');
            echo json_encode($return);
            break;

        case 'display_users':
            $users = User::check_all_users($_POST['name'], $_POST['firstName'], $_POST['adress'], $_POST['phone'], $_POST['mail'], $_POST['type'], $_POST['active']);
            $return = $twig->render('user/users_tab_filler.html.twig', array(
                'users' => $users
            ));
            echo json_encode($return);
            break;

        case 'inactivate_user':
            $user_cars = User::check_cars($_POST['id'], "");
            $user_rdv = User::check_rdv($_POST['id'], "");
            $user = new User($_POST['id']);
            $notif = new Notification();
            $notification_user = $notif->check_if_notify($_POST['id']);
            $notif->uncheck_notification($notification_user, $_POST['id']);
            $user->disable($user_cars, $user_rdv);
            echo json_encode(0);
            break;

        case 'activate_user':
            $user = new User($_POST['id']);
            $user_cars = $user->count_cars($_POST['id']);
            $user->enable($_POST['id'], $user_cars);
            echo json_encode(0);
            break;

        case 'add_user':
            $data = json_decode($_POST['values'], true);
            $status = 1;
            $msg = "Utilisateur enregistré";
            if ($data['inputPassword'] != $data['inputPassword2']) {
                $status = 0;
                $msg = "Vérifiez votre mot de passe";
            }
            $user = User::check_user($data['inputLoginAdd']);
            $civilite = empty($data['civilite']) ? $data['civilite'] = "" : $data['civilite'];
            if ($user) {
                $status = 0;
                $msg = "Le login existe déjà!";
            }
            if ($status == 1) {
                $currenPwdExp = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m"), date("d") + 30, date("Y")));
                $client = User::create_user_admin(
                    $data['civilite'],
                    $data['inputPrenomAdd'],
                    $data['inputNomAdd'],
                    $data['inputLoginAdd'],
                    $data['inputAddrAdd'],
                    $data['inputTelAdd'],
                    $data['inputPassword'],
                    $data['typeAccount'],
                    $currenPwdExp,
                    User::random_hash(),
                );
                //Add traces in BDD
                $traces = new Trace(0);
                $traces->setId_user(Security::decrypt($_SESSION['id'], false), 'created by admin', 'account');
            }
            echo json_encode(array("status" => $status, "msg" => $msg));
            break;


        case 'modifyUser':
            $data = json_decode($_POST['values'], true);
            $init_control = new Control();
            $check = $init_control->check_fields($data);
            if ($check['status'] == 0) {
                $msg = $check['msg'];
                $status = $check['status'];
            } else {
                $status = 1;
                $msg = 'Les modifications ont bien été prises en compte!';
                $user = new User($_POST['id']);
                $user->setEmail_user($data['inputLogin']);
                $user->setLastname_user($data['inputNom']);
                $user->setFirstname_user($data['inputPrenom']);
                $user->setPhone_user($data['inputTel']);
                $user->setAdress_user($data['inputAddr']);
                $user->update();

                //Add traces in BDD
                $traces = new Trace(0);
                $traces->setId_user(Security::decrypt($_SESSION['id'], false));
                $traces->setType('account');
                $traces->setAction('modify');
                $traces->create();
            }
            echo json_encode(array("status" => $status, "msg" => $msg));
            break;

        case 'display_ban_tab':
            $return = $twig->render('ban/ban_account_structure.html.twig');
            echo json_encode($return);
            break;

        case 'display_ban_users';
            $banUsers = LoginAttempt::check_all_attempts();
            $return = $twig->render('ban/ban_filler.html.twig', array(
                'banAccounts' => $banUsers
            ));
            echo json_encode($return);
            break;

        case 'deban_user':
            $msg = '';
            LoginAttempt::unban_user($_POST['userId']);
            $msg = 'Compté débloqué';
            echo json_encode($msg);
            break;

        case 'display_admin_archives':
            $return = $twig->render('archive/archives_structure.html.twig');
            echo json_encode($return);
            break;

        case 'admin_archives':
            $archives = Archive::admin_archives();
            $return = $twig->render('archive/archives_filler.html.twig', array(
                'archives' => $archives
            ));
            echo json_encode($return);
            break;

        case 'show_contre_visite':
            $id_archive = $_POST['rdvID'];
            $rapport_contre_visite = "Aucun procès-verbal n'est disponible";
            $archives = Intervention::check_rdv_archives($id_archive);
            $client = new User($archives['id_user']);
            if (!empty($archives['pv'])) {
                $path_pv = Security::decrypt($archives['pv'], $client->getHash());
                $decrypted_file_content = Security::decrypt(file_get_contents("../../../var/generate/minutes/" . $path_pv), $client->getHash());
                $encoded_content = base64_encode($decrypted_file_content);
                $rapport_contre_visite = "<iframe src='data:application/pdf;base64, $encoded_content' height='600' class='w-100'></iframe>";
            }
            echo json_encode(array("rdvID" => $id_archive, "rapport" => $rapport_contre_visite));
            break;

        case 'display_logs_tab':
            $return = $twig->render('monitoring/logs_structure.html.twig');
            echo json_encode($return);
            break;

        case 'admin_logs':
            $logs = Trace::display_traces();
            $return = $twig->render('monitoring/logs_filler.html.twig', array(
                'logs' => $logs,
            ));
            echo json_encode($return);
            break;

        case 'display_settings':
            $return = $twig->render('setting/settings_structure.html.twig');
            echo json_encode($return);

            break;

        case 'show_settings':
            $settings = new Setting(1);

            $hours_convert = array(
                "slot_interval_h" => gmdate("H", $settings->getSlot_interval()),
                "slot_interval_i" => gmdate("i", $settings->getSlot_interval()),
                "start_time_am_h" => gmdate("H", $settings->getStart_time_am()),
                "start_time_am_i" => gmdate("i", $settings->getStart_time_am()),
                "end_time_pm_h" => gmdate("H", $settings->getEnd_time_pm()),
                "end_time_pm_i" => gmdate("i", $settings->getEnd_time_pm()),
            );
            $return = $twig->render('setting/settings_filler.html.twig', array(
                'set' => $settings,
                'hours' => $hours_convert,
            ));
            echo json_encode($return);

            break;

        case 'update_hour':
            $settings_database = Setting::get_settings();
            $context = $_POST['context'];
            $slot = $_POST['slot'];
            $new_TimeH = $_POST['newTimeH'];
            $new_TimeM = $_POST['newTimeM'];
            error_log("Context : " . $slot . " | Nouvelle heure : " . $new_TimeH . " | Nouvelle minute : " . $new_TimeM);
            $timestamp = ($new_TimeH * 3600) + ($new_TimeM * 60);
            $settings = Setting::change_time_settings($slot, $timestamp);
            echo json_encode(0);
            break;

        case 'update_slot':
            $new_TimeH = $_POST['newTimeH'];
            $new_TimeM = $_POST['newTimeM'];
            $timestamp = ($new_TimeH * 3600) + ($new_TimeM * 60);
            $settings = Setting::change_slot_interval($timestamp);
            echo json_encode(0);
            break;

        case 'session_update':
            $context = $_POST['context'];
            if ($_POST['context'] == 'user' && $_POST["sessionDuration"] > 10) {
                $_POST["sessionDuration"] = 10;
            }
            if ($_POST['context'] == 'internal' && $_POST["sessionDuration"] > 30) {
                $_POST["sessionDuration"] = 30;
            }
            $settings_database = Setting::get_settings();
            $timestamp = $_POST["sessionDuration"] * 60;
            $settings = Setting::change_session_settings($context, $timestamp);
            echo json_encode(0);
            break;

        case 'change_lifts':
            $lifts = $_POST['lifts'];
            $settings = Setting::change_lifts($lifts);
            break;
    }
} else {
    session_destroy();
    $msg = "Accès interdit";
    $status = 0;
    echo json_encode(array('msg' => $msg, 'status' => $status));
}
