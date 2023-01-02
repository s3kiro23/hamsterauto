<?php

require_once 'Database.php';

$db = new Database();
$GLOBALS['Database'] = $db->connexion();

class Model
{
    private $id_modele;
    private $id_marque;
    private $nom_modele;

    public function __construct($id)
    {
        $this->id_modele = $id;
        if ($this->id_modele != 0) {
            $this->checkData($id);
        }
    }

    public function checkData($id)
    {
        $requete = "SELECT * FROM `modeles` WHERE `id_modele` = '" .filter($id) . "'";
        $result = mysqli_query($GLOBALS['Database'], $requete) or die;
        if ($data = mysqli_fetch_array($result)) {
            $this->id_modele = $data['id_modele'];
            $this->id_marque = $data['id_marque'];
            $this->nom_modele = $data['nom_modele'];
        }
    }

    public function getId_modele(){
        return $this->id_modele;
    }

    public function setId_modele($id_modele){
        $this->id_modele = $id_modele;
    }

    public function getId_marque(){
        return $this->id_marque;
    }

    public function setId_marque($id_marque){
        $this->id_marque = $id_marque;
    }

    public function getNom_modele(){
        return $this->nom_modele;
    }

    public function setNom_modele($nom_modele){
        $this->nom_modele = $nom_modele;
    }

}