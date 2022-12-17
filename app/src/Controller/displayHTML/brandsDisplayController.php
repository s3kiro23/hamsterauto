<?php session_start();

require_once '../../Controller/shared.php';

spl_autoload_register(function ($classe) {
    require '../../Entity/' . $classe . '.php';
});

$db = new Database();
$GLOBALS['Database'] = $db->connexion();

switch ($_POST['request']) {

    case 'marquesLoad':
        $html_marque = '<option value="">-</option>';
        $list_marque = Vehicule::checkMarques();
        foreach ($list_marque as $marque) {
            $html_marque .= '<option class="" value="' . $marque['id_marque'] . '">' . $marque['nom_marque'] . '</option>';
        }
        echo json_encode(array("html_marque" => $html_marque));
        break;

    case 'modelesLoad':
        $html_model = '<option value="" class="border border-y-slate-700">-</option>';
        $list_modele = Vehicule::checkModeles($_POST['marque']);
        foreach ($list_modele as $modele) {
            $html_model .= '<option value="' . $modele['id_modele'] . '">' . $modele['nom_modele'] . '</option>';
            $status = 1;
        }
        echo json_encode(array("html_model" => $html_model));
        break;

}