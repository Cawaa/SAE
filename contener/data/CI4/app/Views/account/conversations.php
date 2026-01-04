<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<h1>Mes conversations</h1>

<?php if (empty($conversations)) : ?>
    <p>Aucune conversation.</p>
<?php else: ?>
    <ul>
        <?php foreach ($conversations as $c) : ?>
            <?php
                $other = ((int)$c['buyer_id'] === (int)$userId) ? ($c['seller_username'] ?? '—') : ($c['buyer_username'] ?? '—');
            ?>
            <li>
                <a href="<?= site_url('/conversations/' . (int)$c['id']) ?>">
                    <?= esc($c['beat_title'] ?? 'Beat') ?> — avec <?= esc($other) ?>
                </a>
                <?php if (!empty($c['last_message'])) : ?>
                    <br><small>Dernier message : <?= esc($c['last_message']) ?></small>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<p><a href="<?= site_url('/account') ?>">← Retour Mon compte</a></p>

<?= $this->endSection() ?>
