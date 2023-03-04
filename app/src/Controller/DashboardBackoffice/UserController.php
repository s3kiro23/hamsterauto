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
   $start = isset($_GET['start']) ? $start = (intval($_GET['start']) - 1) * $length : 0;
   $orders = $_POST['order'];
   $search = $_POST['search'];
   $columns = $_POST['columns'];

   // Orders
   foreach ($orders as $key => $order) {
      // Orders does not contain the name of the column, but its number,
      // so add the name so we can handle it just like the $columns array
      $orders[$key]['name'] = $columns[$order['column']]['name'];
   }

   $users = User::fetchAllUsers($start, $length, $orders, $columns);

   $count_users = User::count_users($user_id);

   // Returned objects
   $output = array(
      "start" => $start,
      "length" => $length,
      "draw" => $draw,
      'recordsTotal' => $count_users,
      'recordsFiltered' => count($users),
      'data' => array()
   );
   // Construct response
   foreach ($users as $user) {
      $output['data'][] = [
         '0' => $user->getIs_active() == 0 ? "<span class='text-danger'>" . $user->getLastname_user() . "</span>" : $user->getLastname_user(),
         '1' => $user->getFirstname_user(),
         '2' => $user->getAdress_user(),
         '3' => $user->getPhone_user(),
         '4' => $user->getEmail_user(),
         '5' => $user->getType(),
         '6' => $user->getIs_active() == 1 ? "<img id='LedV'>" : "<img id='LedR'>",
         '7' => "<a role='button' onclick='modalProfilAdmin(" . $user->getId_user() . ", event.target)' data-toggle='tooltip'
         data-placement='bottom' 
         title='modifier utilisateur'><i class='fa-solid fa-user-gear'></i></a>",
         '8' => $user->getIs_active() == 1 ?
            '<span class="offUser" role="button" onclick="inactivateUser(' . $user->getId_user() . ')" data-toggle="tooltip"
             data-placement="bottom" 
             title="désactiver le compte"><i class="fa-solid fa-ban"></i></span>'
            :
            '<span class="onUser" role="button" onclick="activateUser(' . $user->getId_user() . ')" data-toggle="tooltip"
             data-placement="bottom" 
             title="activer le compte"><i class="fa-solid fa-rotate-right"></i></span>',
      ];
   }

   echo json_encode(($output), 200);
}
