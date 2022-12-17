<?php

function checkTel($tel)
{
    $pattern = '/^(?:(?:\+|00)33[\s.-]{0,3}(?:\(0\)[\s.-]{0,3})?|0)[1-9](?:(?:[\s.-]?\d{2}){4}|\d{2}(?:[\s.-]?\d{3}){2})$/';
    if (preg_match_all($pattern, $tel)) {
        return true;
    }
    return false;
}

function checkMail($mail)
{
    $pattern = '/^[a-zA-Z!$#.\-\d]+@[a-zA-Z\d.-]+\.[a-zA-Z]{2,4}$/';
    if (preg_match_all($pattern, $mail)) {
        return true;
    }
    return false;

}

function checkCP($cp)
{
    $pattern = '/^\d{5}$/';
    if (preg_match_all($pattern, $cp)) {
        return true;
    }
    return false;

}

function checkYear($year)
{
    $pattern = '/^(?:19|20)\d{2}$/';
    if (preg_match_all($pattern, $year)) {
        return true;
    }
    return false;

}

function is_logged(): bool
{
    if (isset($_SESSION['id'])) {
        return decrypt($_SESSION['id'], false);
    }
    return false;

}

/*function write_logs($login, $state)
{
    $dateJour = date("d-m-Y H:i:s");

    if ($state == 14) {
        file_put_contents("../../var/log/rdv-events.txt", "\n " . $dateJour . " l'employé avec l'ID " . $login . " vient de faire une nouvelle demande d'intervention !", FILE_APPEND);
    }

}*/

function dateJour(): string
{

    $date = new dateTime();

    return $date->format("d-m-Y H:i:s");

}

function checkField()
{
    if (isset ($_POST['carID']) && empty($_POST['carID'])) {

        return 'Veuillez sélectionner un véhicule';

    } else if (isset($_POST['civilite']) && empty($_POST['civilite'])) {

        return 'Veuillez indiquer votre civilité !';

    } else if (isset($_POST['nom']) && empty($_POST['nom'])) {

        return 'Le champ nom est vide !';

    } else if (isset($_POST['prenom']) && empty($_POST['prenom'])) {

        return 'Le champ prénom est vide !';

    } else if (isset($_POST['email']) && empty($_POST['email'])) {

        return 'Le champ email est vide !';

    } else if (isset($_POST['tel']) && empty($_POST['tel'])) {

        return 'Le champ téléphone est vide !';

    } else if (isset($_POST['immat']) && empty($_POST['immat'])) {

        return 'Veuillez renseigner l\'immatriculation du véhicule !';

    } else if (isset($_POST['marque']) && empty($_POST['marque'])) {

        return 'Veuillez sélectionner une marque de véhicule !';

    } else if (isset($_POST['modele']) && empty($_POST['modele'])) {

        return 'Veuillez sélectionner un modèle de véhicule !';

    } else if (isset($_POST['annee']) && empty($_POST['annee'])) {

        return 'Veuillez renseigner l\'année de 1ère mise en circulation du véhicule !';

    } else if (isset($_POST['carburant']) && empty($_POST['carburant'])) {

        return 'Veuillez choisir le type de carburant du véhicule !';

    } else if (isset($_POST['passwd']) && empty($_POST['passwd'])) {

        return 'Le champ mot de passe est vide !';

    } else if (isset($_POST['passwd2']) && empty($_POST['passwd2'])) {

        return 'Le champ de vérification du mot de passe est vide !';

    } else if (isset($_POST['checkCap']) && empty($_POST['checkCap'])) {

        return 'Le champ captcha est vide !';

    } else if (isset($_POST['creneau']) && empty($_POST['creneau'])) {

        return 'Veuillez sélectionner un créneau disponible !';

    }
    return false;

}

function checkImmat($immat): bool
{
    $patternNew = "^[A-Z]{2} ?- ?\d{3} ?- ?[A-Z]{2}$^";
    $patternOld = "^[0-9]{1,4} ?- ?[A-Z]{1,4} ?- ?[0-9]{1,2}$^";
    if (preg_match_all($patternNew, $immat)) {
        return true;
    } else if (preg_match_all($patternOld, $immat)) {
        return true;
    }
    return false;
}

function checkPassword($passwd, $passwd2): bool
{

    if (isset($passwd) && isset($passwd2)) {

        if (!empty($passwd) && !empty($passwd2)) {

            if ($passwd == $passwd2) {

                return true;

            }

        }

    }
    return false;

}

function checkPasswdLenght($passwd)
{

    $pattern = '/^(?=.*\d)(?=.*[@#\-_$%^&+=§!\?])(?=.*[a-z])(?=.*[A-Z])[0-9A-Za-z@#\-_$%^&+=§!\?]{8,15}$/';

    if (preg_match_all($pattern, $passwd)) {

        return true;

    }
    return false;

}

function captcha(): int
{
    return random_int(0, 999);
}

function checkCaptcha($capToCheck, $RandCap): bool
{

    if ($capToCheck == $RandCap) {

        return true;

    }
    return false;

}

function encrypt($data, $hash_user): string
{
    $initVector = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
    if ($hash_user) {
        $key = base64_decode($hash_user);
    } else {
        $key = base64_decode("H2F:Dm94S|b+&3fE6=epazezaAZEzea@!");
    }
    $encryption_key = base64_decode($key);
    $encrypted = openssl_encrypt($data, 'aes-256-cbc', $encryption_key, 0, $initVector);
    return base64_encode($encrypted . '::' . $initVector);
}

function decrypt($data, $hash_user)
{
    if ($hash_user) {
        $key = base64_decode($hash_user);
    } else {
        $key = base64_decode("H2F:Dm94S|b+&3fE6=epazezaAZEzea@!");
    }
    $encryption_key = base64_decode($key);
    list($encrypted_data, $initVector) = explode('::', base64_decode($data), 2);
    return openssl_decrypt($encrypted_data, 'aes-256-cbc', $encryption_key, 0, $initVector);
}

function random_hash(): string
{
    $longueur = 30;
    $listeCar = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!%@$?';
    $chaine = '';
    $max = mb_strlen($listeCar, '8bit') - 1;
    for ($i = 0; $i < $longueur; ++$i) {
        $chaine .= $listeCar[random_int(0, $max)];
    }
    return $chaine;
}


