<?php

session_start();

spl_autoload_register(function ($classe) {
   require '../../Entity/' . $classe . '.php';
});

$db = new Database();
$GLOBALS['Database'] = $db->connexion();

if ($_POST) {
   $user_id = Security::decrypt($_SESSION['id'], false);

   // Get the parameters from DataTable Ajax Call
   $draw = intval($_POST['draw']);
   $length = $_POST['length'];
   $start = isset($_GET['start']) ? $start = ($_GET['start'] - 1) * $length : $start = 0;
   $orders = $_POST['order'];
   $search = $_POST['search'];
   $columns = $_POST['columns'];

   // Orders
   foreach ($orders as $key => $order) {
      // Orders does not contain the name of the column, but its number,
      // so add the name so we can handle it just like the $columns array
      $orders[$key]['name'] = $columns[$order['column']]['name'];
   }

   $cars_user = User::fetchCars($start, $length, $orders, $search['value'], $user_id);

   $count_all_cars = User::countAllCar($user_id);

   // Returned objects
   $output = array(
      "start" => $start,
      "length" => $length,
      "draw" => $draw,
      'recordsTotal' => $count_all_cars,
      'recordsFiltered' => count($cars_user),
      'data' => array()
   );



   // Construct response
   foreach ($cars_user as $car) {
      //Check diff between control and today
      $one_month = 2505600;
      $check_diff = $car->getNext_control() - time();
      $id_vehicle = Security::encrypt($car->getId_vehicle(), true);
      $output['data'][] = [
         '0' => ($car->getNext_control() == null || $car->getNext_control() < time()) ? "<img id='LedR'>" : (($check_diff > 0 && $check_diff <= $one_month) ? "<img id='LedO'>" : "<img id='LedV'>"),
         '1' => '<img src="/public/assets/img/logo/' . str_replace(" ", "", $car->getBrand_name()) . '.png" alt="' . $car->getBrand_name() . '">',
         '2' => (new Model($car->getId_model()))->getModel_name(),
         '3' => $car->getRegistration(),
         '4' => '<a class="text-decoration-none" 
                     role="button" 
                     data-bs-toggle="dropdown">
                     <i class="fa-solid fa-ellipsis fa-xl"></i>
                  </a>
                  <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                           <button 
                              class="addCG dropdown-item"
                              type="button"
                              data-id=' . $id_vehicle . '>
                              📑 Ajout carte grise
                           </button>
                        </li>
                        <li>
                           <button 
                              class="modifyCar dropdown-item"
                              type="button"
                              data-id=' . $id_vehicle . '>
                              🖊️ Modifier
                           </button>
                        </li>
                        <li>                            
                           <button 
                              class="deleteCar dropdown-item"
                              type="button"
                              data-id=' . $id_vehicle . '>
                              🗑️ Supprimer
                           </button>
                        </li>
                  </ul>',
      ];
   }

   echo json_encode(($output), 200);
}
