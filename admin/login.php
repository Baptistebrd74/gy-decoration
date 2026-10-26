<?php
declare(strict_types=1);
require __DIR__ . '/inc/admin.php';

const ESSAIS_MAX = 5;
const FENETRE_ESSAIS = 15 * 60; // 5 essais ratés en 15 minutes => blocage

if (!empty($_SESSION['admin_id'])) {
    rediriger('index.php');
}

$erreur = '';
$identifiant = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifier_csrf();
    $pdo = bdd();
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'inconnue';
    $identifiant = trim((string) ($_POST['identifiant'] ?? ''));
    $motDePasse = (string) ($_POST['mot_de_passe'] ?? '');

    $essais = $pdo->prepare('SELECT COUNT(*) FROM connexions_echouees WHERE ip = ? AND tentee_le > ?');
    $essais->execute([$ip, time() - FENETRE_ESSAIS]);

    if ((int) $essais->fetchColumn() >= ESSAIS_MAX) {
        $erreur = 'Trop d\'essais ratés. Réessaie dans 15 minutes.';
    } else {
        $requete = $pdo->prepare('SELECT id, mot_de_passe FROM admins WHERE identifiant = ?');
        $requete->execute([$identifiant]);
        $admin = $requete->fetch();

        if ($admin && password_verify($motDePasse, $admin['mot_de_passe'])) {
            // Nouvel identifiant de session : empêche de réutiliser une session volée avant la connexion
            session_regenerate_id(true);
            $_SESSION['admin_id'] = (int) $admin['id'];
            $_SESSION['derniere_activite'] = time();
            $pdo->prepare('DELETE FROM connexions_echouees WHERE ip = ?')->execute([$ip]);

            // Si PHP recommande un algorithme plus récent, on met le hash à jour au passage
            if (password_needs_rehash($admin['mot_de_passe'], PASSWORD_DEFAULT)) {
                $pdo->prepare('UPDATE admins SET mot_de_passe = ? WHERE id = ?')
                    ->execute([password_hash($motDePasse, PASSWORD_DEFAULT), $admin['id']]);
            }
            rediriger('index.php');
        }

        $pdo->prepare('INSERT INTO connexions_echouees (ip, tentee_le) VALUES (?, ?)')->execute([$ip, time()]);
        // Ménage : on ne garde que les essais récents
        $pdo->prepare('DELETE FROM connexions_echouees WHERE tentee_le < ?')->execute([time() - 86400]);
        sleep(1); // ralentit les robots qui testent des mots de passe en boucle
        $erreur = 'Identifiant ou mot de passe incorrect.';
    }
}

$titre = 'Connexion';
$sansMenu = true;
require __DIR__ . '/inc/haut.php';
?>
      <form method="post" class="carte carte-connexion">
        <img src="../img/logo-gy.png" alt="GY-Décoration" class="connexion-logo" />
        <h1>Espace administration</h1>
<?php if (isset($_GET['deconnecte'])): ?>
        <p class="flash flash-ok">Tu es bien déconnecté.</p>
<?php endif; ?>
<?php if ($erreur !== ''): ?>
        <p class="flash flash-erreur" role="alert"><?= e($erreur) ?></p>
<?php endif; ?>
        <?= champ_csrf() ?>
        <label for="identifiant">Identifiant</label>
        <input type="text" id="identifiant" name="identifiant" value="<?= e($identifiant) ?>" autocomplete="username" required autofocus />
        <label for="mot_de_passe">Mot de passe</label>
        <input type="password" id="mot_de_passe" name="mot_de_passe" autocomplete="current-password" required />
        <button type="submit" class="bouton bouton-plein">Se connecter</button>
        <a href="../index.php" class="retour-site">← Retour au site</a>
      </form>
<?php require __DIR__ . '/inc/bas.php'; ?>
