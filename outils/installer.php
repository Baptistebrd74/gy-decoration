<?php
declare(strict_types=1);

// Crée les tables et les remplit avec les textes et photos par défaut.
// À lancer en ligne de commande : php outils/installer.php
// On peut le relancer sans risque : il ajoute seulement ce qui manque, sans écraser les textes modifiés.

if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

require __DIR__ . '/../inc/app.php';

// SQLite : le dossier de la base doit exister avant la connexion
$dsn = config()['db_dsn'];
if (str_starts_with($dsn, 'sqlite:')) {
    $dossier = dirname(substr($dsn, strlen('sqlite:')));
    if (!is_dir($dossier)) {
        mkdir($dossier, 0700, true);
    }
}

$pdo = bdd();
$sqlite = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME) === 'sqlite';

// Seules ces deux syntaxes changent entre SQLite (en local) et MySQL (en ligne)
$id = $sqlite ? 'INTEGER PRIMARY KEY AUTOINCREMENT' : 'INT UNSIGNED AUTO_INCREMENT PRIMARY KEY';
$options = $sqlite ? '' : ' ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci';

$pdo->exec("CREATE TABLE IF NOT EXISTS contenus (
    id $id,
    cle VARCHAR(100) NOT NULL UNIQUE,
    page VARCHAR(30) NOT NULL,
    section VARCHAR(60) NOT NULL,
    libelle VARCHAR(150) NOT NULL,
    type VARCHAR(20) NOT NULL,
    valeur TEXT NOT NULL,
    ordre INT NOT NULL DEFAULT 0
)$options");

$pdo->exec("CREATE TABLE IF NOT EXISTS photos (
    id $id,
    fichier VARCHAR(255) NOT NULL,
    titre VARCHAR(100) NOT NULL,
    description VARCHAR(200) NOT NULL,
    categorie VARCHAR(30) NOT NULL,
    largeur INT NOT NULL,
    hauteur INT NOT NULL,
    ordre INT NOT NULL DEFAULT 0,
    cree_le DATETIME NOT NULL
)$options");

$pdo->exec("CREATE TABLE IF NOT EXISTS admins (
    id $id,
    identifiant VARCHAR(60) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL
)$options");

// Sert à bloquer quelqu'un qui essaie des mots de passe au hasard
$pdo->exec("CREATE TABLE IF NOT EXISTS connexions_echouees (
    id $id,
    ip VARCHAR(45) NOT NULL,
    tentee_le INT NOT NULL
)$options");

// --- Textes : on ajoute les nouveaux, on met à jour les libellés, on ne touche jamais aux valeurs ---
$existants = $pdo->query('SELECT cle FROM contenus')->fetchAll(PDO::FETCH_COLUMN);
$ajout = $pdo->prepare('INSERT INTO contenus (cle, page, section, libelle, type, valeur, ordre) VALUES (?, ?, ?, ?, ?, ?, ?)');
$majLibelle = $pdo->prepare('UPDATE contenus SET page = ?, section = ?, libelle = ?, type = ?, ordre = ? WHERE cle = ?');

$nouveaux = 0;
foreach (require __DIR__ . '/contenus-par-defaut.php' as $ordre => [$cle, $section, $libelle, $type, $valeur]) {
    $page = explode('.', $cle)[0];
    if (in_array($cle, $existants, true)) {
        $majLibelle->execute([$page, $section, $libelle, $type, $ordre, $cle]);
    } else {
        $ajout->execute([$cle, $page, $section, $libelle, $type, $valeur, $ordre]);
        $nouveaux++;
    }
}
echo "Textes : $nouveaux ajouté(s).\n";

// --- Photos : seulement au premier lancement ---
if ((int) $pdo->query('SELECT COUNT(*) FROM photos')->fetchColumn() === 0) {
    $ajout = $pdo->prepare('INSERT INTO photos (fichier, titre, description, categorie, largeur, hauteur, ordre, cree_le) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
    $photos = require __DIR__ . '/photos-par-defaut.php';
    foreach ($photos as $ordre => [$fichier, $titre, $description, $categorie, $largeur, $hauteur]) {
        $ajout->execute([$fichier, $titre, $description, $categorie, $largeur, $hauteur, $ordre, date('Y-m-d H:i:s')]);
    }
    echo 'Photos : ' . count($photos) . " ajoutée(s).\n";
}

if ((int) $pdo->query('SELECT COUNT(*) FROM admins')->fetchColumn() === 0) {
    echo "Aucun compte admin : lancer maintenant php outils/creer-admin.php\n";
}
echo "Installation terminée.\n";
