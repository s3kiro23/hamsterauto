<?php

class Security
{
    private $session;
    private $type;

    public function __construct($session, $type)
    {
        $this->session = $session;
        $this->type = $type;
    }

    public function getSession()
    {
        return $this->session;
    }

    public function setSession($session)
    {
        $this->session = $session;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    static public function check_security(){
        $status = 'client';
        $user = new User(Security::decrypt($_SESSION['id'], false));
        $user_type = $user->getType();
        
        if($user_type && $user_type === 'technicien'){
            $status = 'technicien';
        }
        if($user_type && $user_type === 'admin'){
            $status = 'admin';
        }

        return $status;
    }

    static public function create_session($user){

        $_SESSION['id'] = Security::encrypt($user['id_user'], false);
        $_SESSION['auth'] = true;
        $_SESSION['start'] = time();
        //valeur a changer pour le temps de session( x * nbre de secondes)
        $_SESSION['expire'] = $_SESSION['start'] + (1*60);
        $user = new User(Security::decrypt($_SESSION['id'], false));
        $type_user = $user->getType();
        $_SESSION['typeUser'] = $type_user;
      
        //Add traces in database
        $traces = new Trace(0);
        $traces->setId_user(Security::decrypt($_SESSION['id'], false));
        $traces->setType('session');
        $traces->setAction('logged');
        $traces->create();

        return $type_user;
    }

    /*   DEBUT Chiffrement des données*/
    static public function encrypt($data, $hash_user): string
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

    static public function decrypt($data, $hash_user)
    {
        if ($hash_user) {
            $key = base64_decode($hash_user);
        } else {
            $key = base64_decode("H2F:Dm94S|b+&3fE6=epazezaAZEzea@!");
        }
        $encryption_key = base64_decode($key);
        if(strlen($data) > 29){
            list($encrypted_data, $initVector) = explode('::', base64_decode($data), 2);
            return openssl_decrypt($encrypted_data, 'aes-256-cbc', $encryption_key, 0, $initVector);
        }
        return false;
    }

    static public function random_hash(): string
    {
        $lenght = 30;
        $list_char = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!%@$?';
        $chaine = '';
        $max = mb_strlen($list_char, '8bit') - 1;
        for ($i = 0; $i < $lenght; ++$i) {
            $chaine .= $list_char[random_int(0, $max)];
        }
        return $chaine;
    }
    /*   FIN Chiffrement des données*/

    static public function check_timeslots($time_slot){
        $check = True;
        $nb_Lifts = Setting::get_settings();
        $slots_reserved = Intervention::slots_reserved($time_slot);
        if ($slots_reserved >= $nb_Lifts['nb_lift']){
            $check = False;
        }
        return $check;
    }
}