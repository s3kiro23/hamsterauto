<?php
session_start();

require '../config/Twig.php';

$user = $_SESSION['typeUser'];

if ($user === 'admin' && isset($_SESSION['id'])) {
    echo $twig->render('admin/admin_office.html.twig');
} else {
    header("Location: acces-interdit");
}
