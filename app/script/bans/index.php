<?php
require_once '../../src/Entity/Database.php';

$db = new Database('ban');
$GLOBALS['Database'] = $db->connexion();

// récupération du timestamp actuel
$currentDate = time();

// requete SQL pour trouver tous les users ayant 3 errors (compte bloqué),
//  renvoye l'error la plus récente pour pouvoir la comparer au timestamp actuel
$requete = 'SELECT `id_user`, MAX(`generated_at`) AS `last_date`
    FROM `login_attempt`
    WHERE `id_user` IN (SELECT `id_user`
        FROM `login_attempt`
        GROUP BY `id_user`
        HAVING COUNT(*) >= 3)
    GROUP BY `id_user`';
$result = mysqli_query($GLOBALS['Database'], $requete) or die;
foreach ($result as $count => $user) {
    $dateBan = strtotime($user['last_date']);
    //Ci-dessous ajouter a $dateBan une durée au dela de laquelle on veut débloquer le compte (par exemple 15min: +900 ou 15 * 60)
    $banTimer = $dateBan + (15 * 60);
    if ($banTimer <= $currentDate) {
        $requete2 = "DELETE FROM `login_attempt` 
            WHERE `login_attempt`.`id_user` = '" . mysqli_real_escape_string($GLOBALS['Database'], $user['id_user']) . "'";
        mysqli_query($GLOBALS['Database'], $requete2) or die;
        error_log('Compte utilisateur n°' . $user['id_user'] . ' débloqué <br>'); 
    }
}

//SELECT * FROM `error` WHERE date < "2022-09-27 16:48:00";
