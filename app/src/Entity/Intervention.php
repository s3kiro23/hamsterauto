<?php

class Intervention
{
    private $id_intervention;
    private $num_tech;
    private $id_user;
    private $id_vehicle;
    private $time_slot;
    private $booked_date;
    private $state;
    // state 0 = en attente, state 1 = inter en cours, state 2 = CT ok, state 3 = Contre-visite, state 4 = annulé
    private $report;
    private $pv;
    private $notified;

    public function __construct($id)
    {
        $this->id_intervention = $id;
        if ($this->id_intervention != 0) {
            $this->check_data($id);
        }
    }

    public function check_data($id)
    {
        $requete = "SELECT * FROM `awaiting_intervention`
        INNER JOIN `user` ON `awaiting_intervention`.`id_user` = `user`.`id_user` 
        INNER JOIN `vehicle` ON `awaiting_intervention`.`id_vehicle` = `vehicle`.`id_vehicle`
        WHERE `id_intervention` = '" . filter($id) . "'";
        $result = mysqli_query($GLOBALS['Database'], $requete) or die;
        if ($data = mysqli_fetch_array($result)) {
            $this->num_tech = $data['num_tech'];
            $this->id_user = $data['id_user'];
            $this->id_vehicle = $data['id_vehicle'];
            $this->time_slot = $data['time_slot'];
            $this->booked_date = $data['booked_date'];
            $this->state = $data['state'];
            $this->report = $data['report'];
            $this->pv = $data['pv'];
            $this->notified = $data['notified'];
        }
    }

    static public function new_CT($id_user, $time_slot, $id_vehicle, $state)
    {
        $requete = "INSERT INTO `awaiting_intervention` (`id_user`, `time_slot`, `id_vehicle`, `state`) 
                    VALUES ('" . filter($id_user) . "','" . filter($time_slot) . "',
                    '" . filter($id_vehicle) . "','" . filter($state) . "')";
        mysqli_query($GLOBALS['Database'], $requete) or die;
        return $GLOBALS['Database']->insert_id;
    }

    static public function slots_reserved($time_slot)
    {
        $requete = "SELECT count(*) as nbSlots FROM `awaiting_intervention` WHERE `time_slot` ='" . filter($time_slot) . "'";
        $result = mysqli_query($GLOBALS['Database'], $requete) or die;
        $data = mysqli_fetch_assoc($result);
        return $data["nbSlots"];
    }

    public function update()
    {
        $requete = "UPDATE `awaiting_intervention` SET 
            `num_tech`='" . filter($this->num_tech) . "', 
            `id_user`='" . filter($this->id_user) . "',
            `id_vehicle`='" . filter($this->id_vehicle) . "', 
            `time_slot`='" . filter($this->time_slot) . "',
            `state`='" . filter($this->state) . "', 
            `report`='" . filter($this->report) . "',
            `pv`='" . filter($this->pv) . "',
            `notified`='" . filter($this->notified) . "'
            WHERE `id_intervention` ='" . filter($this->id_intervention) . "'";

        mysqli_query($GLOBALS['Database'], $requete) or die;

        if ($this->state > 1) {
            $requete = "INSERT INTO `archive` SELECT * FROM `awaiting_intervention`
            WHERE `id_intervention` ='" . filter($this->id_intervention) . "'";
            mysqli_query($GLOBALS['Database'], $requete) or die;

            $requete2 = "DELETE FROM `awaiting_intervention` WHERE `id_intervention` ='" . filter($this->id_intervention) . "'";
            mysqli_query($GLOBALS['Database'], $requete2) or die;
        }
    }

