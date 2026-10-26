<?php
declare(strict_types=1);

// Traitement des photos envoyées depuis l'admin.

const TAILLE_MAX_ENVOI = 20 * 1024 * 1024; // 20 Mo (les photos de téléphone font souvent 5 à 10 Mo)
const DOSSIER_GALERIE = 'img/galerie';

/**
 * Vérifie la photo envoyée, puis crée deux versions WebP : grande (1600 px) et vignette (800 px).
 * Renvoie [chemin sans extension, largeur vignette, hauteur vignette].
 * Lance une RuntimeException avec un message lisible par Yann en cas de problème.
 */
function enregistrer_photo(array $fichier): array
{
    $erreur = $fichier['error'] ?? UPLOAD_ERR_NO_FILE;
    if ($erreur === UPLOAD_ERR_NO_FILE) {
        throw new RuntimeException('Choisis une photo à envoyer.');
    }
    if (in_array($erreur, [UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE], true) || $fichier['size'] > TAILLE_MAX_ENVOI) {
        throw new RuntimeException('La photo est trop lourde (20 Mo maximum).');
    }
    if ($erreur !== UPLOAD_ERR_OK || !is_uploaded_file($fichier['tmp_name'])) {
        throw new RuntimeException('L\'envoi de la photo a échoué, réessaie.');
    }

    // Le vrai type du fichier, lu dans son contenu : on ne fait pas confiance au nom ".jpg"
    $type = (new finfo(FILEINFO_MIME_TYPE))->file($fichier['tmp_name']);
    ini_set('memory_limit', '512M'); // une photo de 12 Mpx prend ~50 Mo une fois décompressée
    $image = match ($type) {
        'image/jpeg' => @imagecreatefromjpeg($fichier['tmp_name']),
        'image/png' => @imagecreatefrompng($fichier['tmp_name']),
        'image/webp' => @imagecreatefromwebp($fichier['tmp_name']),
        default => throw new RuntimeException('Format non accepté : envoie une photo JPG, PNG ou WebP.'),
    };
    if (!$image) {
        throw new RuntimeException('Cette image est illisible ou abîmée.');
    }
    if ($type === 'image/jpeg') {
        $image = redresser($image, $fichier['tmp_name']);
    }

    $dossier = RACINE . '/' . DOSSIER_GALERIE;
    if (!is_dir($dossier) && !mkdir($dossier, 0755, true)) {
        throw new RuntimeException('Impossible de créer le dossier des photos sur le serveur.');
    }

    // Nom de fichier généré : jamais celui envoyé (il pourrait contenir n'importe quoi)
    $base = DOSSIER_GALERIE . '/' . date('Ymd') . '-' . bin2hex(random_bytes(5));
    redimensionner($image, 1600, RACINE . "/$base.webp", 82);
    [$largeur, $hauteur] = redimensionner($image, 800, RACINE . "/$base-sm.webp", 78);

    return [$base, $largeur, $hauteur];
}

// Les téléphones enregistrent parfois la photo "couchée" avec une info de rotation (EXIF) : on l'applique
function redresser(GdImage $image, string $chemin): GdImage
{
    if (!function_exists('exif_read_data')) {
        return $image;
    }
    $exif = @exif_read_data($chemin);
    $angle = match ((int) ($exif['Orientation'] ?? 1)) {
        3 => 180,
        6 => -90,
        8 => 90,
        default => 0,
    };
    return $angle ? imagerotate($image, $angle, 0) : $image;
}

// Réduit l'image pour que son plus grand côté fasse $max px (sans jamais l'agrandir), puis l'enregistre en WebP
function redimensionner(GdImage $source, int $max, string $destination, int $qualite): array
{
    $largeur = imagesx($source);
    $hauteur = imagesy($source);
    $ratio = min(1, $max / max($largeur, $hauteur));
    $nouvelleLargeur = max(1, (int) round($largeur * $ratio));
    $nouvelleHauteur = max(1, (int) round($hauteur * $ratio));

    $image = imagecreatetruecolor($nouvelleLargeur, $nouvelleHauteur);
    imagealphablending($image, false); // garde la transparence des PNG
    imagesavealpha($image, true);
    imagecopyresampled($image, $source, 0, 0, 0, 0, $nouvelleLargeur, $nouvelleHauteur, $largeur, $hauteur);

    if (!imagewebp($image, $destination, $qualite)) {
        throw new RuntimeException('Impossible d\'enregistrer la photo sur le serveur.');
    }
    return [$nouvelleLargeur, $nouvelleHauteur];
}

// Supprime les fichiers d'une photo, seulement s'ils ont été envoyés par l'admin
function supprimer_fichiers_photo(string $base): void
{
    if (!preg_match('~^' . DOSSIER_GALERIE . '/[\w-]+$~', $base)) {
        return; // photo d'origine du site (img/realisations) : on garde le fichier
    }
    foreach (["$base.webp", "$base-sm.webp"] as $fichier) {
        if (is_file(RACINE . '/' . $fichier)) {
            unlink(RACINE . '/' . $fichier);
        }
    }
}
