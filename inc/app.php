<?php
declare(strict_types=1);

// Point d'entrée commun à toutes les pages : config, connexion à la base, fonctions d'affichage.

const RACINE = __DIR__ . '/..';

// Catégories de la galerie (data-cat => libellé des filtres)
const CATEGORIES = [
    'peinture' => 'Peinture',
    'deco' => 'Papier peint & motifs',
    'pro' => 'Professionnels',
];

function config(): array
{
    static $config = null;
    if ($config === null) {
        $chemin = RACINE . '/config.php';
        if (!is_file($chemin)) {
            http_response_code(500);
            exit('Configuration manquante : copier config.example.php en config.php.');
        }
        $config = require $chemin;
    }
    return $config;
}

function bdd(): PDO
{
    static $pdo = null;
    if ($pdo === null) {
        $config = config();
        $pdo = new PDO($config['db_dsn'], $config['db_user'] ?? null, $config['db_pass'] ?? null, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }
    return $pdo;
}

// Échappe un texte avant de l'afficher dans le HTML (protège contre l'injection de code)
function e(string $texte): string
{
    return htmlspecialchars($texte, ENT_QUOTES, 'UTF-8');
}

// Tous les textes du site, chargés en une seule requête : ['accueil.hero_titre' => '...', ...]
function contenus(): array
{
    static $contenus = null;
    if ($contenus === null) {
        $contenus = bdd()->query('SELECT cle, valeur FROM contenus')->fetchAll(PDO::FETCH_KEY_PAIR);
    }
    return $contenus;
}

// Valeur brute d'un texte (à échapper avec e() avant affichage)
function brut(string $cle): string
{
    return contenus()[$cle] ?? '';
}

// Texte sur une ligne. *mot* = mot mis en valeur (utilisé dans les grands titres)
function t(string $cle): string
{
    return preg_replace('/\*(.+?)\*/u', '<span class="highlight">$1</span>', e(brut($cle)));
}

// Texte long : une ligne vide = nouveau paragraphe, **mot** = gras,
// les adresses email et les liens deviennent cliquables
function paragraphes(string $cle, string $attributs = ''): string
{
    $texte = trim(str_replace("\r\n", "\n", brut($cle)));
    if ($texte === '') {
        return '';
    }

    $html = '';
    foreach (preg_split('/\n\s*\n/', $texte) as $bloc) {
        $bloc = preg_replace('/\*\*(.+?)\*\*/us', '<strong>$1</strong>', e(trim($bloc)));
        $html .= "<p$attributs>" . nl2br(rendre_cliquable($bloc), false) . "</p>\n";
    }
    return $html;
}

function rendre_cliquable(string $html): string
{
    $html = preg_replace('~\bhttps?://[^\s<]+[^\s<.,;:!?)]~u', '<a href="$0" target="_blank" rel="noopener">$0</a>', $html);
    return preg_replace('/(?<![\w.+\/-])[\w.+-]+@[\w-]+(?:\.[\w-]+)+/u', '<a href="mailto:$0">$0</a>', $html);
}

// "06 59 11 37 34" => "tel:0659113734"
function lien_tel(string $numero): string
{
    return 'tel:' . preg_replace('/[^\d+]/', '', $numero);
}

function photos(?int $limite = null): array
{
    $sql = 'SELECT * FROM photos ORDER BY ordre, id';
    if ($limite !== null) {
        $sql .= ' LIMIT ' . $limite;
    }
    return bdd()->query($sql)->fetchAll();
}

// Une vignette de la galerie (même HTML que l'ancienne version statique)
function vignette(array $photo): string
{
    $alt = $photo['titre'] . ($photo['description'] !== '' ? ' – ' . $photo['description'] : '');
    return '<a href="' . e($photo['fichier']) . '.webp" class="gallery-item" data-cat="' . e($photo['categorie']) . '">'
        . '<img src="' . e($photo['fichier']) . '-sm.webp" alt="' . e($alt) . '" width="' . (int) $photo['largeur']
        . '" height="' . (int) $photo['hauteur'] . '" loading="lazy" decoding="async" />'
        . '<div class="gallery-overlay"><h3>' . e($photo['titre']) . '</h3><p>' . e($photo['description']) . '</p></div>'
        . '</a>';
}
