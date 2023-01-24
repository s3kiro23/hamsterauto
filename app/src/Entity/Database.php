<?php

class Database
{

    private $db;

    public function __construct()
    {

        $hostname = "localhost";
        if ($_SERVER['HTTP_HOST'] == 'hamsterauto.local:8001') {
            $hostname = "database_mysql";
        }

        try {
            $this->db = mysqli_connect($hostname, "db", "Db123!@20", "hamsterauto");
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