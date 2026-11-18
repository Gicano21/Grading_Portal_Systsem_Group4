<?= $this->extend('Layout/portal') ?>
<?= $this->section('title') ?>Classes and grades<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="page-heading"><div><p class="eyebrow">SYSTEM ADMINISTRATION</p><h1>Classes &amp; grades</h1><p class="muted">Review class rosters and enter or correct grades.</p></div></div>
<section class="panel">
    <?php if ($classes === []): ?>
        <p class="empty-state">No class offerings found. A registrar can add classes in the registrar workspace.</p>
    <?php else: ?>
        <div class="table-wrap"><table>
            <thead><tr><th>Course</th><th>Section</th><th>Academic year</th><th>Instructor</th><th>Roster</th><th>Actions</th></tr></thead>
            <tbody><?php foreach ($classes as $class): ?>
                <tr>
                    <td><strong><?= esc($class['course_code']) ?></strong><span class="table-sub"><?= esc($class['course']) ?></span></td>
                    <td><?= esc($class['section']) ?></td>
                    <td><?= esc($class['academic_year']) ?><span class="table-sub"><?= esc($class['semester']) ?></span></td>
                    <td><?= esc($class['instructor_first_name'] . ' ' . $class['instructor_last_name']) ?></td>
                    <td><?= esc((string) $class['student_count']) ?> students</td>
                    <td><a class="button button-small button-secondary" href="<?= site_url('admin/classes/' . $class['class_offering_id'] . '/grades') ?>">View roster &amp; grades</a></td>
                </tr>
            <?php endforeach; ?></tbody>
        </table></div>
    <?php endif; ?>
</section>
<?= $this->endSection() ?>
