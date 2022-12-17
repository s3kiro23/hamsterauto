<?php

require_once 'Database.php';

$db = new Database();
$GLOBALS['Database'] = $db->connexion();

class Vehicule
{
    private $id_user;
    private $id_vehicule;
    private $id_marque;
    private $nom_marque;
    private $id_modele;
    private $immat_vehicule;
    private $annee_vehicule;
    private $carburant_vehicule;
    private $infos_vehicule;
    private $owned;

    public function __construct($id)
    {
        $this->id_vehicule = $id;
        if ($this->id_vehicule != 0) {
            $this->checkData($id);
        }
    }

    // fonction pour récupérer l'ensemble des données d'un véhicule
    public function checkData($id)
    {
        $requete = "SELECT * FROM `vehicules` 
        INNER JOIN `modeles` ON `modeles`.`id_modele` = `vehicules`.`id_modele`
        INNER JOIN `marques` ON `modeles`.`id_marque` = `marques`.`id_marque`
        INNER JOIN `users` ON `users`.`id_user` = `vehicules`.`id_user`
        WHERE `id_vehicule` = '" . mysqli_real_escape_string($GLOBALS['Database'], $id) . "'";
        $result = mysqli_query($GLOBALS['Database'], $requete) or die;
        if ($data = mysqli_fetch_array($result)) {
            $this->id_vehicule = $data['id_vehicule'];
            $this->id_user = $data['id_user'];
            $this->id_marque = $data['id_marque'];
            $this->nom_marque = $data['nom_marque'];
            $this->id_modele = $data['id_modele'];
            $this->immat_vehicule = $data['immat_vehicule'];
            $this->annee_vehicule = $data['annee_vehicule'];
            $this->carburant_vehicule = $data['carburant_vehicule'];
            $this->infos_vehicule = $data['infos_vehicule'];
            $this->owned = $data['owned'];
        }
    }

    // fonction qui récupère le fichier de la carte grise
    public function getCG($carFile, $user_hash): string
    {
        $CG = "";
        $pathFile = decrypt($carFile, $user_hash);
        $extension = strtolower(pathinfo($pathFile, PATHINFO_EXTENSION));

        if ($extension == "png" || $extension == "jpeg" || $extension == "jpg") {
            $CG = "<img src='../upload/$pathFile' alt='CG' height='600' class='w-80'/>";
        } else if ($extension == "pdf") {
            $CG = "<iframe src='../upload/$pathFile' height='600' class='w-100'></iframe>";
        }
        return $CG;
    }

    // Fonction pour créer un nouveau véhicule
    static public function newVehicule($id_user, $id_modele, $immat_vehicule, $annee_vehicule, $carburant_vehicule, $owned)
    {
        $requete = "INSERT INTO `vehicules` (`id_user`, `id_modele`, `immat_vehicule`, `annee_vehicule`, `carburant_vehicule`, `owned`) 
                    VALUES ('" .filter($id_user) . "','" .filter($id_modele) . "',
                    '" .filter($immat_vehicule) . "','" .filter($annee_vehicule) . "',
                    '" .filter($carburant_vehicule) . "','" .filter($owned) . "')";
                    mysqli_query($GLOBALS['Database'], $requete) or die;
        error_log($requete);
        return $GLOBALS['Database']->insert_id;

    }

    static public function checkImmat($immat)
    {
        $requete = "SELECT count(*) AS nbrCar FROM vehicules WHERE `immat_vehicule` = '" .filter($immat) . "'";
        $result = mysqli_query($GLOBALS['Database'], $requete) or die;
        $data = mysqli_fetch_assoc($result);

        return (int)$data['nbrCar'];
    }

    static public function checkMarques()
    {
        $list_marque = [];
        $result = mysqli_query($GLOBALS['Database'], "SELECT * FROM marques ORDER BY `nom_marque`") or die;

        while ($data = mysqli_fetch_array($result)) {
            $list_marque[] = $data;
        }
        return $list_marque;
    }

    static public function checkModeles($idMarque)
    {
        $list_modele = [];
        $requete = " SELECT * FROM modeles WHERE id_marque='" .filter($idMarque) . "'";
        $result = mysqli_query($GLOBALS['Database'], $requete) or die;
        while ($data = mysqli_fetch_array($result)) {
            $list_modele[] = $data;
        }
        return $list_modele;
    }

    public function delete($id)
    {
        $id_car = $id;
        $requete = "DELETE FROM `vehicules` WHERE `id_vehicule` ='" .filter($id_car) . "'";
        mysqli_query($GLOBALS['Database'], $requete) or die;
    }

    public function update()
    {
        $requete = "UPDATE `vehicules` 
                    SET `id_vehicule`='" .filter($this->id_vehicule) . "', 
                        `id_user`='" .filter($this->id_user) . "',                    
                        `id_modele`='" .filter($this->id_modele) . "',
                        `immat_vehicule`='" .filter($this->immat_vehicule) . "', 
                        `annee_vehicule`='" .filter($this->annee_vehicule) . "',
                        `carburant_vehicule`='" .filter($this->carburant_vehicule) . "',
                        `infos_vehicule`='" .filter($this->infos_vehicule) . "',
                        `owned`='" .filter($this->owned) . "'
                    WHERE `id_vehicule` ='" .filter($this->id_vehicule) . "'";
        mysqli_query($GLOBALS['Database'], $requete) or die;
    }

    public function getId_vehicule()
    {
        return $this->id_vehicule;
    }

    public function setId_vehicule($id_vehicule)
    {
        $this->id_vehicule = $id_vehicule;
    }

    public function getId_user()
    {
        return $this->id_vehicule;
    }

    public function setId_user($id_user)
    {
        $this->id_user = $id_user;
    }

    public function getNom_marque()
    {
        return $this->nom_marque;
    }

    public function setNom_marque($nom_marque)
    {
        $this->nom_marque = $nom_marque;
    }

    public function getId_modele()
    {
        return $this->id_modele;
    }

    public function setId_modele($id_modele)
    {
        $this->id_modele = $id_modele;
    }
    public function getId_marque()
    {
        return $this->id_marque;
    }

    public function setId_marque($id_marque)
    {
        $this->id_marque = $id_marque;
    }

    public function getImmat_vehicule()
    {
        return $this->immat_vehicule;
    }

    public function setImmat_vehicule($immat_vehicule)
    {
        $this->immat_vehicule = $immat_vehicule;
    }

    public function getAnnee_vehicule()
    {
        return $this->annee_vehicule;
    }

    public function setAnnee_vehicule($annee_vehicule)
    {
        $this->annee_vehicule = $annee_vehicule;
    }

    public function getCarburant_vehicule()
    {
        return $this->carburant_vehicule;
    }

    public function setCarburant_vehicule($carburant_vehicule)
    {
        $this->carburant_vehicule = $carburant_vehicule;
    }

    public function getInfos_vehicule()
    {
        return $this->infos_vehicule;
    }

    public function setInfos_vehicule($infos_vehicule)
    {
        $this->infos_vehicule = $infos_vehicule;
    }

    public function getOwned()
    {
        return $this->owned;
    }

    public function setOwned($owned)
    {
        $this->owned = $owned;
    }

}