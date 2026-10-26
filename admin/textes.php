<?php
declare(strict_types=1);
require __DIR__ . '/inc/admin.php';
exiger_connexion();

const LONGUEUR_MAX = 5000;

// Page du site à afficher pour vérifier le résultat
const APERCU = [
    'global' => 'contact.php',
    'accueil' => 'index.php',
    'services' => 'services.php',
    'realisations' => 'realisations.php',
    'apropos' => 'a-propos.php',
    'contact' => 'contact.php',
    'mentions' => 'mentions-legales.php',
];

$code = (string) ($_GET['page'] ?? 'global');
if (!isset(PAGES_TEXTES[$code])) {
    rediriger('textes.php');
}

$pdo = bdd();
$requete = $pdo->prepare('SELECT cle, section, libelle, type, valeur FROM contenus WHERE page = ? ORDER BY ordre');
$requete->execute([$code]);
$champs = $requete->fetchAll();

// Vérifie une valeur selon son type, renvoie un message d'erreur ou '' si tout va bien
function erreur_champ(array $champ, string $valeur): string
{
    if (mb_strlen($valeur) > LONGUEUR_MAX) {
        return 'Texte trop long (' . LONGUEUR_MAX . ' caractères maximum).';
    }
    return match ($champ['type']) {
        'email' => filter_var($valeur, FILTER_VALIDATE_EMAIL) ? '' : 'Adresse email invalide.',
        'url' => $valeur === '' || (filter_var($valeur, FILTER_VALIDATE_URL) && str_starts_with($valeur, 'https://'))
            ? '' : 'Le lien doit commencer par https://',
        'tel' => preg_match('/^\+?[\d .]{10,20}$/', $valeur) ? '' : 'Numéro de téléphone invalide.',
        default => '',
    };
}

$erreurs = [];
$valeurs = array_column($champs, 'valeur', 'cle');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifier_csrf();
    $envoyes = $_POST['contenus'] ?? [];

    foreach ($champs as $champ) {
        $valeur = trim(str_replace("\r\n", "\n", (string) ($envoyes[$champ['cle']] ?? '')));
        if ($champ['type'] !== 'paragraphe') {
            $valeur = str_replace("\n", ' ', $valeur); // une seule ligne
        }
        $valeurs[$champ['cle']] = $valeur;
        if ($erreur = erreur_champ($champ, $valeur)) {
            $erreurs[$champ['cle']] = $erreur;
        }
    }

    if (!$erreurs) {
        // Tout ou rien : si une mise à jour échoue, aucune n'est gardée
        $pdo->beginTransaction();
        $maj = $pdo->prepare('UPDATE contenus SET valeur = ? WHERE cle = ?');
        foreach ($valeurs as $cle => $valeur) {
            $maj->execute([$valeur, $cle]);
        }
        $pdo->commit();
        flash('ok', 'C\'est enregistré ! Les changements sont déjà en ligne.');
        rediriger('textes.php?page=' . $code);
    }
}

// Regroupe les champs par section pour l'affichage
$sections = [];
foreach ($champs as $champ) {
    $sections[$champ['section']][] = $champ;
}

$titre = 'Textes · ' . PAGES_TEXTES[$code];
$actif = 'textes';
require __DIR__ . '/inc/haut.php';
?>
      <nav class="onglets" aria-label="Pages du site">
<?php foreach (PAGES_TEXTES as $autre => $libelle): ?>
        <a href="textes.php?page=<?= $autre ?>"<?= $autre === $code ? ' class="actif" aria-current="page"' : '' ?>><?= e($libelle) ?></a>
<?php endforeach; ?>
      </nav>

      <div class="entete-page">
        <h1><?= e(PAGES_TEXTES[$code]) ?></h1>
        <a href="../<?= APERCU[$code] ?>" target="_blank" rel="noopener" class="bouton">Voir la page ↗</a>
      </div>

<?php if ($erreurs): ?>
      <p class="flash flash-erreur" role="alert">Rien n'a été enregistré : corrige les champs en rouge.</p>
<?php endif; ?>

      <form method="post">
        <?= champ_csrf() ?>
<?php foreach ($sections as $section => $champsSection): ?>
        <fieldset class="carte">
          <legend><?= e($section) ?></legend>
<?php foreach ($champsSection as $champ):
    $id = 'champ-' . str_replace('.', '-', $champ['cle']);
    $nom = 'contenus[' . e($champ['cle']) . ']';
    $valeur = $valeurs[$champ['cle']];
    $erreur = $erreurs[$champ['cle']] ?? '';
?>
          <div class="champ<?= $erreur ? ' champ-erreur' : '' ?>">
            <label for="<?= $id ?>"><?= e($champ['libelle']) ?></label>
<?php if ($champ['type'] === 'paragraphe'): ?>
            <textarea id="<?= $id ?>" name="<?= $nom ?>" rows="<?= max(3, min(14, (int) ceil(mb_strlen($valeur) / 80) + substr_count($valeur, "\n"))) ?>"><?= e($valeur) ?></textarea>
<?php else: ?>
            <input type="<?= ['email' => 'email', 'url' => 'url', 'tel' => 'tel'][$champ['type']] ?? 'text' ?>" id="<?= $id ?>" name="<?= $nom ?>" value="<?= e($valeur) ?>" />
<?php endif; ?>
<?php if ($erreur): ?>
            <p class="message-erreur"><?= e($erreur) ?></p>
<?php endif; ?>
          </div>
<?php endforeach; ?>
        </fieldset>
<?php endforeach; ?>
        <div class="barre-enregistrer">
          <button type="submit" class="bouton bouton-plein">Enregistrer les modifications</button>
        </div>
      </form>
<?php require __DIR__ . '/inc/bas.php'; ?>
