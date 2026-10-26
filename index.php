<?php
require __DIR__ . '/inc/app.php';
$page = ['cle' => 'accueil', 'fichier' => 'index.php', 'lightbox' => true];
require __DIR__ . '/inc/haut.php';
?>

    <!-- HERO -->
    <section class="hero">
      <img src="img/logo-gy.png" alt="" class="hero-watermark" />
      <div class="container">
        <div class="hero-content">
          <span class="hero-badge"><?= t('accueil.hero_badge') ?></span>
          <h1><?= t('accueil.hero_titre') ?></h1>
          <?= paragraphes('accueil.hero_texte') ?>
          <div class="hero-buttons">
            <a href="contact.php" class="btn btn-primary"><?= t('accueil.hero_bouton1') ?></a>
            <a href="realisations.php" class="btn btn-outline"><?= t('accueil.hero_bouton2') ?></a>
          </div>
        </div>
        <div class="hero-image">
          <img
            src="img/realisations/sejour-bleu-canard.webp"
            srcset="img/realisations/sejour-bleu-canard-sm.webp 800w, img/realisations/sejour-bleu-canard.webp 1600w"
            sizes="(max-width: 968px) 100vw, 560px"
            alt="Séjour repeint en bleu canard par GY-Décoration"
            width="1600" height="1067"
            fetchpriority="high"
          />
          <span class="hero-image-caption"><?= t('accueil.hero_legende') ?></span>
        </div>
      </div>
    </section>

    <!-- SERVICES APERCU -->
    <section class="section" id="services">
      <div class="container">
        <div class="section-header">
          <h2><?= t('accueil.services_titre') ?></h2>
          <p><?= t('accueil.services_sous_titre') ?></p>
          <div class="line"></div>
        </div>
        <div class="services-grid services-grid-4">
          <div class="service-card">
            <div class="service-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" width="26" height="26"><path d="M3 11.5 12 4l9 7.5"/><path d="M5.5 10v9a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-9"/><path d="M10 20v-6h4v6"/></svg></div>
            <h3><?= t('accueil.service1_titre') ?></h3>
            <?= paragraphes('accueil.service1_texte') ?>
          </div>
          <div class="service-card">
            <div class="service-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" width="26" height="26"><path d="M12 3a9 9 0 1 0 0 18c1.1 0 1.6-.6 1.6-1.4 0-.4-.15-.75-.4-1a1.4 1.4 0 0 1 1-2.4H16a4 4 0 0 0 4-4c0-5-3.6-9.2-8-9.2Z"/><circle cx="7.5" cy="11" r="1"/><circle cx="10.5" cy="7.5" r="1"/><circle cx="15" cy="7.5" r="1"/><circle cx="17" cy="11" r="1"/></svg></div>
            <h3><?= t('accueil.service2_titre') ?></h3>
            <?= paragraphes('accueil.service2_texte') ?>
          </div>
          <div class="service-card">
            <div class="service-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" width="26" height="26"><rect x="3" y="3" width="18" height="18" rx="1"/><path d="M3 8h4M3 13h3M3 18h4M8 3v4M13 3v3M18 3v4"/></svg></div>
            <h3><?= t('accueil.service3_titre') ?></h3>
            <?= paragraphes('accueil.service3_texte') ?>
          </div>
          <div class="service-card">
            <div class="service-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" width="26" height="26"><path d="M3 12a9 9 0 0 1 15.3-6.4L21 8"/><path d="M21 4v4h-4"/><path d="M21 12a9 9 0 0 1-15.3 6.4L3 16"/><path d="M3 20v-4h4"/></svg></div>
            <h3><?= t('accueil.service4_titre') ?></h3>
            <?= paragraphes('accueil.service4_texte') ?>
          </div>
        </div>
      </div>
    </section>

    <!-- REALISATIONS APERCU : les 3 premières photos de la galerie -->
    <section class="section section-alt">
      <div class="container">
        <div class="section-header">
          <h2><?= t('accueil.real_titre') ?></h2>
          <p><?= t('accueil.real_sous_titre') ?></p>
          <div class="line"></div>
        </div>
        <div class="gallery-grid">
<?php foreach (photos(3) as $photo): ?>
          <?= vignette($photo) ?>

<?php endforeach; ?>
        </div>
        <div style="text-align: center; margin-top: 40px">
          <a href="realisations.php" class="btn btn-outline"><?= t('accueil.real_bouton') ?></a>
        </div>
      </div>
    </section>

    <!-- A PROPOS APERCU -->
    <section class="section">
      <div class="container">
        <div class="about-content">
          <div class="about-image">
            <img src="img/realisations/yann-grotte.webp" alt="Yann Grotte, peintre de GY-Décoration, pinceau entre les dents sur un chantier" width="1440" height="1080" loading="lazy" decoding="async" />
          </div>
          <div class="about-text">
            <h2><?= t('accueil.apropos_titre') ?></h2>
            <?= paragraphes('accueil.apropos_texte') ?>
            <a href="a-propos.php" class="btn btn-outline"><?= t('accueil.apropos_bouton') ?></a>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA -->
    <section class="section section-cta">
      <div class="container" style="text-align: center">
        <h2 style="font-size: 2rem; color: var(--blue-900); margin-bottom: 16px"><?= t('accueil.cta_titre') ?></h2>
        <?= paragraphes('accueil.cta_texte', ' class="cta-texte"') ?>
        <a href="contact.php" class="btn btn-primary"><?= t('accueil.cta_bouton') ?></a>
      </div>
    </section>

<?php require __DIR__ . '/inc/bas.php'; ?>