    static public function fetchRdvAdmin($registration)
    {
        $list_Rdv = [];
        $requete = "SELECT *, awaiting_intervention.id_intervention AS cryptedId, 
            user.lastname_user AS nomTech FROM awaiting_intervention 
            INNER JOIN vehicle ON awaiting_intervention.id_vehicle = vehicle.id_vehicle 
            INNER JOIN model ON vehicle.id_model = model.id_model
            INNER JOIN brand ON model.id_brand = brand.id_brand
            INNER JOIN user ON awaiting_intervention.id_user = user.id_user
            WHERE awaiting_intervention.state BETWEEN 0 AND 1
            AND vehicle.registration LIKE '%" . filter($registration) . "%'
            ORDER BY `id_intervention` ASC";

        $result = mysqli_query($GLOBALS['Database'], $requete) or die;
        while ($data = mysqli_fetch_assoc($result)) {
            $data['cryptedId'] = Security::encrypt($data['cryptedId'], false);
            $data['brand_name'] = $data['brand_name'];
            $data['brand_name'] = str_replace(" ", "", $data['brand_name']);
            $data['time_slot_fr'] = Convert::date_to_fullFR($data['time_slot']);
            $list_Rdv[] = $data;
        }
        return $list_Rdv;
    }


    static public function check_rdv_admin($start, $length, $orders, $search)
    {
        $list_Rdv = [];
        $requete = "SELECT *, awaiting_intervention.id_intervention AS cryptedId, 
            user.lastname_user AS nomTech FROM awaiting_intervention 
            INNER JOIN vehicle ON awaiting_intervention.id_vehicle = vehicle.id_vehicle 
            INNER JOIN model ON vehicle.id_model = model.id_model
            INNER JOIN brand ON model.id_brand = brand.id_brand
            INNER JOIN user ON awaiting_intervention.id_user = user.id_user
            WHERE awaiting_intervention.state BETWEEN 0 AND 1
            AND vehicle.registration LIKE '%" . filter($search['value']) . "%'";

        // Order
        foreach ($orders as $key => $order) {
            // $order['name'] is the name of the order column as sent by the JS
            if ($order['name'] != '') {
                $orderColumn = null;
                switch ($order['name']) {
                    case 'inter': {
                            $orderColumn = 'id_intervention';
                            break;
                        }
                    case 'date': {
                            $orderColumn = 'time_slot';
                            break;
                        }
                    case 'registration': {
                            $orderColumn = 'registration';
                            break;
                        }
                    case 'state': {
                            $orderColumn = 'state';
                            break;
                        }
                }
                if ($orderColumn !== null) {
                    $asc = $order['dir'];
                    $requete .= " ORDER BY `$orderColumn` $asc";
                }
            }
        }

        $requete .= " LIMIT $length OFFSET $start";

        $result = mysqli_query($GLOBALS['Database'], $requete) or die;
        while ($data = mysqli_fetch_assoc($result)) {
            $data['cryptedId'] = Security::encrypt($data['cryptedId'], false);
            $data['brand_name'] = $data['brand_name'];
            $data['brand_name'] = str_replace(" ", "", $data['brand_name']);
            $data['time_slot_fr'] = Convert::date_to_fullFR($data['time_slot']);
            $list_Rdv[] = $data;
        }
        return $list_Rdv;
    }

    static public function countRdvAdmin()
    {
        $requete = "SELECT count(*) AS nbRdv FROM `awaiting_intervention`";

        $result = mysqli_query($GLOBALS['Database'], $requete) or die;
        $data = mysqli_fetch_assoc($result);
        return (int)$data['nbRdv'];
    }

    static public function check_rdv_archives($id)
    {
        $requete = "SELECT * FROM archive
                    WHERE id_archive = '" . filter($id) . "'";
        $result = mysqli_query($GLOBALS['Database'], $requete) or die;

        return mysqli_fetch_assoc($result);
    }

    static public function generate_slot_available($currentDate)
    {
        $timestampActuel = (time() + 900);
        // $timestamp prends en compte les heures été/hiver actuellement +2h ou 7200 sec + les 15 min ou 900 sec
        $tab_available = [];
        $timeSettings = Setting::get_settings();
        for ($e = strtotime($currentDate) + $timeSettings['start_time_am']; $e <= strtotime($currentDate) + $timeSettings['end_time_pm']; $e = $e + $timeSettings['slot_interval']) {
            // condition pour n'afficher que les créneaux dont l'heure n'est pas deja passée
            if ($e > $timestampActuel) {
                $tab_available[] = $e;
            }
        }
        return $tab_available;
    }

