<?php

require_once 'Database.php';

$db = new Database();
$GLOBALS['Database'] = $db->connexion();

class ControleTech
{
    private $id_controle;
    private $id_tech;
    private $id_user;
    private $id_vehicule;
    private $id_time_slot;
    private $booked_date;
    private $state;
    private $report;
    private $minute;

    public function __construct($id)
    {
        $this->id_controle = $id;
        if ($this->id_controle != 0) {
            $this->checkData($id);
        }
    }

    // SELECT * FROM `controle_tech` 
    //     INNER JOIN `vehicules` ON `controle_tech`.`id_vehicule` = `vehicules`.`id_vehicule`
    //     INNER JOIN `modeles` ON `vehicules`.`id_modele` = `modeles`.`id_modele`
    //     INNER JOIN `marques` ON `modeles`.`id_marque` = `marques`.`id_marque`   
    //     INNER JOIN `clients` ON `controle_tech`.`id_user` =   `clients`.`id_user`
    //     WHERE `id_controle` = '" . mysqli_real_escape_string($GLOBALS['Database'], $id) . "'";
    public function checkData($id)
    {
        $requete = "SELECT * FROM `controle_tech`
        INNER JOIN `users` ON `controle_tech`.`id_user` = `users`.`id_user` 
        INNER JOIN `vehicules` ON `controle_tech`.`id_vehicule` = `vehicules`.`id_vehicule`
        WHERE `id_controle` = '" .filter($id) . "'";
        $result = mysqli_query($GLOBALS['Database'], $requete) or die;
        if ($data = mysqli_fetch_array($result)) {
            $this->id_tech = $data['id_tech'];
            $this->id_user = $data['id_user'];
            $this->id_vehicule = $data['id_vehicule'];
            $this->id_time_slot = $data['id_time_slot'];
            $this->booked_date = $data['booked_date'];
            $this->state = $data['state'];
            $this->report = $data['report'];
            $this->minute = $data['minute'];
        }
    }

    static public function newCT($id_user, $id_time_slot, $id_vehicule, $state)
    {
        $requete = "INSERT INTO `controle_tech` (`id_user`, `id_time_slot`, `id_vehicule`, `state`) 
                    VALUES ('" .filter($id_user) . "','" .filter($id_time_slot) . "',
                    '" .filter($id_vehicule) . "','" .filter($state) . "')";
                    mysqli_query($GLOBALS['Database'], $requete) or die;
        return $GLOBALS['Database']->insert_id;
    }

    public function update()
    {
        $requete = "UPDATE `controle_tech` SET 
                            `id_tech`='" .filter($this->id_tech) . "', 
                            `id_user`='" .filter($this->id_user) . "',
                            `id_vehicule`='" .filter($this->id_vehicule) . "', 
                            `id_time_slot`='" .filter($this->id_time_slot) . "',
                            `state`='" .filter($this->state) . "', 
                            `report`='" .filter($this->report) . "',
                            `minute`='" .filter($this->minute) . "'
        WHERE `id_controle` ='" .filter($this->id_controle) . "'";
        error_log('requete update:');
        mysqli_query($GLOBALS['Database'], $requete) or die;
    }


    static public function generateSlotAvailable($currentDate)
    {
        $timestampActuel = (time() + 900);
        // $timestamp prends en compte les heures été/hiver actuellement +2h ou 7200 sec + les 15 min ou 900 sec
        $tab_available = [];
        $timeSettings = Setting::getSettings();
        for ($e = strtotime($currentDate) + $timeSettings['start_time_am']; $e <= strtotime($currentDate) + $timeSettings['end_time_pm']; $e = $e + $timeSettings['slot_interval']) {
            // condition pour n'afficher que les créneaux dont l'heure n'est pas deja passée
            if ($e > $timestampActuel) {
                $tab_available[] = $e;
            }
        }
        return $tab_available;
    }

