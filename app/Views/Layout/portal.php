<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($this->renderSection('title')) ?> | GradePortal</title>
    <link rel="stylesheet" href="<?= base_url('css/portal.css') ?>">
</head>
<body>
    <?php $user = session('user'); ?>
    <header class="topbar">
        <a class="brand" href="<?= site_url($user ? 'dashboard' : 'login') ?>">Grade<span>Portal</span></a>
        <?php if ($user): ?>
            <nav class="navigation" aria-label="Main navigation">
                <?php if ($user['role'] === 'Admin'): ?>
                    <a href="<?= site_url('admin') ?>">Overview</a>
                    <a href="<?= site_url('admin/classes') ?>">Classes &amp; grades</a>
                <?php elseif ($user['role'] === 'Registrar'): ?>
                    <a href="<?= site_url('registrar') ?>">Registrar workspace</a>
                <?php elseif ($user['role'] === 'Instructor'): ?>
                    <a href="<?= site_url('instructor/classes') ?>">My classes</a>
                <?php else: ?>
                    <a href="<?= site_url('student/grades') ?>">My grades</a>
                <?php endif; ?>
            </nav>
            <div class="user-menu">
                <span><?= esc($user['username']) ?> <small><?= esc($user['role']) ?></small></span>
                <form method="post" action="<?= site_url('logout') ?>">
                    <?= csrf_field() ?>
                    <button class="button button-quiet" type="submit">Sign out</button>
                </form>
            </div>
        <?php endif; ?>
    </header>
    <main class="page">
        <?php if ($message = session()->getFlashdata('message')): ?>
            <div class="notice notice-success" role="status"><?= esc($message) ?></div>
        <?php endif; ?>
        <?php if ($error = session()->getFlashdata('error')): ?>
            <div class="notice notice-error" role="alert"><?= esc($error) ?></div>
        <?php endif; ?>
        <?php foreach (session()->getFlashdata('gradeErrors') ?? [] as $gradeError): ?>
            <div class="notice notice-error" role="alert"><?= esc($gradeError) ?></div>
        <?php endforeach; ?>
        <?= $this->renderSection('content') ?>
    </main>
    <footer class="footer">GradePortal <span>Academic records, organized.</span></footer>
</body>
</html>
