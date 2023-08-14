<?php

class Database
{

    private $db;

    public function __construct($type = null)
    {
        $config = parse_ini_file('/opt/hamsterauto/config.ini', true);
        $user = "";
        $pwd = "";
        $hostname = "localhost";

        if ($type == null) {
            $user = $config['database']['DB_USER'];
            $pwd = $config['database']['DB_PASSWORD'];
        } else if ($type == 'api') {
            $user = $config['database']['API_USER'];
            $pwd = $config['database']['API_PASSWORD'];
        } else if ($type == 'ban') {
            $user = $config['database']['BAN_USER'];
            $pwd = $config['database']['BAN_PASSWORD'];
        }
        
        if (isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] == 'hamsterauto.local:8001') {
            $hostname = "database_mysql";
        }

        try {
            $this->db = mysqli_connect($hostname, $user, $pwd, "hamsterauto");
        } catch (RuntimeException $e) {
            exit(0);
        }
    }

    public function connexion()
    {
        return $this->db;
    }
}

function filter($value): string
{
    return mysqli_real_escape_string($GLOBALS['Database'], $value);
}

function ROOT_DIR(): string
{
    return $_SERVER['DOCUMENT_ROOT'];
}