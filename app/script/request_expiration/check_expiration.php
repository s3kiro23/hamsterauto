<?php

spl_autoload_register(function ($classe) {
    require '../../src/Entity/' . $classe . '.php';
});

$request = new Request(0);
$request->check_expiration();