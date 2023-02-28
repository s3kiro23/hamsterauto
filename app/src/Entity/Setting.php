<?php

class Setting
{
    private $id_setting;
    private $slot_interval;
    private $start_time_am;
    private $end_time_am;
    private $start_time_pm;
    private $end_time_pm;
    private $nb_lift;
    private $session_user;
    private $session_internal;
    private $coordinates;

    public function __construct($id)
    {
        $this->id_setting = $id;
        if ($this->id_setting != 0) {
            $this->check_data($id);
        }
    }

    public function check_data($id)
    {
        $requete = "SELECT * FROM `setting` WHERE id_setting = '" . filter($id) . "'";
        $result = mysqli_query($GLOBALS['Database'], $requete) or die;
        if ($data = mysqli_fetch_array($result)) {
            $this->id_setting = $data['id_setting'];
            $this->slot_interval = $data['slot_interval'];
            $this->start_time_am = $data['start_time_am'];
            $this->end_time_am = $data['end_time_am'];
            $this->start_time_pm = $data['start_time_pm'];
            $this->end_time_pm = $data['end_time_pm'];
            $this->nb_lift = $data['nb_lift'];
            $this->session_user = $data['session_duration_user'];
            $this->session_internal = $data['session_duration_internal'];
            $this->coordinates = $data['coordinates'];
        }
    }

    static public function autoload()
    {
        return

            spl_autoload_register(function ($classe) {
                $html_file = array("RequestHTML", "ContactHTML", "FormHTML", "GenerateDateHTML", "LoadClientHTML", "LoadTechHTML", "MenuHTML", "PaginationHTML");
                if (in_array($classe, $html_file)) {
                    require $_SERVER['DOCUMENT_ROOT'] . "/src/Entity/HTML/" . $classe . ".php";
                } else if ($classe != "Setting") {
                    require $_SERVER['DOCUMENT_ROOT'] . "/src/Entity/" . $classe . ".php";
                }
            });
    }

    static public function get_settings()
    {
        $requete = "SELECT * FROM `setting`";
        $result = mysqli_query($GLOBALS['Database'], $requete) or die;

        return mysqli_fetch_assoc($result);
    }


    static public function change_time_settings($slot, $time)
    {
        $requete = "UPDATE `setting` SET `$slot`='" . filter($time) . "' ";
        mysqli_query($GLOBALS['Database'], $requete) or die;
    }

    static public function change_slot_interval($time)
    {
        $requete = "UPDATE `setting` SET `slot_interval` ='" . filter($time) . "' ";
        mysqli_query($GLOBALS['Database'], $requete) or die;
    }

    static public function change_session_settings($context, $time)
    {
        $requete = "UPDATE `setting` SET `session_duration_$context`='" . filter($time) . "' ";
        mysqli_query($GLOBALS['Database'], $requete) or die;
    }

    static public function change_lifts($lifts)
    {
        $requete = "UPDATE `setting` SET `nb_lift`='" . filter($lifts) . "' ";
        mysqli_query($GLOBALS['Database'], $requete) or die;
    }

    public function getId_setting()
    {
        return $this->id_setting;
    }

    public function setId_setting($id_setting)
    {
        $this->id_setting = $id_setting;
    }

    public function getSlot_interval()
    {
        return $this->slot_interval;
    }

    public function setSlot_interval($slot_interval)
    {
        $this->slot_interval = $slot_interval;
    }

    public function getStart_time_am()
    {
        return $this->start_time_am;
    }

    public function setStart_time_am($start_time_am)
    {
        $this->start_time_am = $start_time_am;
    }

    public function getEnd_time_am()
    {
        return $this->end_time_am;
    }

    public function setEnd_time_am($end_time_am)
    {
        $this->end_time_am = $end_time_am;
    }

    public function getStart_time_pm()
    {
        return $this->start_time_pm;
    }

    public function setStart_time_pm($start_time_pm)
    {
        $this->start_time_pm = $start_time_pm;
    }

    public function getEnd_time_pm()
    {
        return $this->end_time_pm;
    }

    public function setEnd_time_pm($end_time_pm)
    {
        $this->end_time_pm = $end_time_pm;
    }

    public function getNb_lift()
    {
        return $this->nb_lift;
    }

    public function setNb_lift($nb_lift)
    {
        $this->nb_lift = $nb_lift;
    }

    public function getSession_User()
    {
        return $this->session_user;
    }

    public function setSession_User($session_user)
    {
        $this->session_user = $session_user;
    }

    public function getSession_Internal()
    {
        return $this->session_internal;
    }

    public function setSession_Internal($session_internal)
    {
        $this->session_internal = $session_internal;
    }


    public function getCoordinates()
    {
        return $this->coordinates;
    }

    public function setCoordinates($coordinates)
    {
        $this->coordinates = $coordinates;
    }
}
