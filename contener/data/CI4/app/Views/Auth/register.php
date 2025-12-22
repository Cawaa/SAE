<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
    <h2>S'inscrire</h2>
    <form action="<?= base_url('register') ?>" method="post">
        <input type="text" name="username" placeholder="Nom d'utilisateur" required><br><br>
        <input type="email" name="email" placeholder="Email" required><br><br>
        <input type="password" name="password" placeholder="Mot de passe" required><br><br>
        <button type="submit">Créer mon compte</button>
    </form>
<?= $this->endSection() ?>