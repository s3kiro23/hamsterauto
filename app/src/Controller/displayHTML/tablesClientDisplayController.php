<?php 

session_start();

require $_SERVER['DOCUMENT_ROOT']."/src/Entity/Setting.php";
Setting::autoload();

$db = new Database();
$GLOBALS['Database'] = $db->connexion();


switch ($_POST['request']) {

    /*Tableaux dashboard Client DEBUT*/

    case 'loadCarsRecap':
        setlocale(LC_TIME, "fr_FR", "French");

        if (isset($_SESSION['id']) && $_POST['type'] == "cars") {
            $html_car = "";
            $tab_userCars = User::check_cars(
                Security::decrypt($_SESSION['id'], false),
                false
            );
            foreach ($tab_userCars as $car) {
                $html_car .= LoadClientHTML::carsRecap(
                    $car['brand_name'],
                    $car['model_name'],
                    $car['registration'],
                    Security::encrypt($car['id_vehicle'], true)
                );
            }
            echo json_encode(array(
                'htmlCar' => $html_car,
            ));
        }

        if (isset($_SESSION['id']) && $_POST['type'] == "rdv") {
            $html_RDV = "";
            $tab_rdv = User::check_rdv(
                Security::decrypt($_SESSION['id'], false),
                null
            );
            foreach ($tab_rdv as $rdv) {
                $tech = new User($rdv['id_user']);
                $html_RDV .= LoadClientHTML::rdvRecap(
                    Convert::date_to_fullFR($rdv['time_slot']) . " à " . date("H", $rdv['time_slot']) . "h" . date("i", $rdv['time_slot']),
                    $rdv['state'],
                    $rdv['registration'],
                    $rdv['id_intervention']
                );
            }
            echo json_encode(array(
                'htmlRDV' => $html_RDV,
            ));
        }

        if (isset($_SESSION['id']) && $_POST['type'] == "archives") {
            $html_archives = "Aucunes données n'est disponible";
            $pagination_my_archives = PaginationHTML::clientHistory($_SESSION['id']);
            empty($_POST['page']) || !isset($_POST['page']) ? $_POST['page'] = 1 : $_POST['page'];
            $tab_archives = User::check_history(
                Security::decrypt($_SESSION['id'], false),
                PaginationHTML::off7($_POST['page'])
            );
            $nbr_of_archives = [
                "current" => count($tab_archives),
                "total" => $pagination_my_archives['total_rdv']
            ];
            foreach ($tab_archives as $archives) {
                $tech = new User($archives['num_tech']);
                $html_archives .= LoadClientHTML::history(
                    $archives['id_archive'], Convert::date_to_fullFR($archives['time_slot']),
                    $tech->getFirstname_user(),
                    $archives['registration'], $archives['state']
                );
            }
            echo json_encode(array(
                'htmlArchives' => $html_archives,
                'paginationMyArchives' => $pagination_my_archives['html'],
                "nbrOfArchives" => $nbr_of_archives
            ));
        }

        break;

    /*Tableaux dashboard Client FIN*/

    default :

        echo json_encode(1);

        break;

}
