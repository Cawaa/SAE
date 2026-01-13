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
$orderId    = (int)($order['id'] ?? 0);
$paidAt     = (string)($order['paid_at'] ?? '');
$totalCents = (int)($order['total_cents'] ?? 0);
$total      = $totalCents / 100;
?>

<p>Merci pour votre paiement !</p>

<?php if ($orderId > 0) : ?>
    <p><small>Commande #<?= $orderId ?></small></p>
<?php endif; ?>

<?php if ($paidAt !== '') : ?>
    <p><small>Paiement enregistré le : <?= esc($paidAt) ?></small></p>
<?php endif; ?>

<hr>

<h2>Résumé de votre commande</h2>

<?php if (empty($items)) : ?>
    <p>Aucun item trouvé pour cette commande.</p>
<?php else : ?>
    <ul>
        <?php foreach ($items as $it) : ?>
            <?php
            $beatId = (int)($it['beat_id'] ?? 0);
            $label  = (string)($it['beat_title'] ?? '');
            $pc     = (int)($it['price_cents'] ?? 0);
            ?>
            <li>
                <?php if ($beatId > 0) : ?>
                    <a href="<?= site_url('beats/' . $beatId) ?>">
                        <?= esc($label !== '' ? $label : ('Beat #' . $beatId)) ?>
                    </a>
                <?php else : ?>
                    <?= esc($label !== '' ? $label : 'Beat') ?>
                <?php endif; ?>
                — <?= number_format($pc / 100, 2, ',', ' ') ?> €
            </li>
        <?php endforeach; ?>
    </ul>

    <p><strong>Total : <?= number_format($total, 2, ',', ' ') ?> €</strong></p>
<?php endif; ?>

<hr>

<p>
    <a href="<?= site_url('/account/wallet') ?>">→ Aller dans mon Wallet (retélécharger mes achats)</a>
</p>

<p>
    <a href="<?= site_url('/beats') ?>">← Retour à la boutique</a>
</p>

<?= $this->endSection() ?>
