<?php

require_once 'Database.php';
require_once 'PDF.php';

$db = new Database();
$GLOBALS['Database'] = $db->connexion();

class Vehicle
{
    private $id_user;
    private $id_vehicle;
    private $id_brand;
    private $brand_name;
    private $id_model;
    private $registration;
    private $first_release;
    private $fuel;
    private $next_control;
    private $notified;
    private $owned;

    public function __construct($id)
    {
        $this->id_vehicle = $id;
        if ($this->id_vehicle != 0) {
            $this->checkData($id);
        }
    }

    // fonction pour récupérer l'ensemble des données d'un véhicule
    public function checkData($id)
    {
        $requete = "SELECT * FROM `vehicle` 
        INNER JOIN `model` ON `model`.`id_model` = `vehicle`.`id_model`
        INNER JOIN `brand` ON `model`.`id_brand` = `brand`.`id_brand`
        INNER JOIN `user` ON `user`.`id_user` = `vehicle`.`id_user`
        WHERE `id_vehicle` = '" . mysqli_real_escape_string($GLOBALS['Database'], $id) . "'";
        $result = mysqli_query($GLOBALS['Database'], $requete) or die;
        if ($data = mysqli_fetch_array($result)) {
            $this->id_vehicle = $data['id_vehicle'];
            $this->id_user = $data['id_user'];
            $this->id_brand = $data['id_brand'];
            $this->brand_name = $data['brand_name'];
            $this->id_model = $data['id_model'];
            $this->registration = $data['registration'];
            $this->first_release = $data['first_release'];
            $this->fuel = $data['fuel'];
            $this->next_control = $data['next_control'];
            $this->notified = $data['notified'];
            $this->owned = $data['owned'];
        }
    }

    static public function count_cars()
    {
        $requete = "SELECT COUNT(*) as nbCars FROM vehicle";
        $result = mysqli_query($GLOBALS['Database'], $requete) or die;
        $data = mysqli_fetch_assoc($result);
        return (int)$data['nbCars'];
    }

    static public function popular_brand()
    {
        $requete = "SELECT brand_name
                     FROM brand 
                     INNER JOIN model ON model.id_brand = brand.id_brand
                     INNER JOIN vehicle ON vehicle.id_model = model.id_model
                     GROUP BY brand_name
                     ORDER BY COUNT(*) DESC
                     LIMIT 1";
        $result = mysqli_query($GLOBALS['Database'], $requete) or die;
        $data = mysqli_fetch_assoc($result);
        return $data;
    }

    // fonction qui récupère le fichier de la carte grise
    public function get_CG($carFile, $user_hash): string
    {
        $CG = "";
        $pathFile = Security::decrypt($carFile, $user_hash);
        $extension = strtolower(pathinfo($pathFile, PATHINFO_EXTENSION));
        // déchiffrement file
        $decrypted_file_content = Security::decrypt(file_get_contents("../../upload/" . $pathFile), $user_hash);
        $encoded_content = base64_encode($decrypted_file_content);

        if ($extension == "png" || $extension == "jpeg" || $extension == "jpg") {
            $CG = "<img src='data:image/png;base64, $encoded_content' alt='CG' height='600' class='w-80'/>";
        } else if ($extension == "pdf") {
            $CG = "<iframe src='data:application/pdf;base64, $encoded_content' height='600' class='w-100'></iframe>";
        }
        return $CG;
    }

    // Fonction pour créer un nouveau véhicule
    static public function new_vehicle($id_user, $id_model, $registration, $first_release, $fuel, $owned)
    {
        $requete = "INSERT INTO `vehicle` (`id_user`, `id_model`, `registration`, `first_release`, `fuel`, `owned`) 
                    VALUES ('" . filter($id_user) . "','" . filter($id_model) . "',
                    '" . filter($registration) . "','" . filter($first_release) . "',
                    '" . filter($fuel) . "','" . filter($owned) . "')";
        mysqli_query($GLOBALS['Database'], $requete) or die;
        return $GLOBALS['Database']->insert_id;
    }

    static public function check_registration($registration)
    {
        $requete = "SELECT count(*) AS nbrCar FROM vehicle WHERE `registration` = '" . filter($registration) . "'";
        $result = mysqli_query($GLOBALS['Database'], $requete) or die;
        $data = mysqli_fetch_assoc($result);

        return (int)$data['nbrCar'];
    }

    static public function check_brands()
    {
        $list_marque = [];
        $result = mysqli_query($GLOBALS['Database'], "SELECT * FROM brand ORDER BY `brand_name`") or die;

        while ($data = mysqli_fetch_array($result)) {
            $list_marque[] = $data;
        }
        return $list_marque;
    }

