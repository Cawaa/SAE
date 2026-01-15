<?= $this->extend('layouts/main') ?>

<?= $this->section('extra-css') ?>
    <link rel="stylesheet" href="<?= base_url('css/cart.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="checkout-container">
    <div class="checkout-card">
        <h1>🛒 Finaliser l'achat</h1>

        <?php if (!empty($hasUnavailable)): ?>
            <div class="checkout-warning">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <p>⚠️ Votre panier contient des beats indisponibles. Veuillez retourner au panier pour les retirer avant de continuer.</p>
            </div>
            
            <div class="checkout-actions">
                <a href="<?= site_url('cart') ?>" class="btn-back-to-cart">← Retour au panier</a>
            </div>
        <?php else: ?>
            <?php if (session()->getFlashdata('error')): ?>
                <div class="checkout-error">
                    ⚠️ <?= esc(session()->getFlashdata('error')) ?>
                </div>
            <?php endif; ?>

            <div class="checkout-total-section">
                <div class="checkout-total-label">Montant total à payer</div>
                <div class="checkout-total-amount"><?= number_format((float)$total, 2, ',', ' ') ?> €</div>
            </div>

            <form method="post" action="<?= site_url('cart/checkout') ?>">
                <?= csrf_field() ?>
                
                <div class="checkout-actions">
                    <button type="submit" class="btn-checkout-confirm">
                        ✓ Confirmer et payer
                    </button>
                    
                    <a href="<?= site_url('cart') ?>" class="btn-back-to-cart">← Retour au panier</a>
                </div>
            </form>

            <div class="checkout-info">
                💳 <strong>Paiement simulé</strong> - Aucune transaction réelle ne sera effectuée
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>
