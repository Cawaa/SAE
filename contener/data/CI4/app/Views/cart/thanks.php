<?= $this->extend('layouts/main') ?>

<?= $this->section('extra-css') ?>
    <link rel="stylesheet" href="<?= base_url('css/cart.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php
$orderId    = (int)($order['id'] ?? 0);
$paidAt     = (string)($order['paid_at'] ?? '');
$totalCents = (int)($order['total_cents'] ?? 0);
$total      = $totalCents / 100;
?>

<div class="thanks-container">
    <div class="thanks-card">
        <!-- Icône de succès -->
        <div class="thanks-icon">
            ✓
        </div>

        <h1>Paiement réussi !</h1>
        <p class="thanks-subtitle">Merci pour votre achat. Vos beats sont maintenant disponibles dans votre wallet.</p>

        <!-- Informations de commande -->
        <?php if ($orderId > 0): ?>
            <div class="order-info-box">
                <div class="order-number">
                    <strong>Commande #<?= $orderId ?></strong>
                </div>
                <?php if ($paidAt !== ''): ?>
                    <div class="order-date">
                        📅 Paiement enregistré le : <?= date('d/m/Y à H:i', strtotime($paidAt)) ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <!-- Messages flash -->
        <?php if (session()->getFlashdata('error')): ?>
            <div class="checkout-error">
                ⚠️ <?= esc(session()->getFlashdata('error')) ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="checkout-info" style="background: #d1fae5; border-color: #6ee7b7; color: #065f46;">
                ✓ <?= esc(session()->getFlashdata('success')) ?>
            </div>
        <?php endif; ?>

        <!-- Résumé de la commande -->
        <div class="order-summary-section">
            <h2>📦 Résumé de votre commande</h2>

            <?php if (empty($items)): ?>
                <div class="empty-order">
                    <p>Aucun article trouvé pour cette commande.</p>
                </div>
            <?php else: ?>
                <div class="order-items-list">
                    <?php foreach ($items as $it): ?>
                        <?php
                        $beatId = (int)($it['beat_id'] ?? 0);
                        $label  = (string)($it['beat_title'] ?? '');
                        $pc     = (int)($it['price_cents'] ?? 0);
                        ?>
                        <div class="order-item">
                            <div class="order-item-title">
                                <?php if ($beatId > 0): ?>
                                    <a href="<?= site_url('beats/' . $beatId) ?>">
                                        🎵 <?= esc($label !== '' ? $label : ('Beat #' . $beatId)) ?>
                                    </a>
                                <?php else: ?>
                                    🎵 <?= esc($label !== '' ? $label : 'Beat') ?>
                                <?php endif; ?>
                            </div>
                            <div class="order-item-price">
                                <?= number_format($pc / 100, 2, ',', ' ') ?> €
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="order-total-box">
                    <div class="order-total-label">Total payé</div>
                    <div class="order-total-amount"><?= number_format($total, 2, ',', ' ') ?> €</div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Actions -->
        <div class="thanks-actions">
            <a href="<?= site_url('/account/wallet') ?>" class="btn-thanks-primary">
                💼 Accéder à mon Wallet
            </a>
            
            <a href="<?= site_url('/boutique') ?>" class="btn-thanks-secondary">
                🔍 Continuer mes achats
            </a>
        </div>

        <!-- Info supplémentaire -->
        <div class="thanks-info-box">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div>
                <strong>Téléchargement :</strong> Vos fichiers audio sont disponibles dans votre wallet. Vous pouvez les télécharger à tout moment.
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
