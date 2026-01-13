<?= $this->extend('layouts/main') ?>

<?= $this->section('extra-css') ?>
    <link rel="stylesheet" href="<?= base_url('css/account.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="container">
    <h1>Wallet</h1>

    <?php if (session()->getFlashdata('error')) : ?>
        <p><strong><?= esc(session()->getFlashdata('error')) ?></strong></p>
    <?php endif; ?>

    <?php if (session()->getFlashdata('success')) : ?>
        <p><strong><?= esc(session()->getFlashdata('success')) ?></strong></p>
    <?php endif; ?>

    <?php if (empty($wallet)) : ?>
        <div class="empty-state">
            <p>Aucun wallet pour le moment.</p>
        </div>
    <?php else: ?>
        <div class="balance-card">
            <p>Solde actuel</p>
            <div class="balance-amount">
                <?= number_format(((int)$wallet['balance_cents'])/100, 2, ',', ' ') ?> €
            </div>
            <p>Votre argent est sécurisé et prêt à être utilisé ou retiré.</p>
        </div>
    <?php endif; ?>

    <hr>

    <h2>Mes achats</h2>

    <?php if (empty($purchases)) : ?>
        <div class="empty-state">
            <p>Vous n’avez encore acheté aucun beat.</p>
        </div>
    <?php else: ?>

        <?php
        // Regroupement par commande pour un affichage propre
        $groups = [];
        foreach ($purchases as $row) {
            $oid = (int)($row['order_id'] ?? 0);
            if (!isset($groups[$oid])) {
                $groups[$oid] = [
                    'paid_at'     => $row['paid_at'] ?? null,
                    'total_cents' => (int)($row['total_cents'] ?? 0),
                    'items'       => [],
                ];
            }
            $groups[$oid]['items'][] = $row;
        }
        ?>

        <?php foreach ($groups as $orderId => $g): ?>
            <div class="balance-card" style="margin-top: 16px;">
                <p><strong>Commande #<?= (int)$orderId ?></strong></p>

                <?php if (!empty($g['paid_at'])): ?>
                    <p><small>Achetée le <?= esc($g['paid_at']) ?></small></p>
                <?php endif; ?>

                <p>Total commande : <strong><?= number_format($g['total_cents']/100, 2, ',', ' ') ?> €</strong></p>

                <ul>
                    <?php foreach ($g['items'] as $it): ?>
                        <li style="margin: 6px 0;">
                            <?= esc($it['beat_title'] ?? ('Beat #' . (int)$it['beat_id'])) ?>
                            — <?= number_format(((int)($it['price_cents'] ?? 0))/100, 2, ',', ' ') ?> €
                            —
                            <a href="<?= site_url('/beats/' . (int)$it['beat_id'] . '/download') ?>">
                                Retélécharger
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>

            </div>
        <?php endforeach; ?>

    <?php endif; ?>

    <a href="<?= site_url('/account') ?>" class="back-link">← Retour Mon compte</a>
</div>

<?= $this->endSection() ?>
