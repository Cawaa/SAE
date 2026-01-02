<?= $this->extend('layouts/main') ?>

<?= $this->section('extra-css') ?>
    <link rel="stylesheet" href="<?= base_url('css/beats.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    
    <div class="home-card hero-section">
        <h1>Découvrez des beats de créateurs</h1>
        <p class="subtitle">Achetez des instrumentales exclusives pour soutenir des beatmakers indépendants.</p>
        <a href="<?= base_url('/beats') ?>" class="btn-beatflow">Voir le catalogue des beats</a>
    </div>

    <?php $beatsList = $beats ?? ($listings ?? []); ?>

    <div class="latest-beats-section">
        <h2 class="section-title">Derniers beats</h2>
        <?php if (empty($beatsList)) : ?>
            <div class="home-card" style="text-align:center;">
                <p>Aucun beat pour le moment.</p>
            </div>
        <?php else : ?>
            <div class="beats-grid">
                <?php foreach ($beatsList as $b) : ?>
                    <div class="home-card beat-card-variant">
                        <div class="beat-info">
                            <h3>
                                <a href="<?= base_url('/beats/' . (int)$b['id']) ?>">
                                    <?= esc($b['title']) ?>
                                </a>
                            </h3>
                            <p class="beat-genre"><?= esc($b['category_name'] ?? 'Sans genre') ?></p>
                            <div class="beat-details">
                                <span><?= esc($b['bpm'] ?? '—') ?> BPM</span>
                                <span><?= esc($b['musical_key'] ?? '—') ?></span>
                            </div>
                        </div>
                        <div class="beat-footer">
                            <span class="beat-price"><?= esc($b['price']) ?> €</span>
                            <small>par <strong><?= esc($b['seller_username'] ?? 'N/A') ?></strong></small>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    
    <?php if (!empty($stats)) : ?>
        <div class="home-card stats-container-variant">
            <div class="stat-item"><strong><?= esc($stats['active_beats'] ?? 0) ?></strong> <span>Beats Actifs</span></div>
            <div class="stat-item"><strong><?= esc($stats['total_beats'] ?? 0) ?></strong> <span>Total Beats</span></div>
            <div class="stat-item"><strong><?= esc($stats['sold_beats'] ?? 0) ?></strong> <span>Vendus</span></div>
            <div class="stat-item"><strong><?= esc($stats['total_users'] ?? 0) ?></strong> <span>Utilisateurs</span></div>
        </div>
    <?php endif; ?>
<?= $this->endSection() ?>