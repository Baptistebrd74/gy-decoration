<?php
/**
 * Début des pages de l'admin. Attend $titre (titre de la page) et $actif (lien du menu en cours).
 * $sansMenu = true sur la page de connexion.
 */
?>
<!doctype html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="robots" content="noindex, nofollow" />
    <title><?= e($titre) ?> · Admin GY-Décoration</title>
    <link rel="icon" href="../img/logo-gy.png" type="image/png" />
    <link rel="stylesheet" href="admin.css" />
  </head>
  <body<?= !empty($sansMenu) ? ' class="page-connexion"' : '' ?>>
<?php if (empty($sansMenu)): ?>
    <header class="barre">
      <a href="index.php" class="barre-logo"><img src="../img/logo-gy.png" alt="" /> <span>Administration</span></a>
      <nav class="barre-liens">
        <a href="index.php"<?= $actif === 'accueil' ? ' class="actif"' : '' ?>>Tableau de bord</a>
        <a href="galerie.php"<?= $actif === 'galerie' ? ' class="actif"' : '' ?>>Galerie</a>
        <a href="textes.php"<?= $actif === 'textes' ? ' class="actif"' : '' ?>>Textes</a>
        <a href="compte.php"<?= $actif === 'compte' ? ' class="actif"' : '' ?>>Mon compte</a>
        <a href="../index.php" target="_blank" rel="noopener">Voir le site ↗</a>
        <form method="post" action="logout.php">
          <?= champ_csrf() ?>
          <button type="submit" class="lien-bouton">Se déconnecter</button>
        </form>
      </nav>
    </header>
<?php endif; ?>
    <main class="contenu">
      <?= afficher_flash() ?>
