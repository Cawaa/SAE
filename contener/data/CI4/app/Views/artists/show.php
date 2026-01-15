<?= $this->extend('layouts/main') ?>

<?= $this->section('extra-css') ?>
    <link rel="stylesheet" href="<?= base_url('css/artists.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php
$avatarUrl = static function (?string $avatarRel): string {
    $avatarRel = (string)($avatarRel ?? '');
    if ($avatarRel === '') {
        return base_url('images/avatars/default.jpg');
    }
    
    $avatarRel = str_replace(['..', '\\'], ['', '/'], $avatarRel);
    $avatarRel = ltrim($avatarRel, '/');

    if (str_starts_with($avatarRel, 'images/')) {
        return base_url($avatarRel);
    }
    if (str_starts_with($avatarRel, 'avatars/')) {
        return base_url('images/' . $avatarRel);
    }

    return base_url('images/avatars/' . $avatarRel);
};
?>

<div style="max-width: 1200px; margin: 0 auto; padding: 20px;">
    <!-- Header de l'artiste -->
    <div style="display: flex; align-items: center; gap: 30px; margin-bottom: 40px; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
        <div class="artist-avatar" style="width: 150px; height: 150px;">
            <img src="<?= esc($avatarUrl($artist['avatar'] ?? null)) ?>" alt="Avatar de <?= esc($artist['username']) ?>" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
        </div>

        <div style="flex: 1;">
            <h1 style="margin: 0 0 10px 0; color: #1e293b; font-size: 2.5rem;"><?= esc($artist['username']) ?></h1>
            
            <?php if (!empty($artist['artist_genre'])): ?>
                <div style="color: #64748b; font-size: 1.1rem; margin-bottom: 10px;">
                    🎵 Genre : <?= esc($artist['artist_genre']) ?>
                </div>
            <?php endif; ?>

            <div style="display: flex; gap: 30px; color: #64748b; font-size: 1rem;">
                <span>📊 <?= count($availableBeats) ?> beats disponibles</span>
                <span>✅ <?= count($soldBeats) ?> beats vendus</span>
            </div>
        </div>

        <?php if (session()->get('user_id') && (int)session()->get('user_id') !== (int)$artist['id']) : ?>
            <div>
                <a href="<?= site_url('/conversations/with/' . (int)$artist['id']) ?>" class="btn-action btn-contact" style="display: inline-block; padding: 12px 24px; background: var(--primary-blue); color: white; border-radius: 6px; text-decoration: none; font-weight: 600;">
                    💬 Contacter
                </a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Beats disponibles -->
    <?php if (!empty($availableBeats)): ?>
        <section style="margin-bottom: 50px;">
            <h2 style="color: #1e293b; font-size: 1.8rem; margin-bottom: 20px;">Beats disponibles</h2>
            
            <div class="beats-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px;">
                <?php foreach ($availableBeats as $beat): ?>
                    <div class="beat-card" style="background: white; border-radius: 8px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); transition: transform 0.2s;">
                        <a href="<?= site_url('beats/' . $beat['id']) ?>" style="text-decoration: none; color: inherit;">
                            <h3 style="margin: 0 0 10px 0; color: #1e293b; font-size: 1.2rem;"><?= esc($beat['title']) ?></h3>
                            
                            <div style="color: #64748b; font-size: 0.9rem; margin-bottom: 10px;">
                                <?php if (!empty($beat['category_id'])): ?>
                                    <span>📁 Catégorie <?= esc($beat['category_id']) ?></span>
                                <?php endif; ?>
                            </div>

                            <?php if (!empty($beat['bpm']) || !empty($beat['musical_key'])): ?>
                                <div style="color: #64748b; font-size: 0.9rem; margin-bottom: 10px;">
                                    <?php if (!empty($beat['bpm'])): ?>
                                        <span>🥁 <?= esc($beat['bpm']) ?> BPM</span>
                                    <?php endif; ?>
                                    <?php if (!empty($beat['musical_key'])): ?>
                                        <span style="margin-left: 10px;">🎹 <?= esc($beat['musical_key']) ?></span>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <div style="margin-top: 15px; font-size: 1.5rem; font-weight: 700; color: var(--primary-blue);">
                                <?= esc($beat['price']) ?> €
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    <?php else: ?>
        <section style="margin-bottom: 50px;">
            <h2 style="color: #1e293b; font-size: 1.8rem; margin-bottom: 20px;">Beats disponibles</h2>
            <p style="color: #64748b; text-align: center; padding: 40px;">Aucun beat disponible pour le moment.</p>
        </section>
    <?php endif; ?>

    <!-- Beats vendus -->
    <?php if (!empty($soldBeats)): ?>
        <section>
            <h2 style="color: #1e293b; font-size: 1.8rem; margin-bottom: 20px;">Beats vendus</h2>
            
            <div class="beats-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px;">
                <?php foreach ($soldBeats as $beat): ?>
                    <div class="beat-card" style="background: white; border-radius: 8px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); opacity: 0.7;">
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                            <h3 style="margin: 0; color: #1e293b; font-size: 1.2rem;"><?= esc($beat['title']) ?></h3>
                            <span style="background: #22c55e; color: white; padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; font-weight: 600;">VENDU</span>
                        </div>
                        
                        <div style="color: #64748b; font-size: 0.9rem; margin-bottom: 10px;">
                            <?php if (!empty($beat['category_id'])): ?>
                                <span>📁 Catégorie <?= esc($beat['category_id']) ?></span>
                            <?php endif; ?>
                        </div>

                        <div style="margin-top: 15px; font-size: 1.5rem; font-weight: 700; color: #64748b;">
                            <?= esc($beat['price']) ?> €
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
