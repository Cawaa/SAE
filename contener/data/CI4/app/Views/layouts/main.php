<?php
use App\Models\CartModel;
use App\Models\CartItemModel;
$nbArticles = 0;
$session = session();
if ($session->get('isLoggedIn')) {
    $userId = (int)$session->get('user_id');
    $cartModel = new CartModel();
    $cart = $cartModel->where('user_id', $userId)->first();
    if ($cart) {
        $itemModel = new CartItemModel();
        $nbArticles = $itemModel->countItems((int)$cart['id']);
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>TEMPO - <?= $title ?? 'Accueil' ?></title>
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
    
    <?= $this->renderSection('extra-css') ?>

    <style>
        body { 
            background-image: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), 
                              url('<?= base_url("images/image_accueil.jpg") ?>');
        }
    </style>
</head>
<body>
    <nav>
    <div class="nav-left">
        <a href="<?= base_url('/') ?>">Accueil</a>
        <a href="<?= site_url('artists') ?>">Artistes</a>
        <a href="<?= base_url('/boutique') ?>">Boutique</a>
    </div>
    <div class="nav-center">TEMPO</div>
        <div class="nav-right">
            <a href="<?= base_url('/cart') ?>">Panier<?= $nbArticles > 0 ? " ($nbArticles)" : '' ?></a>
            <?php if($session->get('isLoggedIn')): ?>
                <span>Bonjour, <?= esc($session->get('username')) ?></span>
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