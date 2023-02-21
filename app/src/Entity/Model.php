<?php

class Model
{
    private $id_model;
    private $id_brand;
    private $model_name;

    public function __construct($id)
    {
        $this->id_model = $id;
        if ($this->id_model != 0) {
            $this->check_data($id);
        }
    }

    public function check_data($id)
    {
        $requete = "SELECT * FROM `model` WHERE `id_model` = '" .filter($id) . "'";
        $result = mysqli_query($GLOBALS['Database'], $requete) or die;
        if ($data = mysqli_fetch_array($result)) {
            $this->id_model = $data['id_model'];
            $this->id_brand = $data['id_brand'];
            $this->model_name = $data['model_name'];
        }
    }

    static public function count_models(){
        $requete = "SELECT COUNT(*) as nbModels FROM model";
        $result = mysqli_query($GLOBALS['Database'], $requete) or die;
        $data = mysqli_fetch_assoc($result);
        return (int)$data['nbModels'];
    }

    public function getId_model(){
        return $this->id_model;
    }

    public function setId_model($id_model){
        $this->id_model = $id_model;
    }

    public function getId_brand(){
        return $this->id_brand;
    }

    public function setId_brand($id_brand){
        $this->id_brand = $id_brand;
    }

    public function getModel_name(){
        return $this->model_name;
    }

    public function setModel_name($model_name){
        $this->model_name = $model_name;
    }

}