<footer class="site-footer">
    <div class="footer-content">
        <!-- Section Gauche -->
        <div class="footer-section footer-about">
            <h3>TEMPO</h3>
            <p>La plateforme de vente de beats des producteurs indépendants.</p>
            <div class="footer-social">
                <a href="#" aria-label="Facebook">f</a>
                <a href="#" aria-label="Twitter">𝕏</a>
                <a href="#" aria-label="Instagram">📷</a>
            </div>
        </div>

        <!-- Section Centre -->
        <div class="footer-section footer-links">
            <h4>Navigation</h4>
            <ul>
                <li><a href="<?= site_url('/') ?>">Accueil</a></li>
                <li><a href="<?= site_url('/beats') ?>">Boutique</a></li>
                <li><a href="<?= site_url('/artists') ?>">Artistes</a></li>
                <li><a href="<?= site_url('/cart') ?>">Panier</a></li>
            </ul>
        </div>

        <!-- Section Compte -->
        <div class="footer-section footer-account">
            <h4>Compte</h4>
            <ul>
                <?php if (session()->get('isLoggedIn')): ?>
                    <li><a href="<?= site_url('/account') ?>">Mon compte</a></li>
                    <li><a href="<?= site_url('/account/beats') ?>">Mes beats</a></li>
                    <li><a href="<?= site_url('/account/favorites') ?>">Mes favoris</a></li>
                    <li><a href="<?= site_url('/logout') ?>">Déconnexion</a></li>
                <?php else: ?>
                    <li><a href="<?= site_url('/login') ?>">Connexion</a></li>
                    <li><a href="<?= site_url('/register') ?>">S'inscrire</a></li>
                <?php endif; ?>
            </ul>
        </div>

        <!-- Section Légal -->
        <div class="footer-section footer-legal">
            <h4>Informations</h4>
            <ul>
                <li><a href="<?= site_url('/conditions-utilisation') ?>">Conditions d'utilisation</a></li>
                <li><a href="#">Politique de confidentialité</a></li>
                <li><a href="#">Contact</a></li>
                <li><a href="#">À propos</a></li>
            </ul>
        </div>
    </div>

    <!-- Barre inférieure -->
    <div class="footer-bottom">
        <p>&copy; <?= date('Y') ?> <strong>TEMPO</strong> — Projet pédagogique IUT. Tous droits réservés.</p>
        <p>PHP • CodeIgniter 4 • MVC</p>
    </div>
</footer>