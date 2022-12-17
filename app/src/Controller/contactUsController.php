<?php

require_once '../Controller/shared.php';
spl_autoload_register(function ($classe) {
    require '../Entity/' . $classe . '.php';
});

$db = new Database();
$GLOBALS['db'] = $db->connexion();

switch ($_POST['request']) {

    case 'contact-form':
        $tab_input = json_decode($_POST['tabInput'], true);
        $status = 1;
        $msg = HTML::messageHamster();
        if (checkField()) {
            $status = 0;
            $msg = checkField();
        } else if (!checkCaptcha($tab_input[4], $_POST['captcha'])) {
            $status = 0;
            $msg = "Les captcha ne correspondent pas !";
        }else if ($_POST['rgpd'] = false){
            error_log(1);
            $status = 0;
            $msg = "Veuillez accepter les conditions RGPD !";
        } else {
            //Add Job mail in Queue table
            $mail = new Mailing();
            $mailTemplate = $mail->getContact($tab_input);
            $queued = new Queued(0);
            $queued->setType("mail");
            $queued->setTemplate(json_encode($mailTemplate));
            $queued->create();
        }

        echo json_encode(array("status" => $status, "msg" => $msg));
        break;

    case 'getTimes':

        $times = new Setting(1);
        $opening = date("H:i", $times->getStart_time_am());
        $close = date("H:i", $times->getEnd_time_pm());
        $days = ["Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi"];
        $coordinates = json_decode($times->getCoordinates(), true);
        $htmlContent = "";
        foreach ($days as $day) {
            $htmlContent .= $day . ' ' . $opening . ' – ' . $close . '<br>';
        }
        $contentBIA = '
            <div id="content">
                <div class="d-flex flex-row gap-2" id="siteNotice">
                    <img class="align-middle" src="../public/assets/img/logo_simple.svg" style="width: 2rem" alt="logo-contact">
                    <h4 id="firstHeading" class="align-content-center firstHeading">Afl<span style="color: #4bbf73">a</span>uto <span style="color:lightskyblue;">Bastia</span></h4>
                </div>
                <div id="bodyContent">
                    <p>' . $coordinates['aflo_bia']['addr'] . '</p>
                    <p><b>Horaires : </b><br>
                    ' . $htmlContent . '
                    dimanche Fermé<br>
                    <p>Site : <a target="_blank" href="https://www.aflokkat.com/">https://www.aflokkat.com/</a>
                </div>
            </div>
        ';
        $contentAJA = '
            <div id="content">
                <div class="d-flex flex-row gap-2" id="siteNotice">
                    <img class="align-middle" src="../public/assets/img/logo_simple.svg" style="width: 2rem" alt="logo-contact">
                    <h4 id="firstHeading" class="align-content-center firstHeading">Afl<span style="color: #4bbf73">a</span>uto <span style="color:lightcoral;">Ajaccio</span></h4>
                </div>
                <div id="bodyContent">
                    <p>' . $coordinates['aflo_aja']['addr'] . '</p>
                    <p><b>Horaires : </b><br>
                    ' . $htmlContent . '
                    dimanche Fermé<br>
                    <p>Site : <a target="_blank" href="https://www.aflokkat.com/">https://www.aflokkat.com/</a>
                </div>
            </div>
        ';

        echo json_encode(array("contentBIA" => $contentBIA, "contentAJA" => $contentAJA, "coordinates" => $coordinates));
        break;

    default :

        echo json_encode(1);
        break;

}