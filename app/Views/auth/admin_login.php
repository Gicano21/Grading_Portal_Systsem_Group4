<?= $this->extend('Layout/portal') ?>
<?= $this->section('title') ?>Administrator sign in<?= $this->endSection() ?>
<?= $this->section('content') ?>
<section class="login-layout">
    <div class="login-intro">
        <p class="eyebrow">ADMINISTRATOR ACCESS</p>
        <h1>Manage the <span>academic portal.</span></h1>
        <p>Administrator sign-in is separate from student, instructor, and registrar access.</p>
    </div>
    <div class="panel login-panel">
        <p class="eyebrow">SECURE WORKSPACE</p>
        <h2>Administrator sign in</h2>
        <form method="post" action="<?= site_url('admin') ?>" class="form-stack">
            <?= csrf_field() ?>
            <label for="username">Username</label>
            <input id="username" name="username" value="<?= esc($username) ?>" autocomplete="username" required>
            <label for="password">Password</label>
            <input id="password" name="password" type="password" autocomplete="current-password" required>
            <button type="submit" class="button button-primary button-wide">Sign in</button>
        </form>
        <p class="table-note"><a class="text-link" href="<?= site_url('login') ?>">Return to regular sign in</a></p>
    </div>
</section>
<?= $this->endSection() ?>
