<?php
session_start();

if (!$_SESSION['auth']) {
    header('location:index.html');
} else {
    $current_time = time();
    if ($current_time > $_SESSION['expire']) {
        session_unset();
        session_destroy();
        header('location:index.html');
    } else {

        spl_autoload_register(function ($classe) {
            require '../Entity/' . $classe . '.php';
        });

        $db = new Database();
        $GLOBALS['db'] = $db->connexion();

        if (isset($_FILES['file']) && !empty($_FILES['file'])) {

            $msg = "";
            $allow_types = array('pdf', 'doc', 'docx', 'jpg', 'png', 'jpeg');
            $status = 0;
            $car_ID = Security::decrypt($_SESSION['carID'], true);

            if ($_FILES['file']['error'] != 4) {
                
                $user = new User(Security::decrypt($_SESSION['id'], false));
                $target_dir = "../../upload/";
                $file_name = basename($_FILES['file']['name']);
                $target_file = $target_dir . $file_name;
                $file_type = pathinfo($target_file, PATHINFO_EXTENSION);
                $uploaded_file = "";
                $date = date("dmyhi");

                // chiffrement des données
                $file_content = file_get_contents($_FILES['file']['tmp_name']);
                $encrypted_file_content = Security::encrypt($file_content, $user->getHash());
                file_put_contents($_FILES['file']['tmp_name'], $encrypted_file_content);

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
                    $check_car_file = new Upload($car_ID);
                    $user = new User(Security::decrypt($_SESSION['id'], false));
                    Upload::upload_file(Security::encrypt($uploaded_file, $user->getHash()), $car_ID, $check_car_file->check_file());

                    //Add traces in BDD
                    $traces = new Trace(0);
                    $traces->setTracesIN(Security::decrypt($_SESSION['id'], false), 'upload', 'file');
                }

            }
            echo json_encode(array("status" => $status, "msg" => $msg));
        }
    }
}
