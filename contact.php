<?php
require __DIR__ . '/inc/app.php';
$page = ['cle' => 'contact', 'fichier' => 'contact.php'];
require __DIR__ . '/inc/haut.php';
$facebook = brut('global.facebook');
?>

    <!-- PAGE HEADER -->
    <section class="hero" style="padding: 60px 0 40px">
      <div class="container" style="display: block; text-align: center">
        <span class="hero-badge"><?= t('contact.badge') ?></span>
        <h1 style="font-size: 2.6rem"><?= t('contact.titre') ?></h1>
        <?= paragraphes('contact.intro', ' class="page-intro"') ?>
      </div>
    </section>

    <!-- CONTACT -->
    <section class="section">
      <div class="container">
        <div class="contact-wrapper">
          <div class="contact-info">
            <h3><?= t('contact.coord_titre') ?></h3>
            <?= paragraphes('contact.coord_texte') ?>

            <div class="contact-detail">
              <div class="icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" width="20" height="20"><path d="M6 3h3l1.5 4-2 1.5a12 12 0 0 0 6 6l1.5-2 4 1.5v3a1.5 1.5 0 0 1-1.6 1.5C11.5 18 6 12.5 5.5 5.6A1.5 1.5 0 0 1 6 3Z"/></svg></div>
              <div class="text">
                <strong>Téléphone</strong>
                <span><a href="<?= e(lien_tel(brut('global.telephone'))) ?>"><?= e(brut('global.telephone')) ?></a></span>
              </div>
            </div>

            <div class="contact-detail">
              <div class="icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" width="20" height="20"><rect x="3" y="5" width="18" height="14" rx="1.5"/><path d="M3.5 6l8.5 7 8.5-7"/></svg></div>
              <div class="text">
                <strong>Email</strong>
                <span><a href="mailto:<?= e(brut('global.email')) ?>"><?= e(brut('global.email')) ?></a></span>
              </div>
            </div>
<?php if ($facebook !== ''): ?>

            <div class="contact-detail">
              <div class="icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" width="20" height="20"><path d="M14 21v-7.5h2.5l.5-3H14V8.8c0-.9.3-1.5 1.6-1.5H17V4.6c-.3 0-1.2-.1-2.3-.1-2.3 0-3.7 1.4-3.7 3.9v2.1H8.5v3H11V21"/></svg></div>
              <div class="text">
                <strong>Facebook</strong>
                <span><a href="<?= e($facebook) ?>" target="_blank" rel="noopener">GY-Décoration</a></span>
              </div>
            </div>
<?php endif; ?>

            <div class="contact-detail">
              <div class="icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" width="20" height="20"><rect x="5" y="3" width="10" height="18"/><path d="M15 8h4v13h-4M8 7h1M11 7h1M8 11h1M11 11h1M8 15h1M11 15h1"/></svg></div>
              <div class="text">
                <strong>SIREN</strong>
                <span><?= e(brut('global.siren')) ?></span>
              </div>
            </div>
          </div>

          <!-- FORMULAIRE DE DEVIS -->
          <form class="contact-form" id="contactForm">
            <!-- Honeypot anti-spam : champ invisible, ignoré par les vrais visiteurs -->
            <div style="position: absolute; left: -9999px" aria-hidden="true">
              <label for="website">Ne pas remplir ce champ</label>
              <input type="text" id="website" name="website" tabindex="-1" autocomplete="off" />
            </div>

            <h3 style="font-size: 1.3rem; font-weight: 700; color: var(--blue-900); margin-bottom: 24px"><?= t('contact.form_titre') ?></h3>

            <div class="form-row">
              <div class="form-group">
                <label for="nom">Nom *</label>
                <input type="text" id="nom" name="nom" placeholder="Votre nom" required />
              </div>
              <div class="form-group">
                <label for="prenom">Prénom *</label>
                <input type="text" id="prenom" name="prenom" placeholder="Votre prénom" required />
              </div>
            </div>

            <div class="form-row">
              <div class="form-group">
                <label for="email">Email *</label>
                <input type="email" id="email" name="email" placeholder="votre@email.com" required />
              </div>
              <div class="form-group">
                <label for="telephone">Téléphone *</label>
                <input type="tel" id="telephone" name="telephone" placeholder="06 XX XX XX XX" required />
              </div>
            </div>

            <div class="form-group">
              <label for="service">Type de prestation</label>
              <select id="service" name="service">
                <option value="">-- Sélectionnez un service --</option>
                <option value="peinture-interieure">Peinture intérieure</option>
                <option value="mise-en-couleur">Mise en couleur</option>
                <option value="preparation">Préparation des supports</option>
                <option value="rafraichissement">Rafraîchissement</option>
                <option value="traitement">Traitement des murs</option>
                <option value="autre">Autre</option>
              </select>
            </div>

            <div class="form-group">
              <label for="message">Décrivez votre projet *</label>
              <textarea id="message" name="message" placeholder="Décrivez votre projet, les surfaces concernées, vos contraintes de délai..." required></textarea>
            </div>

            <button type="submit" class="form-submit"><?= t('contact.form_bouton') ?></button>
            <p style="font-size: 0.8rem; color: var(--gray-400); margin-top: 12px; text-align: center"><?= t('contact.form_note') ?></p>
            <p class="form-rgpd">
              Les informations saisies sont utilisées uniquement par
              GY-Décoration pour répondre à votre demande de devis. Elles ne
              sont jamais transmises à des tiers. Vous pouvez y accéder, les
              rectifier ou les faire supprimer à tout moment :
              <a href="mentions-legales.php#confidentialite">en savoir plus</a>.
            </p>
          </form>
        </div>
      </div>
    </section>

<?php require __DIR__ . '/inc/bas.php'; ?>