    static public function generateSlotUpdate($updateDate)
    {
        $timestampActuel = (time() + 900);
        // $timestamp prends en compte les heures été/hiver actuellement +2h ou 7200 sec + les 15 min ou 900 sec
        $tab_available = [];
        $timeSettings = Setting::getSettings();
        for ($a = $updateDate + $timeSettings['start_time_am']; $a <= $updateDate + $timeSettings['end_time_pm']; $a = $a + $timeSettings['slot_interval']) {
            // condition pour n'afficher que les créneaux dont l'heure n'est pas deja passée
            // à mettre ici également sinon en changeant pour jour suivant et en revenant sur ajourd'hui tous les créneaux réaparraissent
            if ($a > $timestampActuel) {
                $tab_available[] = $a;
            }
        }
        return $tab_available;
    }

    static public function checkTimeSlotReserved($date)
    {
        $timeSlotCheck = false;
        $requete = "SELECT * FROM `controle_tech`  WHERE (`id_time_slot` BETWEEN $date + 28800 AND $date + 64800) AND `state`<> 4 ";
        $result = mysqli_query($GLOBALS['Database'], $requete) or die;
        while ($data = mysqli_fetch_assoc($result)) {
            $timeSlotCheck[] = $data;
        }
        // error_log(json_encode($timeSlotCheck));
        return $timeSlotCheck;
    }
    static public function checkAllRdv($off7, $state, $immat, $currentDate)
    {
        $ts_current_date = $currentDate;
        $list_vehicule = [];
        if ($state == 2) {
            $requete = "SELECT * FROM `controle_tech` 
                    INNER JOIN `vehicules` ON `controle_tech`.`id_vehicule` = `vehicules`.`id_vehicule`
                    INNER JOIN `modeles` ON `vehicules`.`id_modele` = `modeles`.`id_modele`
                    INNER JOIN `marques` ON `modeles`.`id_marque` = `marques`.`id_marque`   
                    INNER JOIN `users` ON `controle_tech`.`id_user` =   `users`.`id_user`
                    WHERE `controle_tech`.`state` >= 2
                    ORDER BY `id_controle` DESC LIMIT 5 OFFSET $off7 ";
        } else {
            $requete = "SELECT * FROM `controle_tech` 
                        INNER JOIN `vehicules` ON `controle_tech`.`id_vehicule` = `vehicules`.`id_vehicule` 
                        INNER JOIN `modeles` ON `vehicules`.`id_modele` = `modeles`.`id_modele`
                        INNER JOIN `marques` ON `modeles`.`id_marque` = `marques`.`id_marque`   
                        INNER JOIN `users` ON `controle_tech`.`id_user` =   `users`.`id_user`
                        WHERE `controle_tech`.`state` = '" .filter($state) . "'
                        AND `id_time_slot` BETWEEN '" .filter($ts_current_date) . "' + 28800 
                        AND '" .filter($ts_current_date) . "' + 64800
                        AND `vehicules`.`immat_vehicule` LIKE '%" .filter($immat) . "%'
                        ORDER BY `id_time_slot` ASC LIMIT 5 OFFSET $off7 ";
                        
        }
        error_log($requete);
        $result = mysqli_query($GLOBALS['Database'], $requete) or die;
        while ($data = mysqli_fetch_assoc($result)) {
            $list_vehicule[] = $data;
        }
        return $list_vehicule;

    }

    static public function checkRdvNextDays($off7, $state, $currentDate)
    {
        /*        $dateSuivante = date("d-m-Y", strtotime('+' . $count . 'day'));*/
        /*        $jourRDV = strtotime($dateSuivante);*/
        $list_vehicule = [];
        if ($state == 0) {
            $requete = "SELECT * FROM `controle_tech` 
        INNER JOIN `vehicules` ON `controle_tech`.`id_vehicule` = `vehicules`.`id_vehicule`  
        INNER JOIN `modeles` ON `vehicules`.`id_modele` = `modeles`.`id_modele`
        INNER JOIN `marques` ON `modeles`.`id_marque` = `marques`.`id_marque`   
        INNER JOIN `users` ON `controle_tech`.`id_user` =   `users`.`id_user`
        WHERE `controle_tech`.`state` = '" .filter(0) . "'
        AND `id_time_slot` BETWEEN '" .filter($currentDate) . "' + 28800
        AND '" .filter($currentDate) . "' + 64800
        ORDER BY `id_time_slot` ASC LIMIT 5 OFFSET $off7";
        }
        error_log('NEXT '.$requete);
        $result = mysqli_query($GLOBALS['Database'], $requete) or die;
        while ($data = mysqli_fetch_assoc($result)) {
            $list_vehicule[] = $data;
        }
        return $list_vehicule;
    }

