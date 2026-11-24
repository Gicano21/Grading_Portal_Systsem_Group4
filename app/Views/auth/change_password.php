<?= $this->extend('Layout/portal') ?>
<?= $this->section('title') ?>Change password<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="page-heading">
    <div><p class="eyebrow">ACCOUNT SECURITY</p><h1>Change password</h1><p class="muted">Confirm your current password before choosing a new one.</p></div>
</div>
<section class="panel login-panel">
    <form method="post" action="<?= site_url('account/password') ?>" class="form-stack">
        <?= csrf_field() ?>
        <label for="current_password">Current password</label>
        <input id="current_password" name="current_password" type="password" autocomplete="current-password" required>
        <label for="password">New password</label>
        <input id="password" name="password" type="password" autocomplete="new-password" minlength="8" required>
        <label for="password_confirm">Confirm new password</label>
        <input id="password_confirm" name="password_confirm" type="password" autocomplete="new-password" minlength="8" required>
        <button type="submit" class="button button-primary">Update password</button>
    </form>
</section>
<?= $this->endSection() ?>
