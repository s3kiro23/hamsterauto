<?php

spl_autoload_register(function ($classe) {
    require $classe . ".php";
});

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require Kernel::ROOT_DIR().'\vendor\autoload.php';

class Mailing
{
    public function send($data)
    {
        $mail = new PHPMailer(true);
        try {
            //Server settings
            /*$mail->SMTPDebug = SMTP::DEBUG_SERVER;            //Enable verbose debug output*/
            $mail->isSMTP();                                    //Send using SMTP
            /*            $mail->Host = 'smtp-mail.outlook.com';                     //Set the SMTP server to send through*/
            $mail->Host = 'smtp.gmail.com';                     //Set the SMTP server to send through
            $mail->SMTPAuth = true;                             //Enable SMTP authentication
            $mail->Username = 'shadow.s3kir0@gmail.com';        //SMTP username
            $mail->Password = 'ivjakmgdpgarxgrp';               //SMTP password
            /*$mail->Username = 'aflauto2b@outlook.fr';        //SMTP username
            $mail->Password = '@flaut0!@20';               //SMTP password*/
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; //Enable implicit TLS encryption
            $mail->Port = 587;                                  //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
            $mail->CharSet = 'UTF-8';                           //Format d'encodage à utiliser pour les caractères

            //Recipients
            $mail->setFrom('contact@outlook.fr', 'AFLAUTO');
            /*    $mail->addAddress('joe@example.net', 'Joe User');*/     //Add a recipient
            /*    $mail->addAddress($client->getEmail_user());            //Name is optional*/
            $mail->addAddress($data['mail']);           //Name is optional
            if (isset($data['reply']) && !empty($data['reply'])) {
                $mail->addReplyTo($data['reply'], 'Information');
            }
            $mail->addReplyTo('no-reply@aflauto.fr', 'Information');
            /*    $mail->addCC('cc@example.com');
                $mail->addBCC('bcc@example.com');*/
            if (isset($data['attachment']) && !empty($data['attachment'])) {
                $mail->addAttachment($data['attachment']);
            }

            $mail->isHTML(true);     //Set email format to HTML
            $mail->Subject = $data['subject'];
            $mail->AddEmbeddedImage("../../public/assets/img/logoMail.png", "logo", "logoMail.png");
            $mail->Body = $data['body'];
            $mail->send();
            error_log('Le mail a été envoyé !');
        } catch (Exception $e) {
            error_log("Erreur lors de l'envoi du mail. Logs: {$mail->ErrorInfo}");
        }
    }

    public function getContact($userInfo): array
    {
        $body = $userInfo['3'];
        $subject = "Demande d'information formulaire de contact - " . $userInfo['0'] . " " . $userInfo['1'];
        $mail = 'shadow.s3kir0@gmail.com';
        $reply = $userInfo['2'];

        return array("subject" => $subject, "body" => $body, "mail" => $mail, "reply" => $reply);
    }

    public function getSign_Up($mailUser,$civilite,$nom):array
    {
        $body = "
            <div style='background-color: #EFEFF3; border-radius: 5px; box-shadow: 2px 2px 10px black; padding: 10px'>
                <div style='display: flex; justify-content: center'>
                    <img src='cid:logoMail.png' alt='logo_aflauto'/>
                </div>
                <br>
                    Bonjour {$civilite}. {$nom},
                <br><br>
               Votre inscription sur Alfauto.com a été validée !
                <br><br>
                Vous pouvez dès à présent vous connecter sur votre espace personnel
                <br>
                en utilisant votre adresse mail:  {$mailUser}
                <br><br>
                <a  
                href='http://localhost/controle_tech/templates/' 
                target = '_blank'>Je me connecte à mon espace
                </a>
                <br><br><br>
                L'équipe d'<a style='text-decoration: none; color: black' 
                            href='http://localhost/controle_tech/templates/' 
                            target = '_blank'><b>Afl<span style = 'color: #4bbf73'>A</span>uto</b>
                            </a> vous remercie et espère vous voir lors de vos prochains contrôle technique.
                <br><br>
                <p>☎️ 06.00.00.00.01</p>
                🌐<a href='http://localhost/controle_tech/templates/' target = '_blank'> Aflauto.com</a>
            </div>
            ";
        $subject = "Confirmation de votre inscription";
        $mail = $mailUser;
        return array("subject" => $subject, "body" => $body, "mail" => $mail);
    }


    public function getCT_OK($user, $CT, $carUser): array
    {
        $body = "
        <div style='background-color: #EFEFF3; border-radius: 5px; box-shadow: 2px 2px 10px black; padding: 10px'>
            <div style='display: flex; justify-content: center'>
                <img src='cid:logoMail.png' alt='logo_aflauto'/>
            </div>
            <br>
                Bonjour {$user->getPrenom_user()},
            <br><br>
            Le contrôle technique de votre véhicule immatriculé <b>{$carUser->getImmat_vehicule()}</b> est terminé !
            <br><br>
            Vous pouvez dès à présent venir le récupérer .
            <br><br>
            Une facture est jointe à ce mail, récapitulant l'ensemble des prestations réalisées sur votre véhicule.
            <br><br><br>
            L'équipe d'<a style='text-decoration: none; color: black' 
                        href='http://localhost/controle_tech/templates/' 
                        target = '_blank'><b>Afl<span style = 'color: #4bbf73'>A</span>uto</b>
                        </a> vous remercie et espère vous revoir lors de vos prochains contrôle technique.
                        <br><br>
                        <p>☎️ 06.00.00.00.01</p>
                        🌐<a href='http://localhost/controle_tech/templates/' target = '_blank'> Aflauto.com</a>
        </div>
        ";
        $subject = "Compte rendu d'intervention";
        $mail = $user->getEmail_user();
        $attachment = '../var/generate/minutes/' . decrypt($CT->getMinute(), $user->getHash());

        return array("subject" => $subject, "body" => $body, "mail" => $mail, "attachment" => $attachment);
    }

