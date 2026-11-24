<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($this->renderSection('title')) ?> | GradePortal</title>
    <link rel="stylesheet" href="<?= base_url('css/portal.css') ?>">
</head>
<body>
    <?php
    $user = auth()->user();
    $roleLabel = 'User';
    foreach (['admin' => 'Admin', 'registrar' => 'Registrar', 'instructor' => 'Instructor', 'student' => 'Student'] as $group => $label) {
        if ($user?->inGroup($group)) {
            $roleLabel = $label;
            break;
        }
    }
    ?>
    <header class="topbar">
        <a class="brand" href="<?= site_url($user ? 'dashboard' : 'login') ?>">Grade<span>Portal</span></a>
        <?php if ($user): ?>
            <nav class="navigation" aria-label="Main navigation">
                <?php if ($user->inGroup('admin')): ?>
                    <a href="<?= site_url('admin/dashboard') ?>">Overview</a>
                    <a href="<?= site_url('admin/records/students') ?>">Students</a>
                    <a href="<?= site_url('admin/records/instructors') ?>">Instructors</a>
                    <a href="<?= site_url('admin/records/programs') ?>">Programs</a>
                    <a href="<?= site_url('admin/records/courses') ?>">Courses</a>
                <?php elseif ($user->inGroup('registrar')): ?>
                    <a href="<?= site_url('registrar') ?>">Registrar workspace</a>
                    <a href="<?= site_url('registrar/grades') ?>">Search grades</a>
                <?php elseif ($user->inGroup('instructor')): ?>
                    <a href="<?= site_url('instructor/classes') ?>">My classes</a>
                <?php else: ?>
                    <a href="<?= site_url('student/grades') ?>">My grades</a>
                <?php endif; ?>
            </nav>
            <div class="user-menu">
                <span><?= esc($user->username) ?> <small><?= esc($roleLabel) ?></small></span>
                <a class="button button-quiet" href="<?= site_url('account/password') ?>">Change password</a>
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
