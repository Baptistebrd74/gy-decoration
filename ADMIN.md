# Admin GY-Décoration

Yann modifie lui-même les textes, ses coordonnées et la galerie depuis `/admin`.

## Comment ça marche

- Tous les textes du site sont dans la table `contenus` (une ligne = un texte, ex. `accueil.hero_titre`).
- Les photos de la galerie sont dans la table `photos`. Celles envoyées depuis l'admin vont dans `img/galerie/`.
- Les pages `.php` lisent la base via `inc/app.php`, et partagent `inc/haut.php` / `inc/bas.php`.
- L'admin (`admin/`) : connexion, textes par page, galerie, changement de mot de passe.

## En local

```bash
cp config.example.php config.php   # puis passer en SQLite (lignes commentées dans le fichier)
php outils/installer.php           # crée les tables et met les textes/photos de départ
php outils/creer-admin.php         # crée ton compte
php -S localhost:8080              # puis http://localhost:8080/admin
```

## Mise en ligne sur o2switch (une seule fois)

1. **cPanel > Bases de données MySQL** : créer une base, un utilisateur, et lui donner tous les droits sur la base.
2. Envoyer les fichiers du site (sans `config.php` ni `data/`).
3. Sur le serveur, copier `config.example.php` en `config.php` et y mettre le nom de la base, l'utilisateur et le mot de passe.
4. **cPanel > Terminal**, dans le dossier du site :
   ```bash
   php outils/installer.php
   php outils/creer-admin.php
   ```
5. Vérifier que `https://gy-decoration.fr/config.php` et `https://gy-decoration.fr/inc/app.php` renvoient bien une erreur 403 (accès interdit).
6. Donner à Yann l'adresse `https://gy-decoration.fr/admin` et son identifiant. Il pourra changer son mot de passe dans « Mon compte ».

## Rendre un nouveau texte modifiable

1. L'ajouter dans `outils/contenus-par-defaut.php`.
2. L'afficher dans la page avec `t('page.cle')` (texte court) ou `paragraphes('page.cle')` (texte long).
3. Relancer `php outils/installer.php` : il ajoute le nouveau texte sans écraser ceux que Yann a modifiés.

## Sécurité en place

- Mots de passe hashés (`password_hash`), jamais stockés en clair.
- Requêtes préparées partout (pas d'injection SQL), textes échappés à l'affichage (pas d'injection de code).
- Jeton CSRF sur tous les formulaires de l'admin.
- Blocage après 5 mauvais mots de passe en 15 minutes, déconnexion après 2 h d'inactivité.
- Photos : vrai type vérifié dans le fichier, nom généré par le serveur, ré-encodées en WebP, aucun script exécutable dans `img/galerie/`.
- `config.php`, `inc/`, `outils/` et `admin/inc/` sont interdits d'accès depuis le web (`.htaccess`).
