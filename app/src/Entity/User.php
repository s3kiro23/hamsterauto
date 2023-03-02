<?php

class User
{

    private $id_user;
    private $civilite_user;
    private $firstname_user;
    private $lastname_user;
    private $email_user;
    private $phone_user;
    private $adress_user;
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
        $requete = "SELECT * FROM `user` WHERE `id_user` = '" . filter($id) . "'";
        $result = mysqli_query($GLOBALS['Database'], $requete) or die;
        if ($data = mysqli_fetch_assoc($result)) {
            $this->id_user = $data['id_user'];
            $this->civilite_user = $data['civilite_user'];
            $this->lastname_user = $data['lastname_user'];
            $this->firstname_user = $data['firstname_user'];
            $this->phone_user = $data['phone_user'];
            $this->email_user = $data['email_user'];
            $this->adress_user = $data['adress_user'];
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

    static public function create($civilite_user, $firstname_user, $lastname_user, $email_user, $phone_user, $password_user, $type, $pwdExp_user, $hash, $active)
    {
        $requete = "INSERT INTO `user` (`civilite_user`, `firstname_user`, `lastname_user`, `email_user`, `phone_user`, 
                     `password_user`, `type`, `pwdExp_user`, `hash`, `is_active`) 
                    VALUES ('" . filter($civilite_user) . "','" . filter($firstname_user) . "',
                    '" . filter($lastname_user) . "','" . filter($email_user) . "',
                    '" . filter($phone_user) . "','" . filter(password_hash($password_user, PASSWORD_BCRYPT)) . "',
                    '" . filter($type) . "','" . filter($pwdExp_user) . "',
                    '" . filter($hash) . "',
                    '" . filter($active) . "')";
        mysqli_query($GLOBALS['Database'], $requete) or die;

        return $GLOBALS['Database']->insert_id;
    }

    static public function create_user_admin($civilite_user, $firstname_user, $lastname_user, $email_user, $adress, $phone_user, $password_user, $type, $pwdExp_user, $hash)
    {
        $requete = "INSERT INTO user (civilite_user, firstname_user, lastname_user, email_user, adress_user, phone_user, 
                     password_user, type, pwdExp_user, hash) 
                    VALUES ('" . filter($civilite_user) . "','" . filter($firstname_user) . "',
                    '" . filter($lastname_user) . "','" . filter($email_user) . "',
                    '" . filter($adress) . "',
                    '" . filter($phone_user) . "','" . filter(password_hash($password_user, PASSWORD_BCRYPT)) . "',
                    '" . filter($type) . "','" . filter($pwdExp_user) . "',
                    '" . filter($hash) . "')";
        mysqli_query($GLOBALS['Database'], $requete) or die;

