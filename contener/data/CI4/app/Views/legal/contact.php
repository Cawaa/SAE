<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<link rel="stylesheet" href="<?= base_url('css/legal.css') ?>">

<div class="legal-container">
    <h1>Contactez-nous</h1>

    <div class="legal-section">
        <h2> Support & Questions</h2>
        <p>Vous avez une question sur nos services, un problème technique ou besoin d'aide ?</p>
        <p><strong>Email :</strong> <a href="mailto:support@tempo-beats.com" class="highlight">support@tempo-beats.com</a></p>
        <p><strong>Délai de réponse :</strong> Nous nous engageons à répondre sous <span class="highlight">24h ouvrées</span></p>
    </div>

    <div class="legal-section">
        <h2> Données Personnelles (RGPD)</h2>
        <p>Pour toute demande concernant vos données personnelles (accès, rectification, suppression) :</p>
        <p><strong>Email DPO :</strong> <a href="mailto:privacy@tempo-beats.com" class="highlight">privacy@tempo-beats.com</a></p>
        <p><strong>Email DPO :</strong> <a href="mailto:dpo@tempo-beats.com" class="highlight">dpo@tempo-beats.com</a></p>
    </div>

    <div class="legal-section">
        <h2> Partenariats & Business</h2>
        <p>Vous êtes un label, un studio ou souhaitez établir un partenariat ?</p>
        <p><strong>Email :</strong> <a href="mailto:business@tempo-beats.com" class="highlight">business@tempo-beats.com</a></p>
    </div>

    <div class="legal-section">
        <h2> Signaler un Abus</h2>
        <p>Vous avez détecté un contenu frauduleux, une violation de droits d'auteur ou un comportement inapproprié ?</p>
        <p><strong>Email :</strong> <a href="mailto:abuse@tempo-beats.com" class="highlight">abuse@tempo-beats.com</a></p>
        <p><em>Merci d'inclure : lien vers le contenu, description du problème, preuves si disponibles</em></p>
    </div>

    <div class="legal-section">
        <h2> Réseaux Sociaux</h2>
        <p>Suivez-nous et restez informés de nos actualités :</p>
        <ul>
            <li><strong>Instagram :</strong> <a href="#" class="highlight">@tempo.beats</a></li>
            <li><strong>Twitter/X :</strong> <a href="#" class="highlight">@tempobeats</a></li>
            <li><strong>Facebook :</strong> <a href="#" class="highlight">TEMPO Beats</a></li>
        </ul>
    </div>

    <div class="legal-section">
        <h2> Informations Légales</h2>
        <p><strong>TEMPO</strong> - Projet pédagogique IUT</p>
        <p>Équipe : Bracq Noé, Devillers Tino, Dufresne Elric, Martin Sacha</p>
        <p>BUT Informatique - Année 2025-2026</p>
    </div>

    <div class="legal-section">
        <h2> FAQ Rapide</h2>
        <p><strong>Comment publier un beat ?</strong></p>
        <p>→ Créez un compte, allez dans "Mon compte" > "Mes beats" > "Publier un beat"</p>
        
        <p><strong>Comment acheter un beat ?</strong></p>
        <p>→ Parcourez la boutique, ajoutez au panier, passez commande</p>
        
        <p><strong>Les beats sont-ils exclusifs ?</strong></p>
        <p>→ Oui ! Chaque beat est retiré du catalogue après achat</p>
        
        <p><strong>Comment contacter un vendeur ?</strong></p>
        <p>→ Sur la page du beat, cliquez sur "Contacter" pour démarrer une conversation</p>
    </div>

    <p style="text-align: center; font-size: 0.9rem; color: #777; margin-top: 40px;">
         <strong>Astuce :</strong> Avant de nous contacter, consultez nos <a href="<?= site_url('/conditions-utilisation') ?>" class="highlight">CGU</a> et notre <a href="<?= site_url('/confidentialite') ?>" class="highlight">Politique de confidentialité</a>
    </p>
</div>
<?= $this->endSection() ?>
