<?php

class LoginAttempt
{
    private $id_user;
    private $remote_ip;
    private $email_user;

    public function __construct($id_user, $remote_ip, $email_user)
    {
        $this->id_user = $id_user;
        $this->remote_ip = $remote_ip;
        $this->email_user = $email_user;
    }

    static public function create($data)
    {
        $requete = "INSERT INTO `login_attempt` (`id_user`, `email_user`, `remote_ip`) 
                    VALUES ('" . mysqli_real_escape_string($GLOBALS['Database'], $data['id_user']) . "',
                    '" . mysqli_real_escape_string($GLOBALS['Database'], $data['mail']) . "',
                    '" . mysqli_real_escape_string($GLOBALS['Database'], $data['remote_ip']) . "')";
        $result = mysqli_query($GLOBALS['Database'], $requete) or die;
        return $GLOBALS['Database']->insert_id;
    }

    static public function fecthAllAttempts($start, $length, $orders, $search)
    {
        $bans = [];
        $requete = "SELECT *, MAX(`generated_at`) as lastDate FROM `login_attempt` 
        WHERE  `email_user` LIKE '%" . filter($search['value']) . "%' OR
        `remote_ip` LIKE '%" . filter($search['value']) . "%' OR
        `generated_at` LIKE '%" . filter($search['value']) . "%'
        GROUP BY `id_user` HAVING COUNT(*) = 3";

        // Order
        foreach ($orders as $key => $order) {
            // $order['name'] is the name of the order column as sent by the JS
            if ($order['name'] != '') {
                $orderColumn = null;
                switch ($order['name']) {
                    case 'login': {
                            $orderColumn = 'email_user';
                            break;
                        }
                    case 'ip': {
                            $orderColumn = 'remote_ip';
                            break;
                        }
                    case 'date': {
                            $orderColumn = 'generated_at';
                            break;
                        }
                }
                if ($orderColumn !== null) {
                    $asc = $order['dir'];
                    $requete .= " ORDER BY `$orderColumn` $asc";
                }
            }
        }

        $requete .= " LIMIT $length OFFSET $start";

        $result = mysqli_query($GLOBALS['Database'], $requete) or die;
        while ($data = mysqli_fetch_assoc($result)) {
            $bans[] = $data;
        }
        return $bans;
    }

    static public function countAllBans()
    {
        $requete = "SELECT count(*) AS nbBans FROM `login_attempt` GROUP BY `id_user` HAVING COUNT(*) = 3";

        $result = mysqli_query($GLOBALS['Database'], $requete) or die;
        if (mysqli_num_rows($result) > 0) {
            $data = mysqli_fetch_assoc($result);
            return (int)$data['nbBans'];
        } else {
            return 0;
        }
    }

    static public function unban_user($id)
    {
        $requete = "DELETE FROM `login_attempt` WHERE `id_user` ='" . filter($id) . "'";
        mysqli_query($GLOBALS['Database'], $requete) or die;
    }

    static public function check_log($id_user)
    {
        $result = mysqli_query($GLOBALS['Database'], "SELECT * FROM login_attempt WHERE id_user='" . mysqli_real_escape_string($GLOBALS['Database'], $id_user) . "'") or die;
        return mysqli_num_rows($result);
    }

    public function getId_user()
    {
        return $this->id_user;
    }

    public function setId_user($id_user)
    {
        $this->id_user = $id_user;
    }

    public function getRemote_ip()
    {
        return $this->remote_ip;
    }

    public function setRemote_ip($remote_ip)
    {
        $this->remote_ip = $remote_ip;
    }

    public function getEmail_user()
    {
        return $this->email_user;
    }

    public function setEmail_user($email_user)
    {
        $this->email_user = $email_user;
    }
}
