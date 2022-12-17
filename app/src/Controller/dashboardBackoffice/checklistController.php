<?php
session_start();
$currentTime = time();
if (!isset($_SESSION['id']) || $currentTime > $_SESSION['expire']) {
    $status = 2;
    $msg = 'Nécessite une authentification, retour à la page de connexion';
    session_unset();
    session_destroy();
    echo json_encode(array('msg' => $msg, 'status' => $status));
} else {

    require_once '../../Controller/shared.php';
    require_once '../../Controller/authorization.php';

    spl_autoload_register(function ($classe) {
        require '../../Entity/' . $classe . '.php';
    });

    $db = new Database();
    $GLOBALS['Database'] = $db->connexion();

    $whoIs = false;
    if (isset($_SESSION['id']) && !empty($_SESSION['id'])) {
        $whoIs = new User(decrypt($_SESSION['id'], false));
    }
    if (!getAuthorizationUser($whoIs)) {
        $status = 2;
        session_destroy();
        $msg = "Vous n'êtes pas autorisé à accéder à cette page ! <br> Redirection vers la page de login...";
        echo json_encode(array('msg' => $msg, 'status' => $status));
    } else {

        switch ($_POST['request']) {

            case 'validationCT' :

                $idControle = $_POST['id_controle'];
                $msg = 'Intervention en cours de validation...';
                $report = json_decode($_POST['tab_checkbox'], true);

                $CT = new ControleTech($idControle);
                $carUser = new Vehicule($CT->getId_vehicule());
                $user = new User($CT->getId_user());
                $mail = new Mailing();
                $PvPDF = new PDF();

                if (sizeof($report) == 0) {
                    $CT->setState(2);
                    $PDFTemplate = $PvPDF->pv($carUser, $CT, $user);
                    $CT->setPv(encrypt($PvPDF->generatePDF($PDFTemplate), $user->getHash()));
                    $mailTemplate = $mail->getCT_OK($user, $CT, $carUser);
                } else {
                    $CT->setState(3);
                    $CT->setReport(json_encode($report));
                    $PDFTemplate = $PvPDF->pv($carUser, $CT, $user);
                    $CT->setPv(encrypt($PvPDF->generatePDF($PDFTemplate), $user->getHash()));
                    $mailTemplate = $mail->getCT_KO($user, $CT, $carUser);
                }
                $CT->update();

                //Add Job mail in Queue table
                $queued = new Queued(0);
                $queued->setType("mail");
                $queued->setTemplate(json_encode($mailTemplate));
                $queued->create();

                //Add Job Sms in Queue table
                $sms = new SMS();
                $smsTemplate = $sms->getCT_Finish($user, $carUser);
                $queued = new Queued(0);
                $queued->setType("sms");
                $queued->setTemplate(json_encode($smsTemplate));
                $queued->create();

                echo json_encode(array("msg" => $msg));

                break;

            case 'load_check_list':
                $html = '';
                $id_inter = $_POST['intervention'];
                $data = ControleTech::infoChecklist($id_inter);
                $tech = new User($data['num_tech']);

                $html = '
                    <div class="col-12">
                        <li class="fw-bold">Technicien: 
                            <span id="numeroTech" class="fw-normal"> ' . $tech->getPrenom_user() . '</span>
                        </li>
                        <li class="fw-bold">Marque: 
                            <span id="marqueInter" class="fw-normal"> ' . $data['nom_marque'] . '</span>
                        </li>
                        <li class="fw-bold">Modèle: 
                            <span id="modeleInter" class="fw-normal"> ' . $data['nom_modele'] . '</span>
                        </li>
                        <li class="fw-bold">Immat: 
                            <span id="immatInter" class="fw-normal"> ' . $data['immat_vehicule'] . '</span>
                        </li>
                    </div>
                ';

                $html_inter = '<div>Intervention n° <span id="numeroInter">' . $id_inter . '</span></div>';

                echo json_encode(array("html" => $html, "html_inter" => $html_inter));

                break;

        }
    }
}                
