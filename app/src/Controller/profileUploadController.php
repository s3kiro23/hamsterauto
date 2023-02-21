<?php
session_start();

if(!$_SESSION['auth']) {
    header('location:/');
}
else {
    $currentTime = time();
    if($currentTime > $_SESSION['expire']) {
        session_unset();
        session_destroy();
        header('location:/');
    }else {

        spl_autoload_register(function ($classe) {
            require '../Entity/' . $classe . '.php';
        });

        $db = new Database();
        $GLOBALS['Database'] = $db->connexion();

        if (isset($_FILES['file']) && !empty($_FILES['file'])) {

            $msg = "";
            $allow_types = array('jpg', 'png', 'jpeg');
            $status = 0;

            if ($_FILES['file']['error'] != 4) {

                $target_dir = "../../upload/profiles/";
                $file_name = basename($_FILES['file']['name']);
                $target_file = $target_dir . $file_name;
                $file_type = pathinfo($target_file, PATHINFO_EXTENSION);
                $uploaded_file = "";
                $date = date("dMy");

                if (in_array($file_type, $allow_types)) {
                    if (move_uploaded_file($_FILES['file']['tmp_name'], $target_dir . $date . "_" . $_FILES['file']['name'])) {
                        $uploaded_file = $date . "_" . $_FILES['file']['name'];
                        $msg = "Upload réalisé avec succès!";
                        $status = 1;
                    } else {
                        $msg = "Erreur lors de l'upload du fichier!";
                    }
                } else {
                    $msg = "Erreur, seulement les extensions " . implode('/', $allow_types) . "sont autorisés pour l'upload!";
                }
                if ($status == 1) {
                    $user = new User(Security::decrypt($_SESSION['id'], false));
                    $user->setImg_profile($uploaded_file);
                    $user->update();

                    //Add traces in BDD
                    $traces = new Trace(0);
                    $traces->setTracesIN(Security::decrypt($_SESSION['id'], false), 'modify', 'account');
                }

            }
            echo json_encode(array("status" => $status, "msg" => $msg));
        }}
}
