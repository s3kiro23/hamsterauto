<?php

spl_autoload_register(function ($classe) {
    require $classe . ".php";
});
require_once Kernel::ROOT_DIR().'\src\Controller\shared.php';

$db = new Database();
$GLOBALS['Database'] = $db->connexion();

class Technicien
{
    private $id_tech;
    private $nom_tech;
    private $prenom_tech;
    private $type;

    public function __construct($id)
    {
        $this->id_tech = $id;
        if ($this->id_tech != 0) {
            $this->checkData($id);
        }
    }

    public function checkData($id)
    {
        $requete = "SELECT * FROM `techniciens` WHERE `id_tech` = '" .filter($id) . "'";
        $result = mysqli_query($GLOBALS['Database'], $requete) or die;
        if ($data = mysqli_fetch_assoc($result)) {
            $this->id_tech = $data['id_tech'];
            $this->nom_tech = $data['nom_tech'];
            $this->prenom_tech = $data['prenom_tech'];
            $this->type = $data['type'];
        }
    }

    static public function checkTech($mail)
    {
        $userCheck = false;

        $requete = "SELECT * FROM `techniciens` WHERE `email_tech` = '" .filter($mail) . "'";
        $result = mysqli_query($GLOBALS['Database'], $requete) or die;
        if ($data = mysqli_fetch_assoc($result)) {
            $userCheck[] = $data;
        }

        return $userCheck;

    }

    public function getId_tech()
    {
        return $this->id_tech;
    }

    public function setId_tech($id_tech)
    {
        $this->id_tech = $id_tech;
    }

    public function getNom_tech()
    {
        return $this->nom_tech;
    }

    public function setNom_tech($nom_tech)
    {
        $this->nom_tech = $nom_tech;
    }

    public function getPrenom_tech()
    {
        return $this->prenom_tech;
    }

    public function setPrenom_tech($prenom_tech)
    {
        $this->prenom_tech = $prenom_tech;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

}