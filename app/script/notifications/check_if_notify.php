<?php

require_once "../../src/Entity/Notification.php";

$check_notify = new Notification();
$check_notify->next_control();
$check_notify->next_rdv();