    static public function checkRdvPreviousDays($off7, $state, $currentDate)
    {
        /*        $datePrecedente = date("d-m-Y", strtotime('+' . $count . 'day'));
                $jourRDV = strtotime($datePrecedente);*/
        $list_vehicule2 = [];
        if ($state == 0) {
            $requete = "SELECT * FROM `controle_tech` 
        INNER JOIN `vehicules` ON `controle_tech`.`id_vehicule` = `vehicules`.`id_vehicule`
        INNER JOIN `modeles` ON `vehicules`.`id_modele` = `modeles`.`id_modele`
        INNER JOIN `marques` ON `modeles`.`id_marque` = `marques`.`id_marque`  
        INNER JOIN `users` ON `controle_tech`.`id_user` =   `users`.`id_user`
        WHERE `controle_tech`.`state` = '" .filter(0) . "'
        AND `id_time_slot` BETWEEN '" .filter($currentDate) . "' + 28800
        AND '" .filter($currentDate) . "' + 64800
        ORDER BY `id_time_slot` ASC LIMIT 5 OFFSET $off7";
        }
        error_log('PREVIOUS '.$requete);
        $result = mysqli_query($GLOBALS['Database'], $requete) or die;
        while ($data = mysqli_fetch_assoc($result)) {
            $list_vehicule2[] = $data;
        }
        return $list_vehicule2;
    }

    static public function countRdv($state, $currentDate)
    {
        if ($state == 2) {
            $requete = " SELECT count(*) AS nbRdv FROM `controle_tech` 
                        WHERE `state` >= 2 ";
        } else {
            $requete = " SELECT count(*) AS nbRdv FROM `controle_tech` 
                        WHERE `id_time_slot` BETWEEN '" .filter($currentDate) . "' + 28800 
                        AND '" .filter($currentDate) . "' + 64800
                        AND `state` = '" .filter($state) . "'";
        }
        $result = mysqli_query($GLOBALS['Database'], $requete) or die;
        $data = mysqli_fetch_assoc($result);
        return (int)$data['nbRdv'];
    }

    public function getCarMinute($client){
        return decrypt($this->getMinute(), $client->getHash());
    }

    public function getId_controle()
    {
        return $this->id_controle;
    }

    public function setId_controle($id_controle)
    {
        $this->id_controle = $id_controle;
    }

    public function getId_tech()
    {
        return $this->id_tech;
    }

    public function setId_tech($id_tech)
    {
        $this->id_tech = $id_tech;
    }

    public function getId_user()
    {
        return $this->id_user;
    }

    public function setId_user($id_user)
    {
        $this->id_user = $id_user;
    }

    public function getId_vehicule()
    {
        return $this->id_vehicule;
    }

    public function setId_vehicule($id_vehicule)
    {
        $this->id_vehicule = $id_vehicule;
    }

    public function getId_time_slot()
    {
        return $this->id_time_slot;
    }

    public function setId_time_slot($id_time_slot)
    {
        $this->id_time_slot = $id_time_slot;
    }

    public function getBooked_date()
    {
        return $this->booked_date;
    }

    public function setBooked_date($booked_date)
    {
        $this->booked_date = $booked_date;
    }

    public function getState()
    {
        return $this->state;
    }

    public function setState($state)
    {
        $this->state = $state;
    }

    public function getReport()
    {
        return $this->report;
    }

    public function setReport($report)
    {
        $this->report = $report;
    }

    public function getMinute()
    {
        return $this->minute;
    }

    public function setMinute($minute)
    {
        $this->minute = $minute;
    }

}

