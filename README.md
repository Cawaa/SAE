# eq_1_04_BRACQ-Noe_DEVILLERS-Tino_DUFRESNE-Elric_MARTIN-Sacha




Ce dépôt contient l'application **CodeIgniter 4 (CI4)** et l'environnement de conteneurisation basé sur **Podman** pour le développement.

L'environnement comprend trois services :

1.  **php** (`web`): PHP 8.4 + Apache (avec Composer, CI4 extensions, etc.).
2.  **mysql8** (`mysql`): Base de données MySQL 8.0.
3.  **phpmyadmin**: Interface de gestion pour MySQL.

-----

## 1\. 📂 Structure du Projet

Votre dépôt Git doit être organisé comme suit pour que les scripts de synchronisation fonctionnent correctement. Notez que le nom du dossier d'environnement (ici `contener`) est renommé selon votre souhait.

```
Votre-Dépôt-Git/
├── contener/                    
│   ├── app_php/
│   ├── scripts/
│   ├── compose.yaml
│   └── data/
│       ├── CI4/                 <-- 🚨 Le répertoire racine de votre application CodeIgniter
│       │   ├── app/             (Logique métier)
│       │   ├── public/          (DocumentRoot d'Apache)
│       │   └── ...
│       ├── phpinfo.php
│       └── test_connexion.php
└── README.md
```

### 🚨 Important

  * **Toutes les modifications du code CI4** doivent se faire dans le dossier local `data/CI4/`.
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
| **Test de connexion** | `http://localhost:8080/test_connexion.php` | Vérification de la connexion `php` au service `mysql`. |
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