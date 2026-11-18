<?= $this->extend('Layout/portal') ?>
<?= $this->section('title') ?>My classes<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="page-heading"><div><p class="eyebrow">INSTRUCTOR WORKSPACE</p><h1>My classes</h1><p class="muted">View your assigned rosters and manage student grades.</p></div></div>
<section class="panel">
    <?php if ($classes === []): ?>
        <p class="empty-state">No classes are currently assigned to your account.</p>
    <?php else: ?>
        <div class="table-wrap"><table>
            <thead><tr><th>Course</th><th>Section</th><th>Academic year</th><th>Students</th><th></th></tr></thead>
            <tbody><?php foreach ($classes as $class): ?>
                <tr>
                    <td><strong><?= esc($class['course_code']) ?></strong><span class="table-sub"><?= esc($class['course']) ?></span></td>
                    <td><?= esc($class['section']) ?></td>
                    <td><?= esc($class['academic_year']) ?><span class="table-sub"><?= esc($class['semester']) ?></span></td>
                    <td><?= esc((string) $class['student_count']) ?></td>
                    <td><a class="button button-small button-primary" href="<?= site_url('instructor/classes/' . $class['class_offering_id'] . '/grades') ?>">Open gradebook</a></td>
                </tr>
            <?php endforeach; ?></tbody>
        </table></div>
    <?php endif; ?>
</section>
<?= $this->endSection() ?>
