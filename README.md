# Projet Tempo - Guide de lancement
Ce projet a été réalisé par Noé BRACQ, Elric DUFRESNE, Tino DEVILLERS et Sacha MARTIN dans le cadre de notre SAE3.1 du BUT Informatique à l'IUT de Nantes.

Ce dépôt contient notre application **CodeIgniter 4** et son environnement de conteneurisation basé sur **Podman**.

Tempo est une plateforme de mise en relation directe entre créateurs et acheteurs (C2C). Ce site permettra aux beatmakers de mettre en vente leurs compositions musicales et aux clients de les acquérir pour leurs propres projets.
## 1. Prérequis

Avant de commencer, assurez-vous d'avoir installé les outils suivants :

* **Podman** (CLI sur Linux ou Podman Desktop).
* **Podman Compose**.
* Un compte **Docker.io** (nécessaire pour le téléchargement des images MySQL et PHP).

---

## 2. Lancement du projet (Linux / IUT)

Sur un système Linux déjà configuré (comme à l'IUT), suivez ces étapes pour lancer le projet rapidement :

1. Ouvrez un terminal dans le dossier racine du projet.
2. Accédez au dossier de l'environnement :
```bash
cd contener

```


3. Lancez le script d'installation automatisé :
```bash
./setup.sh

```


*Ce script s'occupe de la connexion à docker.io, du démarrage des conteneurs, du transfert du code, de l'installation des dépendances (Composer) et de la préparation de la base de données*.

### Accès aux services

* **Application :** [http://localhost:8081](https://www.google.com/search?q=http://localhost:8081)
* **BD phpMyAdmin :** [http://localhost:8082](https://www.google.com/search?q=http://localhost:8082)

---

## 3. Identifiants Admin

Accéder à l'interface d'administration via /admin avec le compte :

* **Email :** `admin@tempo.test`
* **Mot de passe :** `admin0`

---

## 4. Résolution des problèmes courants

### Erreur lors du script `./setup.sh`

Si le script échoue au moment de la configuration de la base de données, augmentez le temps d'attente (`sleep`) dans le fichier `setup.sh` pour laisser plus de temps à MySQL pour s'initialiser.

### Erreur d'image (Build)

Si une image ne se charge pas correctement, forcez la reconstruction sans cache :

```bash
podman compose build --no-cache web

```

### Problème de permissions ou dossier `writable`

Si les migrations échouent ou si l'application ne peut pas écrire de fichiers, vous pouvez recréer les dossiers et réinitialiser les permissions manuellement dans le conteneur :

```bash
mkdir -p writable/{cache,logs,session,uploads}
mkdir -p writable/uploads/masters
mkdir -p public/uploads/previews

chown -R www-data:www-data writable public/uploads
chmod -R 775 writable public/uploads

```

### Dossier `vendor` dans Git

Assurez-vous que le dossier `vendor` n'est pas suivi par Git. Si c'est le cas, utilisez la commande suivante pour le retirer du cache sans supprimer les fichiers locaux :

```bash
git rm -r --cached data/CI4/vendor

```

### Pour Lancer le projet manuellement

placez vous a la racine du projet

```bash
cd conteneur
./script/create.sh
./script/push.sh
./script/terminal.sh
```

dans le conteneur
```bash
cd CI4
composer install
mkdir -p writable/cache writable/debugbar writable/logs writable/session writable/uploads
chmod -R 777 writable
mkdir -p public/images/avatars
chmod -R 777 public/images/avatars
mv env .env
php spark migrate
php spark db:seed DatabaseSeeder
```