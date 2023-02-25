<?php

class Export
{

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
      header("Content-Type:application/csv");
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
            mb_convert_encoding($state, 'UTF-8')
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
         $user = new User($archive['id_user']);
         fputcsv($csv, array(
            $user->getEmail_user(),
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
