<?php

spl_autoload_register(function ($classe) {
    require $classe . ".php";
});
require_once Kernel::ROOT_DIR().'/src/Controller/shared.php';

$db = new Database();
$GLOBALS['Database'] = $db->connexion();

class Traces
{
    private $id_user;
    private $type;
    private $action;
    private $triggered_at;

    public function __construct($id)
    {
        $this->id_user = $id;
        if ($this->id_user != 0) {
            $this->checkData($id);
        }
    }

    public function checkData($id)
    {
        $requete = "SELECT * FROM `traces` WHERE `id_user` = '" . mysqli_real_escape_string($GLOBALS['Database'], $id) . "'";
        $result = mysqli_query($GLOBALS['Database'], $requete) or die;
        if ($data = mysqli_fetch_assoc($result)) {
            $this->id_user = $data['id_user'];
            $this->type = $data['type'];
            $this->action = $data['action'];
            $this->triggered_at = $data['triggered_at'];
        }
    }

    public function create()
    {
        $requete = "INSERT INTO `traces` (`id_user`,`type`, `action`)
        VALUES ('" . mysqli_real_escape_string($GLOBALS['Database'], $this->id_user) . "',
        '" . mysqli_real_escape_string($GLOBALS['Database'], $this->type) . "',
        '" . mysqli_real_escape_string($GLOBALS['Database'], $this->action) . "')";
        mysqli_query($GLOBALS['Database'], $requete) or die;

        return $GLOBALS['Database']->insert_id;
    }

    public function delete()
    {
        $requete = "DELETE FROM `traces` WHERE `id_user` ='" . mysqli_real_escape_string($GLOBALS['Database'], $this->id_user) . "'";
        mysqli_query($GLOBALS['Database'], $requete) or die;
    }

    public function getId_user(){
        return $this->id_user;
    }

    public function setId_user($id_user){
        $this->id_user = $id_user;
    }

    public function getType(){
        return $this->type;
    }

    public function setType($type){
        $this->type = $type;
    }

    public function getAction(){
        return $this->action;
    }

    public function setAction($action){
        $this->action = $action;
    }

    public function getTriggered_at(){
        return $this->triggered_at;
    }

    public function setTriggered_at($triggered_at){
        $this->triggered_at = $triggered_at;
    }
}