<?php
declare(strict_types=1);
require __DIR__ . '/inc/admin.php';
exiger_connexion();

$nbPhotos = (int) bdd()->query('SELECT COUNT(*) FROM photos')->fetchColumn();

$titre = 'Tableau de bord';
$actif = 'accueil';
require __DIR__ . '/inc/haut.php';
?>
      <h1>Bonjour Yann 👋</h1>
      <p class="intro">Tout ce que tu modifies ici apparaît immédiatement sur le site.</p>

      <div class="grille">
        <a href="galerie.php" class="carte carte-lien">
          <span class="carte-icone">🖼️</span>
          <h2>Galerie</h2>
          <p><?= $nbPhotos ?> photo<?= $nbPhotos > 1 ? 's' : '' ?>. Ajouter, modifier, supprimer ou changer l'ordre.</p>
        </a>
        <a href="textes.php?page=global" class="carte carte-lien">
          <span class="carte-icone">📞</span>
          <h2>Coordonnées</h2>
          <p>Téléphone, email, lien Facebook, SIREN.</p>
        </a>
        <a href="textes.php?page=mentions" class="carte carte-lien">
          <span class="carte-icone">⚖️</span>
          <h2>Mentions légales</h2>
          <p>Éditeur, médiateur de la consommation, confidentialité.</p>
        </a>
      </div>

      <h2 class="titre-section">Textes des pages</h2>
      <div class="grille grille-petite">
<?php foreach (PAGES_TEXTES as $code => $libelle): ?>
<?php if (in_array($code, ['global', 'mentions'], true)) continue; ?>
        <a href="textes.php?page=<?= $code ?>" class="carte carte-lien">
          <h3><?= e($libelle) ?></h3>
        </a>
<?php endforeach; ?>
      </div>

      <div class="carte aide">
        <h2>Petites astuces</h2>
        <ul>
          <li>Dans un <strong>grand titre</strong>, entoure un mot d'étoiles pour le mettre en couleur : <code>Donnez de la *couleur* à vos intérieurs</code>.</li>
          <li>Dans un <strong>texte long</strong>, laisse une ligne vide pour commencer un nouveau paragraphe, et écris <code>**comme ceci**</code> pour mettre en gras.</li>
          <li>Les adresses email et les liens (https://…) deviennent cliquables tout seuls.</li>
        </ul>
      </div>
<?php require __DIR__ . '/inc/bas.php'; ?>
