# eq_1_04_BRACQ-Noe_DEVILLERS-Tino_DUFRESNE-Elric_MARTIN-Sacha



## 1. Structure du Projet

La structure est conçue pour être claire et sécurisée. Le dossier /public est le seul point d'entrée exposé au web, garantissant que la logique interne et les fichiers de configuration restent privés.

    /
    ├── public/          <- Point d'entrée web (accessible)
    ├── app/             <- Cœur de l'application (logique métier)
    ├── .htaccess        <- Règles de réécriture d'URL
    ├── composer.json    <- Liste des dépendances
    └── README.md


## 2. Dossiers Critiques Détaillés

### 2.1. 📁 Le Dossier /public (Point d'Entrée)

Le dossier public est le Front Controller de l'application.

Rôle Principal : C'est le seul dossier accessible directement par les utilisateurs via le navigateur. Tous les accès HTTP passent par ce dossier, ce qui garantit que l'application démarre toujours au même endroit.

Sécurité : L'existence de ce dossier assure que les fichiers sensibles (comme la configuration de la base de données, les modèles et les contrôleurs) ne peuvent jamais être lus directement depuis le web.

Contenu Clé :

- index.php : Le fichier d'amorçage initial qui initialise le framework, charge l'autoloader et lance le routage.

- assets/ : Contient toutes les ressources statiques nécessaires à l'affichage :

- css/ : Feuilles de style.

- js/ : Fichiers JavaScript.

- img/ : Images du site et des produits.

### 2.2. 📂 Le Dossier /app (Cœur du MVC)

Le dossier app contient toute la logique de l'application, organisée selon le modèle Modèle-Vue-Contrôleur (MVC).

#### 2.2.1. /app/Controllers/

Les Contrôleurs gèrent la requête de l'utilisateur. Ils reçoivent les données (par exemple, un formulaire soumis), font appel au Modèle pour interagir avec les données, et choisissent quelle Vue afficher en réponse.

Exemples : ProductController.php, CartController.php, OrderController.php.

#### 2.2.2. /app/Models/

Les Modèles représentent la structure des données (les entités de votre e-commerce) et encapsulent la logique d'accès à la base de données.

Exemples : Product.php, User.php, Order.php.

#### 2.2.3. /app/Views/

Les Vues sont responsables de la présentation des données à l'utilisateur. Ce sont généralement des fichiers HTML contenant de petites portions de code PHP pour insérer les données fournies par les Contrôleurs.



#### 2.2.4. /app/Core/

Contient les classes d'infrastructure et d'utilitaires génériques utilisées par l'ensemble de l'application (ex. : la gestion des routes, la connexion à la base de données).


