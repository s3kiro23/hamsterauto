<?php

require_once 'Database.php';

$db = new Database();
$GLOBALS['Database'] = $db->connexion();

class Setting
{
    private $id_settings;
    private $slot_interval;
    private $start_time_am;
    private $end_time_am;
    private $start_time_pm;
    private $end_time_pm;
    private $nb_lifts;
    private $coordinates;

    public function __construct($id)
    {
        $this->id_settings = $id;
        if ($this->id_settings != 0) {
            $this->checkData($id);
        }
    }

    public function checkData($id)
    {
        $requete = "SELECT * FROM `settings` WHERE id_settings = '" .filter($id) . "'";
        $result = mysqli_query($GLOBALS['Database'], $requete) or die;
        if ($data = mysqli_fetch_array($result)) {
            $this->id_settings = $data['id_settings'];
            $this->slot_interval = $data['slot_interval'];
            $this->start_time_am = $data['start_time_am'];
            $this->end_time_am = $data['end_time_am'];
            $this->start_time_pm = $data['start_time_pm'];
            $this->end_time_pm = $data['end_time_pm'];
            $this->nb_lifts = $data['nb_lifts'];
            $this->coordinates = $data['coordinates'];
        }
    }

    static public function getSettings()
    {

        $requete = "SELECT * FROM `settings`";
        $result = mysqli_query($GLOBALS['Database'], $requete) or die;

        return mysqli_fetch_assoc($result);
    }

    public function getId_settings(){
        return $this->id_settings;
    }

    public function setId_settings($id_settings){
        $this->id_settings = $id_settings;
    }

    public function getSlot_interval(){
        return $this->slot_interval;
    }

    public function setSlot_interval($slot_interval){
        $this->slot_interval = $slot_interval;
    }

    public function getStart_time_am(){
        return $this->start_time_am;
    }

    public function setStart_time_am($start_time_am){
        $this->start_time_am = $start_time_am;
    }

    public function getEnd_time_am(){
        return $this->end_time_am;
    }

    public function setEnd_time_am($end_time_am){
        $this->end_time_am = $end_time_am;
    }

    public function getStart_time_pm(){
        return $this->start_time_pm;
    }

    public function setStart_time_pm($start_time_pm){
        $this->start_time_pm = $start_time_pm;
    }

    public function getEnd_time_pm(){
        return $this->end_time_pm;
    }

    public function setEnd_time_pm($end_time_pm){
        $this->end_time_pm = $end_time_pm;
    }

    public function getNb_lifts(){
        return $this->nb_lifts;
    }

    public function setNb_lifts($nb_lifts){
        $this->nb_lifts = $nb_lifts;
    }

    public function getCoordinates(){
        return $this->coordinates;
    }

    public function setCoordinates($coordinates){
        $this->coordinates = $coordinates;
    }

}