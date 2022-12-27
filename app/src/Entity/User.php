<?php

require_once 'Database.php';
require_once ROOT_DIR() . '/src/Controller/shared.php';


$db = new Database();
$GLOBALS['Database'] = $db->connexion();

class User
{

    private $id_user;
    private $civilite_user;
    private $prenom_user;
    private $nom_user;
    private $email_user;
    private $telephone_user;
    private $adresse_user;
    private $password_user;
    private $type;
    private $pwdExp_user;
    private $a2f;
    private $created_date;
    private $hash;
    private $img_profile;
    private $is_active;

    public function __construct($id)
    {

        $this->id_user = $id;
        if ($this->id_user != 0) {
            $this->checkData($id);
        }

    }

    public function checkData($id)
    {
        $requete = "SELECT * FROM `users` WHERE `id_user` = '" .filter($id) . "'";
        $result = mysqli_query($GLOBALS['Database'], $requete) or die;
        if ($data = mysqli_fetch_assoc($result)) {
            $this->id_user = $data['id_user'];
            $this->civilite_user = $data['civilite_user'];
            $this->nom_user = $data['nom_user'];
            $this->prenom_user = $data['prenom_user'];
            $this->telephone_user = $data['telephone_user'];
            $this->email_user = $data['email_user'];
            $this->adresse_user = $data['adresse_user'];
            $this->password_user = $data['password_user'];
            $this->type = $data['type'];
            $this->pwdExp_user = $data['pwdExp_user'];
            $this->a2f = $data['a2f'];
            $this->created_date = $data['created_date'];
            $this->hash = $data['hash'];
            $this->img_profile = $data['img_profile'];
            $this->is_active = $data['is_active'];
        }
    }

    static public function create($civilite_user, $prenom_user, $nom_user, $email_user, $telephone_user, $password_user, $type, $pwdExp_user, $hash)
    {
        $requete = "INSERT INTO `users` (`civilite_user`, `prenom_user`, `nom_user`, `email_user`, `telephone_user`, 
                     `password_user`, `type`, `pwdExp_user`, `hash`) 
                    VALUES ('" .filter($civilite_user) . "','" .filter($prenom_user) . "',
                    '" .filter($nom_user) . "','" .filter($email_user) . "',
                    '" .filter($telephone_user) . "','" .filter(password_hash($password_user, PASSWORD_BCRYPT)) . "',
                    '" .filter($type) . "','" .filter($pwdExp_user) . "',
                    '" .filter($hash) . "')";
                    error_log($requete);
        $result = mysqli_query($GLOBALS['Database'], $requete) or die;

