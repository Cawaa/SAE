<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
    <h1>Bienvenue sur TEMPO</h1>
    <p>Ceci est votre nouvelle page d'accueil personnalisée.</p>
    <a href="<?= base_url('/boutique') ?>">Voir le catalogue des produits</a>
<?= $this->endSection() ?>