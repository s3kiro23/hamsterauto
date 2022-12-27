<?php

require_once 'Database.php';

require_once ROOT_DIR().'/src/Controller/shared.php';

$db = new Database();
$GLOBALS['Database'] = $db->connexion();

class LoginAttempts
{
    private $id_user;

    public function __construct($id_user)
    {
        $this->id_user = $id_user;
    }

    static public function create($data)
    {
        $requete = "INSERT INTO `login_attempts` (`id_user`, `email_user`, `remote_ip`) 
                    VALUES ('" . mysqli_real_escape_string($GLOBALS['Database'], $data['id_user']) . "',
                    '" . mysqli_real_escape_string($GLOBALS['Database'], $data['mail']) . "',
                    '" . mysqli_real_escape_string($GLOBALS['Database'], $data['remote_ip']) . "')";
        $result = mysqli_query($GLOBALS['Database'], $requete) or die;
        return $GLOBALS['Database']->insert_id;
    }

    static public function checkLog($id_user)
    {
        $result = mysqli_query($GLOBALS['Database'], "SELECT * FROM login_attempts WHERE id_user='" . mysqli_real_escape_string($GLOBALS['Database'], $id_user) . "'") or die;
        return mysqli_num_rows($result);

    }


}