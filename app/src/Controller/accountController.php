<?php
session_start();
$currentTime = time();
if (!isset($_SESSION['id'])) {
    $status = 2;
    $msg = 'Nécessite une authentification, retour à la page de connexion';
    session_unset();
    session_destroy();
    echo json_encode(array('msg' => $msg, 'status' => $status));
} elseif ($currentTime > $_SESSION['expire']) {
    $status = 2;
    $msg = 'Session expirée, retour à la page principale';
    session_unset();
    session_destroy();
    echo json_encode(array('msg' => $msg, 'status' => $status));
} else {


    spl_autoload_register(function ($classe) {
        require '../Entity/' . $classe . '.php';
    });
    require_once './shared.php';

    $db = new Database();
    $GLOBALS['db'] = $db->connexion();

    switch ($_POST['request']) {

        case 'profil_content' :
            $user = new User(decrypt($_SESSION['id'], false));
            echo json_encode(array("login" => $user->getEmail_user(),
                "nom" => $user->getNom_user(),
                "prenom" => $user->getPrenom_user(),
                "tel" => $user->getTelephone_user(),
                "adresse" => $user->getAdresse_user(),
                "a2f" => $user->getA2f(),
                "image" => $user->getImg_profile(),
            ));

            break;

        case 'modify' :
            $status = 1;
            $msg = 'Les modifications ont bien été prises en compte!';
            $user = new User(decrypt($_SESSION['id'], false));
            $data = json_decode($_POST['values']);
            $user->setEmail_user($data[0]);
            $user->setNom_user($data[1]);
            $user->setPrenom_user($data[2]);
            $user->setTelephone_user($data[3]);
            $user->setAdresse_user($data[4]);
            $user->update();

            //Add traces in BDD
            $traces = new Traces(0);
            $traces->setId_user(decrypt($_SESSION['id'], false));
            $traces->setType('account');
            $traces->setAction('modify');
            $traces->create();

            echo json_encode(array("status" => $status, "msg" => $msg));
            break;

        case 'disableAccount' :
            $msg = "Erreur";
            $id = decrypt($_SESSION['id'], false);
            if (isset($id) & !empty($id)) {
                $user = new User($id);
                $user->disable($id);

                //Add traces in BDD
                $traces = new Traces(0);
                $traces->setId_user($id);
                $traces->setType('account');
                $traces->setAction('disabled');
                $traces->create();

                $msg = 'Success';
            }
            echo json_encode(array('msg' => $msg));
            break;

        case 'activationA2F':
            $status = 0;
            $msg = "Désactivation 2FA réussie !";
            $id = decrypt($_SESSION['id'], false);
            $user = new User($id);

            //Add traces in BDD
            $traces = new Traces(0);
            $traces->setId_user($id);
            $traces->setType('security');

            if (!$user->getA2f()) {
                $user->setA2f(1);
                $status = 1;
                $msg = "Activation 2FA réussie !";
                $traces->setAction('a2f-enabled');
            } else {
                $user->setA2f(0);
                $traces->setAction('a2f-disabled');
            }
            $traces->create();
            $user->update();

            echo json_encode(array("status" => $status, "msg" => $msg));
            break;
    }
}


