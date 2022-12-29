<?php

require_once 'Database.php';

$db = new Database();
$GLOBALS['Database'] = $db->connexion();

require __DIR__.'/../../vendor/autoload.php';

use \Ovh\Api;

class SMS
{
    public function send($data)
    {
        error_log("insideSendFct");
        $endpoint = 'ovh-eu';
        $applicationKey = "56533cf0f5ff7344";
        $applicationSecret = "0aeb493a9696e888bc0a68896e696f5e";
        $consumer_key = "08dcfd7977c8f162b417aab800c0a351";

        $conn = new Api($applicationKey,
            $applicationSecret,
            $endpoint,
            $consumer_key);

        $smsServices = $conn->get('/sms');
        foreach ($smsServices as $smsService) {
            error_log(json_encode($smsService));
        }

        $content = (object)array(
            "charset" => "UTF-8",
            "class" => "phoneDisplay",
            "coding" => "7bit",
            "message" => $data['bodySMS'],
            "noStopClause" => false,
            "priority" => "high",
            "receivers" => ["+33{$data['receiver']}"],
            "sender" => "AFLAUTO",
            "senderForResponse" => true,
            "validityPeriod" => 2880
        );
        $resultPostJob = $conn->post('/sms/' . $smsServices[0] . '/jobs', $content);

        error_log(json_encode($resultPostJob));

        $smsJobs = $conn->get('/sms/' . $smsServices[0] . '/jobs');
        error_log(json_encode($smsJobs));
    }

    public function getCT_Finish($user, $carUser): array
    {
        $bodySMS = "Bonjour {$user->getPrenom_user()}, le contrôle technique de votre véhicule immatriculé {$carUser->getImmat_vehicule()} est terminé ! Vous pouvez dès à présent venir le récupérer. Merci pour votre confiance. Votre centre Hamsterauto.";

        return array("bodySMS" => $bodySMS, "receiver" => $user->getTelephone_user());
    }

    public function getA2F($user): array
    {
        $fetchSMS = User::sms($user->getId_user());
        $bodySMS = "Voici votre code SMS pour la connexion à votre compte Hamsterauto : " . $fetchSMS;

        return array("bodySMS" => $bodySMS, "receiver" => $user->getTelephone_user());
    }

    public function getRDV($user, $carUser, $CT): array
    {
        setlocale(LC_TIME, "fr_FR", "French");
        $bodySMS = "Bonjour {$user->getPrenom_user()}, votre rendez-vous du " . utf8_encode(strftime("%A %d %B %G", $CT->getTime_slot())) . " pour le véhicule immatriculé {$carUser->getImmat_vehicule()} vient d'être confirmé sur notre plateforme. Merci pour votre confiance. Votre centre Hamsterauto.";

        return array("bodySMS" => $bodySMS, "receiver" => $user->getTelephone_user());
    }

    public function setSMSJobRDV ($data) {
        $CT = new ControleTech($data['CT']);
        $user = new User($data['user']);
        $carUser = new Vehicule($data['car']);
        $smsTemplate = $this->getRDV($user, $carUser, $CT);
        $queued = new Queued(0);
        $queued->setType("sms");
        $queued->setTemplate(json_encode($smsTemplate));
        $queued->create();
    }

    public function setSMSJobA2F ($userID) {
        $user = new User(decrypt($userID, false));
        $smsTemplate = $this->getA2F($user);
        $queued = new Queued(0);
        $queued->setType("sms");
        $queued->setTemplate(json_encode($smsTemplate));
        $queued->create();
    }

/*    public function setSMSJobFinish ($data) {
        $smsTemplate = $this->getCT_Finish($data['car'], $data['user']);
        $queued = new Queued(0);
        $queued->setType("sms");
        $queued->setTemplate(json_encode($smsTemplate));
        $queued->create();
    }*/

}