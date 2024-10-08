<?php
session_start();

spl_autoload_register(function ($classe) {
    require '../../Entity/' . $classe . '.php';
});

$db = new Database();
$GLOBALS['Database'] = $db->connexion();

switch ($_POST['request']) {

    case 'captcha' :
        $check = new Control();
        $get_captcha = $check->captcha();
        echo json_encode(array('get_captcha' => $get_captcha));
        break;

    case 'signIn' :
        $data = json_decode($_POST['tabInput'], true);
        $status = 1;
        $msg = array(
            "title" => "Demande de création de compte transmise !",
            "text" => "Vous allez recevoir sous peu un mail d'activation à l'adresse indiquée."
        );
        $user = User::check_user($data['inputEmail']);
        $civilite = empty($data['civilite']) ? $data['civilite'] = "" : $data['civilite'];
        $init_control = new Control();
        $check = $init_control->check_fields($data);

        if ($check['status'] == 0) {
            $msg = $check['msg'];
            $status = $check['status'];
        } else if ($user) {
            $status = 0;
            $msg = "Le login existe déjà!";
        }

        if ($status == 1) {
            $current_pwd_exp = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m"), date("d") + 1, date("Y")));
            $client = User::create(
                $data['civilite'],
                $data['inputPrenom'],
                $data['inputNom'],
                $data['inputEmail'],
                $data['inputTel'],
                $data['inputPassword'],
                "client",
                $current_pwd_exp,
                User::random_hash(),
                0
            );
            $user = new User($client);
            $token = $user->request();

            //Add Job in queue
            $mail = new Mailing();
            $mail->setSignIn_Job($user, $token);

            //Add traces in BDD
            $traces = new Trace(0);
            $traces->setTracesIN($client, 'new', 'account');
        }

        echo json_encode(array("status" => $status, "msg" => $msg));

        break;

    case 'activateAccount':
        $msg = '';
        $token = $_POST['token'];
        $traces = new Trace(0);
        $user_token = User::check_token($token);
        if( $user_token){
            $user = new User($user_token['id_user']);
            $user->setIs_active(1);
            $user->update();
            User::update_request($user_token['id_user']);
            $traces->setTracesIN($user_token['id_user'], 'account', 'activate');
        }
        echo json_encode(array("msg" => $msg));
        break;
}
