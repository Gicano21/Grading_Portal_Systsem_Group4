<?= $this->extend('Layout/portal') ?>
<?= $this->section('title') ?>Grade report<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="page-heading no-print">
    <div><p class="eyebrow">REGISTRAR REPORT</p><h1>Class grade report</h1><p class="muted"><?= esc($class['course_code'] . ' · ' . $class['course'] . ' · ' . $class['section'] . ' · ' . $class['academic_year'] . ' ' . $class['semester']) ?></p></div>
    <button class="button button-primary" type="button" onclick="window.print()">Print report</button>
</div>
<section class="panel report-panel">
    <div class="report-heading"><p class="eyebrow">GRADING PORTAL MANAGEMENT SYSTEM</p><h2><?= esc($class['course_code'] . ' — ' . $class['course']) ?></h2><p><?= esc($class['section'] . ' · ' . $class['academic_year'] . ' · ' . $class['semester']) ?></p><p>Instructor: <?= esc($class['instructor_first_name'] . ' ' . $class['instructor_last_name']) ?></p></div>
    <?php if ($roster === []): ?><p class="empty-state">There are no enrolled students in this class.</p><?php else: ?>
        <div class="table-wrap"><table>
            <thead><tr><th>#</th><th>Student</th><th>Prelim</th><th>Midterm</th><th>Pre-final</th><th>Final</th><th>Final grade</th></tr></thead>
            <tbody><?php foreach ($roster as $index => $student): ?>
                <tr><td><?= esc((string) ($index + 1)) ?></td><td><?= esc($student['last_name'] . ', ' . $student['first_name']) ?></td>
                    <?php foreach (['prelim', 'midterm', 'pre_final', 'final', 'final_grade'] as $period): ?><td><?= $student[$period] === null ? '—' : esc(number_format((float) $student[$period], 2)) ?></td><?php endforeach; ?>
                </tr>
            <?php endforeach; ?></tbody>
        </table></div>
    <?php endif; ?>
    <p class="table-note">Final grades are the arithmetic mean of the four grading periods.</p>
</section>
<?= $this->endSection() ?>
