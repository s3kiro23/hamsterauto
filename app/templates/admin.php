<?php
session_start();

require '../config/Twig.php';

$user = $_SESSION['typeUser'];

if ($user === 'admin' && isset($_SESSION['id'])){
    echo $twig-> render('adminOffice.html.twig');
}else {
    header("Location: ./403.html");
}

    
    