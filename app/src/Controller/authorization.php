<?php
require_once 'shared.php';

spl_autoload_register(function ($classe) {
    require '../../Entity/' . $classe . '.php';
});

$db = new Database();
$GLOBALS['db'] = $db->connexion();

function getAuthorizationUser($whoIs): bool
{
    $status = true;
    if (!$whoIs || $whoIs->getType() != "technicien" || !is_logged()) {
        $status = false;
    }
    return $status;
}

function getAuthorizationAll(): bool
{
    error_log("user");
    $status = true;
    if (!is_logged()) {
        $status = false;
    }
    return $status;
}