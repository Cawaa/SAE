<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<h1>Mon profil</h1>

<?php if (!empty($error)) : ?>
    <p style="color:red;"><?= esc($error) ?></p>
<?php endif; ?>
<?php if (!empty($success)) : ?>
    <p style="color:green;"><?= esc($success) ?></p>
<?php endif; ?>

<form method="post" enctype="multipart/form-data" action="<?= site_url('/account/profile') ?>">
    <?= csrf_field() ?>

    <p>
        <label>Genre musical (optionnel)</label><br>
        <input type="text" name="artist_genre" value="<?= esc($user['artist_genre'] ?? '') ?>">
    </p>

    <p>
        <label>Avatar (optionnel)</label><br>
        <input type="file" name="avatar" accept="image/*">
    </p>

    <button type="submit">Enregistrer</button>
</form>

<p><a href="<?= site_url('/account') ?>">← Retour Mon compte</a></p>

<?= $this->endSection() ?>
