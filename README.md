# Avant de push 
## Toujours vérifier que le dossier vendor n'est pas listé dans les fichiers commited dans la commande git status

Si c'est le cas faire `git rm -r --cached data/CI4/vendor`

## Pour lancer le projet :
1. Si ce n'est pas fait copier coller le fichier `env` et le renommer `.env` dans data/CI4 et décommentez les lignes comme suivant (si cela n'est pas fait) : 

```
#--------------------------------------------------------------------
# DATABASE
#--------------------------------------------------------------------

database.default.hostname = mysql
database.default.database = tp
database.default.username = user
database.default.password = pass
database.default.DBDriver = MySQLi
database.default.DBPrefix =
database.default.port = 3306
```
2. Lancer le script create.sh
3. Lancer le script push.sh
4. Lancer le script terminal.sh
5. Une fois dans le terminal aller dans le dossier `CI4`
6. Taper la commande `composer install`
(on utilise pas shield mais un auth maison)
7. Taper la commande `php spark migrate`
8. Taper la commande `php spark db:seed DatabaseSeeder`

Si la commande php spark migrate ne passe pas, c'est sans doute parce que writable n'est pas crée automatiquement. Pour créer writable et tout ce dont ont à besoin dedans (dans le terminal du conteneur): 
`mkdir -p writable/{cache,logs,session,uploads}`
`mkdir -p writable/uploads/{masters,previews}`
`touch writable/index.html`
`chown -R www-data:www-data writable`
`chmod -R 775 writable`

Ce dépôt contient l'application **CodeIgniter 4 (CI4)** et l'environnement de conteneurisation basé sur **Podman** pour le développement.

L'environnement comprend trois services :

1.  **php** (`web`): PHP 8.4 + Apache (avec Composer, CI4 extensions, etc.).
2.  **mysql8** (`mysql`): Base de données MySQL 8.0.
3.  **phpmyadmin**: Interface de gestion pour MySQL.

-----


### 🚨 Important

  * **Toutes les modifications du code CI4** doivent se faire dans les dossiers du conteneur `/var/www/html/CI4/`.
  * Il faut push au début et pull à la fin d'une session de travail.
  * Le dossier `data/CI4/` correspond à `/var/www/html/CI4/` à l'intérieur du conteneur.

-----

## 2\. ⚙️ Prérequis et Configuration Initiale (🚨Windows)

1.  **Installation de Podman :** Installez **Podman Desktop** sur Windows (ou Podman CLI sur Linux).
2.  **Machine Podman :** Démarrez la machine virtuelle Podman (une seule fois par session) :
    ```bash
    podman machine start
    ```
3.  **Outil Compose :** Assurez-vous que l'outil Compose (`podman compose`) est installé (souvent via Podman Desktop ou `pip` sur Linux).

-----

## 3\. 🛠️ Lancement et Workflow (Windows/Git Bash & Fedora/Linux)

### A. Démarrage de l'Environnement

Placez-vous à la racine du dossier d'environnement (là où se trouve `compose.yaml` et le dossier `scripts/`).

```bash
cd contener

# Lancer la construction et le démarrage des conteneurs
./scripts/create.sh
```

### B. Accès aux Services

| Service | Accès | Description |
| :--- | :--- | :--- |
| **CodeIgniter 4** | `http://localhost:8081` | Le Virtual Host CodeIgniter. |
| **phpMyAdmin** | `http://localhost:8082` | Gestion de la base de données. |

### C. Workflow de Développement (Synchronisation du Code)

Après avoir modifié vos fichiers **localement**, vous devez les transférer au conteneur.

  * **Transférer le code** vers le conteneur et mettre à jour les permissions :
    ```bash
    ./scripts/push.sh
    ```
  * **Récupérer le code**  :
    ```bash
    ./scripts/pull.sh
    ```

### D. Accès au Terminal du Conteneur

Le script a été modifié pour fonctionner sous Git Bash (`MSYS_NO_PATHCONV=1`).

```bash
# Ouvre un terminal dans le conteneur 'php'
./scripts/terminal.sh
```



### E. Arrêt de l'Environnement

```bash
./scripts/down.sh
```