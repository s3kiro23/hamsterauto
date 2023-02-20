<?php

spl_autoload_register(function ($classe) {
    require $classe . ".php";
});

$db = new Database();
$GLOBALS['Database'] = $db->connexion();

class Upload
{
    private $id_upload;
    private $file_name;
    private $id_vehicle;
    private $submitted_on;

    public function __construct($id)
    {

        $this->id_vehicle = $id;
        if ($this->id_vehicle != 0) {
            $this->check_data($id);
        }

    }

    public function check_data($id)
    {
        $requete = "SELECT * FROM `upload` WHERE `id_vehicle` = '" .filter($id) . "'";
        $result = mysqli_query($GLOBALS['Database'], $requete) or die;
        if ($data = mysqli_fetch_assoc($result)) {
            $this->id_upload = $data['id_upload'];
            $this->file_name = $data['file_name'];
            $this->id_vehicle = $data['id_vehicle'];
            $this->submitted_on = $data['submitted_on'];
        }
    }


    public static function upload_file($file_name, $id_vehicle, $fetch)
    {
        if ($fetch) {
            $requete = "UPDATE `upload` SET `file_name`='" .filter($file_name) . "'
                WHERE `id_vehicle` ='" .filter($id_vehicle) . "'";
        } else {
            $requete = "INSERT INTO `upload` (`file_name`, `id_vehicle`) 
                    VALUES ('" .filter($file_name) . "','" .filter($id_vehicle) . "')";
        }
        mysqli_query($GLOBALS['Database'], $requete) or die;

        return $GLOBALS['Database']->insert_id;
    }

    public function check_file()
    {
        $files_checked = false;

        $requete = "SELECT * FROM upload WHERE `id_vehicle` = '" .filter($this->id_vehicle) . "'";
        $result = mysqli_query($GLOBALS['Database'], $requete) or die;
        if ($data = mysqli_fetch_assoc($result)) {
            $files_checked[] = $data;
        }

        return $files_checked;

    }

	public function getId_upload()
    {
		return $this->id_upload;
	}

	public function setId_upload($id_upload)
    {
		$this->id_upload = $id_upload;
	}

    public function getFile_name()
    {
        return $this->file_name;
    }

    public function setFile_name($file_name)
    {
        $this->file_name = $file_name;
    }

    public function getId_vehicle()
    {
        return $this->id_vehicle;
    }

    public function setId_vehicle($id_vehicle)
    {
        $this->id_vehicle = $id_vehicle;
    }

    public function getSubmitted_on()
    {
        return $this->submitted_on;
    }

    public function setSubmitted_on($submitted_on)
    {
        $this->submitted_on = $submitted_on;
    }

}