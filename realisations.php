<?php
require __DIR__ . '/inc/app.php';
$page = ['cle' => 'realisations', 'fichier' => 'realisations.php', 'lightbox' => true];
require __DIR__ . '/inc/haut.php';
?>

    <!-- PAGE HEADER -->
    <section class="hero" style="padding: 60px 0 40px">
      <div class="container" style="display: block; text-align: center">
        <span class="hero-badge"><?= t('realisations.badge') ?></span>
        <h1 style="font-size: 2.6rem"><?= t('realisations.titre') ?></h1>
        <?= paragraphes('realisations.intro', ' class="page-intro"') ?>
      </div>
    </section>

    <!-- GALLERY (photos gérées depuis l'admin) -->
    <section class="section">
      <div class="container">
        <div class="filters" role="group" aria-label="Filtrer les réalisations">
          <button type="button" class="filter is-active" data-filter="all" aria-pressed="true">Tout</button>
<?php foreach (CATEGORIES as $code => $libelle): ?>
          <button type="button" class="filter" data-filter="<?= e($code) ?>" aria-pressed="false"><?= e($libelle) ?></button>
<?php endforeach; ?>
        </div>
        <div class="gallery-grid">
<?php foreach (photos() as $photo): ?>
          <?= vignette($photo) ?>

<?php endforeach; ?>
        </div>
      </div>
    </section>

    <!-- CTA -->
    <section class="section section-cta">
      <div class="container" style="text-align: center">
        <h2 style="font-size: 2rem; color: var(--blue-900); margin-bottom: 16px"><?= t('realisations.cta_titre') ?></h2>
        <?= paragraphes('realisations.cta_texte', ' class="cta-texte"') ?>
        <a href="contact.php" class="btn btn-primary"><?= t('realisations.cta_bouton') ?></a>
      </div>
    </section>

<?php require __DIR__ . '/inc/bas.php'; ?>
