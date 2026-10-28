<?php
/**
 * Début de page commun : <head> + menu.
 * Attend $page = ['cle' => 'accueil', 'fichier' => 'index.php', 'noindex' => false]
 */
$titre = brut($page['cle'] . '.meta_titre');
$description = brut($page['cle'] . '.meta_description');
$urlSite = rtrim(config()['url_site'], '/');
$url = $urlSite . '/' . ($page['fichier'] === 'index.php' ? '' : $page['fichier']);

$menu = [
    'index.php' => 'Accueil',
    'services.php' => 'Services',
    'realisations.php' => 'Réalisations',
    'a-propos.php' => 'À propos',
];
?>
<!doctype html>
<html lang="fr">
  <head>
    <meta charset="UTF-8"/>
    <base href="/" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= e($titre) ?></title>
    <meta name="description" content="<?= e($description) ?>" />
<?php if (!empty($page['noindex'])): ?>
    <meta name="robots" content="noindex, follow" />
<?php else: ?>
    <meta property="og:type" content="website" />
    <meta property="og:title" content="<?= e($titre) ?>" />
    <meta property="og:description" content="<?= e($description) ?>" />
    <meta property="og:image" content="<?= e($urlSite) ?>/img/og-image.jpg" />
    <meta property="og:url" content="<?= e($url) ?>" />
    <meta property="og:locale" content="fr_FR" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="<?= e($titre) ?>" />
    <meta name="twitter:description" content="<?= e($description) ?>" />
    <meta name="twitter:image" content="<?= e($urlSite) ?>/img/og-image.jpg" />
    <link rel="canonical" href="<?= e($url) ?>" />
<?php endif; ?>
    <link rel="preload" href="fonts/fraunces-latin.woff2" as="font" type="font/woff2" crossorigin />
    <link rel="icon" href="img/logo-gy.png" type="image/png" />
    <link rel="stylesheet" href="css/style.css" />
  </head>
  <body>
    <!-- HEADER -->
    <header class="header" id="header">
      <div class="container">
        <a href="index.php" class="logo"
          ><img src="img/logo-gy.png" alt=""
        /></a>
        <nav>
          <ul class="nav-links" id="navLinks">
<?php foreach ($menu as $fichier => $libelle): ?>
            <li><a href="<?= $fichier ?>"<?= $fichier === $page['fichier'] ? ' class="active"' : '' ?>><?= $libelle ?></a></li>
<?php endforeach; ?>
            <li><a href="contact.php" class="nav-cta<?= $page['fichier'] === 'contact.php' ? ' active' : '' ?>">Contact</a></li>
          </ul>
        </nav>
        <button class="burger" id="burger" aria-label="Menu">
          <span></span>
          <span></span>
          <span></span>
        </button>
      </div>
    </header>
