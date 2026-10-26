<?php
declare(strict_types=1);

// Crée le compte admin, ou change son mot de passe s'il existe déjà.
// À lancer en ligne de commande : php outils/creer-admin.php
// Le mot de passe n'est jamais écrit dans un fichier : il est tapé ici puis stocké hashé.

if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

require __DIR__ . '/../inc/app.php';

function demander(string $question, bool $cache = false): string
{
    echo $question;
    if ($cache) {
        shell_exec('stty -echo');
    }
    $reponse = trim((string) fgets(STDIN));
    if ($cache) {
        shell_exec('stty echo');
        echo "\n";
    }
    return $reponse;
}

$identifiant = demander('Identifiant : ');
if ($identifiant === '') {
    exit("Identifiant vide, rien n'a été fait.\n");
}

$motDePasse = demander('Mot de passe (12 caractères minimum) : ', true);
if (mb_strlen($motDePasse) < 12) {
    exit("Mot de passe trop court, rien n'a été fait.\n");
}
if (demander('Confirmer le mot de passe : ', true) !== $motDePasse) {
    exit("Les deux mots de passe sont différents, rien n'a été fait.\n");
}

$hash = password_hash($motDePasse, PASSWORD_DEFAULT);
$pdo = bdd();

$existe = $pdo->prepare('SELECT id FROM admins WHERE identifiant = ?');
$existe->execute([$identifiant]);

if ($existe->fetchColumn()) {
    $pdo->prepare('UPDATE admins SET mot_de_passe = ? WHERE identifiant = ?')->execute([$hash, $identifiant]);
    echo "Mot de passe de « $identifiant » mis à jour.\n";
} else {
    $pdo->prepare('INSERT INTO admins (identifiant, mot_de_passe) VALUES (?, ?)')->execute([$identifiant, $hash]);
    echo "Compte « $identifiant » créé.\n";
}
