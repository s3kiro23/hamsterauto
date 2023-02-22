<?php

require $_SERVER['DOCUMENT_ROOT']."/src/Entity/Setting.php";
Setting::autoload();

$db = new Database();
$GLOBALS['Database'] = $db->connexion();

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
