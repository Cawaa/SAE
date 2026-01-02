<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<h1>Inscription</h1>

<?php if (!empty($error)) : ?>
    <p><?= esc($error) ?></p>
<?php endif; ?>

<form method="post" action="<?= base_url('/register') ?>">
    <?= csrf_field() ?>

    <label>Pseudo</label><br>
    <input type="text" name="username" required><br><br>

    <label>Email</label><br>
    <input type="email" name="email" required><br><br>

    <label>Mot de passe</label><br>
    <input type="password" name="password" required><br><br>

    <label>Confirmer mot de passe</label><br>
    <input type="password" name="confirm_password" required><br><br>

    <button type="submit">Créer le compte</button>
</form>

<p><a href="<?= base_url('/login') ?>">Déjà un compte ?</a></p>

<?= $this->endSection() ?>
