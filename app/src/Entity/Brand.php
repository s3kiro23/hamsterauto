<?php

class Brand
{
    private $id_brand;
    private $brand_name;

    public function __construct($id)
    {
        $this->id_brand = $id;
        if ($this->id_brand != 0) {
            $this->check_data($id);
        }
    }

    public function check_data($id)
    {
        $requete = "SELECT * FROM `brand` WHERE `id_brand` = '" .filter($id) . "'";
        $result = mysqli_query($GLOBALS['Database'], $requete) or die;
        if ($data = mysqli_fetch_array($result)) {
            $this->id_brand = $data['id_brand'];
            $this->brand_name = $data['brand_name'];
        }
    }

    static public function count_brands(){
        $requete = "SELECT COUNT(*) as nbBrands FROM brand";
        $result = mysqli_query($GLOBALS['Database'], $requete) or die;
        $data = mysqli_fetch_assoc($result);
        return (int)$data['nbBrands'];
    }

    public function getId_brand(){
        return $this->id_brand;
    }

    public function setId_brand($id_brand){
        $this->id_brand = $id_brand;
    }

    public function getBrand_name(){
        return $this->brand_name;
    }

    public function setBrand_name($brand_name){
        $this->brand_name = $brand_name;
    }

}