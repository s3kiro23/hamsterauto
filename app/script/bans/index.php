<?php
class Database{
    private $db;
    public function __construct(){

        $hostname = "localhost";
        if (isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] == 'hamsterauto.local:8001') {
        $hostname = "database_mysql";
        }

        try{
            $this->db = mysqli_connect("localhost", "script_bans", "Db123!@66", "hamsterauto");
        } catch (RuntimeException $e){
            exit(0);
        }
    }
    public function connexion(){
        return $this->db;
    }
}
$db = new Database();
$GLOBALS['Database'] = $db->connexion();

// récupération du timestamp actuel
$currentDate = time();

// requete SQL pour trouver tous les users ayant 3 errors (compte bloqué),
//  renvoye l'error la plus récente pour pouvoir la comparer au timestamp actuel
$requete = 'SELECT `login_attempt`.*
FROM `login_attempt`
WHERE `login_attempt`.`date` = (SELECT MAX(login_attempt2.date)
                 	FROM `login_attempt` `login_attempt2`
                	 WHERE `login_attempt2`.`id_user` = `login_attempt`.`id_user` HAVING COUNT(`login_attempt`.`id_user`)>2)';

$result = mysqli_query($GLOBALS['Database'], $requete) or die;
foreach($result as $count => $user){
    $dateBan = strtotime($user['date']);
    //Ci-dessous ajouter a $dateBan une durée au dela de laquelle on veut débloquer le compte (par exemple 15min: +900)
    $banTimer = $dateBan; 
    if($banTimer <= $currentDate){
        $requete2 = "DELETE FROM `login_attempt` 
                    WHERE `login_attempt`.`id_user` = '".mysqli_real_escape_string($GLOBALS['Database'], $user['id_user'])."'";
        mysqli_query($GLOBALS['Database'], $requete2) or die;
        echo 'Compte utilisateur n°'.$user['id_user'].' débloqué <br>';
    } 
}

//SELECT * FROM `error` WHERE date < "2022-09-27 16:48:00";
