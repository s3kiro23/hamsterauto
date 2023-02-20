<?php

class Session{

    static public function session_ending_soon(){
        $msg = '';
        $status = 0;
        if (time() >=  ($_SESSION['expire'] - 30 )){
            $msg = 'Etes-vous toujours là ?';
            $status = 1;
        }
        return array('msg' => $msg, 'status' => $status);
    }

    static public function session_extend(){
        $user = new User(Security::decrypt($_SESSION['id'], false));
        $isactive = $user->getIs_active();
        $status = 0;
        $time = '';
        if($isactive == 1){
            $status = 1;
            $setting = Setting::get_settings();
            $session_user = $setting['session_duration_user'];
            $session_internal = $setting['session_duration_internal'];
            $time = $session_user - 30;
            $_SESSION['expire'] = (time() + ($session_user) );
            if (($_SESSION['typeUser'] != 'user')){
                $time = $session_internal - 30;
                $_SESSION['expire'] = (time() + ($session_internal) );
            }
        }
        return array('time' => $time, 'status' => $status);
    }
    
    static public function session_ending(){
        $status = $_SESSION['typeUser'];
        $msg = "Retour à la page d'accueil";
        $traces = new Trace(0);
        $traces->setTracesIN(Security::decrypt($_SESSION['id'], false), 'logout', 'session');
        $_SESSION = array();
        session_destroy();
        unset($_SESSION);
        return array('msg' => $msg, 'status' => $status);
    }
}
