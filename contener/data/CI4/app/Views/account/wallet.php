<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<h1>Wallet</h1>

<?php if (empty($wallet)) : ?>
    <p>Aucun wallet pour le moment.</p>
<?php else: ?>
    <p>Solde : <?= number_format(((int)$wallet['balance_cents'])/100, 2, ',', ' ') ?> €</p>
<?php endif; ?>

<p><a href="<?= site_url('/account') ?>">← Retour Mon compte</a></p>

<?= $this->endSection() ?>
