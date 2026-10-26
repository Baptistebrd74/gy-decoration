<?php
declare(strict_types=1);
require __DIR__ . '/inc/admin.php';

// Déconnexion en POST uniquement : un simple lien piégé ne peut pas déconnecter l'admin
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    rediriger('index.php');
}
verifier_csrf();

$_SESSION = [];
setcookie(session_name(), '', ['expires' => time() - 3600, 'path' => '/']);
session_destroy();

rediriger('login.php?deconnecte=1');
