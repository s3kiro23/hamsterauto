<?php

spl_autoload_register(function ($classe) {
    require '../Entity/' . $classe . '.php';
});

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require __DIR__.'/../../vendor/autoload.php';

class Mailing
{
    public function send($data)
    {
        $mail = new PHPMailer(true);
        try {
            //Server settings
            // $mail->SMTPDebug = SMTP::DEBUG_SERVER;

            //Config gmail
            // $mail->isSMTP();                                    //Send using SMTP
            // $mail->Host = 'smtp.gmail.com';                     //Set the SMTP server to send through
            // $mail->SMTPAuth = true;                             //Enable SMTP authentication
            // $mail->Username = 'shadow.s3kir0@gmail.com';        //SMTP username
            // $mail->Password = 'ivjakmgdpgarxgrp';               //SMTP password
            // $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; //Enable implicit TLS encryption
            // $mail->Port = 587;                                  //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
            // $mail->CharSet = 'UTF-8';                           //Format d'encodage à utiliser pour les caractères

            //Config OVH mail
            $mail->isSMTP();                                    //Send using SMTP
            $mail->Host = 'ssl0.ovh.net';                     //Set the SMTP server to send through
            $mail->SMTPAuth = true;                             //Enable SMTP authentication
            $mail->Username = 'contact@hamsterauto.com';        //SMTP username
            $mail->Password = 'pMEKZjEq3pnZMY@t';               //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; //Enable implicit TLS encryption
            $mail->Port = 465;                                  //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
            $mail->CharSet = 'UTF-8';                           //Format d'encodage à utiliser pour les caractères
            $mail->Encoding = 'base64';

            //Recipients
            $mail->setFrom('contact@hamsterauto.com', 'HamsterAuto Services');
            $mail->addAddress($data['mail']);           //Name is optional
            if (isset($data['reply']) && !empty($data['reply'])) {
                $mail->addReplyTo($data['reply'], 'Information');
            }
            if ($data['mail'] != 'contact@hamsterauto.com'){
                $mail->addReplyTo('no-reply@hamsterauto.com', 'Information');
            }
            if (isset($data['attachment']) && !empty($data['attachment'])) {
                $mail->addAttachment("../" . $data['attachment']);
            }

            $mail->isHTML(true);     //Set email format to HTML
            $mail->Subject = $data['subject'];
            $mail->AddEmbeddedImage("../../public/assets/img/logoDark_unscreen.png", "logo", "logoDark_unscreen");
            $mail->Body = $data['body'];
            $mail->send();
        } catch (Exception $e) {
            error_log("Erreur lors de l'envoi du mail. Logs:" . $mail->ErrorInfo);
        }
    }

    public function getContact($user_info): array
    {
        $body = $user_info['inputTexte'];
        $subject = "Demande d'information formulaire de contact - " . $user_info['inputPrenom'] . " " . $user_info['inputNom'];
        $mail = 'contact@hamsterauto.com';
        $reply = $user_info['inputEmail'];

        return array("subject" => $subject, "body" => $body, "mail" => $mail, "reply" => $reply);
    }

    public function getSign_up($user, $token): array
    {
        $body = "
            <div style='background-color: #EFEFF3; border-radius: 5px; box-shadow: 2px 2px 10px black; padding: 10px'>
                <div style='display: flex; justify-content: center'>
                    <img src='cid:logoDark_unscreen' alt='logo_hamsterauto' style='width: 160px'/>
                </div>
                <br>
                <b>Bonjour {$user->getFirstname_user()},</b>
                <br><br>
                Votre inscription sur notre plateforme a été enregistrée!
                <br><br>
                Afin de pouvoir accéder à votre espace personnel, vous devez :
                <br><br>
                <button>
                    <a style = 'text-decoration: none;' href='https://hamsterauto.com/activate-account?token=$token' target = '_blank'><b> Activer votre compte </b></a>
                </button>
                <br><br><br>
                Ou copier et coller cette URL dans la barre de recherche de votre navigateur :<br>
                <a href='https://hamsterauto.com/activate-account?token=$token'> https://hamsterauto.com/activate-account?token=$token</a>
                <br><br><br>
                L'équipe d'<a style='text-decoration: none; color: black' 
                            href='https://hamsterauto.com/' 
                            target = '_blank'><b>Hamster<span style = 'color: #4bbf73'>A</span>uto</b>
                            </a> vous remercie et espère vous voir lors de vos prochains contrôle technique.
                <br><br>
                <p>☎️ 06.00.00.00.01</p>
                🌐<a href='https://hamsterauto.com/' target = '_blank'> HamsterAuto.com</a>
            </div>
            ";
        $subject = "Confirmation de votre inscription";
        $mail = $user->getEmail_user();
        return array("subject" => $subject, "body" => $body, "mail" => $mail);
    }

