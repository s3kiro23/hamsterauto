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
   $start = $_POST['start'];
   $length = $_POST['length'];
   $orders = $_POST['order'];
   $search = $_POST['search'];
   $columns = $_POST['columns'];

   // Orders
   foreach ($orders as $key => $order) {
      // Orders does not contain the name of the column, but its number,
      // so add the name so we can handle it just like the $columns array
      $orders[$key]['name'] = $columns[$order['column']]['name'];
   }
   $rdv_user = User::fetchAllRdv($start, $length, $search['value'], $user_id);

   $count_rdv = User::countAllRdv($user_id);

   // Returned objects
   $output = array(
      "draw" => $draw,
      'recordsTotal' => $count_rdv,
      'recordsFiltered' => count($rdv_user),
      'data' => array()
   );
   // Construct response
   foreach ($rdv_user as $rdv) {
      $output['data'][] = [
         '0' => Convert::date_to_fullFR($rdv->getTime_slot()),
         '1' => (new Vehicle($rdv->getId_vehicle()))->getRegistration(),
         '2' => $rdv->getState() == 1 ? '<div class="badge rounded-pill bg-soft-info text-info">
         Pris en charge
         </div>' : '<div class="badge rounded-pill text-secondary bg-soft-secondary">
         En attente
         </div>',
         '3' => "<button  onclick='deleteRdvUser(`" . Security::encrypt($rdv->getId_intervention(), false) . "`)' 
                     id='deleteRdv' 
                     type='button'
                     class='deleteRdv border-0 bg-transparent' 
                     data-toggle='tooltip'
                     data-placement='bottom' 
                     title='Supprimer intervention'>
                     <i class='fa-solid fa-xmark text-danger fa-xl'></i>
                  </button>",
      ];
   }

   echo json_encode(($output), 200);
}
