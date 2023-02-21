<?php

class Queued
{
    private $id_queue;
    private $id_user;
    private $type;
    private $template;

    public function __construct($id)
    {
        $this->id_queue = $id;
        if ($this->id_queue != 0) {
            $this->check_data($id);
        }
    }

    public function check_data($id)
    {
        $requete = "SELECT * FROM `queued` WHERE id_queue = '" .filter($id) . "'";
        $result = mysqli_query($GLOBALS['Database'], $requete) or die;
        if ($data = mysqli_fetch_array($result)) {
            $this->id_queue = $data['id_queue'];
            $this->id_user = $data['id_user'];
            $this->type = $data['type'];
            $this->template = $data['template'];
        }
    }

    static public function getJobs_queued()
    {
        $mail_in_queue = [];

        $requete = "SELECT * FROM queued";
        $result = mysqli_query($GLOBALS['Database'], $requete) or die;
        while ($data = mysqli_fetch_assoc($result)) {
            $mail_in_queue[] = $data;
        }
        return $mail_in_queue;
    }

    public function create()
    {
        $requete = "INSERT INTO `queued` (`id_user`, `type`, `template`)
        VALUES ('" .filter($this->id_user) . "','" .filter($this->type) . "','" .filter($this->template) . "')";
        mysqli_query($GLOBALS['Database'], $requete) or die;

        return $GLOBALS['Database']->insert_id;
    }

    public function delete()
    {
        $requete = "DELETE FROM `queued` WHERE `id_queue` ='" .filter($this->id_queue) . "'";
        mysqli_query($GLOBALS['Database'], $requete) or die;
    }

	public function getId_queue(){
		return $this->id_queue;
	}

	public function setId_queue($id_queue){
		$this->id_queue = $id_queue;
	}

    public function getId_user(){
		return $this->id_user;
	}

	public function setId_user($id_user){
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

    public function getTemplate()
    {
        return $this->template;
    }

    public function setTemplate($template)
    {
        $this->template = $template;
    }

}