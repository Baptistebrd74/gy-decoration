<?php
require __DIR__ . '/inc/app.php';
$page = ['cle' => 'apropos', 'fichier' => 'a-propos.php'];
require __DIR__ . '/inc/haut.php';
?>

    <!-- PAGE HEADER -->
    <section class="hero" style="padding: 60px 0 40px">
      <div class="container" style="display: block; text-align: center">
        <span class="hero-badge"><?= t('apropos.badge') ?></span>
        <h1 style="font-size: 2.6rem"><?= t('apropos.titre') ?></h1>
        <?= paragraphes('apropos.intro', ' class="page-intro"') ?>
      </div>
    </section>

    <!-- ABOUT -->
    <section class="section">
      <div class="container">
        <div class="about-content">
          <div class="about-image">
            <img src="img/realisations/yann-grotte.webp" alt="Yann Grotte, peintre de GY-Décoration, pinceau entre les dents sur un chantier" width="1440" height="1080" decoding="async" />
          </div>
          <div class="about-text">
            <h2><?= t('apropos.presentation_titre') ?></h2>
            <?= paragraphes('apropos.presentation_texte') ?>
          </div>
        </div>
      </div>
    </section>

    <!-- VALEURS -->
    <section class="section section-alt">
      <div class="container">
        <div class="section-header">
          <h2><?= t('apropos.valeurs_titre') ?></h2>
          <p><?= t('apropos.valeurs_sous_titre') ?></p>
          <div class="line"></div>
        </div>
        <div class="services-grid">
          <div class="service-card" style="text-align: center">
            <div class="service-icon" style="margin: 0 auto 20px"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" width="26" height="26"><path d="M8 14c-1.5-1.5-2-3.5-1.3-5.7C7.6 5.7 10 4 13 4c3.9 0 7 3.1 7 7 0 2.5-1.3 4-2.5 5-.8.7-1.5 1.3-1.5 2.5a2 2 0 0 1-4 0"/><path d="M10 12a2 2 0 1 1 4 0c0 1.2-1 1.8-1 3"/></svg></div>
            <h3><?= t('apropos.valeur1_titre') ?></h3>
            <?= paragraphes('apropos.valeur1_texte') ?>
          </div>
          <div class="service-card" style="text-align: center">
            <div class="service-icon" style="margin: 0 auto 20px"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" width="26" height="26"><circle cx="12" cy="12" r="9"/><path d="M9 12.5l2 2 4.5-5"/></svg></div>
            <h3><?= t('apropos.valeur2_titre') ?></h3>
            <?= paragraphes('apropos.valeur2_texte') ?>
          </div>
          <div class="service-card" style="text-align: center">
            <div class="service-icon" style="margin: 0 auto 20px"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" width="26" height="26"><circle cx="9" cy="12" r="5"/><circle cx="15" cy="12" r="5"/></svg></div>
            <h3><?= t('apropos.valeur3_titre') ?></h3>
            <?= paragraphes('apropos.valeur3_texte') ?>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA -->
    <section class="section section-cta">
      <div class="container" style="text-align: center">
        <h2 style="font-size: 2rem; color: var(--blue-900); margin-bottom: 16px"><?= t('apropos.cta_titre') ?></h2>
        <?= paragraphes('apropos.cta_texte', ' class="cta-texte"') ?>
        <a href="contact.php" class="btn btn-primary"><?= t('apropos.cta_bouton') ?></a>
      </div>
    </section>

<?php require __DIR__ . '/inc/bas.php'; ?>
