<?php

require_once "Database.php";

$db = new Database();
$GLOBALS['Database'] = $db->connexion();

class Request
{
    private $id_request;
    private $id_user;
    private $hash;
    private $state;
    private $requested_at;

    public function __construct($id)
    {
        $this->id_request = $id;
        if ($this->id_request != 0) {
            $this->check_data($id);
        }
    }

    public function check_data($id)
    {
        $requete = "SELECT * FROM `request` WHERE `id_request` = '" . filter($id) . "'";
        $result = mysqli_query($GLOBALS['Database'], $requete) or die;
        if ($data = mysqli_fetch_assoc($result)) {
            $this->id_request = $data['id_request'];
            $this->id_user = $data['id_user'];
            $this->hash = $data['hash'];
            $this->state = $data['state'];
            $this->requested_at = $data['requested_at'];
        }
    }

    public function check_expiration()
    {
        $request_check = [];
        $requete = "SELECT * FROM `request` WHERE `state` = 0";
        $result = mysqli_query($GLOBALS['Database'], $requete) or die;
        while ($data = mysqli_fetch_assoc($result)) {
            $request_check[] = $data;
        }

        // Check diff between timestamp
        if (!empty($request_check)) {
            $timelaps = 600;
            foreach ($request_check as $request) {
                $diff = strtotime(date('Y-m-d H:i:s')) - strtotime($request['requested_at']);
                if ($diff >= $timelaps) {
                    $object = new Request($request['id_request']);
                    $object->setState(1);
                    $object->update();
                    unset($object);
                }
            }
        }
    }

    public function update()
    {
        $requete = "UPDATE `request` SET 
                 `id_user`='" . filter($this->id_user) . "',
                 `hash`='" . filter($this->hash) . "', 
                 `state`='" . filter($this->state) . "',
                 `requested_at`='" . filter($this->requested_at) . "'
                 WHERE `id_request` = '" . filter($this->id_request) . "'";
        mysqli_query($GLOBALS['Database'], $requete) or die;
    }

    public function getId_request(){
        return $this->id_request;
    }

    public function setId_request($id_request){
        $this->id_request = $id_request;
    }

    public function getId_user(){
        return $this->id_user;
    }

    public function setId_user($id_user){
        $this->id_user = $id_user;
    }

    public function getHash(){
        return $this->hash;
    }

    public function setHash($hash){
        $this->hash = $hash;
    }

    public function getState(){
        return $this->state;
    }

    public function setState($state){
        $this->state = $state;
    }

    public function getRequested_at(){
        return $this->requested_at;
    }

    public function setRequested_at($requested_at){
        $this->requested_at = $requested_at;
    }
}