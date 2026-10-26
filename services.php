<?php
require __DIR__ . '/inc/app.php';
$page = ['cle' => 'services', 'fichier' => 'services.php'];
require __DIR__ . '/inc/haut.php';
?>

    <!-- PAGE HEADER -->
    <section class="hero" style="padding: 60px 0 40px">
      <div class="container" style="display: block; text-align: center">
        <span class="hero-badge"><?= t('services.badge') ?></span>
        <h1 style="font-size: 2.6rem"><?= t('services.titre') ?></h1>
        <?= paragraphes('services.intro', ' class="page-intro"') ?>
      </div>
    </section>

    <!-- SERVICES DETAIL -->
    <section class="section">
      <div class="container">
        <div class="services-grid services-grid-center">
          <div class="service-card">
            <div class="service-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" width="26" height="26"><path d="M3 11.5 12 4l9 7.5"/><path d="M5.5 10v9a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-9"/><path d="M10 20v-6h4v6"/></svg></div>
            <h3><?= t('services.service1_titre') ?></h3>
            <?= paragraphes('services.service1_texte') ?>
          </div>
          <div class="service-card">
            <div class="service-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" width="26" height="26"><path d="M12 3a9 9 0 1 0 0 18c1.1 0 1.6-.6 1.6-1.4 0-.4-.15-.75-.4-1a1.4 1.4 0 0 1 1-2.4H16a4 4 0 0 0 4-4c0-5-3.6-9.2-8-9.2Z"/><circle cx="7.5" cy="11" r="1"/><circle cx="10.5" cy="7.5" r="1"/><circle cx="15" cy="7.5" r="1"/><circle cx="17" cy="11" r="1"/></svg></div>
            <h3><?= t('services.service2_titre') ?></h3>
            <?= paragraphes('services.service2_texte') ?>
          </div>
          <div class="service-card">
            <div class="service-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" width="26" height="26"><rect x="3" y="3" width="18" height="18" rx="1"/><path d="M3 8h4M3 13h3M3 18h4M8 3v4M13 3v3M18 3v4"/></svg></div>
            <h3><?= t('services.service3_titre') ?></h3>
            <?= paragraphes('services.service3_texte') ?>
          </div>
          <div class="service-card">
            <div class="service-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" width="26" height="26"><path d="M3 12a9 9 0 0 1 15.3-6.4L21 8"/><path d="M21 4v4h-4"/><path d="M21 12a9 9 0 0 1-15.3 6.4L3 16"/><path d="M3 20v-4h4"/></svg></div>
            <h3><?= t('services.service4_titre') ?></h3>
            <?= paragraphes('services.service4_texte') ?>
          </div>
          <div class="service-card">
            <div class="service-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" width="26" height="26"><path d="M12 3l7 3v6c0 4.5-3 7.5-7 9-4-1.5-7-4.5-7-9V6l7-3Z"/><path d="M9 12l2 2 4-4"/></svg></div>
            <h3><?= t('services.service5_titre') ?></h3>
            <?= paragraphes('services.service5_texte') ?>
          </div>
        </div>
      </div>
    </section>

    <!-- PREPARATION -->
    <section class="section section-alt">
      <div class="container">
        <div class="about-content">
          <div class="about-image">
            <img src="img/realisations/chantier-preparation.webp" alt="Chantier en cours : bandes et enduits sur plaques de plâtre avant peinture" width="1600" height="1200" loading="lazy" decoding="async" />
          </div>
          <div class="about-text">
            <h2><?= t('services.prep_titre') ?></h2>
            <?= paragraphes('services.prep_texte') ?>
            <a href="realisations.php" class="btn btn-outline"><?= t('services.prep_bouton') ?></a>
          </div>
        </div>
      </div>
    </section>

    <!-- PROCESS -->
    <section class="section">
      <div class="container">
        <div class="section-header">
          <h2><?= t('services.methode_titre') ?></h2>
          <p><?= t('services.methode_sous_titre') ?></p>
          <div class="line"></div>
        </div>
        <div class="services-grid services-grid-4">
<?php for ($i = 1; $i <= 4; $i++): ?>
          <div class="service-card" style="text-align: center">
            <div class="service-icon" style="margin: 0 auto 20px; font-family: 'Fraunces', serif; font-weight: 600">0<?= $i ?></div>
            <h3><?= t("services.etape{$i}_titre") ?></h3>
            <?= paragraphes("services.etape{$i}_texte") ?>
          </div>
<?php endfor; ?>
        </div>
      </div>
    </section>

    <!-- CTA -->
    <section class="section section-cta">
      <div class="container" style="text-align: center">
        <h2 style="font-size: 2rem; color: var(--blue-900); margin-bottom: 16px"><?= t('services.cta_titre') ?></h2>
        <?= paragraphes('services.cta_texte', ' class="cta-texte"') ?>
        <a href="contact.php" class="btn btn-primary"><?= t('services.cta_bouton') ?></a>
      </div>
    </section>

<?php require __DIR__ . '/inc/bas.php'; ?>
