<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>TEMPO - <?= $title ?? 'Accueil' ?></title>
    <style>
        body { 
            font-family: 'Arial', sans-serif; 
            margin: 0; 
            padding: 0; 
            /* Image de fond fixe qui reste en place pendant le scroll */
            background-image: url('<?= base_url("images/image_accueil.jpg") ?>');
            background-size: cover; 
            background-position: center center;
            background-attachment: fixed;
            background-repeat: no-repeat;
            min-height: 100vh;
        }

        /* Navigation fixe en haut pour rester visible */
        nav { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            padding: 15px 50px; 
            background: white;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .nav-left a, .nav-right a { color: #333; text-decoration: none; margin: 0 15px; font-weight: 500; }
        .nav-center { font-size: 1.8rem; font-weight: 900; letter-spacing: 3px; }

        .main-content { width: 100%; }
    </style>
</head>
<body>
    <nav>
        <div class="nav-left">
            <a href="<?= base_url('/') ?>">Home</a>
            <a href="<?= base_url('/artistes') ?>">Artistes</a>
            <a href="<?= base_url('/boutique') ?>">Boutique</a>
        </div>
        <div class="nav-center">TEMPO</div>
        <div class="nav-right">
            <a href="<?= base_url('login') ?>">Connexion</a>
            
        </div>
    </nav>

    <div class="main-content">
        <?= $this->renderSection('content') ?>
    </div>
</body>
</html>