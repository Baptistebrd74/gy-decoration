<?php
require __DIR__ . '/inc/app.php';
$page = ['cle' => 'mentions', 'fichier' => 'mentions-legales.php', 'noindex' => true];
require __DIR__ . '/inc/haut.php';

// [titre affiché, clé du texte, ancre]. Une section vide (ex. médiateur pas encore choisi) n'est pas affichée.
$sections = [
    ['Éditeur du site', 'mentions.editeur', ''],
    ['Médiateur de la consommation', 'mentions.mediateur', 'mediateur'],
    ['Hébergement', 'mentions.hebergement', ''],
    ['Propriété intellectuelle', 'mentions.propriete', ''],
    ['Politique de confidentialité', 'mentions.confidentialite', 'confidentialite'],
    ['Cookies', 'mentions.cookies', 'cookies'],
];
?>

    <!-- PAGE HEADER -->
    <section class="hero" style="padding: 60px 0 40px">
      <div class="container" style="display: block; text-align: center">
        <span class="hero-badge">Informations légales</span>
        <h1 style="font-size: 2.6rem">Mentions <span class="highlight">légales</span></h1>
      </div>
    </section>

    <!-- CONTENU -->
    <section class="section">
      <div class="container texte-legal" style="max-width: 800px">
<?php foreach ($sections as [$titre, $cle, $ancre]): ?>
<?php if (trim(brut($cle)) === '') continue; ?>
        <div<?= $ancre !== '' ? ' id="' . $ancre . '"' : '' ?>>
          <h2><?= $titre ?></h2>
          <?= paragraphes($cle) ?>
        </div>
<?php endforeach; ?>
      </div>
    </section>

<?php require __DIR__ . '/inc/bas.php'; ?>
