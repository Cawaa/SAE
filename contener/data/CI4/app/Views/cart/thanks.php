<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<h1><?= esc($title ?? 'Merci') ?></h1>

<?php if (session()->getFlashdata('error')) : ?>
    <p><strong><?= esc(session()->getFlashdata('error')) ?></strong></p>
<?php endif; ?>

<?php if (session()->getFlashdata('success')) : ?>
    <p><strong><?= esc(session()->getFlashdata('success')) ?></strong></p>
<?php endif; ?>

<?php
$paidAt = $order['paid_at'] ?? null;
$total  = ((int)($totalCents ?? 0)) / 100;
?>

<p>Merci pour votre paiement !</p>

<?php if (!empty($paidAt)) : ?>
    <p><small>Paiement enregistré le : <?= esc($paidAt) ?></small></p>
<?php endif; ?>

<h2>Résumé de votre commande</h2>

<?php if (empty($items)) : ?>
    <p>Aucun item trouvé pour cette commande.</p>
<?php else : ?>
    <ul>
        <?php foreach ($items as $it) : ?>
            <li>
                <a href="<?= site_url('beats/' . (int)$it['beat_id']) ?>">
                    <?= esc($it['beat_title'] ?? ('Beat #' . (int)$it['beat_id'])) ?>
                </a>
                — <?= number_format(((int)($it['price_cents'] ?? 0)) / 100, 2, '.', '') ?> €
            </li>
        <?php endforeach; ?>
    </ul>

    <p><strong>Total : <?= number_format($total, 2, '.', '') ?> €</strong></p>
<?php endif; ?>

<p>
    <a href="<?= site_url('/account/wallet') ?>">→ Aller dans mon Wallet (retélécharger mes achats)</a>
</p>

<p>
    <a href="<?= site_url('/beats') ?>">← Retour à la boutique</a>
</p>

<?= $this->endSection() ?>
