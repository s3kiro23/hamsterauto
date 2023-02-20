<?php

require_once 'Database.php';

$db = new Database();
$GLOBALS['Database'] = $db->connexion();

class Archive
{
    private $id_archive;
    private $num_tech;
    private $id_user;
    private $lastname_user;
    private $phone_user;
    private $email_user;
    private $id_vehicle;
    private $registration;
    private $time_slot;
    private $booked_date;
    private $state;
    // state 0 = en attente, state 1 = inter en cours, state 2 = CT ok, state 3 = Contre-visite, state 4 = annulé
    private $report;
    private $pv;

    public function __construct($id)
    {
        $this->id_archive = $id;
        if ($this->id_archive != 0) {
            $this->check_data($id);
        }
    }

    public function check_data($id)
    {
        $requete = "SELECT * FROM `archive`
        INNER JOIN `user` ON `archive`.`id_user` = `user`.`id_user` 
        INNER JOIN `vehicle` ON `archive`.`id_vehicle` = `vehicle`.`id_vehicle`
        WHERE `id_archive` = '" .filter($id) . "'";
        $result = mysqli_query($GLOBALS['Database'], $requete) or die;
        if ($data = mysqli_fetch_array($result)) {
            $this->num_tech = $data['num_tech'];
            $this->id_user = $data['id_user'];
            $this->lastname_user = $data['lastname_user'];
            $this->phone_user = $data['phone_user'];
            $this->email_user = $data['email_user'];
            $this->id_vehicle = $data['id_vehicle'];
            $this->registration = $data['registration'];
            $this->time_slot = $data['time_slot'];
            $this->booked_date = $data['booked_date'];
            $this->state = $data['state'];
            $this->report = $data['report'];
            $this->pv = $data['pv'];
        }
    }
    static public function admin_archives(){
        $archives = array();
        $requete = "SELECT * FROM `archive` 
                    INNER JOIN `vehicle` ON `archive`.`id_vehicle` = `vehicle`.`id_vehicle`
                    INNER JOIN `user` ON `archive`.`id_user` =   `user`.`id_user`
                    ORDER BY `id_archive` DESC";
        $result = mysqli_query($GLOBALS['Database'], $requete) or die;
        while ($data = mysqli_fetch_array($result)) {
            array_push($archives, new Archive($data['id_archive'], $data['lastname_user'], $data['phone_user'], $data['registration'], $data['email_user']));
        }
        return $archives;
    }

    public function getId_archive()
    {
        return $this->id_archive;
    }

    public function setId_archive($id_archive)
    {
        $this->id_archive = $id_archive;
    }

    public function getNum_tech()
    {
        return $this->num_tech;
    }

    public function setNum_tech($num_tech)
    {
        $this->num_tech = $num_tech;
    }

    public function getLastname_user()
    {
        return $this->lastname_user;
    }

    public function setLastname_user($lastname_user)
    {
        $this->lastname_user = $lastname_user;
    }
    public function getPhone_user()
    {
        return $this->phone_user;
    }

    public function setPhone_user($phone_user)
    {
        $this->phone_user = $phone_user;
    }
    public function getEmail_user()
    {
        return $this->email_user;
    }

    public function setEmail_user($email_user)
    {
        $this->email_user = $email_user;
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

    public function setRegistration($registration)
    {
        $this->registration = $registration;
    }
    public function getRegistration()
    {
        return $this->registration;
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

}

