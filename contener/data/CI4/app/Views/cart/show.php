<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<link rel="stylesheet" href="<?= base_url('css/cart.css') ?>">

<div class="cart-container">
    
    <?php if (session()->getFlashdata('error')): ?>
      <div class="alert alert-error">⚠️ <?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>

    <?php if (empty($items)): ?>
      <div class="empty-cart">
        <h1>Votre Panier</h1>

        <p>Votre panier est actuellement vide.</p>
        <a class="btn-checkout" href="<?= site_url('beats') ?>" style="width: auto; display: inline-block;">Parcourir les beats</a>
      </div>
    <?php else: ?>

    <div class="cart-top-bar">
      <h1>Votre Panier</h1>

        <form method="post" action="<?= site_url('cart/clear') ?>" onsubmit="return confirm('Voulez-vous vraiment vider votre panier ?');">
          <?= csrf_field() ?>
          <button type="submit" class="btn-clear-top">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
              <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
              <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
            </svg>
            Vider le panier
          </button>
        </form>
    </div>
    
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
            <div class="cart-list-footer">
              <a href="<?= site_url('boutique') ?>" class="back-to-shop">
                  ← Continuer mes achats
              </a>
            </div>
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