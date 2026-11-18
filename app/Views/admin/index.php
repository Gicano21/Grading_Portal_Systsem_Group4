<?= $this->extend('Layout/portal') ?>
<?= $this->section('title') ?>Administrator overview<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="page-heading">
    <div><p class="eyebrow">SYSTEM ADMINISTRATION</p><h1>Overview</h1><p class="muted">Manage academic records and review class activity.</p></div>
    <a class="button button-primary" href="<?= site_url('admin/classes') ?>">Manage classes &amp; grades</a>
</div>
<section class="stats-grid">
    <?php foreach (['students' => 'Students', 'instructors' => 'Instructors', 'courses' => 'Courses', 'classes' => 'Class offerings'] as $key => $label): ?>
        <article class="stat-card"><span><?= esc($label) ?></span><strong><?= esc((string) $stats[$key]) ?></strong><small>In the system</small></article>
    <?php endforeach; ?>
</section>
<section class="panel">
    <div class="section-heading"><div><p class="eyebrow">ACADEMIC SCHEDULE</p><h2>Class offerings</h2></div><a class="text-link" href="<?= site_url('admin/classes') ?>">View all classes &rarr;</a></div>
    <?php if ($classes === []): ?>
        <p class="empty-state">No classes have been added yet.</p>
    <?php else: ?>
        <div class="table-wrap"><table>
            <thead><tr><th>Course</th><th>Section</th><th>Academic year</th><th>Instructor</th><th>Students</th><th></th></tr></thead>
            <tbody><?php foreach (array_slice($classes, 0, 5) as $class): ?>
                <tr>
                    <td><strong><?= esc($class['course_code']) ?></strong><span class="table-sub"><?= esc($class['course']) ?></span></td>
                    <td><?= esc($class['section']) ?></td>
                    <td><?= esc($class['academic_year']) ?><span class="table-sub"><?= esc($class['semester']) ?></span></td>
                    <td><?= esc($class['instructor_first_name'] . ' ' . $class['instructor_last_name']) ?></td>
                    <td><?= esc((string) $class['student_count']) ?></td>
                    <td><a class="text-link" href="<?= site_url('admin/classes/' . $class['class_offering_id'] . '/grades') ?>">Grades</a></td>
                </tr>
            <?php endforeach; ?></tbody>
        </table></div>
    <?php endif; ?>
</section>
<?= $this->endSection() ?>
