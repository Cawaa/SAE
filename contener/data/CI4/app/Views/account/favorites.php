<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<h1>Mes favoris</h1>

<?php if (empty($favorites)) : ?>
    <p>Aucun favori.</p>
<?php else: ?>
    <ul>
        <?php foreach ($favorites as $b) : ?>
            <li>
                <a href="<?= site_url('/beats/' . (int)$b['id']) ?>">
                    <?= esc($b['title']) ?>
                </a>
                — <?= number_format((float)$b['price'], 2, ',', ' ') ?> €
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<p><a href="<?= site_url('/account') ?>">← Retour Mon compte</a></p>

<?= $this->endSection() ?>
