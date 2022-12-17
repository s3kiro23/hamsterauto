<?php

require_once 'Database.php';

$db = new Database();
$GLOBALS['Database'] = $db->connexion();

class Brand
{
    private $id_marque;
    private $nom_marque;

    public function __construct($id)
    {
        $this->id_marque = $id;
        if ($this->id_marque != 0) {
            $this->checkData($id);
        }
    }

    public function checkData($id)
    {
        $requete = "SELECT * FROM `marques` WHERE `id_marque` = '" .filter($id) . "'";
        $result = mysqli_query($GLOBALS['Database'], $requete) or die;
        if ($data = mysqli_fetch_array($result)) {
            $this->id_marque = $data['id_marque'];
            $this->nom_marque = $data['nom_marque'];
        }
    }

    public function getId_marque(){
        return $this->id_marque;
    }

    public function setId_marque($id_marque){
        $this->id_marque = $id_marque;
    }

    public function getNom_marque(){
        return $this->nom_marque;
    }

    public function setNom_marque($nom_marque){
        $this->nom_marque = $nom_marque;
    }

}