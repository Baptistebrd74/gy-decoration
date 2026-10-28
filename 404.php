<?php
require __DIR__ . '/inc/app.php';
http_response_code(404);
$page = ['cle' => 'erreur404', 'fichier' => '404.php', 'noindex' => true];
require __DIR__ . '/inc/haut.php';
?>

    <section class="hero" style="padding: 60px 0 40px">
      <div class="container" style="display: block; text-align: center">
        <span class="hero-badge">Erreur 404</span>
        <h1 style="font-size: 2.6rem">Oups, cette page <span class="highlight">n'existe pas</span></h1>
        <p class="page-intro">Le lien est peut-être cassé, ou la page a été déplacée.</p>
        <a href="index.php" class="btn btn-primary">Retour à l'accueil</a>
      </div>
    </section>

<?php require __DIR__ . '/inc/bas.php'; ?>