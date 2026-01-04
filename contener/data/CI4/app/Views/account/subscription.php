<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<h1>Abonnement</h1>

<h3>Attention, l'achat d'un abonnement n'est pas remboursable ! </h3>

<?php if ($msg = session()->getFlashdata('success')) : ?>
    <p style="color:green;"><?= esc($msg) ?></p>
<?php endif; ?>
<?php if ($msg = session()->getFlashdata('error')) : ?>
    <p style="color:red;"><?= esc($msg) ?></p>
<?php endif; ?>

<?php if (empty($subscription)) : ?>
    <p><strong>Statut :</strong> Aucun abonnement actif.</p>
<?php else: ?>
    <p><strong>Statut :</strong> Abonnement actif</p>
    <p><strong>Type :</strong> <?= esc($subscription['type'] ?? '—') ?></p>
    <p><strong>Début :</strong> <?= esc($subscription['started_at'] ?? '—') ?></p>
    <p><strong>Fin :</strong> <?= esc($subscription['ends_at'] ?? '—') ?></p>
<?php endif; ?>

<hr>

<h2>Acheter un abonnement (simulation)</h2>
<p>
    Ici, on simule l’achat : cliquer active (ou prolonge) un abonnement en base, sans paiement réel.
</p>

<form method="post" action="<?= site_url('/account/subscription/buy') ?>">
    <?= csrf_field() ?>

    <label for="type">Choisir une offre :</label>
    <select name="type" id="type">
        <option value="premium">Premium (30 jours)</option>
        <option value="pro">Pro (30 jours)</option>
    </select>

    <button type="submit">Activer / Prolonger</button>
</form>
</p>
    Pour annuler un abonnement merci de nous contacter. 
<p>
<p style="margin-top:1rem;">
    <a href="<?= site_url('/account') ?>">← Retour Mon compte</a>



<?= $this->endSection() ?>
