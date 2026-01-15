<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<link rel="stylesheet" href="<?= base_url('css/legal.css') ?>">

<div class="legal-container">
    <h1>Politique de Confidentialité</h1>

    <div class="legal-section">
        <h2>1. Collecte des Données</h2>
        <p>Dans le cadre de notre engagement éthique, <span class="highlight">TEMPO</span> applique une politique stricte de <span class="highlight">minimisation des données</span>. Nous ne collectons que les informations strictement nécessaires au fonctionnement du service :</p>
        <ul>
            <li><strong>Données d'identification</strong> : pseudo, email (pour la création et gestion de compte)</li>
            <li><strong>Données de transaction</strong> : historique d'achats et de ventes (pour la comptabilité et la traçabilité)</li>
            <li><strong>Données de navigation</strong> : cookies essentiels uniquement (session, panier)</li>
            <li><strong>Contenus publiés</strong> : fichiers audio (beats), descriptions, métadonnées musicales</li>
        </ul>
        <p><strong>Nous ne collectons PAS :</strong> données de géolocalisation, profils publicitaires, historique de navigation à des fins marketing.</p>
    </div>

    <div class="legal-section">
        <h2>2. Utilisation des Données</h2>
        <p>Vos données personnelles sont utilisées uniquement pour :</p>
        <ul>
            <li>Créer et gérer votre compte utilisateur</li>
            <li>Traiter vos transactions d'achat et de vente</li>
            <li>Assurer le support client et la résolution de litiges</li>
            <li>Respecter nos obligations légales (comptabilité, fiscalité)</li>
        </ul>
        <p class="highlight">Aucune revente, aucun partage avec des tiers commerciaux.</p>
    </div>

    <div class="legal-section">
        <h2>3. Sécurité et Protection</h2>
        <p>Nous mettons en œuvre des mesures de sécurité robustes :</p>
        <ul>
            <li><strong>Chiffrement des mots de passe</strong> : algorithmes bcrypt/argon2</li>
            <li><strong>Connexions sécurisées</strong> : HTTPS obligatoire</li>
            <li><strong>Stockage sécurisé</strong> : serveurs protégés, sauvegardes régulières</li>
            <li><strong>Watermarking audio</strong> : protection des previews contre l'usage frauduleux</li>
        </ul>
    </div>

    <div class="legal-section">
        <h2>4. Vos Droits (RGPD)</h2>
        <p>Conformément au Règlement Général sur la Protection des Données (RGPD), vous disposez des droits suivants :</p>
        <ul>
            <li><strong>Droit d'accès</strong> : consulter les données que nous détenons sur vous</li>
            <li><strong>Droit de rectification</strong> : corriger vos informations personnelles</li>
            <li><strong>Droit à l'effacement ("droit à l'oubli")</strong> : supprimer votre compte et anonymiser vos données</li>
            <li><strong>Droit d'opposition</strong> : refuser certains traitements</li>
            <li><strong>Droit à la portabilité</strong> : récupérer vos données dans un format structuré</li>
        </ul>
        <p>Pour exercer ces droits, contactez-nous à : <a href="mailto:privacy@tempo-beats.com" class="highlight">privacy@tempo-beats.com</a></p>
    </div>

    <div class="legal-section">
        <h2>5. Cookies</h2>
        <p>TEMPO utilise uniquement des <span class="highlight">cookies essentiels</span> au fonctionnement :</p>
        <ul>
            <li>Cookie de session (authentification)</li>
            <li>Cookie panier (mémorisation des articles)</li>
        </ul>
        <p>Aucun cookie publicitaire, aucun tracker tiers.</p>
    </div>

    <div class="legal-section">
        <h2>6. Conservation des Données</h2>
        <p>Les données sont conservées pendant :</p>
        <ul>
            <li><strong>Comptes actifs</strong> : durée d'utilisation du service</li>
            <li><strong>Comptes supprimés</strong> : anonymisation immédiate des données personnelles, conservation des données transactionnelles pour obligations légales (10 ans)</li>
            <li><strong>Fichiers audio</strong> : conservés tant que le beat est actif, suppression après vente (sauf copie de sauvegarde légale)</li>
        </ul>
    </div>

    <div class="legal-section">
        <h2>7. Modifications de la Politique</h2>
        <p>Nous nous réservons le droit de modifier cette politique. Les utilisateurs seront informés par email des changements majeurs.</p>
    </div>

    <div class="legal-section">
        <h2>8. Contact</h2>
        <p>Pour toute question concernant vos données personnelles :</p>
        <ul>
            <li>Email : <a href="mailto:privacy@tempo-beats.com" class="highlight">privacy@tempo-beats.com</a></li>
        </ul>
    </div>

    <p style="text-align: center; font-size: 0.9rem; color: #777; margin-top: 40px;">
        Dernière mise à jour : <?= date('d/m/Y') ?><br>
        Version 1.0
    </p>
</div>
<?= $this->endSection() ?>