    public function getNew_Control($user, $car_user): array
    {
        $body = "
        <div style='background-color: #EFEFF3; border-radius: 5px; box-shadow: 2px 2px 10px black; padding: 10px'>
            <div style='display: flex; justify-content: center'>
                <img src='cid:logoDark_unscreen' alt='logo_hamsterauto' style='width: 160px'/>
            </div>
            <br>
                Bonjour {$user->getFirstname_user()},
            <br><br>
            Le contrôle technique de votre véhicule immatriculé <b>{$car_user->getRegistration()}</b> arrive bientôt à échéance!
            <br><br>
            Vous pouvez dès à présent prendre rendez-vous sur notre plateforme <a href='https://hamsterauto.com/' target = '_blank'>hamsterauto.com</a>.
            <br><br><br>
            L'équipe d'<a style='text-decoration: none; color: black' 
                        href='https://hamsterauto.com/' 
                        target = '_blank'><b>Hamster<span style = 'color: #4bbf73'>A</span>uto</b>
                        </a> vous remercie et espère vous revoir lors de vos prochains contrôle technique.
                        <br><br>
                        <p>☎️ 06.00.00.00.01</p>
                        🌐<a href='https://hamsterauto.com/' target = '_blank'> HamsterAuto.com</a>
        </div>
        ";
        $subject = "Rappel contrôle technique";
        $mail = $user->getEmail_user();

        return array("subject" => $subject, "body" => $body, "mail" => $mail);
    }

    public function getCT_OK($user, $CT, $car_user): array
    {
        $body = "
        <div style='background-color: #EFEFF3; border-radius: 5px; box-shadow: 2px 2px 10px black; padding: 10px'>
            <div style='display: flex; justify-content: center'>
                <img src='cid:logoDark_unscreen' alt='logo_hamsterauto' style='width: 160px'/>
            </div>
            <br>
                Bonjour {$user->getFirstname_user()},
            <br><br>
            Le contrôle technique de votre véhicule immatriculé <b>{$car_user->getRegistration()}</b> est terminé!
            <br><br>
            Vous pouvez dès à présent venir le récupérer.
            <br><br>
            Une facture est jointe à ce mail, récapitulant l'ensemble des prestations réalisées sur votre véhicule.
            <br><br><br>
            L'équipe d'<a style='text-decoration: none; color: black' 
                        href='https://hamsterauto.com/' 
                        target = '_blank'><b>Hamster<span style = 'color: #4bbf73'>A</span>uto</b>
                        </a> vous remercie et espère vous revoir lors de vos prochains contrôle technique.
                        <br><br>
                        <p>☎️ 06.00.00.00.01</p>
                        🌐<a href='https://hamsterauto.com/' target = '_blank'> HamsterAuto.com</a>
        </div>
        ";
        $subject = "Compte rendu d'intervention";
        $mail = $user->getEmail_user();
        $attachment = '../var/generate/minutes/' . Security::decrypt($CT->getPv(), $user->getHash());

        return array("subject" => $subject, "body" => $body, "mail" => $mail, "attachment" => $attachment);
    }

    public function getCT_KO($user, $CT, $car_user): array
    {
        $body = "
        <div style='background-color: #EFEFF3; border-radius: 5px; box-shadow: 2px 2px 10px black; padding: 10px'>
            <div style='display: flex; justify-content: center'>
                <img src='cid:logoDark_unscreen' alt='logo_hamsterauto' style='width: 160px'/>
            </div>
            <br>
            Bonjour {$user->getFirstname_user()}, 
            <br><br>
            Le contrôle technique de votre véhicule immatriculé <b>{$car_user->getRegistration()}</b> est terminé! 
            <br><br>
            Vous pouvez dès à présent venir le récupérer. Ce dernier fait l'objet d'une <b style='text - decoration: underline'>contre visite</b>.
            <br><br>
            Un récapitulatif des points à faire contrôler en garage dans un délai de 2 mois dès réception du présent mail, vous est adressé en pièce-jointe.
            <br><br><br>
            L'équipe d'<a style='text - decoration: none; color: black' 
                        href='https://hamsterauto.com/'
                        target = '_blank' ><b>Hamster<span style = 'color: #4bbf73' >A</span>uto</b>
                        </a > reste à votre disposition pour tout complément d'information.
                        <br><br>
                        <p>☎️ 06.00.00.00.01</p>
                        🌐<a href='https://hamsterauto.com/' target = '_blank'> HamsterAuto.com</a>
        </div>
        ";
        $subject = "Compte rendu d'intervention";
        $mail = $user->getEmail_user();
        $attachment = '../var/generate/minutes/' . Security::decrypt($CT->getPv(), $user->getHash());

        return array("subject" => $subject, "body" => $body, "mail" => $mail, "attachment" => $attachment);
    }

