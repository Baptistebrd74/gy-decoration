<?php
/**
 * Fin de page commune : pied de page + scripts.
 * $page['lightbox'] = true pour ajouter la visionneuse de photos.
 */
$facebook = brut('global.facebook');
?>
    <!-- FOOTER -->
    <footer class="footer">
      <div class="container">
        <div class="footer-grid">
          <div class="footer-brand">
            <a href="index.php" class="logo"
              ><img src="img/logo-gy.png" alt=""
            /></a>
            <?= paragraphes('global.pied_description') ?>
          </div>
          <div>
            <h4>Navigation</h4>
            <ul>
              <li><a href="index.php">Accueil</a></li>
              <li><a href="services.php">Services</a></li>
              <li><a href="realisations.php">Réalisations</a></li>
              <li><a href="a-propos.php">À propos</a></li>
              <li><a href="contact.php">Contact</a></li>
            </ul>
          </div>
          <div>
            <h4>Contact</h4>
            <ul>
              <li><a href="<?= e(lien_tel(brut('global.telephone'))) ?>"><?= e(brut('global.telephone')) ?></a></li>
              <li><a href="mailto:<?= e(brut('global.email')) ?>"><?= e(brut('global.email')) ?></a></li>
<?php if ($facebook !== ''): ?>
              <li><a href="<?= e($facebook) ?>" target="_blank" rel="noopener">Facebook</a></li>
<?php endif; ?>
              <li><a href="mentions-legales.php">SIREN : <?= e(brut('global.siren')) ?></a></li>
            </ul>
          </div>
        </div>
        <div class="footer-bottom">
          <span>&copy; <?= date('Y') ?> GY-Décoration. Tous droits réservés.</span>
          <span><a href="mentions-legales.php" style="opacity: 0.85">Mentions légales</a></span>
        </div>
      </div>
    </footer>
<?php if (!empty($page['lightbox'])): ?>

    <!-- LIGHTBOX -->
    <dialog class="lightbox" id="lightbox" aria-label="Photo en grand">
      <button class="lightbox-close" type="button" aria-label="Fermer">&times;</button>
      <button class="lightbox-nav lightbox-prev" type="button" aria-label="Photo précédente">&#8249;</button>
      <figure>
        <img src="" alt="" />
        <figcaption></figcaption>
      </figure>
      <button class="lightbox-nav lightbox-next" type="button" aria-label="Photo suivante">&#8250;</button>
    </dialog>
<?php endif; ?>

    <script src="js/main.js"></script>
  </body>
</html>
