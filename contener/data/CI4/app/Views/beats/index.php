<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<h1>Beats</h1>

<?php if (session()->get('user_id')) : ?>
    <p><a href="<?= base_url('/beats/create') ?>">Publier un beat</a></p>
<?php endif; ?>

<form method="GET" action="<?= base_url('/beats') ?>">
    <!-- IMPORTANT : recherche seulement sur clic -->
    <input type="hidden" name="do_search" value="1">

    <label>Recherche</label>
    <input type="text" name="q" value="<?= esc($filters['q'] ?? '') ?>">

    <label>Genre</label>
    <select name="category_id">
        <option value="">-- tous --</option>
        <?php foreach (($categories ?? []) as $c) : ?>
            <option value="<?= esc($c['id']) ?>" <?= ((string)($filters['category_id'] ?? '') === (string)$c['id']) ? 'selected' : '' ?>>
                <?= esc($c['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label>BPM min</label>
    <input type="number" name="bpm_min" min="0" value="<?= esc($filters['bpm_min'] ?? '') ?>">

    <label>BPM max</label>
    <input type="number" name="bpm_max" min="0" value="<?= esc($filters['bpm_max'] ?? '') ?>">

    <label>Clé</label>
    <input type="text" name="musical_key" placeholder="ex: Am" value="<?= esc($filters['musical_key'] ?? '') ?>">

    <button type="submit">Rechercher</button>
</form>

<hr>

<?php if (empty($doSearch)) : ?>
    <p><strong>Propositions de beats</strong> (filtre non lancé)</p>
<?php else : ?>
    <p><strong>Résultats de recherche</strong></p>
<?php endif; ?>

<hr>

<?php if (empty($beats)) : ?>
    <p>Aucun beat trouvé.</p>
<?php else : ?>
    <ul>
        <?php foreach ($beats as $b) : ?>
            <li>
                <a href="<?= base_url('/beats/' . (int)$b['id']) ?>">
                    <?= esc($b['title']) ?>
                </a>
                —
                <?= esc($b['category_name'] ?? 'Sans genre') ?>
                —
                <?= esc($b['bpm'] ?? '—') ?> BPM
                —
                <?= esc($b['musical_key'] ?? '—') ?>
                —
                <?= esc($b['price']) ?> €
                <small>(<?= esc($b['seller_username'] ?? 'N/A') ?>)</small>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<?= $this->endSection() ?>
