<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>TEMPO - <?= $title ?? 'Accueil' ?></title>
    <style>
    body { 
        font-family: sans-serif; 
        margin: 0; 
        padding: 0;
        
        /* --- DÉBUT DE LA CONFIGURATION IMAGE DE FOND --- */
        /* Charge l'image depuis le dossier public/images/ */
        background-image: url('<?= base_url("images/image_accueil.jpg") ?>');
        
        /* L'image prend toute la largeur et la hauteur de l'écran */
        background-size: cover; 
        
        /* L'image est centrée */
        background-position: center center;
        
        /* L'image reste fixe (ne bouge pas) quand on scrolle */
        background-attachment: fixed;
        
        /* Assure que le fond ne se répète pas */
        background-repeat: no-repeat;
        
        /* Assure que le fond prend au moins la hauteur de l'écran */
        min-height: 100vh;
        /* --- FIN DE LA CONFIGURATION --- */
    }

    nav { 
        display: flex; 
        justify-content: space-between; 
        align-items: center; 
        padding: 15px 30px; 
        background: rgba(51, 51, 51, 0.9); /* Légère transparence sur le menu pour le style */
        color: white; 
    }
    .nav-left { flex: 1; }
    .nav-center { flex: 1; text-align: center; font-size: 1.5rem; font-weight: bold; letter-spacing: 2px; }
    .nav-right { flex: 1; text-align: right; }
    nav a { color: white; text-decoration: none; margin-left: 15px; }
    
    .container { 
        padding: 40px; 
        max-width: 1000px; 
        margin: 50px auto; 
        
        /* Ajout d'un fond blanc semi-transparent pour lire le texte par-dessus l'image */
        background-color: rgba(255, 255, 255, 0.85); 
        border-radius: 10px;
        box-shadow: 0 0 20px rgba(0,0,0,0.3);
    }
    .error { color: red; }
</style>
</head>
<body>
    <nav>
        <div class="nav-left">
            <a href="<?= base_url('/') ?>">Accueil</a>
            <a href="<?= base_url('/artistes') ?>">Artistes</a>
            <a href="<?= base_url('/boutique') ?>">Boutique</a>
        </div>
        <div class="nav-center">TEMPO</div>
        <div class="nav-right">
            <?php if(session()->get('isLoggedIn')): ?>
                <span>Bonjour, <?= session()->get('username') ?></span>
                <a href="<?= base_url('logout') ?>">Déconnexion</a>
            <?php else: ?>
                <a href="<?= base_url('login') ?>">Connexion</a>
                <a href="<?= base_url('register') ?>">S'inscrire</a>
            <?php endif; ?>
        </div>
    </nav>

    <div class="container">
        <?= $this->renderSection('content') ?>
    </div>
</body>
</html>