    public function getCT_Canceled($user, $CT, $car_user): array
    {
        setlocale(LC_TIME, "fr_FR", "French");
        $rdv_date = date("d/m/Y", $CT->getTime_slot());
        $rdv_time = date("H:i", $CT->getTime_slot());
        $body = "
        <div style='background-color: #EFEFF3; border-radius: 5px; box-shadow: 2px 2px 10px black; padding: 10px'>
            <div style='display: flex; justify-content: center'>
                <img src='cid:logoDark_unscreen' alt='logo_hamsterauto' style='width: 160px'/>
            </div>
            <br>
            Bonjour {$user->getFirstname_user()},
            <br><br>
            La demande d'annulation de votre rendez-vous du <b>$rdv_date à $rdv_time</b> pour le véhicule immatriculé <b>{$car_user->getRegistration()}</b> a bien été prise en compte !
            <br><br>
            Nous espérons vous revoir prochainement pour le suivi de vos véhicules.
            <br><br><br>
            L'équipe d'<a style='text - decoration: none; color: black' href='https://hamsterauto.com/' target='_blank'><b>Hamster<span style='color: #4bbf73'>A</span>uto</b></a>.
            <br><br>
            <p>☎️ 06.00.00.00.01</p>
            🌐 <a href='https://hamsterauto.com/' target='_blank'>HamsterAuto.com</a>
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
                        <img src='cid:logoDark_unscreen' alt='logo_hamsterauto' style='width: 160px'/>
                    </div>
                    <br>
                    Bonjour {$user->getFirstname_user()},
                    <br><br>
                    Vous avez oublié votre mot de passe ?<br>
                    Nous avons reçu une demande de réinitialisation pour votre compte.
                    <br><br>
                    Pour pouvoir récupérer ce dernier, veuillez cliquer sur le lien ci-dessous :
                    <br><br>
                    <button>
                        <a style = 'text-decoration: none;' href = 'https://hamsterauto.com/change-password?token=$token'><b> Récupération de mon compte </b ></a >
                    </button>
                    <br><br><br>
                    Ou copier et coller cette URL dans la barre de recherche de votre navigateur :<br>
                    <a href='https://hamsterauto.com/change-password?token=$token'> https://hamsterauto.com/change-password?token=$token</a>
                    <br><br><br>
                    L'équipe d'<a style='text-decoration: none; color: black' href= 'https://hamsterauto.com/' target='_blank' ><b>Hamster<span style='color: #4bbf73'>A</span>uto</b></a>.
                    <br><br>
                    <p>☎️ 06.00.00.00.01</p>
                    🌐<a href='https://hamsterauto.com/' target = '_blank'> HamsterAuto.com</a>
                </div>
            ";
        $subject = "Récupération de votre compte utilisateur";
        $mail = $user->getEmail_user();

        return array("subject" => $subject, "body" => $body, "mail" => $mail);
    }

    public function setDeleted_CTJob($data)
    {
        $mail_template = $this->getCT_Canceled($data['user'], $data['CT'], $data['car']);
        $queued = new Queued(0);
        $queued->setId_user($data['user']->getId_user());
        $queued->setType("mail");
        $queued->setTemplate(json_encode($mail_template));
        $queued->create();
    }

    public function setNewControl_Job($data)
    {
        $mail_template = $this->getNew_Control($data['user'], $data['car']);
        $queued = new Queued(0);
        $queued->setId_user($data['user']->getId_user());
        $queued->setType("mail");
        $queued->setTemplate(json_encode($mail_template));
        $queued->create();
    }

    public function setToken_Job($user, $token)
    {
        $mail_template = $this->getToken($user, $token);
        $queued = new Queued(0);
        $queued->setId_user($user->getId_user());
        $queued->setType("mail");
        $queued->setTemplate(json_encode($mail_template));
        $queued->create();
    }

    public function setFinished_Job($user, $mail_template)
    {
        $queued = new Queued(0);
        $queued->setId_user($user->getId_user());
        $queued->setType("mail");
        $queued->setTemplate(json_encode($mail_template));
        $queued->create();
    }

    public function setSignIn_Job($user, $token)
    {
        $mailTemplate = $this->getSign_Up($user, $token);
        $queued = new Queued(0);
        $queued->setId_user($user->getId_user());
        $queued->setType("mail");
        $queued->setTemplate(json_encode($mailTemplate));
        $queued->create();
    }

    public function setContact_Job($data)
    {
        $mailTemplate = $this->getContact($data);
        $queued = new Queued(0);
        $queued->setId_user(0);
        $queued->setType("mail");
        $queued->setTemplate(json_encode($mailTemplate));
        $queued->create();
    }
}
