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

   $interventions = Intervention::check_rdv_admin($start, $length, $orders, $search);

   $count_interv = Intervention::countRdvAdmin();

   // Returned objects
   $output = array(
      "start" => $start,
      "length" => $length,
      "draw" => $draw,
      'recordsTotal' => $count_interv,
      'recordsFiltered' => count($interventions),
      'data' => array()
   );
   // Construct response
   foreach ($interventions as $intervention) {
      $output['data'][] = [
         '0' => "<button onclick='modalRdvInfo(" . json_encode($intervention['cryptedId']) . ")' 
                  class='modalrdvInfo border-0 bg-transparent font-medium' 
                  type='button' data-toggle='tooltip' 
                  data-id=" . $intervention['cryptedId'] . "
                  data-placement='bottom' 
                  title='Voir infos'>
                  <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-search' viewBox='0 0 16 16'>
                     <path d = 'M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 
                        0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z' />
                  </svg >
               </button >",
         '1' => "<span class='text-muted'>" . $intervention['id_intervention'] . "</span>",
         '2' => "<span class='text-muted'>" . $intervention['time_slot_fr'] . " à " . gmdate("G\hi", $intervention['time_slot']) . "</span>",
         '3' => "<span class='text-muted'><img src='../public/assets/img/logo/" . $intervention['brand_name'] . ".png'></span>",
         '4' => "<span class='text-muted'>" . $intervention['model_name'] . "</span>",
         '5' => "<span class='text-muted'>" . $intervention['registration'] . "</span>",
         '6' => $intervention['state'] == 0 ?
            "<div class='badge rounded-pill text-secondary bg-soft-secondary'>
             En attente
            </div>"
            : "<div class='badge rounded-pill text-info bg-soft-info'>Pris en charge</div>",
         '7' => "<button
                  type='button' 
                  data-id=" . $intervention['cryptedId'] . "
                  class='deleteRdvTech border-0 bg-transparent' 
                  data-toggle='tooltip' 
                  data-placement='bottom' 
                  title='Supprimer intervention'>
                  <i class='fa-solid fa-xmark text-danger fa-xl'></i>
               </button>",
      ];
   }

   echo json_encode(($output), 200);
}