    public function getCT_KO($user, $CT, $carUser): array
    {
        $body = "
        <div style='background-color: #EFEFF3; border-radius: 5px; box-shadow: 2px 2px 10px black; padding: 10px'>
            <div style='display: flex; justify-content: center'>
                <img src='cid:logoMail.png' alt='logo_aflauto'/>
            </div>
            <br>
            Bonjour {$user->getPrenom_user()}, 
            <br><br>
            Le contrôle technique de votre véhicule immatriculé <b>{$carUser->getImmat_vehicule()}</b> est terminé ! 
            <br><br>
            Vous pouvez dès à présent venir le récupérer. Ce dernier fait l'objet d'une <b style='text - decoration: underline'>contre visite</b>.
            <br><br>
            Un récapitulatif des points à faire contrôler en garage dans un délai de 2 mois dès réception du présent mail, vous est adressé en pièce-jointe.
            <br><br><br>
            L'équipe d'<a style='text - decoration: none; color: black' 
                        href='http://localhost/controle_tech/templates/'
                        target = '_blank' ><b>Afl<span style = 'color: #4bbf73' >A</span>uto</b>
                        </a > reste à votre disposition pour tout complément d'information.
                        <br><br>
                        <p>☎️ 06.00.00.00.01</p>
                        🌐<a href='http://localhost/controle_tech/templates/' target = '_blank'> Aflauto.com</a>
        </div>
        ";
        $subject = "Compte rendu d'intervention";
        $mail = $user->getEmail_user();
        $attachment = '../var/generate/minutes/' . decrypt($CT->getMinute(), $user->getHash());

        return array("subject" => $subject, "body" => $body, "mail" => $mail, "attachment" => $attachment);
    }

    public function getCT_Canceled($user, $CT, $carUser): array
    {
        setlocale(LC_TIME, "fr_FR", "French");
        $rdvDate = date("d/m/Y", $CT->getId_time_slot());
        $rdvTime = date("H:i", $CT->getId_time_slot());
        $body = "
        <div style='background-color: #EFEFF3; border-radius: 5px; box-shadow: 2px 2px 10px black; padding: 10px'>
            <div style='display: flex; justify-content: center'>
                <img src='cid:logoMail.png' alt='logo_aflauto'/>
            </div>
            <br>
            Bonjour {$user->getPrenom_user()},
            <br><br>
            La demande d'annulation de votre rendez-vous du <b>$rdvDate à $rdvTime</b> pour le véhicule immatriculé <b>{$carUser->getImmat_vehicule()}</b> a bien été prise en compte !
            <br><br>
            Nous espérons vous revoir prochainement pour le suivi de vos véhicules.
            <br><br><br>
            L'équipe d'<a style='text - decoration: none; color: black' href='http://localhost/controle_tech/templates/' target='_blank'><b>Afl<span style='color: #4bbf73'>A</span>uto</b></a>.
            <br><br>
            <p>☎️ 06.00.00.00.01</p>
            🌐 <a href='http://localhost/controle_tech/templates/' target='_blank'>Aflauto.com</a>
        </div>
        ";
        $subject = "Information concernant votre intervention";
        $mail = $user->getEmail_user();

        return array("subject" => $subject, "body" => $body, "mail" => $mail);
    }

    public function getToken($user, $token): array
    {
        $body = "
        <div style='background-color: #EFEFF3; border-radius: 5px; box-shadow: 2px 2px 10px black; padding: 10px'>
            <div style='display: flex; justify-content: center'>
                <img src='cid:logoMail.png' alt='logo_aflauto'/>
            </div>
            <br>
            Bonjour {$user->getPrenom_user()},
            <br><br>
             Vous avez oublié votre mot de passe ?<br>
            Nous avons reçu une demande de réinitialisation pour votre compte .
            <br><br>
            Pour pouvoir récupérer ce dernier, veuillez cliquer sur le lien ci - dessous :
            <br><br>
            <button>
                <a style = 'text-decoration: none;' href = 'http://localhost/controle_tech/templates/change-password.html?token=$token'><b> Récupération de mon compte </b ></a >
            </button>
            <br><br><br>
            Ou copier et coller cette URL dans la barre de recherche de votre navigateur :<br>
            <a href='http://localhost/controle_tech/templates/change-password.html?token=$token'> http://localhost/controle_tech/templates/change-password.html?token=$token</a>
            <br><br><br>
            L'équipe d'<a style='text-decoration: none; color: black' href= 'http://localhost/controle_tech/templates/' target='_blank' ><b>Afl<span style='color: #4bbf73'>A</span>uto</b></a>.
            <br><br>
            <p>☎️ 06.00.00.00.01</p>
            🌐<a href='http://localhost/controle_tech/templates/' target = '_blank'> Aflauto.com</a>
        </div>
            ";
        $subject = "Récupération de votre compte utilisateur";
        $mail = $user->getEmail_user();

        return array("subject" => $subject, "body" => $body, "mail" => $mail);
    }
}