    static public function check_models($idMarque)
    {
        $list_modele = [];
        $requete = " SELECT * FROM model WHERE id_brand='" . filter($idMarque) . "'";
        $result = mysqli_query($GLOBALS['Database'], $requete) or die;
        while ($data = mysqli_fetch_array($result)) {
            $list_modele[] = $data;
        }
        return $list_modele;
    }

    public function check_bind_rdv($id)
    {
        $requete = "SELECT * FROM awaiting_intervention WHERE `id_vehicle` = '" . filter($id) . "'";
        $result = mysqli_query($GLOBALS['Database'], $requete) or die;
        return mysqli_fetch_assoc($result);
    }

    static public function check_next_control($id)
    {
        $cars_list = [];
        $one_month = 2505600;
        $start_date_range = strtotime(Convert::current_date());
        $end_date_range = strtotime(Convert::current_date()) + $one_month;

        $requete = "SELECT * FROM vehicle WHERE `next_control` IS NOT NULL
         AND `id_user` IN (" . filter($id) . ") 
         AND `owned` = 1
         AND `next_control` BETWEEN '" . filter($start_date_range) . "' AND '" . filter($end_date_range) . "'
         AND `notified` = 0";
        $result = mysqli_query($GLOBALS['Database'], $requete) or die;
        while ($data = mysqli_fetch_assoc($result)) {
            $cars_list[] = $data;
        }
        return $cars_list;
    }

    static public function check_next_rdv($id)
    {
        $rdv_list = [];
        $two_day = 86400 * 2;
        $start_date_range = strtotime(Convert::current_date());
        $end_date_range = strtotime(Convert::current_date()) + $two_day;
        $requete = "SELECT * FROM awaiting_intervention WHERE `id_user` IN (" . filter($id) . ")
         AND `time_slot` BETWEEN '" . filter($start_date_range) . "' AND '" . filter($end_date_range) . "'
         AND `notified` = 0
         AND `state` = 0";
        $result = mysqli_query($GLOBALS['Database'], $requete) or die;
        while ($data = mysqli_fetch_assoc($result)) {
            $rdv_list[] = $data;
        }
        return $rdv_list;
    }

    public function update()
    {
        $requete = "UPDATE `vehicle` 
                    SET `id_vehicle`='" . filter($this->id_vehicle) . "', 
                        `id_user`='" . filter($this->id_user) . "',                    
                        `id_model`='" . filter($this->id_model) . "',
                        `registration`='" . filter($this->registration) . "', 
                        `first_release`='" . filter($this->first_release) . "',
                        `fuel`='" . filter($this->fuel) . "',";
        if (!is_null($this->next_control)) {
            $requete .= "`next_control`= '" . filter($this->next_control) . "',";
        }
        $requete .= "`notified`='" . filter($this->notified) . "',
                        `owned`='" . filter($this->owned) . "'
                    WHERE `id_vehicle` ='" . filter($this->id_vehicle) . "'";
        mysqli_query($GLOBALS['Database'], $requete) or die;
    }

    public function getId_vehicle()
    {
        return $this->id_vehicle;
    }

    public function setId_vehicle($id_vehicle)
    {
        $this->id_vehicle = $id_vehicle;
    }

    public function getId_user()
    {
        return $this->id_vehicle;
    }

    public function setId_user($id_user)
    {
        $this->id_user = $id_user;
    }

    public function getBrand_name()
    {
        return $this->brand_name;
    }

    public function setBrand_name($brand_name)
    {
        $this->brand_name = $brand_name;
    }

    public function getId_model()
    {
        return $this->id_model;
    }

    public function setId_model($id_model)
    {
        $this->id_model = $id_model;
    }

    public function getId_brand()
    {
        return $this->id_brand;
    }

    public function setId_brand($id_brand)
    {
        $this->id_brand = $id_brand;
    }

    public function getRegistration()
    {
        return $this->registration;
    }

    public function setRegistration($registration)
    {
        $this->registration = $registration;
    }

    public function getFirst_release()
    {
        return $this->first_release;
    }

    public function setFirst_release($first_release)
    {
        $this->first_release = $first_release;
    }

    public function getFuel()
    {
        return $this->fuel;
    }

    public function setFuel($fuel)
    {
        $this->fuel = $fuel;
    }

    public function getNext_control()
    {
        return $this->next_control;
    }

    public function setNext_control($next_control)
    {
        $this->next_control = $next_control;
    }

    public function getNotified()
    {
        return $this->notified;
    }

    public function setNotified($notified)
    {
        $this->notified = $notified;
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