    static public function generate_slot_update($updateDate)
    {
        $timestampActuel = (time() + 900);
        // $timestamp prends en compte les heures été/hiver actuellement +2h ou 7200 sec + les 15 min ou 900 sec
        $tab_available = [];
        $timeSettings = Setting::get_settings();
        for ($a = $updateDate + $timeSettings['start_time_am']; $a <= $updateDate + $timeSettings['end_time_pm']; $a = $a + $timeSettings['slot_interval']) {
            // condition pour n'afficher que les créneaux dont l'heure n'est pas deja passée
            // à mettre ici également sinon en changeant pour jour suivant et en revenant sur ajourd'hui tous les créneaux réaparraissent
            if ($a > $timestampActuel) {
                $tab_available[] = $a;
            }
        }
        return $tab_available;
    }

    static public function info_checklist($id_inter)
    {

        $requete = "SELECT * FROM `awaiting_intervention` 
            INNER JOIN `vehicle` ON `awaiting_intervention`.`id_vehicle` = `vehicle`.`id_vehicle` 
            INNER JOIN `model` ON `vehicle`.`id_model` = `model`.`id_model` 
            INNER JOIN `brand` ON `model`.`id_brand` = `brand`.`id_brand`   
            WHERE `awaiting_intervention`.`id_intervention` = '" . $id_inter . "'";
        $result = mysqli_query($GLOBALS['Database'], $requete) or die;

        return mysqli_fetch_assoc($result);
    }

    static public function check_timeslot_reserved($date)
    {
        $timeSlotCheck = false;
        $requete = "SELECT * FROM `awaiting_intervention`  
         WHERE (`time_slot` BETWEEN '" . filter($date) . "' + '" . filter(28800) . "' 
                        AND '" . filter($date) . "' + '" . filter(64800) . "' 
                        AND `state`<> '" . filter(4) . "')";
        $result = mysqli_query($GLOBALS['Database'], $requete) or die;
        while ($data = mysqli_fetch_assoc($result)) {
            $timeSlotCheck[] = $data;
        }
        return $timeSlotCheck;
    }

    static public function check_all_rdv($off7, $state, $registration, $currentDate)
    {
        $ts_current_date = $currentDate;
        $list_vehicle = [];
        if ($state == 2) {
            $requete = "SELECT * FROM `archive` 
                    INNER JOIN `vehicle` ON `archive`.`id_vehicle` = `vehicle`.`id_vehicle`
                    INNER JOIN `model` ON `vehicle`.`id_model` = `model`.`id_model`
                    INNER JOIN `brand` ON `model`.`id_brand` = `brand`.`id_brand`   
                    INNER JOIN `user` ON `archive`.`id_user` =   `user`.`id_user`
                    WHERE `archive`.`state` >= 2
                    ORDER BY `id_archive` DESC LIMIT 5 OFFSET $off7 ";
        } else {
            $requete = "SELECT * FROM `awaiting_intervention` 
                        INNER JOIN `vehicle` ON `awaiting_intervention`.`id_vehicle` = `vehicle`.`id_vehicle` 
                        INNER JOIN `model` ON `vehicle`.`id_model` = `model`.`id_model`
                        INNER JOIN `brand` ON `model`.`id_brand` = `brand`.`id_brand`   
                        INNER JOIN `user` ON `awaiting_intervention`.`id_user` =   `user`.`id_user`
                        WHERE `awaiting_intervention`.`state` = '" . filter($state) . "'
                        AND `time_slot` BETWEEN '" . filter($ts_current_date) . "' + 28800 
                        AND '" . filter($ts_current_date) . "' + 64800
                        AND `vehicle`.`registration` LIKE '%" . filter($registration) . "%'
                        ORDER BY `time_slot` ASC LIMIT 5 OFFSET $off7 ";
        }

        $result = mysqli_query($GLOBALS['Database'], $requete) or die;
        while ($data = mysqli_fetch_assoc($result)) {
            $data['brand_name'] = strtolower($data['brand_name']);
            $data['brand_name'] = str_replace(" ", "", $data['brand_name']);
            $list_vehicle[] = $data;
        }
        return $list_vehicle;
    }

