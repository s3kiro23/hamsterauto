<?php

class Database
{

    private $db;

    public function __construct($type = null)
    {
        $user = "";
        $pwd = "";
        $hostname = "localhost";

        if ($type == null) {
            $user = getenv('DB_USER');
            $pwd = getenv('DB_PASSWORD');
        } else if ($type == 'api') {
            $user = getenv('API_USER');
            $pwd = getenv('API_PASSWORD');
        } else if ($type == 'ban') {
            $user = getenv('BAN_USER');
            $pwd = getenv('BAN_PASSWORD');
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