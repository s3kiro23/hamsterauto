<?php

require_once 'Database.php';

$db = new Database();
$GLOBALS['Database'] = $db->connexion();

class Queued
{
    private $id;
    private $type;
    private $template;

    public function __construct($id)
    {
        error_log(1);
        $this->id = $id;
        if ($this->id != 0) {
            $this->checkData($id);
        }
    }

    public function checkData($id)
    {
        $requete = "SELECT * FROM `queued` WHERE id = '" .filter($id) . "'";
        $result = mysqli_query($GLOBALS['Database'], $requete) or die;
        if ($data = mysqli_fetch_array($result)) {
            $this->id = $data['id'];
            $this->type = $data['type'];
            $this->template = $data['template'];
        }
    }

    static public function getJobsQueued()
    {
        $mail_in_queue = [];

        $requete = "SELECT * FROM queued";
        $result = mysqli_query($GLOBALS['Database'], $requete) or die;
        while ($data = mysqli_fetch_assoc($result)) {
            $mail_in_queue[] = $data;
        }
        return $mail_in_queue;
    }

    /*    static public function create($type, $user, $ct, $car)
        {
            error_log(1);
            $requete = "INSERT INTO `queued` (`type`, `template`, `ct_info`, `car_info`)
            VALUES ('" . mysqli_real_escape_string($GLOBALS['Database'], $type) . "','" . mysqli_real_escape_string($GLOBALS['Database'], $user) . "')";
            mysqli_query($GLOBALS['Database'], $requete) or die;
            error_log($requete);

            return $GLOBALS['Database']->insert_id;
        }*/

    public function create()
    {
        $requete = "INSERT INTO `queued` (`type`, `template`)
        VALUES ('" .filter($this->type) . "','" .filter($this->template) . "')";
        mysqli_query($GLOBALS['Database'], $requete) or die;

        return $GLOBALS['Database']->insert_id;
    }

    public function delete()
    {
        $requete = "DELETE FROM `queued` WHERE `id` ='" .filter($this->id) . "'";
        mysqli_query($GLOBALS['Database'], $requete) or die;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getTemplate()
    {
        return $this->template;
    }

    public function setTemplate($template)
    {
        $this->template = $template;
    }

}