    static public function check_archive($id)
    {
        $requete = "SELECT * FROM `archive`
                    WHERE `id_archive` = '" . filter($id) . "'";
        $result = mysqli_query($GLOBALS['Database'], $requete) or die;

        return mysqli_fetch_assoc($result);
    }

    static public function check_rdv_switch_days($off7, $state, $date)
    {
        $list_vehicle = [];
        if ($state == 0) {
            $requete = "SELECT * FROM `awaiting_intervention` 
        INNER JOIN `vehicle` ON `awaiting_intervention`.`id_vehicle` = `vehicle`.`id_vehicle`  
        INNER JOIN `model` ON `vehicle`.`id_model` = `model`.`id_model`
        INNER JOIN `brand` ON `model`.`id_brand` = `brand`.`id_brand`   
        INNER JOIN `user` ON `awaiting_intervention`.`id_user` =   `user`.`id_user`
        WHERE `awaiting_intervention`.`state` = '" . filter(0) . "'
        AND `time_slot` BETWEEN '" . filter($date) . "' + 28800
        AND '" . filter($date) . "' + 64800
        ORDER BY `time_slot` ASC LIMIT 5 OFFSET $off7";
        }
        $result = mysqli_query($GLOBALS['Database'], $requete) or die;
        while ($data = mysqli_fetch_assoc($result)) {
            $list_vehicle[] = $data;
        }
        return $list_vehicle;
    }

    static public function count_rdv($state = null, $Date)
    {
        if ($state == 2) {
            $requete = " SELECT count(*) AS nbRdv FROM `archive` 
                        WHERE `state` >= 2 ";
        } elseif ($state == null) {
            $requete = "SELECT count(*) AS nbRdv FROM `awaiting_intervention`
            WHERE `time_slot` BETWEEN '" . filter($Date) . "' + 28800 
            AND '" . filter($Date) . "' + 64800";
        } else {
            $requete = " SELECT count(*) AS nbRdv FROM `awaiting_intervention` 
                        WHERE `time_slot` BETWEEN '" . filter($Date) . "' + 28800 
                        AND '" . filter($Date) . "' + 64800
                        AND `state` = '" . filter($state) . "'";
        }
        $result = mysqli_query($GLOBALS['Database'], $requete) or die;
        $data = mysqli_fetch_assoc($result);
        return (int)$data['nbRdv'];
    }

    public function getId_intervention()
    {
        return $this->id_intervention;
    }

    public function setId_intervention($id_intervention)
    {
        $this->id_intervention = $id_intervention;
    }

    public function getNum_tech()
    {
        return $this->num_tech;
    }

    public function setNum_tech($num_tech)
    {
        $this->num_tech = $num_tech;
    }

    public function getId_user()
    {
        return $this->id_user;
    }

    public function setId_user($id_user)
    {
        $this->id_user = $id_user;
    }

    public function getId_vehicle()
    {
        return $this->id_vehicle;
    }

    public function setId_vehicle($id_vehicle)
    {
        $this->id_vehicle = $id_vehicle;
    }

    public function getTime_slot()
    {
        return $this->time_slot;
    }

    public function setTime_slot($time_slot)
    {
        $this->time_slot = $time_slot;
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

    public function getPv()
    {
        return $this->pv;
    }

    public function setPv($pv)
    {
        $this->pv = $pv;
    }

    public function getNotified()
    {
        return $this->notified;
    }

    public function setNotified($notified)
    {
        $this->notified = $notified;
    }
}
