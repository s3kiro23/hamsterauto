<?php

require_once "../../src/Entity/Request.php";

$request = new Request(0);
$request->check_expiration();