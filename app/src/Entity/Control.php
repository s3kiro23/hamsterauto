<?php

class Control
{
    /*    DEBUT Gestion session*/
    static public function is_logged(): bool
    {
        if (isset($_SESSION['id'])) {
            return Security::decrypt($_SESSION['id'], false);
        }
        return false;
    }
    /*    FIN Gestion session*/

    /*    DEBUT Contrôle de champs formulaire*/
    public function check_fields($data): array
    {
        $msg = "";
        $status = 1;

        $checkout = array(
            "carID" => "Veuillez sélectionner un véhicule!",
            "civilite" => "Veuillez indiquer votre civilité!",
            "inputNom" => "Veuillez renseigner votre nom!",
            "inputPrenom" => "Veuillez renseigner votre prénom!",
            "inputEmail" => "Veuillez renseigner un email!",
            "inputLogin" => "Veuillez renseigner un identifiant!",
            "inputTel" => "Veuillez renseigner un numéro de téléphone!",
            "inputTexte" => "Veuillez saisir un message!",
            "inputAddr" => "Veuillez saisir une adresse pour votre domicile!",
            "registration" => "Veuillez renseigner l'immatriculation du véhicule!",
            "selectMarque" => "Veuillez sélectionner une marque de véhicule!",
            "selectedModel" => "Veuillez sélectionner un modèle de véhicule!",
            "inputYear" => "Veuillez renseigner l'année de 1ère mise en circulation du véhicule!",
            "fuel" => "Veuillez choisir le type de carburant du véhicule!",
            "old-password" => "Le champ ancien mot de passe est vide!",
            "inputPassword" => "Le champ mot de passe est vide!",
            "inputPassword2" => "Le champ de vérification du mot de passe est vide!",
            "inputCaptcha" => "Le champ captcha est vide!",
            "timeSlot" => "Veuillez sélectionner un créneau disponible!"
        );
        
        foreach ($data as $key => $value) {
            if (empty($value)) {
                $msg = $checkout[$key];
                $status = 0;
                break;
            } else if ($key == "inputEmail" && !$this->check_mail($value) || $key == "inputLogin" && !$this->check_mail($value)) {
                $msg = "Veuillez renseigner un email valide !";
                $status = 0;
                break;
            } else if ($key == "inputTel" && !$this->check_phone($value)) {
                $msg = "Veuillez renseigner un numéro de téléphone valide !";
                $status = 0;
                break;
            } else if ($key == "inputYear" && !$this->check_year($value)) {
                $msg = "Veuillez renseigner une année valide !";
                $status = 0;
                break;
            } else if ($key == "inputCaptcha" && isset($data['captcha']) && !$this->check_captcha($data['inputCaptcha'], $data['captcha'])) {
                $status = 0;
                $msg = "Les captcha ne correspondent pas !";
                break;
            } else if ($key == "inputPassword" && isset($data['inputPassword2']) && !$this->check_password($data['inputPassword'], $data['inputPassword2'])) {
                $status = 0;
                $msg = "Les mots de passe ne correspondent pas !";
                break;
            } else if ($key == "inputPassword" && !$this->check_passwd_strength($data['inputPassword']) || $key == "inputPassword2" && !$this->check_passwd_strength($data['inputPassword2'])) {
                $status = 0;
                $msg = "Veuillez créer un mot de passe plus sécurisé !";
                break;
            } else if ($key == 'rgpd' && $value == false) {
                $status = 0;
                $msg = "Veuillez accepter les conditions RGPD !";
                break;
            }
        }

        return array(
            "msg" => $msg,
            "status" => $status
        );
    }

    public function check_phone($phone): bool
    {
        $pattern = '/^(?:(?:\+|00)33[\s.-]{0,3}(?:\(0\)[\s.-]{0,3})?|0)[1-9](?:(?:[\s.-]?\d{2}){4}|\d{2}(?:[\s.-]?\d{3}){2})$/';
        if (preg_match_all($pattern, $phone)) {
            return true;
        }
        return false;
    }

    public function check_mail($mail): bool
    {
        $pattern = '/^[a-zA-Z!$#.\-\d]+@[a-zA-Z\d.-]+\.[a-zA-Z]{2,4}$/';
        if (preg_match_all($pattern, $mail)) {
            return true;
        }
        return false;
    }

    public function check_cp($cp): bool
    {
        $pattern = '/^\d{5}$/';
        if (preg_match_all($pattern, $cp)) {
            return true;
        }
        return false;
    }

    public function check_year($year): bool
    {
        $pattern = '/^(?:19|20)\d{2}$/';
        if (preg_match_all($pattern, $year)) {
            return true;
        }
        return false;
    }

    public function check_registration($registration): bool
    {
        $patternNew = "^[A-Z]{2} ?- ?\d{3} ?- ?[A-Z]{2}$^";
        $patternOld = "^[0-9]{1,4} ?- ?[A-Z]{1,4} ?- ?[0-9]{1,2}$^";
        if (preg_match_all($patternNew, $registration)) {
            return true;
        } else if (preg_match_all($patternOld, $registration)) {
            return true;
        }
        return false;
    }

    public function check_password($passwd, $passwd2): bool
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

    public function check_passwd_strength($passwd): bool
    {
        $pattern = '/^(?=.*\d)(?=.*[@#\-_$%^&+=§!\?])(?=.*[a-z])(?=.*[A-Z])[0-9A-Za-z@#\-_$%^&+=§!\?]{12,18}$/';
        // $pattern = '/^(?=.*[a-z])[0-9A-Za-z@#\-_$%^&+=§!\?]{1,2}$/';
        if (preg_match_all($pattern, $passwd)) {
            return true;
        }
        return false;
    }

    public function captcha(): int
    {
        return random_int(0, 999);
    }

    public function check_captcha($cap_to_check, $rand_cap): bool
    {
        if ($cap_to_check == $rand_cap) {
            return true;
        }
        return false;
    }
    /*    FIN Contrôle de champs formulaire*/
}