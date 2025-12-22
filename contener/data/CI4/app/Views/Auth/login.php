<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div style="max-width: 400px; margin: 50px auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px; background-color: #f9f9f9;">
    <h2 style="text-align: center; color: #333;">Connexion à TEMPO</h2>

    <?php if (session()->getFlashdata('error')): ?>
        <div style="color: white; background-color: #ff4d4d; padding: 10px; border-radius: 5px; margin-bottom: 15px; text-align: center;">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('msg')): ?>
        <div style="color: white; background-color: #4CAF50; padding: 10px; border-radius: 5px; margin-bottom: 15px; text-align: center;">
            <?= session()->getFlashdata('msg') ?>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('login') ?>" method="post">
        <?= csrf_field() ?> <div style="margin-bottom: 15px;">
            <label for="email" style="display: block; margin-bottom: 5px;">Adresse Email</label>
            <input type="email" name="email" id="email" required 
                   style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">
        </div>

        <div style="margin-bottom: 20px;">
            <label for="password" style="display: block; margin-bottom: 5px;">Mot de passe</label>
            <input type="password" name="password" id="password" required 
                   style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">
        </div>

        <button type="submit" 
                style="width: 100%; padding: 12px; background-color: #333; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px;">
            Se connecter
        </button>
    </form>

    <p style="text-align: center; margin-top: 15px; font-size: 14px;">
        Pas encore de compte ? <a href="<?= base_url('register') ?>" style="color: #007bff; text-decoration: none;">S'inscrire ici</a>
    </p>
</div>
<?= $this->endSection() ?>