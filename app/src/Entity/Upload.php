<?php

require_once 'Database.php';
require_once ROOT_DIR().'/src/Controller/shared.php';

$db = new Database();
$GLOBALS['Database'] = $db->connexion();

class Upload
{
    private $id_upload;
    private $file_name;
    private $id_vehicule;
    private $submitted_on;

    public function __construct($id)
    {

        $this->id_vehicule = $id;
        if ($this->id_vehicule != 0) {
            $this->checkData($id);
        }

    }

    public function checkData($id)
    {
        $requete = "SELECT * FROM `uploads` WHERE `id_vehicule` = '" .filter($id) . "'";
        $result = mysqli_query($GLOBALS['Database'], $requete) or die;
        if ($data = mysqli_fetch_assoc($result)) {
            $this->id_upload = $data['id_upload'];
            $this->file_name = $data['file_name'];
            $this->id_vehicule = $data['id_vehicule'];
            $this->submitted_on = $data['submitted_on'];
        }
    }


    public static function uploadFile($file_name, $id_vehicule, $fetch)
    {
        if ($fetch) {
            $requete = "UPDATE `uploads` SET `file_name`='" .filter($file_name) . "'
                WHERE `id_vehicule` ='" .filter($id_vehicule) . "'";
            error_log($requete);
        } else {
            $requete = "INSERT INTO `uploads` (`file_name`, `id_vehicule`) 
                    VALUES ('" .filter($file_name) . "','" .filter($id_vehicule) . "')";
        }
        mysqli_query($GLOBALS['Database'], $requete) or die;

        return $GLOBALS['Database']->insert_id;
    }

    public function checkFile()
    {
        $filesChecked = false;

        $requete = "SELECT * FROM uploads WHERE `id_vehicule` = '" .filter($this->id_vehicule) . "'";
        $result = mysqli_query($GLOBALS['Database'], $requete) or die;
        if ($data = mysqli_fetch_assoc($result)) {
            $filesChecked[] = $data;
        }

        return $filesChecked;

    }

    public function getIdUpload()
    {
        return $this->id_upload;
    }

    public function setIdUpload($id)
    {
        $this->id_upload = $id;
    }

    public function getFile_name()
    {
        return $this->file_name;
    }

    public function setFile_name($file_name)
    {
        $this->file_name = $file_name;
    }

    public function getId_vehicule()
    {
        return $this->id_vehicule;
    }

    public function setId_vehicule($id_vehicule)
    {
        $this->id_vehicule = $id_vehicule;
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