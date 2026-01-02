<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<?php
    $userId  = (int) (session()->get('user_id') ?? 0);
    $isOwner = ($userId > 0 && (int)$beat['user_id'] === $userId);

    $isActive = ($beat['status'] === 'active' && empty($beat['buyer_id']));
    $isSold   = (!$isActive);
?>

<h1><?= esc($beat['title']) ?></h1>

<?php if ($isSold) : ?>
    <p><strong>⚠️ VENDU / INDISPONIBLE</strong></p>
<?php endif; ?>

<p>
    <strong>Prix :</strong> <?= esc($beat['price']) ?> €<br>
    <strong>Genre :</strong> <?= esc($beat['category_name'] ?? 'Sans genre') ?><br>
    <strong>BPM :</strong> <?= esc($beat['bpm'] ?? '—') ?><br>
    <strong>Clé :</strong> <?= esc($beat['musical_key'] ?? '—') ?><br>
    <strong>Tags :</strong> <?= esc($beat['tags'] ?? '—') ?><br>
    <strong>Vendeur :</strong> <?= esc($beat['seller_username'] ?? 'N/A') ?><br>
</p>

<?php if (!empty($previewPath)) : ?>
    <hr>
    <h3>Écoute (preview)</h3>
    <audio controls preload="none">
        <source src="<?= base_url($previewPath) ?>" type="audio/mpeg">
        Votre navigateur ne supporte pas l’audio.
    </audio>
<?php else : ?>
    <p><em>Aucun fichier preview disponible pour ce beat.</em></p>
<?php endif; ?>

<hr>

<?php if (!empty($beat['description'])) : ?>
    <h3>Description</h3>
    <p><?= nl2br(esc($beat['description'])) ?></p>
<?php endif; ?>

<hr>

<!-- Actions utilisateur -->
<?php if ($userId <= 0) : ?>
    <p>
        <a href="<?= base_url('/login') ?>">Connecte-toi</a> pour ajouter aux favoris, au panier, ou contacter le vendeur.
    </p>
<?php else : ?>
    <?php if ($isOwner) : ?>
        <p><strong>Tu es le propriétaire de ce beat.</strong></p>

        <p>
            <a href="<?= base_url('/beats/' . (int)$beat['id'] . '/edit') ?>">Modifier</a>
        </p>

        <form method="POST" action="<?= base_url('/beats/' . (int)$beat['id'] . '/delete') ?>"
              onsubmit="return confirm('Supprimer ce beat ?');">
            <?= csrf_field() ?>
            <button type="submit">Supprimer</button>
        </form>

    <?php else : ?>
        <?php if ($isActive) : ?>
            <!-- Conversation -->
            <form method="POST" action="<?= base_url('/conversations/start/' . (int)$beat['id']) ?>" style="display:inline;">
                <?= csrf_field() ?>
                <button type="submit">💬 Contacter le vendeur</button>
            </form>

            <!-- Favori -->
            <form method="POST" action="<?= base_url('/favorites/' . (int)$beat['id'] . '/toggle') ?>" style="display:inline;">
                <?= csrf_field() ?>
                <button type="submit">❤️ Favori</button>
            </form>

            <!-- Panier -->
            <form method="POST" action="<?= base_url('/cart/add/' . (int)$beat['id']) ?>" style="display:inline;">
                <?= csrf_field() ?>
                <button type="submit">➕ Ajouter au panier</button>
            </form>

        <?php else : ?>
            <p><strong>Ce beat n’est plus achetable.</strong></p>
        <?php endif; ?>
    <?php endif; ?>
<?php endif; ?>

<hr>

<!-- Suggestions -->
<?php if (!empty($suggestions)) : ?>
    <h3>Autres beats de <?= esc($beat['seller_username'] ?? 'ce vendeur') ?></h3>
    <ul>
        <?php foreach ($suggestions as $s) : ?>
            <li>
                <a href="<?= base_url('/beats/' . (int)$s['id']) ?>">
                    <?= esc($s['title']) ?>
                </a>
                — <?= esc($s['bpm'] ?? '—') ?> BPM
                — <?= esc($s['musical_key'] ?? '—') ?>
                — <?= esc($s['price']) ?> €
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<p><a href="<?= base_url('/beats') ?>">← Retour à la boutique</a></p>

<?= $this->endSection() ?>
