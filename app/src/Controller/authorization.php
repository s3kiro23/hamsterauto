<?php

spl_autoload_register(function ($classe) {
    require '../../Entity/' . $classe . '.php';
});

require "../../Entity/HTML/PaginationHTML.php";
require "../../Entity/HTML/LoadClientHTML.php";

$db = new Database();
$GLOBALS['db'] = $db->connexion();

function getAuthorization_User($whoIs): bool
{
    $status = true;
    if (!$whoIs || $whoIs->getType() != "technicien" || !Control::is_logged()) {
        $status = false;
    }
    return $status;
}

function getAuthorization_All(): bool
{
    $status = true;
    if (!Control::is_logged()) {
        $status = false;
    }
    return $status;
}
