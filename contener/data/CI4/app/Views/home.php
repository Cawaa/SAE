<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
    <h1>Découvrez des beats de créateurs</h1>
    <p>Achetez des instrumentales exclusives pour soutenir des beatmakers indépendants.</p>

    <br>
    <a href="<?= base_url('/beats') ?>">Voir le catalogue des beats</a>

    <hr>

    <?php if (!empty($stats)) : ?>
        <p>
            <strong>Stats :</strong>
            <?= esc($stats['active_beats'] ?? 0) ?> beats actifs /
            <?= esc($stats['total_beats'] ?? 0) ?> beats au total —
            <?= esc($stats['sold_beats'] ?? 0) ?> vendus —
            <?= esc($stats['total_users'] ?? 0) ?> utilisateurs
        </p>
    <?php endif; ?>

    <hr>

    <?php $beatsList = $beats ?? ($listings ?? []); ?>

    <?php if (empty($beatsList)) : ?>
        <p>Aucun beat pour le moment.</p>
    <?php else : ?>
        <h2>Derniers beats</h2>
        <ul>
            <?php foreach ($beatsList as $b) : ?>
                <li>
                    <a href="<?= base_url('/beats/' . (int)$b['id']) ?>">
                        <?= esc($b['title']) ?>
                    </a>
                    —
                    <?= esc($b['category_name'] ?? 'Sans genre') ?>
                    —
                    <?= esc($b['bpm'] ?? '—') ?> BPM
                    —
                    <?= esc($b['musical_key'] ?? '—') ?>
                    —
                    <?= esc($b['price']) ?> €
                    <small>(vendeur: <?= esc($b['seller_username'] ?? 'N/A') ?>)</small>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
<?= $this->endSection() ?>
