<?php
declare(strict_types=1);

// Socle de toutes les pages de l'admin : session sécurisée, jeton CSRF, vérification de connexion.

require __DIR__ . '/../../inc/app.php';

const DUREE_INACTIVITE = 2 * 3600; // déconnexion automatique après 2h sans rien faire

// Les pages de textes, dans l'ordre du menu : code => libellé
const PAGES_TEXTES = [
    'global' => 'Infos générales',
    'accueil' => 'Accueil',
    'services' => 'Services',
    'realisations' => 'Réalisations',
    'apropos' => 'À propos',
    'contact' => 'Contact',
    'mentions' => 'Mentions légales',
];

session_name('gy_admin');
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'httponly' => true,      // le JavaScript ne peut pas lire le cookie
    'samesite' => 'Strict',  // le cookie n'est pas envoyé depuis un autre site
    'secure' => ($_SERVER['HTTPS'] ?? '') !== '' && $_SERVER['HTTPS'] !== 'off',
]);
session_start();

header('X-Frame-Options: DENY');       // l'admin ne peut pas être affiché dans une iframe
header('X-Robots-Tag: noindex, nofollow');
header('Cache-Control: no-store');

function jeton_csrf(): string
{
    return $_SESSION['csrf'] ??= bin2hex(random_bytes(32));
}

// À mettre dans chaque formulaire POST de l'admin
function champ_csrf(): string
{
    return '<input type="hidden" name="csrf" value="' . jeton_csrf() . '">';
}

// Refuse un formulaire qui ne vient pas de l'admin (protection contre le CSRF)
function verifier_csrf(): void
{
    if (!hash_equals(jeton_csrf(), (string) ($_POST['csrf'] ?? ''))) {
        http_response_code(400);
        exit('Le formulaire a expiré. Reviens en arrière et recharge la page.');
    }
}

function rediriger(string $url): never
{
    header('Location: ' . $url);
    exit;
}

function exiger_connexion(): void
{
    $inactif = time() - ($_SESSION['derniere_activite'] ?? 0) > DUREE_INACTIVITE;
    if (empty($_SESSION['admin_id']) || $inactif) {
        $_SESSION = [];
        rediriger('login.php');
    }
    $_SESSION['derniere_activite'] = time();
}

// Message affiché une seule fois sur la page suivante ("Textes enregistrés", erreur...)
function flash(string $type, string $message): void
{
    $_SESSION['flash'] = [$type, $message];
}

function afficher_flash(): string
{
    if (empty($_SESSION['flash'])) {
        return '';
    }
    [$type, $message] = $_SESSION['flash'];
    unset($_SESSION['flash']);
    return '<p class="flash flash-' . e($type) . '" role="status">' . e($message) . '</p>';
}
