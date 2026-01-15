<?php
use App\Models\CartModel;
use App\Models\CartItemModel;

$nbArticles = 0;
$session = session();

if ($session->get('isLoggedIn')) {
    $userId = (int) $session->get('user_id');
    $cartModel = new CartModel();
    $cart = $cartModel->where('user_id', $userId)->first();

    if ($cart) {
        $itemModel = new CartItemModel();
        $nbArticles = $itemModel->countItems((int) $cart['id']);
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TEMPO - <?= $title ?? 'Accueil' ?></title>
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/footer.css') ?>">
    <?= $this->renderSection('extra-css') ?>
</head>
<body>
    <nav>
        <div class="nav-container">
            <!-- Logo -->
            <div class="nav-logo">
                <a href="<?= base_url('/') ?>">TEMPO</a>
            </div>

            <!-- Navigation Desktop -->
            <div class="nav-links" id="navLinks">
                <a href="<?= base_url('/') ?>">Accueil</a>
                <a href="<?= site_url('artists') ?>">Artistes</a>
                <a href="<?= base_url('/boutique') ?>">Boutique</a>
            </div>

            <!-- Actions Desktop -->
            <div class="nav-actions" id="navActions">
                <a href="<?= base_url('/cart') ?>" class="nav-cart">
                    <svg class="cart-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    Panier
                    <?php if ($nbArticles > 0): ?>
                        <span class="cart-badge"><?= $nbArticles ?></span>
                    <?php endif; ?>
                </a>

                <?php if ($session->get('isLoggedIn')): ?>
                    <a href="<?= site_url('/mon-compte') ?>" class="nav-btn nav-btn-secondary">Mon compte</a>
                    <a href="<?= base_url('logout') ?>" class="nav-btn nav-btn-tertiary">Déconnexion</a>
                <?php else: ?>
                    <a href="<?= base_url('login') ?>" class="nav-btn nav-btn-secondary">Connexion</a>
                    <a href="<?= base_url('register') ?>" class="nav-btn nav-btn-primary">S'inscrire</a>
                <?php endif; ?>
            </div>

            <!-- Mobile menu button -->
            <div class="nav-toggle" id="navToggle">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>

        <!-- Mobile menu -->
        <div class="nav-mobile" id="navMobile">
            <a href="<?= base_url('/') ?>">Accueil</a>
            <a href="<?= site_url('artists') ?>">Artistes</a>
            <a href="<?= base_url('/boutique') ?>">Boutique</a>
            <a href="<?= base_url('/cart') ?>">Panier <?= $nbArticles > 0 ? "($nbArticles)" : '' ?></a>

            <?php if ($session->get('isLoggedIn')): ?>
                <a href="<?= site_url('/mon-compte') ?>">Mon compte</a>
                <a href="<?= base_url('logout') ?>">Déconnexion</a>
            <?php else: ?>
                <a href="<?= base_url('login') ?>">Connexion</a>
                <a href="<?= base_url('register') ?>" class="nav-mobile-primary">S'inscrire</a>
            <?php endif; ?>
        </div>
    </nav>

    <div class="container">
        <?= $this->renderSection('content') ?>
    </div>

    <?= $this->include('layouts/footer') ?>

    <script>
        const navToggle = document.getElementById('navToggle');
        const navMobile = document.getElementById('navMobile');

        navToggle.addEventListener('click', () => {
            navToggle.classList.toggle('active');
            navMobile.classList.toggle('active');
        });

        // Fermer le menu lors du clic sur un lien
        document.querySelectorAll('.nav-mobile a').forEach(link => {
            link.addEventListener('click', () => {
                navToggle.classList.remove('active');
                navMobile.classList.remove('active');
            });
        });

        // Fermer le menu lors du clic en dehors
        document.addEventListener('click', (e) => {
            if (!e.target.closest('nav')) {
                navToggle.classList.remove('active');
                navMobile.classList.remove('active');
            }
        });
    </script>
</body>
</html>