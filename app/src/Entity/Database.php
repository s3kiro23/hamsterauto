<?php

class Database{

    private $db;

    public function __construct(){

        try{
            $this->db = mysqli_connect("localhost", "db", "Db123!@20", "hamsterauto");
        } catch (RuntimeException $e){
            exit(0);
        }
    }

    public function connexion(){
        return $this->db;
    }

}
function filter($value): string
{

	return mysqli_real_escape_string($GLOBALS['Database'], $value);
}