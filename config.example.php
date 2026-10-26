<?php
// Copier ce fichier en config.php et remplir les valeurs.
// config.php contient les identifiants de la base : il n'est JAMAIS commité (voir .gitignore).

return [
    // Adresse du site en ligne, sans / à la fin (liens canoniques, partage sur les réseaux)
    'url_site' => 'https://gy-decoration.fr',

    // En ligne (o2switch) : base MySQL créée depuis cPanel
    'db_dsn' => 'mysql:host=localhost;dbname=NOM_DE_LA_BASE;charset=utf8mb4',
    'db_user' => 'UTILISATEUR',
    'db_pass' => 'MOT_DE_PASSE',

    // En local : une base SQLite dans un simple fichier, aucun serveur à installer
    // 'db_dsn' => 'sqlite:' . __DIR__ . '/data/site.sqlite',
    // 'db_user' => null,
    // 'db_pass' => null,
];
