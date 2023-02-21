<?php

spl_autoload_register(function ($classe) {
    require '../Entity/' . $classe . '.php';
});

class Notification
{
    private $id_notif;
    private $next_rdv;
    private $confirmed_rdv;
    private $deleted_rdv;
    private $finished_rdv;
    private $next_control;
    private $car_support;
    private $send_pv;

    public function __construct()
    {
        $requete = "SELECT * FROM `notification`";
        $result = mysqli_query($GLOBALS['Database'], $requete) or die;
        if ($data = mysqli_fetch_assoc($result)) {
            $this->id_notif = $data['id_notif'];
            $this->next_rdv = $data['next_rdv'];
            $this->confirmed_rdv = $data['confirmed_rdv'];
            $this->deleted_rdv = $data['deleted_rdv'];
            $this->finished_rdv = $data['finished_rdv'];
            $this->next_control = $data['next_control'];
            $this->car_support = $data['car_support'];
            $this->send_pv = $data['send_pv'];
        }
    }

    public function update()
    {
        $requete = "UPDATE `notification` 
                    SET `next_control`='" . filter($this->next_control) . "',                    
                        `next_rdv`='" . filter($this->next_rdv) . "',
                        `confirmed_rdv`='" . filter($this->confirmed_rdv) . "',
                        `deleted_rdv`='" . filter($this->deleted_rdv) . "',
                        `finished_rdv`='" . filter($this->finished_rdv) . "',
                        `car_support`='" . filter($this->car_support) . "',
                        `send_pv`='" . filter($this->send_pv) . "'
                    WHERE `id_notif` ='" . filter($this->id_notif) . "'";
        mysqli_query($GLOBALS['Database'], $requete) or die;
    }

    public function check_if_notify($id): array
    {
        $data = [];
        $tab_check = array(
            "rdv" => json_decode($this->next_rdv, true),
            "confirmed" => json_decode($this->confirmed_rdv, true),
            "deleted" => json_decode($this->deleted_rdv, true),
            "finished" => json_decode($this->finished_rdv, true),
            "car" => json_decode($this->car_support, true),
            "control" => json_decode($this->next_control, true),
            "pv" => json_decode($this->send_pv, true),
        );

        foreach ($tab_check as $key => $value) {
            $data[$key] = false;
            if (in_array($id, $value)) {
                $data[$key] = true;
            }
        }
        return $data;
    }

    public function next_control()
    {
        $tab_if_notify = json_decode($this->next_control, true);
        $dataParsing = implode(',', $tab_if_notify);
        $cars = Vehicle::check_next_control($dataParsing);

        if (!empty($cars)) {
            foreach ($cars as $car) {
                try {
                    //Add Job mail in Queue table
                    if (in_array($car['id_user'], $tab_if_notify)) {
                        $carUser = new Vehicle($car['id_vehicle']);
                        $user = new User($car['id_user']);
                        $mail = new Mailing();
                        $dataMail = [
                            "user" => $user,
                            "car" => $carUser,
                        ];
                        $mail->setNewControl_Job($dataMail);
                        //Set cars notified
                        $carUser->setNotified(1);
                        $carUser->update();
                    }
                } catch (Throwable $e) {
                    error_log("Captured Throwable: " . $e->getMessage() . PHP_EOL);
                }
            }
        }
    }

    public function next_rdv()
    {
        $tab_if_notify = json_decode($this->next_rdv, true);
        $dataParsing = implode(',', $tab_if_notify);
        $tab_rdv = Vehicle::check_next_rdv($dataParsing);

        if (!empty($tab_rdv)) {
            foreach ($tab_rdv as $rdv) {
                try {
                    //Add Job mail in Queue table
                    if (in_array($rdv['id_user'], $tab_if_notify)) {
                        $rdvUser = new Intervention($rdv['id_intervention']);
                        $user = new User($rdv['id_user']);
                        $sms = new SMS(0);
                        $dataSMS = [
                            "user" => $user,
                            "rdv" => $rdvUser,
                            "car" => $rdv['id_vehicle']
                        ];
                        $sms->setSMS_JobNextRdv($dataSMS);
                        //Set cars notified
                        $rdvUser->setNotified(1);
                        $rdvUser->setNum_tech(0);
                        $rdvUser->update();
                    }
                } catch (Throwable $e) {
                    error_log("Captured Throwable: " . $e->getMessage() . PHP_EOL);
                }
            }
        }
    }

    public function uncheck_notification($notification_user, $id) {
        $notification_types = [
            'rdv' => 'next_rdv',
            'confirmed' => 'confirmed_rdv',
            'deleted' => 'deleted_rdv',
            'finished' => 'finished_rdv',
            'car' => 'car_support',
            'control' => 'next_control',
            'pv' => 'send_pv',
        ];
        foreach ($notification_user as $key => $value){
            if ($value) {
                $notification_list = json_decode($this->getData($notification_types[$key]), true);
                unset($notification_list[array_search($id, $notification_list)]);
                $this->setData($notification_types[$key], json_encode($notification_list));
                $this->update();
            }                
        }
    }

    public function getData($data): string
    {
        return $this->$data;
    }

    public function setData($type, $data)
    {
        $this->$type = $data;
    }
}

