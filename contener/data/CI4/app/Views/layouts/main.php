<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>TEMPO - <?= $title ?? 'Accueil' ?></title>
    <style>
        body { font-family: sans-serif; margin: 0; padding: 0; }
        nav { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            padding: 15px 30px; 
            background: #333; 
            color: white; 
        }
        .nav-left { flex: 1; }
        .nav-center { flex: 1; text-align: center; font-size: 1.5rem; font-weight: bold; letter-spacing: 2px; }
        .nav-right { flex: 1; text-align: right; }
        nav a { color: white; text-decoration: none; margin-left: 15px; }
        .container { padding: 20px; max-width: 1000px; margin: auto; }
        .error { color: red; }
    </style>
</head>
<body>
    <nav>
        <div class="nav-left">
            <a href="<?= base_url('/') ?>">Accueil</a>
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