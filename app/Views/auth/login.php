<?= $this->extend('Layout/portal') ?>
<?= $this->section('title') ?>Sign in<?= $this->endSection() ?>
<?= $this->section('content') ?>
<section class="login-layout">
    <div class="login-intro">
        <p class="eyebrow">GRADING PORTAL MANAGEMENT SYSTEM</p>
        <h1>Academic records, <span>within reach.</span></h1>
        <p>Sign in to view grades, manage class lists, and support your academic community.</p>
        <div class="login-highlights">
            <div><strong>01</strong><span>Secure, role-based access</span></div>
            <div><strong>02</strong><span>One place for class grades</span></div>
            <div><strong>03</strong><span>Built for students and faculty</span></div>
        </div>
    </div>
    <div class="panel login-panel">
        <p class="eyebrow">WELCOME BACK</p>
        <h2>Sign in to your account</h2>
        <p class="muted">Use the account provided by your school administrator.</p>
        <?php foreach ($errors as $validationError): ?>
            <div class="notice notice-error"><?= esc($validationError) ?></div>
        <?php endforeach; ?>
        <form method="post" action="<?= site_url('login') ?>" class="form-stack">
            <?= csrf_field() ?>
            <label for="username">Username</label>
            <input id="username" name="username" value="<?= esc($username) ?>" autocomplete="username" required>
            <label for="password">Password</label>
            <input id="password" name="password" type="password" autocomplete="current-password" required>
            <button type="submit" class="button button-primary button-wide">Sign in</button>
        </form>
        <?php if (ENVIRONMENT !== 'production'): ?>
            <details class="demo-access">
                <summary>Local demo accounts</summary>
                <p>For local development only. Change or remove these seeded passwords before deploying.</p>
                <ul>
                    <li>Admin: <code>admin</code> / <code>admin123</code></li>
                    <li>Registrar: <code>registrar</code> / <code>registrar123</code></li>
                    <li>Instructor: <code>teacher1</code> / <code>teacher123</code></li>
                    <li>Student: <code>student1</code> / <code>student123</code></li>
                </ul>
            </details>
        <?php endif; ?>
    </div>
</section>
<?= $this->endSection() ?>
