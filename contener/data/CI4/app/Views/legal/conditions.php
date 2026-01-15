<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<link rel="stylesheet" href="<?= base_url('css/legal.css') ?>">

<div class="legal-container">
    <h1>Conditions Générales d'Utilisation</h1>

    <div class="legal-section">
        <h2>1. Objet du Service</h2>
        <p>Tempo est une plateforme d'intermédiation dédiée à l'échange de productions musicales entre beatmakers et interprètes. Notre mission est de vous permettre de construire une <span class="highlight">signature sonore unique</span> dans un environnement saturé.</p>
    </div>

    <div class="legal-section">
        <h2>2. Licence et Exclusivité</h2>
        <p>Toute transaction sur la plateforme garantit une <span class="highlight">exclusivité systématique</span> :</p>
        <ul>
            <li>L'acheteur devient l'unique exploitant de l'instrumentale après validation du paiement.</li>
            <li>Le créateur (Beatmaker) conserve ses droits d'auteur moraux sur l'œuvre.</li>
            <li>Dès l'achat, l'œuvre est <span class="highlight">retirée définitivement du catalogue</span> public pour protéger l'identité sonore de l'acquéreur.</li>
        </ul>
        <p>Un watermark audio est appliqué sur toutes les pré-écoutes pour prévenir toute utilisation frauduleuse avant achat.</p>
    </div>

    <div class="legal-section">
        <h2>3. Responsabilités</h2>
        <p>Les vendeurs garantissent être les titulaires exclusifs des droits sur les œuvres qu'ils proposent. Tempo agit comme tiers de confiance et met à disposition un support client réactif sous 24h pour la gestion des litiges techniques ou de conformité.</p>
    </div>

    <div class="legal-section">
        <h2>4. Données Personnelles (RGPD)</h2>
        <p>Conformément à notre démarche éthique, nous appliquons une politique de minimisation des données :</p>
        <ul>
            <li>Nous ne collectons que les informations strictement nécessaires au service (compte, transactions).</li>
            <li>Les mots de passe sont stockés sous forme chiffrée.</li>
            <li>Vous disposez d'un droit à l'oubli : la suppression de votre compte anonymise vos données personnelles de notre historique.</li>
        </ul>
    </div>

    <p style="text-align: center; font-size: 0.9rem; color: #777;">
        Dernière mise à jour : <?= date('d/m/Y') ?>
    </p>
</div>
<?= $this->endSection() ?>