<?php 
session_start();

if(!$_SESSION['auth']) {
  header('location:index.html');
}
else {
  $currentTime = time();
  if($currentTime > $_SESSION['expire']) {
    session_unset();
    session_destroy();
    header('location:index.html');
  }else {

require_once 'shared.php';

spl_autoload_register(function ($classe) {
    require '../Entity/' . $classe . '.php';
});

$db = new Database();
$GLOBALS['db'] = $db->connexion();

if (isset($_FILES['file']) && !empty($_FILES['file'])) {

    $msg = "";
    $allowTypes = array('pdf', 'doc', 'docx', 'jpg', 'png', 'jpeg');
    $status = 0;
    $carID = decrypt($_SESSION['carID'], false);

    if ($_FILES['file']['error'] != 4) {


        $target_dir = "../../upload/";
        $fileName = basename($_FILES['file']['name']);
        $target_file = $target_dir . $fileName;
        $fileType = pathinfo($target_file, PATHINFO_EXTENSION);
        $uploadedFile = "";
        $date = date("dMy");

        if (in_array($fileType, $allowTypes)) {
            if (move_uploaded_file($_FILES['file']['tmp_name'], $target_dir . $date . "_" . $_FILES['file']['name'])) {
                $uploadedFile = $date . "_" . $_FILES['file']['name'];
                $msg = "Upload réalisé avec succès!";
                $status = 1;
            } else {
                $msg = "Erreur lors de l'upload du fichier!";
            }
        } else {
            $msg = "Erreur, seulement les extensions " . implode('/', $allowTypes) . "sont autorisés pour l'upload!";
        }
        if ($status == 1) {
            $checkCarFile = new Upload($carID);
            $user = new User(decrypt($_SESSION['id'], false));
            /*error_log($fileE);
            error_log(decrypt($fileE, $user->getHash()));*/
            Upload::uploadFile(encrypt($uploadedFile, $user->getHash()), $carID, $checkCarFile->checkFile());

            //Add traces in BDD
            $traces = new Traces(0);
            $traces->setId_user(decrypt($_SESSION['id'], false));
            $traces->setType('file');
            $traces->setAction('upload');
            $traces->create();
        }

    }
    echo json_encode(array("status" => $status, "msg" => $msg));
}}
}
