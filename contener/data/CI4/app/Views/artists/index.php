<?= $this->extend('layouts/main') ?>

<?= $this->section('extra-css') ?>
    <link rel="stylesheet" href="<?= base_url('css/artists.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>


<div class="artists-container">
    <!-- Header -->
    <div class="artists-header">
        <h1>Nos Artistes</h1>
        <p class="subtitle">Découvrez les beatmakers talentueux de notre plateforme</p>
    </div>

    <!-- Filtres -->
    <div class="filters-container">
        <form method="GET" action="<?= site_url('artists') ?>" class="filters-form">
            <div class="filter-group">
                <label>Recherche</label>
                <input type="text" 
                       name="search" 
                       placeholder="Nom d'artiste, genre..." 
                       value="<?= esc($search ?? '') ?>">
            </div>

            <div class="filter-group">
                <label>Trier par</label>
                <select name="sort">
                    <option value="popular" <?= ($sort === 'popular') ? 'selected' : '' ?>>Plus populaires</option>
                    <option value="recent" <?= ($sort === 'recent') ? 'selected' : '' ?>>Plus récents</option>
                    <option value="name" <?= ($sort === 'name') ? 'selected' : '' ?>>Nom (A-Z)</option>
                </select>
            </div>

            <div class="filter-submit">
                <button type="submit" class="btn-search">Rechercher</button>
            </div>
        </form>
    </div>

    <!-- Liste des artistes -->
    <?php if (empty($artists)): ?>
        <div class="no-artists">
            <div class="no-artists-icon">👤</div>
            <p>Aucun artiste trouvé</p>
        </div>
    <?php else: ?>
        <div class="artists-grid">
            <?php foreach ($artists as $artist): ?>
                <a href="<?= site_url('artists/' . (int)$artist['id']) ?>" class="artist-card-link">
                    <article class="artist-card-new">
                        <!-- Artist Avatar -->
                        <div class="artist-header-bg">
                            <?php
                                $avatarUrl = '';
                                if (!empty($artist['avatar'])) {
                                    if (preg_match('#^https?://#', $artist['avatar'])) {
                                        $avatarUrl = $artist['avatar'];
                                    }
                                    elseif (str_starts_with($artist['avatar'], 'uploads/') || str_starts_with($artist['avatar'], 'images/')) {
                                        $avatarUrl = base_url($artist['avatar']);
                                    }
                                    else {
                                        $avatarUrl = base_url('images/' . ltrim($artist['avatar'], '/'));
                                    }
                                }
                            ?>
                            
                            <?php if (!empty($avatarUrl)): ?>
                                <img src="<?= esc($avatarUrl) ?>" 
                                     alt="<?= esc($artist['username']) ?>"
                                     class="artist-avatar-img"
                                     loading="lazy"
                                     onerror="this.style.display='none'; this.parentElement.querySelector('.artist-initials').style.display='flex';">
                                <div class="artist-initials" style="display: none;">
                                    <span><?= strtoupper(substr($artist['username'], 0, 2)) ?></span>
                                </div>
                            <?php else: ?>
                                <div class="artist-initials">
                                    <span><?= strtoupper(substr($artist['username'], 0, 2)) ?></span>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Artist Info -->
                        <div class="artist-content">
                            <h3 class="artist-title">
                                <?= esc($artist['username']) ?>
                            </h3>
                            
                            <?php if (!empty($artist['artist_genre'])): ?>
                                <p class="artist-bio">
                                    Genre : <?= esc($artist['artist_genre']) ?>
                                </p>
                            <?php endif; ?>

                            <div class="artist-stats">
                                <span class="stat-item">
                                    <svg class="stat-icon" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M18 3a1 1 0 00-1.196-.98l-10 2A1 1 0 006 5v9.114A4.369 4.369 0 005 14c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2V7.82l8-1.6v5.894A4.37 4.37 0 0015 12c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2V3z"/>
                                    </svg>
                                    <?= (int)($artist['beats_count'] ?? 0) ?> beats
                                </span>
                                <span class="stat-item">
                                    <svg class="stat-icon" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                                    </svg>
                                    <?= (int)($artist['sold_beats_count'] ?? 0) ?> vendus
                                </span>
                            </div>
                        </div>
                    </article>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- CTA -->
<section class="cta-section">
    <div class="cta-overlay"></div>
    <div class="cta-background"></div>
    
    <div class="cta-content">
        <h2>Vous êtes producteur ?</h2>
        <p>
            Rejoignez notre communauté et vendez vos beats à des milliers d'artistes
        </p>
        <a href="<?= site_url('/register') ?>" class="btn-cta">
            Devenir producteur
        </a>
    </div>
</section>

<?= $this->endSection() ?>