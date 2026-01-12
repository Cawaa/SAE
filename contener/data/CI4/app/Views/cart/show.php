<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<link rel="stylesheet" href="<?= base_url('css/cart.css') ?>">

<div class="cart-container">
    <h1>Votre Panier</h1>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-error">⚠️ <?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>

    <?php if (empty($items)): ?>
        <div class="empty-cart">
            <p>Votre panier est actuellement vide.</p>
            <a class="btn-checkout" href="<?= site_url('beats') ?>" style="width: auto; display: inline-block;">Parcourir les beats</a>
        </div>
    <?php else: ?>

    <div class="cart-grid">
        <div class="cart-items-list">
            <?php foreach ($items as $it): 
                $qty = (int)$it['quantite'];
                $price = (float)$it['price'];
                $line = $qty * $price;
                $isAvailable = ($it['status'] === 'active' && empty($it['buyer_id']));
            ?>
            <div class="cart-item">
                <div class="cart-item-info">
                    <div class="cart-item-title">
                        <a href="<?= site_url('beats/'.$it['beat_id']) ?>"><?= esc($it['title']) ?></a>
                    </div>
                    <div class="cart-item-beatmaker">par <?= esc($it['username'] ?? 'Artiste inconnu') ?></div>
                    
                    <?php if (!$isAvailable): ?>
                        <div class="status-badge">⚠️ Indisponible</div>
                    <?php endif; ?>
                </div>

                <div class="cart-item-actions">
                    <div class="line-total">
                        <?= number_format($line, 2, ',', ' ') ?> €
                    </div>

                    <form method="post" action="<?= site_url('cart/remove-line/'.$it['beat_id']) ?>">
                        <?= csrf_field() ?>
                        <button class="btn-remove" type="submit">Supprimer</button>
                    </form>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <aside class="cart-summary-card">
            <h3>Résumé</h3>
            <div class="summary-row">
                <span>Articles</span>
                <span><?= count($items) ?></span>
            </div>
            
            <div class="summary-total">
                <div class="summary-row">
                    <span>Total</span>
                    <span><?= number_format((float)$total, 2, ',', ' ') ?> €</span>
                </div>
            </div>

            <?php if (!empty($hasUnavailable)): ?>
                <p class="status-badge" style="display:block; text-align:center;">Retirez les articles indisponibles</p>
            <?php else: ?>
                <?php if (!empty($isLoggedIn)): ?>
                    <a class="btn-checkout" href="<?= site_url('cart/checkout') ?>">Procéder au paiement</a>
                <?php else: ?>
                    <a class="btn-checkout" href="<?= site_url('login') ?>" style="background:#555;">Se connecter pour payer</a>
                <?php endif; ?>
            <?php endif; ?>
        </aside>
    </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>