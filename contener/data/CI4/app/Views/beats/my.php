<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<h1><?= esc($title ?? 'Mes beats') ?></h1>

<p>
    <a href="<?= site_url('/beats/create') ?>">+ Ajouter un beat</a>
</p>

<?php if (empty($beats)) : ?>
    <p>Tu n’as encore publié aucun beat.</p>
<?php else : ?>
    <table>
        <thead>
        <tr>
            <th>Titre</th>
            <th>Prix</th>
            <th>BPM</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($beats as $b) : ?>
            <tr>
                <td>
                    <a href="<?= site_url('/beats/' . (int)$b['id']) ?>">
                        <?= esc($b['title']) ?>
                    </a>
                </td>
                <td><?= number_format((float)$b['price'], 2, ',', ' ') ?> €</td>
                <td><?= esc($b['bpm'] ?? '') ?></td>
                <td><?= esc($b['status'] ?? '') ?></td>
                <td>
                    <a href="<?= site_url('/beats/' . (int)$b['id'] . '/edit') ?>">Modifier</a>

                    <form method="post" action="<?= site_url('/beats/' . (int)$b['id'] . '/delete') ?>" style="display:inline;">
                        <?= csrf_field() ?>
                        <button type="submit" onclick="return confirm('Supprimer ce beat ?')">Supprimer</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?= $this->endSection() ?>