        return $GLOBALS['Database']->insert_id;

    }

    static public function random_hash()
    {

        $longueur = 30;
        $listeCar = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!%@$#?';
        $chaine = '';
        $max = mb_strlen($listeCar, '8bit') - 1;
        for ($i = 0; $i < $longueur; ++$i) {
            $chaine .= $listeCar[random_int(0, $max)];
        }
        return $chaine;
    }

    public function update()
    {
        $requete = "UPDATE `users` SET `nom_user`='" .filter($this->nom_user) . "', `prenom_user`='" .filter($this->prenom_user) . "',
        `email_user`='" .filter($this->email_user) . "', `telephone_user`='" .filter($this->telephone_user) . "',
        `adresse_user`='" .filter($this->adresse_user) . "', `password_user`='" .filter($this->password_user) . "',
        `pwdExp_user`='" .filter($this->pwdExp_user) . "', `a2f`='" .filter($this->a2f) . "',
        `img_profile`='" .filter($this->img_profile) . "'
        WHERE `id_user` ='" .filter($this->id_user) . "'";
        mysqli_query($GLOBALS['Database'], $requete) or die;
    }

    public function checkUploadedFiles()
    {
        $filesChecked = [];

        $requete = "SELECT * FROM `upload` WHERE `id_user` = '" .filter($this->id_user) . "'";
        $result = mysqli_query($GLOBALS['Database'], $requete) or die;
        if ($data = mysqli_fetch_assoc($result)) {
            $filesChecked[] = $data;
        }
        return $filesChecked;
    }

    public function disable($id)
    {
        $id_user = $id;
        $requete = "UPDATE `users` 
                    INNER JOIN `vehicules` ON `vehicules`.`id_user` = `users`.`id_user`
                    INNER JOIN `controle_tech` ON `controle_tech`.`id_user` = `users`.`id_user`

                    SET `users`.`is_active`='" .filter(0) . "',
                        `vehicules`.`owned` ='" .filter(0) . "',
                        `controle_tech`.`state`='" .filter(4) . "'
                    WHERE `users`.`id_user` ='" .filter($id_user) . "'";
            error_log($requete);
            mysqli_query($GLOBALS['Database'], $requete) or die;

    }

    static public function checkUser($mail)
    {
        $userCheck = false;

        $requete = "SELECT * FROM `users` WHERE `email_user` = '" .filter($mail) . "'";
        $result = mysqli_query($GLOBALS['Database'], $requete) or die;
        if ($data = mysqli_fetch_assoc($result)) {
            $userCheck[] = $data;
        }

        return $userCheck;

    }

    static public function checkCars($id_user, $id_vehicule)
    {

        $tab_cars = [];

        if ($id_vehicule) {
            $requete = "SELECT * FROM `vehicules`   
                    INNER JOIN `modeles` ON `vehicules`.`id_modele` = `modeles`.`id_modele`
                    INNER JOIN `marques` ON `modeles`.`id_marque` = `marques`.`id_marque` 
                    WHERE `id_user` = '" .filter($id_user) . "'
                    AND `id_vehicule`= '" .filter($id_vehicule) . "'";
        } else {

            $requete = "SELECT * FROM `vehicules` 
                    INNER JOIN `modeles` ON `vehicules`.`id_modele` = `modeles`.`id_modele`
                    INNER JOIN `marques` ON `modeles`.`id_marque` = `marques`.`id_marque` 
                    WHERE `id_user` = '" .filter($id_user) . "'
                    AND `owned` = '" .filter(true) . "'";
        }

        $result = mysqli_query($GLOBALS['Database'], $requete) or die;
        while ($data = mysqli_fetch_assoc($result)) {
            $tab_cars[] = $data;
        }

        return $tab_cars;
    }

    static public function checkRdv($id_user, $id_vehicule)
    {

        $tab_rdv = [];

        if ($id_vehicule) {
            $requete = "SELECT * FROM `controle_tech` 
                    INNER JOIN `vehicules` ON `controle_tech`.`id_vehicule` = `vehicules`.`id_vehicule`
                    INNER JOIN `modeles` ON `vehicules`.`id_modele` = `modeles`.`id_modele`
                    INNER JOIN `marques` ON `modeles`.`id_marque` = `marques`.`id_marque`
                    WHERE `controle_tech`.`id_user` = '" .filter($id_user) . "' 
                    AND `controle_tech`.`id_vehicule` = '" .filter($id_vehicule) . "'
                    AND `state` < '" .filter(2) . "'";
        } else {
            $requete = "SELECT * FROM `controle_tech` 
                    INNER JOIN `vehicules` ON `controle_tech`.`id_vehicule` = `vehicules`.`id_vehicule`
                    INNER JOIN `modeles` ON `vehicules`.`id_modele` = `modeles`.`id_modele`
                    INNER JOIN `marques` ON `modeles`.`id_marque` = `marques`.`id_marque`
                    WHERE `controle_tech`.`id_user` = '" .filter($id_user) . "'
                    AND `state` < '" .filter(2) . "'";
        }
        $result = mysqli_query($GLOBALS['Database'], $requete) or die;
        while ($data = mysqli_fetch_assoc($result)) {
            $tab_rdv[] = $data;
        }

        return $tab_rdv;
    }

    static public function checkHistory($id_user, $off7)
    {

        $tab_history = [];

        $requete = "SELECT * FROM `controle_tech` 
                    INNER JOIN `vehicules` ON `controle_tech`.`id_vehicule` = `vehicules`.`id_vehicule`
                    INNER JOIN `modeles` ON `vehicules`.`id_modele` = `modeles`.`id_modele`
                    INNER JOIN `marques` ON `modeles`.`id_marque` = `marques`.`id_marque`
                    WHERE `controle_tech`.`id_user` = '" .filter($id_user) . "'
                    AND `state` >= '" .filter(2) . "'
                    ORDER BY `id_controle` DESC LIMIT 5 OFFSET $off7 ";
        $result = mysqli_query($GLOBALS['Database'], $requete) or die;

        while ($data = mysqli_fetch_assoc($result)) {
            $tab_history[] = $data;
        }
        return $tab_history;
    }

    static public function countHistory($id_user)
    {
        $requete = "SELECT count(*) AS nbHistory FROM `controle_tech`        
                    WHERE `id_user` = '" .filter($id_user) . "'
                    AND `state` >= '" .filter(2) . "'";
        $result = mysqli_query($GLOBALS['Database'], $requete) or die;
        $data = mysqli_fetch_assoc($result);
        return (int)$data['nbHistory'];
    }


    static public function sms($id_user)
    {

        $code = random_int(1000, 10000);

        $requete = "INSERT INTO `sms` (`id_user`, `code`) 
                    VALUES ('" .filter($id_user) . "',
                    '" .filter($code) . "')";
        mysqli_query($GLOBALS['Database'], $requete) or die;

        return $code;

    }

    static public function checkSmsCode($id_user, $input)
    {
        $smsCheck = false;

        $requete = "SELECT * FROM `sms` WHERE `id_user` = '" .filter($id_user) . "' 
                    AND `code` = '" .filter($input) . "' 
                    AND `state` = 0";
        $result = mysqli_query($GLOBALS['Database'], $requete) or die;
        if ($data = mysqli_fetch_assoc($result)) {
            $smsCheck = $data;
        }
        return $smsCheck;
    }

    static public function updateSMS($id_user)
    {

        $requete = "UPDATE `sms` SET `state`='" .filter(1) . "' WHERE `id_user` ='" .filter($id_user) . "'";
        mysqli_query($GLOBALS['Database'], $requete) or die;

    }

    static public function request($id_user)
    {

        $hash = random_hash();

        $requete = "INSERT INTO request (`id_user`, `hash`)
                VALUES ('" .filter($id_user) . "', '" .filter($hash) . "')";
        mysqli_query($GLOBALS['Database'], $requete) or die;

        return $hash;

    }

    static public function checkRequest($hash)
    {
        $requestCheck = false;

        $requete = "SELECT * FROM `request` WHERE hash= '" .filter($hash) . "' AND state= '" .filter(0) . "'";
        $result = mysqli_query($GLOBALS['Database'], $requete) or die;
        if ($data = mysqli_fetch_assoc($result)) {
            $requestCheck = $data;
        }

        return $requestCheck;

    }

    static public function updateRequest($id_user)
    {
        $requete = "UPDATE `request` SET state = '" .filter(1) . "'
                    WHERE id_user = '" .filter($id_user) . "'";
        mysqli_query($GLOBALS['Database'], $requete) or die;

    }

    public function getId_user()
    {
        return $this->id_user;
    }

    public function setId_user($id_user)
    {
        $this->id_user = $id_user;
    }

    public function getCivilite_user()
    {
        return $this->civilite_user;
    }

    public function setCivilite_user($civilite_user)
    {
        $this->civilite_user = $civilite_user;
    }

    public function getPrenom_user()
    {
        return $this->prenom_user;
    }

    public function setPrenom_user($prenom_user)
    {
        $this->prenom_user = $prenom_user;
    }

    public function getNom_user()
    {
        return $this->nom_user;
    }

    public function setNom_user($nom_user)
    {
        $this->nom_user = $nom_user;
    }

    public function getEmail_user()
    {
        return $this->email_user;
    }

    public function setEmail_user($email_user)
    {
        $this->email_user = $email_user;
    }

    public function getTelephone_user()
    {
        return $this->telephone_user;
    }

    public function setTelephone_user($telephone_user)
    {
        $this->telephone_user = $telephone_user;
    }

    public function getAdresse_user()
    {
        return $this->adresse_user;
    }

    public function setAdresse_user($adresse_user)
    {
        $this->adresse_user = $adresse_user;
    }

    public function getPassword_user()
    {
        return $this->password_user;
    }

    public function setPassword_user($password_user)
    {
        $this->password_user = password_hash($password_user, PASSWORD_BCRYPT);

    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getPwdExp_user()
    {
        return $this->pwdExp_user;
    }

    public function setPwdExp_user($pwdExp_user)
    {
        $this->pwdExp_user = $pwdExp_user;
    }

    public function getA2f()
    {
        return $this->a2f;
    }

    public function setA2f($a2f)
    {
        $this->a2f = $a2f;
    }

    public function getCreated_date()
    {
        return $this->created_date;
    }

    public function setCreated_date($created_date)
    {
        $this->created_date = $created_date;
    }

    public function getHash()
    {
        return $this->hash;
    }

    public function setHash($hash)
    {
        $this->hash = $hash;
    }

    public function getImg_profile(): string
    {
        $img_profile = $this->img_profile;

        if ($img_profile == null) {
            $img_profile = 'https://picsum.photos/60/60';
        } else {
            $img_profile = "../upload/profiles/" . $this->img_profile;
        }

        return $img_profile;
    }

    public function setImg_profile($img_profile)
    {
        $this->img_profile = $img_profile;
    }

    public function getIs_active()
    {
        return $this->is_active;
    }

    public function setIs_active($is_active)
    {
        $this->is_active = $is_active;
    }


}