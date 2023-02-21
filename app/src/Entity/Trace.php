<?php

class Trace
{
    private $id_user;
    private $type;
    private $action;
    private $triggered_at;

    public function __construct($id)
    {
        $this->id_user = $id;
        if ($this->id_user != 0) {
            $this->check_data($id);
        }
    }

    public function check_data($id)
    {
        $requete = "SELECT * FROM `trace` WHERE `id_user` = '" . filter($id) . "'";
        $result = mysqli_query($GLOBALS['Database'], $requete) or die;
        if ($data = mysqli_fetch_assoc($result)) {
            $this->id_user = $data['id_user'];
            $this->type = $data['type'];
            $this->action = $data['action'];
            $this->triggered_at = $data['triggered_at'];
        }
    }

    static public function display_traces(){
        $logs = [];
        $requete = "SELECT *, trace.type AS typeLog FROM trace 
                    INNER JOIN user ON trace.id_user = user.id_user 
                    ORDER BY triggered_at DESC";

        $result = mysqli_query($GLOBALS['Database'], $requete) or die;
        while ($data = mysqli_fetch_assoc($result)) {
           $logs[] = $data;
        }
         return $logs;
    }

    public function create()
    {
        $requete = "INSERT INTO `trace` (`id_user`,`type`, `action`)
        VALUES ('" . filter($this->id_user) . "',
        '" . filter($this->type) . "',
        '" . filter($this->action) . "')";
        mysqli_query($GLOBALS['Database'], $requete) or die;

        return $GLOBALS['Database']->insert_id;
    }

    public function delete()
    {
        $requete = "DELETE FROM `trace` WHERE `id_user` ='" . filter($this->id_user) . "'";
        mysqli_query($GLOBALS['Database'], $requete) or die;
    }

    public function setTracesIN($id, $action, $type)
    {
        $this->setId_user($id);
        $this->setAction($action);
        $this->setType($type);
        $this->create();
    }

    public function getId_user()
    {
        return $this->id_user;
    }

    public function setId_user($id_user)
    {
        $this->id_user = $id_user;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function setAction($action)
    {
        $this->action = $action;
    }

    public function getTriggered_at()
    {
        return $this->triggered_at;
    }

    public function setTriggered_at($triggered_at)
    {
        $this->triggered_at = $triggered_at;
    }
}