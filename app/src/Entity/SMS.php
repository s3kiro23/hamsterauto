<?php

$db = new Database();
$GLOBALS['Database'] = $db->connexion();

require ROOT_DIR() . '/vendor/autoload.php';

use \Ovh\Api;

class SMS
{
    private $id_sms;
    private $id_user;
    private $code;
    private $state;
    private $generated_at;

    public function __construct($id)
    {
        $this->id_sms = $id;
        if ($this->id_sms != 0) {
            $this->check_data($id);
        }
    }

    public function check_data($id)
    {
        $requete = "SELECT * FROM `sms` WHERE `id_sms` = '" . filter($id) . "'";
        $result = mysqli_query($GLOBALS['Database'], $requete) or die;
        if ($data = mysqli_fetch_assoc($result)) {
            $this->id_sms = $data['id_sms'];
            $this->id_user = $data['id_user'];
            $this->code = $data['code'];
            $this->state = $data['state'];
            $this->generated_at = $data['generated_at'];
        }
    }

    public function send($data)
    {
        $endpoint = 'ovh-eu';
        $application_key = "56533cf0f5ff7344";
        $application_secret = "0aeb493a9696e888bc0a68896e696f5e";
        $consumer_key = "08dcfd7977c8f162b417aab800c0a351";

        $conn = new Api($application_key,
            $application_secret,
            $endpoint,
            $consumer_key);

        $sms_services = $conn->get('/sms');
//        foreach ($sms_services as $sms_service) {
//        }

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
        $result_postJob = $conn->post('/sms/' . $sms_services[0] . '/jobs', $content);

        error_log(json_encode($result_postJob));

        $sms_jobs = $conn->get('/sms/' . $sms_services[0] . '/jobs');
        error_log(json_encode($sms_jobs));
    }

    public function check_expiration()
    {
        $sms_check = [];
        $requete = "SELECT * FROM `sms` WHERE `state` = 0";
        $result = mysqli_query($GLOBALS['Database'], $requete) or die;
        while ($data = mysqli_fetch_assoc($result)) {
            $sms_check[] = $data;
        }

        // Check diff between timestamp
        if (!empty($sms_check)) {
            $timelaps = 600;
            foreach ($sms_check as $sms) {
                $diff = strtotime(date('Y-m-d H:i:s')) - strtotime($sms['generated_at']);
                if ($diff >= $timelaps) {
                    $object = new SMS($sms['id_sms']);
                    $object->setState(1);
                    $object->update();
                    unset($object);
                }
            }
        }
    }

    public function update()
    {
        $requete = "UPDATE `sms` SET 
                 `id_user`='" . filter($this->id_user) . "',                    
                 `code`='" . filter($this->code) . "',
                 `state`='" . filter($this->state) . "', 
                 `generated_at`='" . filter($this->generated_at) . "'
                 WHERE `id_sms` = '" . filter($this->id_sms) . "'";
        mysqli_query($GLOBALS['Database'], $requete) or die;

    }

    public function getCT_Finish($car_user, $user): array
    {
        $body_SMS = "Bonjour {$user->getFirstname_user()}, le contrôle technique de votre véhicule immatriculé {$car_user->getRegistration()} est terminé ! Vous pouvez dès à présent venir le récupérer.";

        return array("bodySMS" => $body_SMS, "receiver" => $user->getPhone_user());
    }

    public function getA2F($user): array
    {
        $fetch_SMS = User::sms($user->getId_user());
        $body_SMS = $fetch_SMS . " est votre code SMS pour la connexion à votre compte HamsterAuto.";

        return array("bodySMS" => $body_SMS, "receiver" => $user->getPhone_user());
    }

    public function getSupport($user, $car_user): array
    {
        $body_SMS = "Bonjour {$user->getFirstname_user()}, votre véhicule immatriculé {$car_user->getRegistration()} vient d'être pris en charge par l'un de nos technicien. Merci pour votre confiance. Votre centre HamsterAuto.";

        return array("bodySMS" => $body_SMS, "receiver" => $user->getPhone_user());
    }

