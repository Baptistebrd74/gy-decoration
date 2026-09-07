<?php
declare(strict_types=1);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit;
}

header('Content-Type: application/json; charset=utf-8');

function nettoyer(string $valeur): string
{
    return trim(str_replace(["\r", "\n"], '', $valeur));
}

// Honeypot : champ invisible que seuls les bots remplissent
if (!empty($_POST['website'] ?? '')) {
    echo json_encode(['success' => true]);
    exit;
}

$nom = nettoyer($_POST['nom'] ?? '');
$prenom = nettoyer($_POST['prenom'] ?? '');
$email = nettoyer($_POST['email'] ?? '');
$telephone = nettoyer($_POST['telephone'] ?? '');
$service = nettoyer($_POST['service'] ?? '');
$message = trim($_POST['message'] ?? '');

$erreurs = [];
if ($nom === '') $erreurs[] = 'nom';
if ($prenom === '') $erreurs[] = 'prenom';
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $erreurs[] = 'email';
if ($telephone === '') $erreurs[] = 'telephone';
if ($message === '') $erreurs[] = 'message';

if (!empty($erreurs)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'errors' => $erreurs]);
    exit;
}

$destinataire = 'gydecoration@gmail.com';
$sujet = "Nouvelle demande de devis - $prenom $nom";

$servicesLabels = [
    'peinture-interieure' => 'Peinture intérieure',
    'mise-en-couleur' => 'Mise en couleur',
    'decoration' => 'Décoration intérieure',
    'preparation' => 'Préparation des supports',
    'rafraichissement' => 'Rafraîchissement',
    'traitement' => 'Traitement des murs',
    'autre' => 'Autre',
];
$serviceLabel = $servicesLabels[$service] ?? 'Non précisé';

$corps = "Nouvelle demande de devis via le site GY-Décoration\n\n"
    . "Nom : $nom\n"
    . "Prénom : $prenom\n"
    . "Email : $email\n"
    . "Téléphone : $telephone\n"
    . "Type de prestation : $serviceLabel\n\n"
    . "Message :\n$message\n";

$domaine = $_SERVER['SERVER_NAME'] ?? 'gy-decoration.fr';
$expediteur = "no-reply@$domaine";

$entetes = "From: GY-Décoration <$expediteur>\r\n"
    . "Reply-To: $email\r\n"
    . "Content-Type: text/plain; charset=UTF-8\r\n";

$envoye = mail($destinataire, $sujet, $corps, $entetes);

if ($envoye) {
    echo json_encode(['success' => true, 'prenom' => $prenom, 'nom' => $nom]);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'errors' => ['server']]);
}
