<?php session_start();

spl_autoload_register(function ($classe) {
    require '../../Entity/' . $classe . '.php';
});

$db = new Database();
$GLOBALS['Database'] = $db->connexion();

switch ($_POST['request']) {

    case 'brandsLoad':
        $html_brand = '<option value="">-</option>';
        $list_brand = Vehicle::check_brands();
        foreach ($list_brand as $brand) {
            $html_brand .= '<option class="" value="' . $brand['id_brand'] . '">' . $brand['brand_name'] . '</option>';
        }
        echo json_encode(array("html_brand" => $html_brand));
        break;

    case 'modelsLoad':
        $html_model = '<option value="" class="border border-y-slate-700">-</option>';
        $list_model = Vehicle::check_models($_POST['brand']);
        foreach ($list_model as $model) {
            $html_model .= '<option value="' . $model['id_model'] . '">' . $model['model_name'] . '</option>';
            $status = 1;
        }
        echo json_encode(array("html_model" => $html_model));
        break;

}
