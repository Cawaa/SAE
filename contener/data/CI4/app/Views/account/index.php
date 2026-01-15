<?= $this->extend('layouts/main') ?>
<?= $this->section('extra-css') ?>
    <link rel="stylesheet" href="<?= base_url('css/account.css') ?>">
<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="account-container">
    <div class="account-header">
        <h1 class="account-title">Mon compte</h1>
        <p class="account-subtitle">Gérez votre profil et vos paramètres</p>
    </div>

    <?php if (!empty($user)): ?>
        <!-- Profil Header -->
        <div class="profile-card">
            <div class="profile-info">
                <?php
                    $avatarUrl = '';
                    if (!empty($user['avatar'])) {
                        $avatar = (string)$user['avatar'];
                        
                        // Si c'est déjà une URL complète
                        if (preg_match('#^https?://#', $avatar)) {
                            $avatarUrl = $avatar;
                        }
                        // Si c'est un chemin avec images/ ou uploads/
                        elseif (str_starts_with($avatar, 'images/') || str_starts_with($avatar, 'uploads/')) {
                            $avatarUrl = base_url($avatar);
                        }
                        // Si c'est avatars/* (stocké en DB sans images/)
                        elseif (str_starts_with($avatar, 'avatars/')) {
                            $avatarUrl = base_url('images/' . $avatar);
                        }
                        // Sinon, on suppose que c'est un chemin relatif à images/
                        else {
                            $avatarUrl = base_url('images/avatars/' . ltrim($avatar, '/'));
                        }
                    }
                ?>
                
                <?php if (!empty($avatarUrl)): ?>
                    <img src="<?= esc($avatarUrl) ?>" 
                        alt="<?= esc($user['username']) ?>"
                        class="profile-avatar"
                        loading="lazy"
                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="profile-avatar-fallback" style="display: none;">
                        <?= strtoupper(substr($user['username'], 0, 2)) ?>
                    </div>
                <?php else: ?>
                    <div class="profile-avatar-fallback">
                        <?= strtoupper(substr($user['username'], 0, 2)) ?>
                    </div>
                <?php endif; ?>
                
                <div class="profile-details">
                    <h2 class="profile-name"><?= esc($user['username']) ?></h2>
                    <p class="profile-email"><?= esc($user['email']) ?></p>
                    <?php if (!empty($user['artist_genre'])): ?>
                        <span class="profile-badge">
                            <?= esc($user['artist_genre']) ?>
                        </span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Statistiques -->
        <div class="stats-grid">
            <div class="stat-card stat-card-beats">
                <div class="stat-header">
                    <span class="stat-label">Beats publiés</span>
                    <span class="stat-icon">🎵</span>
                </div>
                <div class="stat-value stat-value-blue"><?= (int)($stats['beats_total'] ?? 0) ?></div>
                <p class="stat-description">
                    <?= (int)($stats['beats_active'] ?? 0) ?> actifs • <?= (int)($stats['beats_sold'] ?? 0) ?> vendus
                </p>
            </div>

            <div class="stat-card stat-card-favorites">
                <div class="stat-header">
                    <span class="stat-label">Favoris</span>
                    <span class="stat-icon">❤️</span>
                </div>
                <div class="stat-value stat-value-pink"><?= (int)($stats['favorites_count'] ?? 0) ?></div>
            </div>

            <div class="stat-card stat-card-conversations">
                <div class="stat-header">
                    <span class="stat-label">Conversations</span>
                    <span class="stat-icon">💬</span>
                </div>
                <div class="stat-value stat-value-green"><?= (int)($stats['conversations_count'] ?? 0) ?></div>
            </div>

            <?php if (!empty($wallet)): ?>
                <div class="stat-card stat-card-wallet">
                    <div class="stat-header">
                        <span class="stat-label stat-label-white">Solde wallet</span>
                        <span class="stat-icon">💰</span>
                    </div>
                    <div class="stat-value stat-value-white">
                        <?= number_format(((int)$wallet['balance_cents'])/100, 2, ',', ' ') ?> €
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Navigation rapide -->
        <div class="navigation-card">
            <h3 class="navigation-title">Navigation</h3>
            <div class="navigation-grid">
                <a href="<?= site_url('/account/profile') ?>" class="nav-item nav-item-blue">
                    <div class="nav-icon">
                        <svg class="nav-svg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div class="nav-content">
                        <div class="nav-title">Gestion du profil</div>
                        <div class="nav-description">Modifier vos informations</div>
                    </div>
                </a>

                <a href="<?= site_url('/account/beats') ?>" class="nav-item nav-item-blue">
                    <div class="nav-icon">
                        <svg class="nav-svg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/>
                        </svg>
                    </div>
                    <div class="nav-content">
                        <div class="nav-title">Mes beats</div>
                        <div class="nav-description">Gérer vos publications</div>
                    </div>
                </a>

                <a href="<?= site_url('/account/favorites') ?>" class="nav-item nav-item-pink">
                    <div class="nav-icon">
                        <svg class="nav-svg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </div>
                    <div class="nav-content">
                        <div class="nav-title">Mes favoris</div>
                        <div class="nav-description">Beats sauvegardés</div>
                    </div>
                </a>

                <a href="<?= site_url('/account/conversations') ?>" class="nav-item nav-item-green">
                    <div class="nav-icon">
                        <svg class="nav-svg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                    </div>
                    <div class="nav-content">
                        <div class="nav-title">Mes conversations</div>
                        <div class="nav-description">Messages et discussions</div>
                    </div>
                </a>

                <a href="<?= site_url('/account/wallet') ?>" class="nav-item nav-item-purple">
                    <div class="nav-icon">
                        <svg class="nav-svg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                    </div>
                    <div class="nav-content">
                        <div class="nav-title">Wallet</div>
                        <div class="nav-description">Solde et transactions</div>
                    </div>
                </a>

                <a href="<?= site_url('/account/subscription') ?>" class="nav-item nav-item-yellow">
                    <div class="nav-icon">
                        <svg class="nav-svg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                        </svg>
                    </div>
                    <div class="nav-content">
                        <div class="nav-title">Abonnement</div>
                        <div class="nav-description">Formules premium</div>
                    </div>
                </a>
            </div>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>