<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<style>
    /* Section Hero qui prend toute la page */
    .hero {
        height: calc(100vh - 70px); /* Toute la hauteur moins la nav */
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: flex-start;
        padding: 0 10%;
        color: white;
        background: rgba(0, 0, 0, 0.2); /* Léger voile pour la lisibilité */
    }

    .hero h1 { font-size: 3.5rem; margin: 0; max-width: 700px; line-height: 1.1; }
    .hero p { font-size: 1.3rem; margin: 30px 0; max-width: 550px; }

    /* Bouton bleu arrondi de la maquette */
    .btn-blue {
        background-color: #2563eb;
        color: white;
        padding: 15px 40px;
        border-radius: 30px;
        text-decoration: none;
        font-weight: bold;
        transition: transform 0.2s;
    }
    .btn-blue:hover { transform: scale(1.05); }

    /* Sections opaques qui "descendent" par-dessus l'image */
    .content-block {
        background: white; /* Fond blanc opaque obligatoire pour l'effet */
        padding: 80px 10%;
        position: relative;
        z-index: 2;
    }

    .section-title { text-align: center; font-size: 2rem; margin-bottom: 50px; }

    /* Grilles Beatmakers */
    .beatmakers-grid { display: flex; justify-content: center; gap: 50px; flex-wrap: wrap; }
    .artist-card { text-align: center; width: 220px; }
    .artist-img { width: 100%; height: 280px; background: #eee; border-radius: 15px; margin-bottom: 15px; }

    /* Grille Beats à la une */
    .beats-grid { 
        display: grid; 
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); 
        gap: 25px; 
    }
    .beat-card {
        border: 2px solid #2563eb;
        border-radius: 15px;
        padding: 20px;
        display: flex;
        align-items: center;
    }
</style>

<div class="hero">
    <h1>Découvrez des sons de créateurs</h1> <p>Achetez les productions de créateurs indépendants pour soutenir leur talent et leur savoir-faire.</p>
    <a href="#" class="btn-blue">En savoir plus</a>
</div>

<div class="content-block">
    <h2 class="section-title">Beatmakers incontournables</h2> <div class="beatmakers-grid">
        <div class="artist-card">
            <div class="artist-img"></div>
            <p><strong>SERAPHIM</strong></p>
        </div>
        <div class="artist-card">
            <div class="artist-img"></div>
            <p><strong>Vladimir Cauchemar</strong></p>
        </div>
        <div class="artist-card">
            <div class="artist-img"></div>
            <p><strong>Perceval</strong></p>
        </div>
    </div>
</div>

<div class="content-block" style="background-color: #fcfcfc; border-top: 1px solid #eee;">
    <h2 class="section-title">Beats à la une</h2> <div class="beats-grid">
        <?php for($i=0; $i<6; $i++): ?>
        <div class="beat-card">
            <div style="width:60px; height:60px; background:#2563eb; border-radius:10px; margin-right:20px;"></div>
            <div style="flex-grow:1;">
                <strong>Auteur : Vlad</strong><br>
                <small>Genre : Drill | 2:37</small>
            </div>
            <div style="font-weight:bold; color:#2563eb; font-size:1.2rem;">7.99€</div>
        </div>
        <?php endfor; ?>
    </div>
</div>

<?= $this->endSection() ?>