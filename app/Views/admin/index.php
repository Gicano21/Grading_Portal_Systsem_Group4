<?= $this->extend('Layout/portal') ?>
<?= $this->section('title') ?>Administrator overview<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="page-heading">
    <div><p class="eyebrow">SYSTEM ADMINISTRATION</p><h1>Overview</h1><p class="muted">Manage students, instructors, programs, and courses.</p></div>
</div>
<section class="stats-grid">
    <?php foreach (['students' => 'Students', 'instructors' => 'Instructors', 'programs' => 'Programs', 'courses' => 'Courses'] as $key => $label): ?>
        <article class="stat-card"><span><?= esc($label) ?></span><strong><?= esc((string) $stats[$key]) ?></strong><small>In the system</small></article>
    <?php endforeach; ?>
</section>
<section class="panel">
    <p class="eyebrow">PORTAL RECORDS</p><h2>Manage academic records</h2>
    <div class="record-links">
        <?php foreach (['students' => 'Students', 'instructors' => 'Instructors', 'programs' => 'Programs', 'courses' => 'Courses'] as $entity => $label): ?>
            <a class="button button-secondary" href="<?= site_url('admin/records/' . $entity) ?>">Manage <?= esc($label) ?></a>
        <?php endforeach; ?>
    </div>
</section>
<?= $this->endSection() ?>
