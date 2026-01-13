<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="admin-container">
    <h1>Panneau d'administration</h1>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <div class="admin-nav">
        <a href="#users-section" class="btn-nav">Gérer les Utilisateurs</a>
        <a href="#beats-section" class="btn-nav">Gérer les Annonces</a>
    </div>

    <hr>

    <section id="users-section">
        <h2>Utilisateurs (<?= count($users) ?>)</h2>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Pseudo</th>
                    <th>Email</th>
                    <th>Rôle</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= $user['id'] ?></td>
                    <td><?= esc($user['username']) ?></td>
                    <td><?= esc($user['email']) ?></td>
                    <td><span class="badge"><?= esc($user['role']) ?></span></td>
                    <td>
                        <?php if ($user['id'] != session()->get('user_id')): ?>
                            <form action="<?= site_url('admin/users/delete/'.$user['id']) ?>" method="post" onsubmit="return confirm('Supprimer cet utilisateur ?');">
                                <button type="submit" class="btn-delete">Supprimer</button>
                            </form>
                        <?php else: ?>
                            <small><em>(Vous)</em></small>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <br><br>
    <hr>

    <section id="beats-section">
        <h2>Annonces / Beats (<?= count($beats) ?>)</h2>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Titre</th>
                    <th>Prix</th>
                    <th>Statut</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($beats as $beat): ?>
                <tr>
                    <td><?= $beat['id'] ?></td>
                    <td><?= esc($beat['title']) ?></td>
                    <td><?= esc($beat['price']) ?> €</td>
                    <td><?= esc($beat['status']) ?></td>
                    <td>
                        <form action="<?= site_url('admin/beats/delete/'.$beat['id']) ?>" method="post" onsubmit="return confirm('Supprimer cette annonce définitivement ?');">
                            <button type="submit" class="btn-delete">Supprimer</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>
</div>

<style>
    .admin-container { padding: 20px; }
    .admin-nav { margin-bottom: 20px; }
    .btn-nav { padding: 10px 15px; background: #eee; text-decoration: none; color: #333; border-radius: 4px; margin-right: 10px; border: 1px solid #ccc; }
    .admin-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    .admin-table th, .admin-table td { border: 1px solid #ddd; padding: 12px; text-align: left; }
    .admin-table th { background-color: #f4f4f4; }
    .btn-delete { background: #ff4444; color: white; border: none; padding: 5px 10px; cursor: pointer; border-radius: 3px; }
    .alert { padding: 10px; margin-bottom: 20px; border-radius: 4px; }
    .alert-success { background: #d4edda; color: #155724; }
    .alert-danger { background: #f8d7da; color: #721c24; }
    .badge { background: #007bff; color: white; padding: 2px 6px; border-radius: 4px; font-size: 0.8em; }
</style>
<?= $this->endSection() ?>