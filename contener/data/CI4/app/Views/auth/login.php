<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<h1>Connexion</h1>

<?php if (!empty($error)) : ?>
    <p><?= esc($error) ?></p>
<?php endif; ?>

<form method="post" action="<?= base_url('/login') ?>">
    <?= csrf_field() ?>

    <label>Email</label><br>
    <input type="email" name="email" required><br><br>

    <label>Mot de passe</label><br>
    <input type="password" name="password" required><br><br>

    <button type="submit">Se connecter</button>
</form>

<p><a href="<?= base_url('/register') ?>">Créer un compte</a></p>

<?= $this->endSection() ?>
