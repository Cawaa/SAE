<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<h1>Mon compte</h1>

<?php if (!empty($user)) : ?>
    <p><strong><?= esc($user['username']) ?></strong> — <?= esc($user['email']) ?></p>
    <?php if (!empty($user['artist_genre'])) : ?>
        <p>Genre : <?= esc($user['artist_genre']) ?></p>
    <?php endif; ?>
<?php endif; ?>

<hr>

<h2>Stats</h2>
<ul>
    <li>Beats : <?= (int)($stats['beats_total'] ?? 0) ?> (actifs: <?= (int)($stats['beats_active'] ?? 0) ?>, vendus: <?= (int)($stats['beats_sold'] ?? 0) ?>)</li>
    <li>Favoris : <?= (int)($stats['favorites_count'] ?? 0) ?></li>
    <li>Conversations : <?= (int)($stats['conversations_count'] ?? 0) ?></li>
</ul>

<hr>

<h2>Navigation</h2>
<ul>
    <li><a href="<?= site_url('/account/profile') ?>">Profil (avatar + genre)</a></li>
    <li><a href="<?= site_url('/account/beats') ?>">Mes beats</a></li>
    <li><a href="<?= site_url('/account/favorites') ?>">Mes favoris</a></li>
    <li><a href="<?= site_url('/account/conversations') ?>">Mes conversations</a></li>
    <li><a href="<?= site_url('/account/wallet') ?>">Wallet</a></li>
    <li><a href="<?= site_url('/account/subscription') ?>">Abonnement</a></li>
    <li><a href="<?= site_url('/account/moderation') ?>">Modération</a></li>
</ul>

<?php if (!empty($wallet)) : ?>
    <p><strong>Solde wallet :</strong> <?= number_format(((int)$wallet['balance_cents'])/100, 2, ',', ' ') ?> €</p>
<?php endif; ?>

<?= $this->endSection() ?>
