<?php
declare(strict_types=1);
require __DIR__ . '/inc/admin.php';
require __DIR__ . '/inc/images.php';
exiger_connexion();

$pdo = bdd();

// Vérifie titre / description / catégorie, renvoie [valeurs propres, message d'erreur ou '']
function lire_infos_photo(): array
{
    $titre = trim((string) ($_POST['titre'] ?? ''));
    $description = trim((string) ($_POST['description'] ?? ''));
    $categorie = (string) ($_POST['categorie'] ?? '');

    $erreur = match (true) {
        $titre === '' => 'Le titre est obligatoire.',
        mb_strlen($titre) > 100 => 'Titre trop long (100 caractères maximum).',
        mb_strlen($description) > 200 => 'Commentaire trop long (200 caractères maximum).',
        !isset(CATEGORIES[$categorie]) => 'Choisis une catégorie.',
        default => '',
    };
    return [[$titre, $description, $categorie], $erreur];
}

// Renumérote l'ordre des photos : 0, 1, 2… selon la liste d'id donnée
function enregistrer_ordre(PDO $pdo, array $ids): void
{
    $maj = $pdo->prepare('UPDATE photos SET ordre = ? WHERE id = ?');
    foreach (array_values($ids) as $position => $id) {
        $maj->execute([$position, $id]);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Un envoi plus gros que post_max_size arrive complètement vide, jeton CSRF compris
    if (empty($_POST) && (int) ($_SERVER['CONTENT_LENGTH'] ?? 0) > 0) {
        flash('erreur', 'La photo est trop lourde pour le serveur (20 Mo maximum).');
        rediriger('galerie.php');
    }
    verifier_csrf();
    $action = (string) ($_POST['action'] ?? '');
    $id = (int) ($_POST['id'] ?? 0);

    if ($action === 'ajouter') {
        [$infos, $erreur] = lire_infos_photo();
        try {
            if ($erreur !== '') {
                throw new RuntimeException($erreur);
            }
            [$fichier, $largeur, $hauteur] = enregistrer_photo($_FILES['photo'] ?? []);
            // La nouvelle photo passe en premier (donc aussi sur l'accueil)
            $premier = (int) $pdo->query('SELECT COALESCE(MIN(ordre), 0) FROM photos')->fetchColumn();
            $pdo->prepare('INSERT INTO photos (fichier, titre, description, categorie, largeur, hauteur, ordre, cree_le) VALUES (?, ?, ?, ?, ?, ?, ?, ?)')
                ->execute([$fichier, ...$infos, $largeur, $hauteur, $premier - 1, date('Y-m-d H:i:s')]);
            flash('ok', 'Photo ajoutée ! Elle est en premier dans la galerie.');
        } catch (RuntimeException $exception) {
            flash('erreur', $exception->getMessage());
            $_SESSION['saisie_photo'] = $infos; // pour ne pas tout retaper
        }
    } elseif ($action === 'modifier') {
        [$infos, $erreur] = lire_infos_photo();
        if ($erreur !== '') {
            flash('erreur', $erreur);
        } else {
            $pdo->prepare('UPDATE photos SET titre = ?, description = ?, categorie = ? WHERE id = ?')->execute([...$infos, $id]);
            flash('ok', 'Photo mise à jour.');
        }
    } elseif ($action === 'supprimer') {
        $requete = $pdo->prepare('SELECT fichier FROM photos WHERE id = ?');
        $requete->execute([$id]);
        if ($fichier = $requete->fetchColumn()) {
            $pdo->prepare('DELETE FROM photos WHERE id = ?')->execute([$id]);
            supprimer_fichiers_photo($fichier);
            flash('ok', 'Photo supprimée.');
        }
    } elseif ($action === 'monter' || $action === 'descendre') {
        $ids = array_column(photos(), 'id');
        $position = array_search($id, $ids);
        $voisin = $action === 'monter' ? $position - 1 : $position + 1;
        if ($position !== false && isset($ids[$voisin])) {
            [$ids[$position], $ids[$voisin]] = [$ids[$voisin], $ids[$position]];
            enregistrer_ordre($pdo, $ids);
        }
    }
    rediriger('galerie.php' . ($id ? '#photo-' . $id : ''));
}

[$saisieTitre, $saisieDescription, $saisieCategorie] = $_SESSION['saisie_photo'] ?? ['', '', ''];
unset($_SESSION['saisie_photo']);
$photos = photos();

$titre = 'Galerie';
$actif = 'galerie';
require __DIR__ . '/inc/haut.php';
?>
      <div class="entete-page">
        <h1>Galerie</h1>
        <a href="../realisations.php" target="_blank" rel="noopener" class="bouton">Voir la page ↗</a>
      </div>
      <p class="intro">Les 3 premières photos s'affichent aussi sur l'accueil. Utilise les flèches pour changer l'ordre.</p>

      <form method="post" enctype="multipart/form-data" class="carte ajout-photo">
        <h2>Ajouter une photo</h2>
        <?= champ_csrf() ?>
        <input type="hidden" name="action" value="ajouter" />
        <div class="champ">
          <label for="photo">Photo (JPG, PNG ou WebP, 20 Mo max.)</label>
          <input type="file" id="photo" name="photo" accept="image/jpeg,image/png,image/webp" required />
        </div>
        <div class="champs-ligne">
          <div class="champ">
            <label for="titre">Titre</label>
            <input type="text" id="titre" name="titre" maxlength="100" value="<?= e($saisieTitre) ?>" placeholder="Ex. : Salon vert sauge" required />
          </div>
          <div class="champ">
            <label for="description">Commentaire</label>
            <input type="text" id="description" name="description" maxlength="200" value="<?= e($saisieDescription) ?>" placeholder="Ex. : Peinture murs et plafond" />
          </div>
          <div class="champ">
            <label for="categorie">Catégorie</label>
            <select id="categorie" name="categorie" required>
<?php foreach (CATEGORIES as $code => $libelle): ?>
              <option value="<?= e($code) ?>"<?= $code === $saisieCategorie ? ' selected' : '' ?>><?= e($libelle) ?></option>
<?php endforeach; ?>
            </select>
          </div>
        </div>
        <button type="submit" class="bouton bouton-plein">Ajouter à la galerie</button>
      </form>

      <h2 class="titre-section"><?= count($photos) ?> photo<?= count($photos) > 1 ? 's' : '' ?> en ligne</h2>
      <ol class="liste-photos">
<?php foreach ($photos as $position => $photo): ?>
        <li class="carte photo" id="photo-<?= (int) $photo['id'] ?>">
          <img src="../<?= e($photo['fichier']) ?>-sm.webp" alt="" loading="lazy" />
          <form method="post" class="photo-infos">
            <?= champ_csrf() ?>
            <input type="hidden" name="action" value="modifier" />
            <input type="hidden" name="id" value="<?= (int) $photo['id'] ?>" />
            <label>Titre <input type="text" name="titre" maxlength="100" value="<?= e($photo['titre']) ?>" required /></label>
            <label>Commentaire <input type="text" name="description" maxlength="200" value="<?= e($photo['description']) ?>" /></label>
            <label>Catégorie
              <select name="categorie">
<?php foreach (CATEGORIES as $code => $libelle): ?>
                <option value="<?= e($code) ?>"<?= $code === $photo['categorie'] ? ' selected' : '' ?>><?= e($libelle) ?></option>
<?php endforeach; ?>
              </select>
            </label>
            <button type="submit" class="bouton">Enregistrer</button>
          </form>
          <form method="post" class="photo-actions">
            <?= champ_csrf() ?>
            <input type="hidden" name="id" value="<?= (int) $photo['id'] ?>" />
            <button type="submit" name="action" value="monter" class="bouton bouton-icone" title="Monter" aria-label="Monter « <?= e($photo['titre']) ?> »"<?= $position === 0 ? ' disabled' : '' ?>>↑</button>
            <button type="submit" name="action" value="descendre" class="bouton bouton-icone" title="Descendre" aria-label="Descendre « <?= e($photo['titre']) ?> »"<?= $position === count($photos) - 1 ? ' disabled' : '' ?>>↓</button>
            <button type="submit" name="action" value="supprimer" class="bouton bouton-danger" data-confirmer="Supprimer « <?= e($photo['titre']) ?> » de la galerie ?">Supprimer</button>
          </form>
<?php if ($position < 3): ?>
          <span class="pastille">Sur l'accueil</span>
<?php endif; ?>
        </li>
<?php endforeach; ?>
      </ol>
<?php require __DIR__ . '/inc/bas.php'; ?>
