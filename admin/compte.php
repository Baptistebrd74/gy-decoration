<?php
declare(strict_types=1);
require __DIR__ . '/inc/admin.php';
exiger_connexion();

$erreur = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifier_csrf();
    $pdo = bdd();
    $requete = $pdo->prepare('SELECT mot_de_passe FROM admins WHERE id = ?');
    $requete->execute([$_SESSION['admin_id']]);
    $hashActuel = (string) $requete->fetchColumn();

    $actuel = (string) ($_POST['actuel'] ?? '');
    $nouveau = (string) ($_POST['nouveau'] ?? '');

    $erreur = match (true) {
        !password_verify($actuel, $hashActuel) => 'Le mot de passe actuel n\'est pas le bon.',
        mb_strlen($nouveau) < 12 => 'Le nouveau mot de passe doit faire au moins 12 caractères.',
        $nouveau !== ($_POST['confirmation'] ?? '') => 'Les deux nouveaux mots de passe sont différents.',
        default => '',
    };

    if ($erreur === '') {
        $pdo->prepare('UPDATE admins SET mot_de_passe = ? WHERE id = ?')
            ->execute([password_hash($nouveau, PASSWORD_DEFAULT), $_SESSION['admin_id']]);
        session_regenerate_id(true);
        flash('ok', 'Mot de passe changé.');
        rediriger('compte.php');
    }
}

$titre = 'Mon compte';
$actif = 'compte';
require __DIR__ . '/inc/haut.php';
?>
      <h1>Mon compte</h1>
      <form method="post" class="carte formulaire-etroit">
        <h2>Changer le mot de passe</h2>
<?php if ($erreur !== ''): ?>
        <p class="flash flash-erreur" role="alert"><?= e($erreur) ?></p>
<?php endif; ?>
        <?= champ_csrf() ?>
        <div class="champ">
          <label for="actuel">Mot de passe actuel</label>
          <input type="password" id="actuel" name="actuel" autocomplete="current-password" required />
        </div>
        <div class="champ">
          <label for="nouveau">Nouveau mot de passe (12 caractères minimum)</label>
          <input type="password" id="nouveau" name="nouveau" minlength="12" autocomplete="new-password" required />
        </div>
        <div class="champ">
          <label for="confirmation">Nouveau mot de passe, encore une fois</label>
          <input type="password" id="confirmation" name="confirmation" minlength="12" autocomplete="new-password" required />
        </div>
        <button type="submit" class="bouton bouton-plein">Changer le mot de passe</button>
      </form>
<?php require __DIR__ . '/inc/bas.php'; ?>
