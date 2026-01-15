<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<link rel="stylesheet" href="<?= base_url('css/legal.css') ?>">

<div class="legal-container">
    <h1>À propos de TEMPO</h1>

    <div class="legal-section">
        <h2> Notre Mission</h2>
        <p><span class="highlight">TEMPO</span> est une plateforme d'intermédiation dédiée à l'échange de productions musicales entre beatmakers et interprètes.</p>
        <p>Dans un environnement musical saturé où l'authenticité devient rare, nous permettons aux artistes de construire une <span class="highlight">signature sonore unique</span> grâce à l'exclusivité systématique.</p>
    </div>

    <div class="legal-section">
        <h2> Notre Vision</h2>
        <p>Nous croyons que chaque artiste mérite un son qui lui est propre. En garantissant l'exclusivité totale de chaque beat vendu, nous protégeons l'identité sonore de nos utilisateurs et valorisons le travail des beatmakers.</p>
        <ul>
            <li><strong>Exclusivité totale</strong> : Chaque beat est retiré définitivement après achat</li>
            <li><strong>Transparence</strong> : Pas de frais cachés, prix directs</li>
            <li><strong>Éthique RGPD</strong> : Minimisation des données, respect de la vie privée</li>
        </ul>
    </div>

    <div class="legal-section">
        <h2> Ce qui nous différencie</h2>
        <p><strong>1. Exclusivité Systématique</strong></p>
        <p>Contrairement aux plateformes classiques qui vendent des licences multiples, TEMPO garantit qu'un beat acheté n'appartient qu'à vous. Votre son reste unique.</p>
        
        <p><strong>2. Support Réactif</strong></p>
        <p>Notre équipe s'engage à répondre sous 24h pour tous vos problèmes techniques ou questions juridiques.</p>
        
        <p><strong>3. Transparence Totale</strong></p>
        <p>Pas de commission cachée, prix clairs, droits d'auteur respectés.</p>
    </div>

    <div class="legal-section">
        <h2> L'Équipe</h2>
        <p><span class="highlight">TEMPO</span> est un projet pédagogique développé par une équipe de 4 étudiants en BUT Informatique :</p>
        <ul>
            <li><strong>Bracq Noé</strong></li>
            <li><strong>Devillers Tino</strong> </li>
            <li><strong>Dufresne Elric</strong> </li>
            <li><strong>Martin Sacha</strong></li>
        </ul>
        <p><em>Année universitaire 2025-2026 - IUT</em></p>
    </div>

    <div class="legal-section">
        <h2> Technologies Utilisées</h2>
        <ul>
            <li><strong>Backend</strong> : PHP 8, CodeIgniter 4 (Framework MVC)</li>
            <li><strong>Base de données</strong> : MySQL</li>
            <li><strong>Frontend</strong> : HTML5, CSS3, JavaScript vanilla</li>
        </ul>
    </div>

    <div class="legal-section">
        <h2> Statistiques du Projet</h2>
        <ul>
            <li> Plateforme d'échange de beats exclusifs</li>
            <li> 100% des beats vendus en exclusivité</li>
            <li> Support client sous 24h</li>
            <li> Protection RGPD et minimisation des données</li>
            <li> Design moderne et responsive</li>
        </ul>
    </div>

    <div class="legal-section">
        <h2> Roadmap Future</h2>
        <p>Fonctionnalités prévues pour les prochaines versions :</p>
        <ul>
            <li>Système de notation et avis sur les vendeurs</li>
            <li>Messagerie en temps réel (WebSocket)</li>
            <li>Abonnements premium pour les beatmakers</li>
            <li>API publique pour intégrations tierces</li>
            <li>Application mobile (iOS/Android)</li>
            <li>Système de royalties automatiques</li>
        </ul>
    </div>

    <div class="legal-section">
        <h2> Nous Contacter</h2>
        <p>Vous avez des questions, suggestions ou souhaitez collaborer ?</p>
        <p><a href="<?= site_url('/contact') ?>" class="highlight">→ Accéder à la page Contact</a></p>
    </div>

    <div class="legal-section">
        <h2> Remerciements</h2>
        <p>Nous tenons à remercier :</p>
        <ul>
            <li>L'équipe pédagogique de l'IUT pour leur accompagnement</li>
            <li>La communauté des beatmakers indépendants pour leur confiance</li>
            <li>Tous les utilisateurs qui testent et améliorent la plateforme</li>
        </ul>
    </div>

    <p style="text-align: center; font-size: 1.2rem; color: #3b82f6; font-weight: bold; margin-top: 40px;">
        TEMPO - Votre son, votre identité
    </p>
</div>
<?= $this->endSection() ?>
