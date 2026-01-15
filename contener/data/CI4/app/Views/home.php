<?= $this->extend('layouts/main') ?>

<?= $this->section('extra-css') ?>
    <link rel="stylesheet" href="<?= base_url('css/beats.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>

    <section class="hero">
        <div class="hero-content">
            <h1>Découvrez des sons de créateurs</h1>
            <p>Achetez les productions de créateurs indépendants pour soutenir leur talent et leur savoir-faire.</p>
            <a href="<?= base_url('/beats') ?>" class="btn-primary">En savoir plus</a>
        </div>
    </section>
    
    <section class="beatmakers">
        <div class="blob blob-left"></div>
        <div class="blob blob-right"></div>

        <h2 class = "section-title">Beatmakers incontournables</h2>
        <div class = "divider"></div>
        
        <div class="artist-grid">
            <a href="<?= base_url('/artists/3') ?>" class="artist-card-link">
                <div class="artist-card">
                    <div class="img-container">
                        <img src="<?= base_url('./images/avatars/prod1.jpg') ?>" alt="Viper Beats">
                    </div>
                    <h3>Viper Beats</h3>
                </div>
            </a>

            <a href="<?= base_url('/artists/4') ?>" class="artist-card-link">
                <div class="artist-card">
                    <div class="img-container">
                        <img src="<?= base_url('./images/avatars/prod2.jpg') ?>" alt="Shadow On The Track">
                    </div>
                    <h3>Shadow On The Track</h3>
                </div>
            </a>

            <a href="<?= base_url('/artists/5') ?>" class="artist-card-link">
                <div class="artist-card">
                    <div class="img-container">
                        <img src="<?= base_url('./images/avatars/prod3.jpg') ?>" alt="Glitch Kid">
                    </div>
                    <h3>Glitch Kid</h3>
                </div>
            </a>
        </div>
    </section>

    <?php $beatsList = $beats ?? ($listings ?? []); ?>

    <div class="latest-beats-section">
        <h2 class="section-title">Derniers beats</h2>
        <div class = "divider"></div>
        <?php if (empty($beatsList)) : ?>
            <div class="home-card" style="text-align:center;">
                <p>Aucun beat pour le moment.</p>
            </div>
        <?php else : ?>
            <div class="beats-grid">
                <?php foreach ($beatsList as $b) : ?>
                    <a href="<?= base_url('/beats/' . (int)$b['id']) ?>" class="beat-card-link">
                        <div class="home-card beat-card-variant">
                            <div class="beat-info">
                                <h3><?= esc($b['title']) ?></h3>
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
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
<?= $this->endSection() ?>