        return $GLOBALS['Database']->insert_id;
    }

    static public function count_users()
    {
        $requete = "SELECT COUNT(*) as nbUsers FROM user";
        $result = mysqli_query($GLOBALS['Database'], $requete) or die;
        $data = mysqli_fetch_assoc($result);
        return (int)$data['nbUsers'];
    }

    static public function check_all_users($lastname, $firstname, $adress, $phone, $mail, $type, $active)
    {
        $users = array();
        $requete = "SELECT * FROM user WHERE lastname_user LIKE '%" . filter($lastname) . "%' 
                    AND firstname_user LIKE '%" . filter($firstname) . "%' 
                    AND IFNULL(adress_user, '')LIKE '%" . filter($adress) . "%'
                    AND phone_user LIKE '%" . filter($phone) . "%' 
                    AND email_user LIKE '%" . filter($mail) . "%' 
                    AND type LIKE '%" . filter($type) . "%' 
                    AND is_active LIKE '%" . filter($active) . "%'
                    ORDER BY lastname_user ASC";
        $result = mysqli_query($GLOBALS['Database'], $requete) or die;
        while ($data = mysqli_fetch_assoc($result)) {
            array_push($users, new User(
                $data['id_user'],
                $data['lastname_user'],
                $data['adress_user'],
                $data['firstname_user'],
                $data['phone_user'],
                $data['email_user'],
                $data['type'],
                $data['is_active']
            ));
        }
        return $users;
    }

    static public function random_hash()
    {

        $lenght = 30;
        $list_char = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!%@$#?';
        $chaine = '';
        $max = mb_strlen($list_char, '8bit') - 1;
        for ($i = 0; $i < $lenght; ++$i) {
            $chaine .= $list_char[random_int(0, $max)];
        }
        return $chaine;
    }

    public function update()
    {
        $requete = "UPDATE `user` SET `lastname_user`='" . filter($this->lastname_user) . "', `firstname_user`='" . filter($this->firstname_user) . "',
        `email_user`='" . filter($this->email_user) . "', `phone_user`='" . filter($this->phone_user) . "',
        `adress_user`='" . filter($this->adress_user) . "', `password_user`='" . filter($this->password_user) . "',
        `pwdExp_user`='" . filter($this->pwdExp_user) . "', `a2f`='" . filter($this->a2f) . "', `is_active`='" . filter($this->is_active) . "',
        `img_profile`='" . filter($this->img_profile) . "'
        WHERE `id_user` ='" . filter($this->id_user) . "'";
        mysqli_query($GLOBALS['Database'], $requete) or die;
    }

    public function check_uploaded_files()
    {
        $filesChecked = [];

        $requete = "SELECT * FROM `upload` WHERE `id_user` = '" . filter($this->id_user) . "'";
        $result = mysqli_query($GLOBALS['Database'], $requete) or die;
        if ($data = mysqli_fetch_assoc($result)) {
            $filesChecked[] = $data;
        }
        return $filesChecked;
    }

    public function count_cars($id)
    {
        $car_check = false;
        $requete = "SELECT * FROM vehicle
        WHERE id_user = '" . filter($id) . "'";
        $result = mysqli_query($GLOBALS['Database'], $requete) or die;
        if ($data = mysqli_fetch_assoc($result)) {
            $car_check = true;
        }
        return $car_check;
    }

    public function enable($id, $user_cars)
    {
        if ($user_cars) {
            $requete = "UPDATE user 
                        INNER JOIN vehicle ON vehicle.id_user = user.id_user
                        SET user.is_active='" . filter(1) . "',
                            vehicle.owned ='" . filter(1) . "'
                        WHERE user.id_user ='" . filter($id) . "' ";
        } else {
            $requete = "UPDATE user
                        SET user.is_active='" . filter(1) . "'
                        WHERE user.id_user ='" . filter($id) . "' ";
        }
        mysqli_query($GLOBALS['Database'], $requete) or die;
    }

    public function disable($car_check, $rdv_check)
    {
        if ($car_check && $rdv_check) {
            $requete = "UPDATE user 
                    INNER JOIN vehicle ON vehicle.id_user = user.id_user
                    INNER JOIN awaiting_intervention ON awaiting_intervention.id_user = user.id_user
                    SET user.is_active='" . filter(0) . "',
                        vehicle.owned ='" . filter(0) . "',
                        awaiting_intervention.state='" . filter(4) . "'
                    WHERE user.id_user ='" . filter($this->id_user) . "'";
        } else if (!$car_check && $rdv_check) {
            $requete = "UPDATE user 
                    INNER JOIN awaiting_intervention ON awaiting_intervention.id_user = user.id_user
                    SET user.is_active='" . filter(0) . "',
                        awaiting_intervention.state='" . filter(4) . "'
                    WHERE user.id_user ='" . filter($this->id_user) . "'";
        } else if ($car_check && !$rdv_check) {
            $requete = "UPDATE user 
                    INNER JOIN vehicle ON vehicle.id_user = user.id_user
                    SET user.is_active='" . filter(0) . "',
                        vehicle.owned ='" . filter(0) . "'
                    WHERE user.id_user ='" . filter($this->id_user) . "'";
        } else if (!$car_check && !$rdv_check) {
            $requete = "UPDATE user 
                    SET user.is_active='" . filter(0) . "'
                    WHERE user.id_user ='" . filter($this->id_user) . "'";
        }
        mysqli_query($GLOBALS['Database'], $requete) or die;
    }

    static public function check_user($mail)
    {
        $user_check = false;

        $requete = "SELECT * FROM `user` WHERE `email_user` = '" . filter($mail) . "'";
        $result = mysqli_query($GLOBALS['Database'], $requete) or die;
        if ($data = mysqli_fetch_assoc($result)) {
            $user_check = $data;
        }

        return $user_check;
    }

    static public function check_cars($id_user, $id_vehicle)
    {

        $tab_cars = [];

        if ($id_vehicle) {
            $requete = "SELECT * FROM `vehicle`   
                    INNER JOIN `model` ON `vehicle`.`id_model` = `model`.`id_model`
                    INNER JOIN `brand` ON `model`.`id_brand` = `brand`.`id_brand` 
                    WHERE `id_user` = '" . filter($id_user) . "'
                    AND `id_vehicle`= '" . filter($id_vehicle) . "'";
        } else {
            $requete = "SELECT * FROM `vehicle` 
                    INNER JOIN `model` ON `vehicle`.`id_model` = `model`.`id_model`
                    INNER JOIN `brand` ON `model`.`id_brand` = `brand`.`id_brand` 
                    WHERE `id_user` = '" . filter($id_user) . "'
                    AND `owned` = '" . filter(true) . "'";
        }

        $result = mysqli_query($GLOBALS['Database'], $requete) or die;
        while ($data = mysqli_fetch_assoc($result)) {
            $data['brand_name'] = strtolower($data['brand_name']);
            $data['brand_name'] = str_replace(" ", "", $data['brand_name']);
            $tab_cars[] = $data;
        }

        return $tab_cars;
    }

    static public function fetchCars($start, $length, $search, $user_id)
    {
        $tab_cars = [];

        $requete = "SELECT * FROM `vehicle` 
                INNER JOIN `model` ON `vehicle`.`id_model` = `model`.`id_model`
                INNER JOIN `brand` ON `model`.`id_brand` = `brand`.`id_brand` 
                WHERE `id_user` = '" . filter($user_id) . "'
                AND `owned` = '" . filter(true) . "'
                AND (`vehicle`.`registration` LIKE '%" . filter($search) . "%')
                LIMIT $length
                OFFSET $start";
                error_log($requete);

        $result = mysqli_query($GLOBALS['Database'], $requete) or die;
        while ($data = mysqli_fetch_assoc($result)) {
            $tab_cars[] = new Vehicle($data['id_vehicle']);
        }

        return $tab_cars;
    }

    static public function countAllCar($user_id)
    {
        $requete = "SELECT count(*) AS nbCars FROM `vehicle`        
        WHERE `id_user` = '" . filter($user_id) . "'
        AND `owned` = '" . filter(1) . "'";

        error_log($requete);

        $result = mysqli_query($GLOBALS['Database'], $requete) or die;
        $data = mysqli_fetch_assoc($result);

        return (int)$data['nbCars'];
    }

    static public function check_rdv($id_user, $id_vehicle)
    {

        $tab_rdv = [];

        if ($id_vehicle) {
            $requete = "SELECT * FROM `awaiting_intervention` 
                    INNER JOIN `vehicle` ON `awaiting_intervention`.`id_vehicle` = `vehicle`.`id_vehicle`
                    INNER JOIN `model` ON `vehicle`.`id_model` = `model`.`id_model`
                    INNER JOIN `brand` ON `model`.`id_brand` = `brand`.`id_brand`
                    WHERE `awaiting_intervention`.`id_user` = '" . filter($id_user) . "' 
                    AND `awaiting_intervention`.`id_vehicle` = '" . filter($id_vehicle) . "'
                    AND `state` < '" . filter(2) . "'";
        } else {
            $requete = "SELECT * FROM `awaiting_intervention` 
                    INNER JOIN `vehicle` ON `awaiting_intervention`.`id_vehicle` = `vehicle`.`id_vehicle`
                    INNER JOIN `model` ON `vehicle`.`id_model` = `model`.`id_model`
                    INNER JOIN `brand` ON `model`.`id_brand` = `brand`.`id_brand`
                    WHERE `awaiting_intervention`.`id_user` = '" . filter($id_user) . "'
                    AND `state` < '" . filter(2) . "'";
        }
        $result = mysqli_query($GLOBALS['Database'], $requete) or die;
        while ($data = mysqli_fetch_assoc($result)) {
            $tab_rdv[] = $data;
        }

        return $tab_rdv;
    }

    static public function fetchAllRdv($start, $length, $search, $user_id)
    {
        $tab_rdv = [];

        $requete = "SELECT * FROM `awaiting_intervention` 
        INNER JOIN `vehicle` ON `awaiting_intervention`.`id_vehicle` = `vehicle`.`id_vehicle`
        INNER JOIN `model` ON `vehicle`.`id_model` = `model`.`id_model`
        INNER JOIN `brand` ON `model`.`id_brand` = `brand`.`id_brand`
        WHERE `awaiting_intervention`.`id_user` = $user_id
        AND `state` < 2
        AND (`vehicle`.`registration` LIKE '%" . filter($search) . "%')
        ORDER BY `time_slot` ASC
        LIMIT $length
        OFFSET $start";

        $result = mysqli_query($GLOBALS['Database'], $requete) or die;
        while ($data = mysqli_fetch_assoc($result)) {
            $tab_rdv[] = new Intervention($data['id_intervention']);
        }

        return $tab_rdv;
    }

    static public function countAllRdv($id_user)
    {
        $requete = "SELECT count(*) AS nbRdv FROM `awaiting_intervention`        
                    WHERE `id_user` = '" . filter($id_user) . "'
                    AND `state` <= '" . filter(1) . "'";
        $result = mysqli_query($GLOBALS['Database'], $requete) or die;
        $data = mysqli_fetch_assoc($result);
        return (int)$data['nbRdv'];
    }

    static public function check_history($id_user, $off7)
    {

        $tab_history = [];

        $requete = "SELECT * FROM `archive` 
                    INNER JOIN `vehicle` ON `archive`.`id_vehicle` = `vehicle`.`id_vehicle`
                    INNER JOIN `model` ON `vehicle`.`id_model` = `model`.`id_model`
                    INNER JOIN `brand` ON `model`.`id_brand` = `brand`.`id_brand`
                    WHERE `archive`.`id_user` = '" . filter($id_user) . "'
                    AND `state` >= '" . filter(2) . "'
                    ORDER BY `time_slot` DESC LIMIT 5 OFFSET $off7 ";
        $result = mysqli_query($GLOBALS['Database'], $requete) or die;

        while ($data = mysqli_fetch_assoc($result)) {
            $tab_history[] = $data;
        }
        return $tab_history;
    }

    static public function count_history($id_user)
    {
        $requete = "SELECT count(*) AS nbHistory FROM `archive`        
                    WHERE `id_user` = '" . filter($id_user) . "'
                    AND `state` >= '" . filter(2) . "'";
        $result = mysqli_query($GLOBALS['Database'], $requete) or die;
        $data = mysqli_fetch_assoc($result);
        return (int)$data['nbHistory'];
    }


    static public function sms($id_user)
    {

        $code = random_int(1000, 10000);

        $requete = "INSERT INTO `sms` (`id_user`, `code`) 
                    VALUES ('" . filter($id_user) . "',
                    '" . filter($code) . "')";
        mysqli_query($GLOBALS['Database'], $requete) or die;

        return $code;
    }

    static public function check_sms_code($id_user, $input)
    {
        $sms_check = false;

        $requete = "SELECT * FROM `sms` WHERE `id_user` = '" . filter($id_user) . "' 
                    AND `code` = '" . filter($input) . "' 
                    AND `state` = 0";

        $result = mysqli_query($GLOBALS['Database'], $requete) or die;
        if ($data = mysqli_fetch_assoc($result)) {
            $sms_check = $data;
        }
        return $sms_check;
    }

    static public function count_sms($id_user)
    {
        $requete = "SELECT count(*) AS nbSMS FROM `sms`        
                    WHERE `id_user` = '" . filter($id_user) . "'
                    AND `state` = '" . filter(0) . "'";
        $result = mysqli_query($GLOBALS['Database'], $requete) or die;
        $data = mysqli_fetch_assoc($result);
        return (int)$data['nbSMS'];
    }

    static public function update_sms($id_user)
    {

        $requete = "UPDATE `sms` SET `state`='" . filter(1) . "' WHERE `id_user` ='" . filter($id_user) . "'";
        mysqli_query($GLOBALS['Database'], $requete) or die;
    }

    public function request()
    {

        $hash = Security::random_hash();

        $requete = "INSERT INTO request (`id_user`, `hash`)
                VALUES ('" . filter($this->id_user) . "', '" . filter($hash) . "')";
        mysqli_query($GLOBALS['Database'], $requete) or die;

        return $hash;
    }

    public function check_request()
    {
        $request_check = [];
        $requete = "SELECT * FROM `request` WHERE `id_user`= '" . filter($this->id_user) . "' AND state= '" . filter(0) . "'";
        $result = mysqli_query($GLOBALS['Database'], $requete) or die;
        while ($data = mysqli_fetch_assoc($result)) {
            $request_check[] = $data;
        }

        return $request_check;
    }

    static public function check_token($hash)
    {
        $request_check = false;

        $requete = "SELECT * FROM `request` WHERE hash= '" . filter($hash) . "' AND state= '" . filter(0) . "'";
        $result = mysqli_query($GLOBALS['Database'], $requete) or die;
        if ($data = mysqli_fetch_assoc($result)) {
            $request_check = $data;
        }

        return $request_check;
    }

    static public function update_request($id_user)
    {
        $requete = "UPDATE `request` SET state = '" . filter(1) . "'
                    WHERE id_user = '" . filter($id_user) . "'";
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

    public function getFirstname_user()
    {
        return $this->firstname_user;
    }

    public function setFirstname_user($firstname_user)
    {
        $this->firstname_user = $firstname_user;
    }

    public function getLastname_user()
    {
        return $this->lastname_user;
    }

    public function setLastname_user($lastname_user)
    {
        $this->lastname_user = $lastname_user;
    }

    public function getEmail_user()
    {
        return $this->email_user;
    }

    public function setEmail_user($email_user)
    {
        $this->email_user = $email_user;
    }

    public function getPhone_user()
    {
        return $this->phone_user;
    }

    public function setPhone_user($phone_user)
    {
        $this->phone_user = $phone_user;
    }

    public function getAdress_user()
    {
        return $this->adress_user;
    }

    public function setAdress_user($adress_user)
    {
        $this->adress_user = $adress_user;
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
