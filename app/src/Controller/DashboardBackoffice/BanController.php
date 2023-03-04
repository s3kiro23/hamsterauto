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

   $bans = LoginAttempt::fecthAllAttempts($start, $length, $orders, $search);

   $count_bans = LoginAttempt::countAllBans();

   // Returned objects
   $output = array(
      "start" => $start,
      "length" => $length,
      "draw" => $draw,
      'recordsTotal' => $count_bans,
      'recordsFiltered' => count($bans),
      'data' => array()
   );
   // Construct response
   foreach ($bans as $ban) {
      $output['data'][] = [
         '0' => $ban['email_user'],
         '1' => $ban['remote_ip'],
         '2' => $ban['lastDate'],
         '3' => "<span role='button' onclick='debanUser(" . $ban['id_user'] . ")' 
            data-toggle='tooltip'
            data-placement='bottom' 
            title='autoriser accès'>
            <i class='fa-solid fa-rotate-right'></i>
         </span>",
      ];
   }

   echo json_encode(($output), 200);
}
