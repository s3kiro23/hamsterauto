<?php

spl_autoload_register(function ($classe) {
    require '../src/Entity/' . $classe . '.php';
});

$testpdf = new PDF();
$user = new User(60);
$car = new Vehicule(92);
$CT = new ControleTech(1);
$template = $testpdf->pv($car, $CT, $user);
$testpdf->generatePDF($template);