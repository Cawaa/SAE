<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<h1>Artistes</h1>

<style>
.artist-row{display:flex;gap:16px;overflow-x:auto;padding-bottom:10px}
.artist-card{flex:0 0 260px;border-radius:12px;padding:14px}
.artist-avatar{display:flex;justify-content:center;margin-bottom:10px}
.artist-avatar img{width:84px;height:84px;border-radius:999px;object-fit:cover}
.artist-name,.artist-genre,.artist-stats{text-align:center;margin:6px 0}
.beat-chips{display:flex;gap:8px;flex-wrap:wrap;justify-content:center;margin-top:10px}
.beat-chip{padding:6px 10px;border-radius:999px;font-size:.9rem;white-space:nowrap;text-decoration:none}
</style>

<section>
  <h2>Les beatmakers qui vendent le plus</h2>

  <div class="artist-row">
    <?php foreach ($topSellers as $u): ?>
      <div class="artist-card">

        <div class="artist-avatar">
          <?php if (!empty($u['avatar'])): ?>
            <img src="<?= esc($u['avatar']) ?>" alt="Avatar">
          <?php endif; ?>
        </div>

        <div class="artist-name"><?= esc($u['username']) ?></div>

        <?php if (!empty($u['artist_genre'])): ?>
          <div class="artist-genre"><?= esc($u['artist_genre']) ?></div>
        <?php endif; ?>

        <div class="artist-stats"><?= (int)$u['sold_count'] ?> beats vendus</div>

        <div class="beat-chips">
          <?php foreach (($soldBeats[(int)$u['user_id']] ?? []) as $b): ?>
            <a class="beat-chip" href="<?= site_url('beats/'.$b['id']) ?>" title="<?= esc($b['category_name']) ?>">
              <?= esc($b['title']) ?>
            </a>
          <?php endforeach; ?>
        </div>

      </div>
    <?php endforeach; ?>
  </div>
</section>

<section style="margin-top:24px;">
  <h2>Les beatmakers qui postent le plus</h2>

  <div class="artist-row">
    <?php foreach ($topPosters as $u): ?>
      <div class="artist-card">

        <div class="artist-avatar">
          <?php if (!empty($u['avatar'])): ?>
            <img src="<?= esc($u['avatar']) ?>" alt="Avatar">
          <?php endif; ?>
        </div>

        <div class="artist-name"><?= esc($u['username']) ?></div>

        <?php if (!empty($u['artist_genre'])): ?>
          <div class="artist-genre"><?= esc($u['artist_genre']) ?></div>
        <?php endif; ?>

        <div class="artist-stats"><?= (int)$u['available_count'] ?> beats disponibles</div>

        <div class="beat-chips">
          <?php foreach (($availBeats[(int)$u['user_id']] ?? []) as $b): ?>
            <a class="beat-chip" href="<?= site_url('beats/'.$b['id']) ?>" title="<?= esc($b['category_name']) ?>">
              <?= esc($b['title']) ?>
            </a>
          <?php endforeach; ?>
        </div>

      </div>
    <?php endforeach; ?>
  </div>
</section>

<section style="margin-top:24px;">
  <h2>Les beatmakers qui font des promotions ? </h2>

</section>

<?= $this->endSection() ?>
