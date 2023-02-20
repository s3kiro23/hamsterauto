<?php

require __DIR__.'/../vendor/autoload.php';

// vues de l'appli
$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../twig');

// instanciation du twig
$twig = new \Twig\Environment($loader,['cache' => false]);