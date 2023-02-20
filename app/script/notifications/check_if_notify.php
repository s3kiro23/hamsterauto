<?php

spl_autoload_register(function ($classe) {
    require '../../src/Entity/' . $classe . '.php';
});

$check_notify = new Notification();
$check_notify->next_control();
$check_notify->next_rdv();