    public function getRDV($user, $car_user, $CT): array
    {
        setlocale(LC_TIME, "fr_FR", "French");
        $body_SMS = "Bonjour {$user->getFirstname_user()}, votre rendez-vous du " . Convert::date_to_fullFR($CT->getTime_slot()) . " pour le véhicule immatriculé {$car_user->getRegistration()} vient d'être confirmé sur notre plateforme. Merci pour votre confiance. Votre centre HamsterAuto.";

        return array("bodySMS" => $body_SMS, "receiver" => $user->getPhone_user());
    }

    public function getNextRDV($user, $car_user, $CT): array
    {
        setlocale(LC_TIME, "fr_FR", "French");
        $body_SMS = "Bonjour {$user->getFirstname_user()}, votre rendez-vous du " . Convert::date_to_fullFR($CT->getTime_slot()) . " pour le véhicule immatriculé {$car_user->getRegistration()} est pour bientôt. Pour préparer au mieux votre visite périodique, faites un tour sur note plateforme ! Merci pour votre confiance. Votre centre HamsterAuto.";

        return array("bodySMS" => $body_SMS, "receiver" => $user->getPhone_user());
    }

    public function setSMS_JobRDV($data)
    {
        $CT = new Intervention($data['CT']);
        $user = new User($data['user']);
        $car_user = new Vehicle($data['car']);
        $smsTemplate = $this->getRDV($user, $car_user, $CT);
        $queued = new Queued(0);
        $queued->setId_user($user->getId_user());
        $queued->setType("sms");
        $queued->setTemplate(json_encode($smsTemplate));
        $queued->create();
    }

    public function setSMS_JobA2F($user_ID)
    {
        $user = new User(Security::decrypt($user_ID, false));
        $smsTemplate = $this->getA2F($user);
        $queued = new Queued(0);
        $queued->setId_user($user->getId_user());
        $queued->setType("sms");
        $queued->setTemplate(json_encode($smsTemplate));
        $queued->create();
    }

    public function setSMS_JobSupport($user, $intervention)
    {
        $car_user = new Vehicle($intervention->getId_vehicle());
        $smsTemplate = $this->getSupport($user, $car_user);
        $queued = new Queued(0);
        $queued->setId_user($user->getId_user());
        $queued->setType("sms");
        $queued->setTemplate(json_encode($smsTemplate));
        $queued->create();
    }

    public function setSMS_JobNextRdv($data)
    {
        $car_user = new Vehicle($data['car']);
        $smsTemplate = $this->getNextRDV($data['user'], $car_user, $data['rdv']);
        $queued = new Queued(0);
        $queued->setId_user($data['user']->getId_user());
        $queued->setType("sms");
        $queued->setTemplate(json_encode($smsTemplate));
        $queued->create();
    }

    public function setSMS_JobFinish($data)
    {
        $smsTemplate = $this->getCT_Finish($data['car'], $data['user']);
        $queued = new Queued(0);
        $queued->setId_user($data['user']->getId_user());
        $queued->setType("sms");
        $queued->setTemplate(json_encode($smsTemplate));
        $queued->create();
    }

    public function getId_sms()
    {
        return $this->id_sms;
    }

    public function setId_sms($id_sms)
    {
        $this->id_sms = $id_sms;
    }

    public function getId_user()
    {
        return $this->id_user;
    }

    public function setId_user($id_user)
    {
        $this->id_user = $id_user;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setCode($code)
    {
        $this->code = $code;
    }

    public function getState()
    {
        return $this->state;
    }

    public function setState($state)
    {
        $this->state = $state;
    }

    public function getGenerated_at()
    {
        return $this->generated_at;
    }

    public function setGenerated_at($generated_at)
    {
        $this->generated_at = $generated_at;
    }

}