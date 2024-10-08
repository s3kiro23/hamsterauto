<?php

class Export
{

   static public function interventionToCSV($filter) {
      $columns = array(
         'N° INTER',
         'DATE',
         'MARQUE',
         'MODELE',
         'IMMATRICULATION',
         'STATUT',
      );

      $name_file = date("dmYHis") . ".csv";
      $csv = fopen(ROOT_DIR() . "/var/generate/doc/" . $name_file, 'w') or die("Can't open php://output");
      header("Content-Type:application/csv");
      header("Content-Disposition:attachment;filename=" . $name_file);
      fputcsv($csv, $columns, ';');

      $data = Intervention::fetchRdvAdmin($filter);

      foreach ($data as $inter) {
         fputcsv($csv, array(
            $inter['id_intervention'],
            Convert::date_to_fullFR($inter['time_slot']),
            (new Brand($inter['id_brand']))->getBrand_name(),
            (new Model($inter['id_model']))->getModel_name(),
            $inter['registration'],
            $inter['state']  == 0 ? "En attente" : "Pris en charge",
         ), ';');
      }

      fclose($csv);

      return $response = array(
         'name' => date("dmY") . ".csv",
         'url' => '/var/generate/doc/' . $name_file
      );
   }

   static public function userToCSV($values)
   {
      $columns = array(
         'NOM',
         'PRENOM',
         'ADRESSE',
         'TELEPHONE',
         'MAIL',
         'TYPE',
         'ETAT',
      );

      $name_file = date("dmYHis") . ".csv";
      $csv = fopen(ROOT_DIR() . "/var/generate/doc/" . $name_file, 'w') or die("Can't open php://output");
      header("Content-Type:application/csv");
      header("Content-Disposition:attachment;filename=" . $name_file);
      fputcsv($csv, $columns, ';');

      $data = User::check_all_users($values['searchName'], $values['searchFirstName'], $values['searchAdress'], $values['searchTel'], $values['searchMail'], $values['searchType'], $values['searchisActive']);

      foreach ($data as $user) {
         fputcsv($csv, array(
            $user->getLastname_user(),
            $user->getFirstname_user(),
            $user->getAdress_user(),
            $user->getPhone_user(),
            $user->getEmail_user(),
            $user->getType(),
            $user->getIs_active() == 1 ? "Actif" : "Inactif"
         ), ';');
      }

      fclose($csv);

      return $response = array(
         'name' => date("dmY") . ".csv",
         'url' => '/var/generate/doc/' . $name_file
      );
   }

   static public function archiveToCSV()
   {
      $columns = array(
         'IMMATRICULATION',
         'NOM CLIENT',
         'EMAIL CLIENT',
         'TELEPHONE',
         'STATUT'
      );

      $states = array(
         "2" => "valide",
         "3" => "contre-visite",
         "4" => "annulé"
      );

      $name_file = date("dmYHis") . ".csv";
      $csv = fopen(ROOT_DIR() . "/var/generate/doc/" . $name_file, 'w') or die("Can't open php://output");
      header("Content-Encoding: UTF-8");
      header("Content-Type: text/csv; charset=UTF-8");
      header("Content-Disposition:attachment;filename=" . $name_file);
      fputcsv($csv, $columns, ';');

      $data = Archive::admin_archives();

      foreach ($data as $archive) {
         $state = $archive->getState();
         if (array_key_exists($state, $states)) {
            $state = $states[$state];
         }
         fputcsv($csv, array(
            $archive->getRegistration(),
            $archive->getLastname_user(),
            $archive->getEmail_user(),
            $archive->getPhone_user(),
            $state,
         ), ';');
      }

      fclose($csv);

      return $response = array(
         'name' => date("dmY") . ".csv",
         'url' => '/var/generate/doc/' . $name_file
      );
   }

   static public function logToCSV()
   {
      $columns = array(
         'LOGIN UTILISATEUR',
         'ACTIONS',
         'DATE ET HEURE'
      );

      $name_file = date("dmYHis") . ".csv";
      $csv = fopen(ROOT_DIR() . "/var/generate/doc/" . $name_file, 'w') or die("Can't open php://output");
      header("Content-Type:application/csv");
      header("Content-Disposition:attachment;filename=" . $name_file);
      fputcsv($csv, $columns, ';');

      $data = Trace::display_traces();

      foreach ($data as $archive) {
         fputcsv($csv, array(
            (new User($archive['id_user']))->getEmail_user(),
            $archive['type'] . "=>" . $archive['action'],
            $archive['triggered_at']
         ), ';');
      }

      fclose($csv);

      return $response = array(
         'name' => date("dmY") . ".csv",
         'url' => '/var/generate/doc/' . $name_file
      );
   }
}
