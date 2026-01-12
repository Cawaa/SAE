<?= $this->extend('layouts/main') ?>

<?= $this->section('extra-css') ?>
    <link rel="stylesheet" href="<?= base_url('css/beat.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="beat-details-container">
    <div class="beat-main-info">
        <h1><?= esc($beat['title']) ?></h1>

        <?php if ($beat['status'] !== 'active' || !empty($beat['buyer_id'])) : ?>
            <p style="color: #e11d48; font-weight: bold;">⚠️ VENDU / INDISPONIBLE</p>
        <?php endif; ?>

        <div style="display: flex; gap: 20px; color: #64748b; margin: 20px 0;">
            <span>📁 <?= esc($beat['category_name'] ?? 'Sans genre') ?></span>
            <span>🥁 <?= esc($beat['bpm'] ?? '—') ?> BPM</span>
            <span>🎹 <?= esc($beat['musical_key'] ?? '—') ?></span>
        </div>

        <?php if (!empty($previewPath)) : ?>
            <audio controls preload="metadata" src="<?= base_url($previewPath) ?>"></audio>
        <?php else : ?>
            <p><em>Aucune preview disponible.</em></p>
        <?php endif; ?>

        <h3>Description</h3>
        <p style="line-height: 1.6; color: #334155;">
            <?= !empty($beat['description']) ? nl2br(esc($beat['description'])) : '<em>Aucune description fournie.</em>' ?>
        </p>
    </div>

    <div class="beat-sidebar-actions">
        <div style="text-align: center; margin-bottom: 20px;">
            <span style="font-size: 2.5rem; font-weight: 800; color: var(--primary-blue);"><?= esc($beat['price']) ?> €</span>
        </div>

        <?php if (session()->get('user_id')) : ?>
            <?php if ((int)session()->get('user_id') === (int)$beat['user_id']) : ?>
                <a href="<?= site_url('/account/beats/' . (int)$beat['id'] . '/edit') ?>" class="btn-action btn-contact">Modifier le beat</a>
            <?php else : ?>
                <form method="POST" action="<?= base_url('/cart/add/' . (int)$beat['id']) ?>">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn-action btn-cart">🛒 Ajouter au panier</button>
                </form>

                <form method="POST" action="<?= base_url('/favorites/' . (int)$beat['id'] . '/toggle') ?>">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn-action btn-fav">❤️ Favori</button>
                </form>

                <form method="POST" action="<?= base_url('/conversations/start/' . (int)$beat['id']) ?>">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn-action btn-contact">💬 Contacter</button>
                </form>
            <?php endif; ?>
        <?php else : ?>
            <a href="<?= base_url('/login') ?>" class="btn-action btn-cart">Connectez-vous pour acheter</a>
        <?php endif; ?>

        <div style="margin-top: 30px; border-top: 1px solid #f1f5f9; padding-top: 20px;">
            <p>Vendeur : <strong><?= esc($beat['seller_username'] ?? 'N/A') ?></strong></p>
            <a href="<?= base_url('/beats') ?>" style="color: var(--primary-blue); text-decoration: none;">← Retour boutique</a>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
