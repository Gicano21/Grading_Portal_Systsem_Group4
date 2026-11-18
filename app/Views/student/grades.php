<?= $this->extend('Layout/portal') ?>
<?= $this->section('title') ?>My grades<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="page-heading"><div><p class="eyebrow">STUDENT PORTAL</p><h1>My grades</h1><p class="muted">Academic results for <?= esc($student['first_name'] . ' ' . $student['last_name']) ?>.</p></div></div>
<section class="panel">
    <?php if ($grades === []): ?>
        <p class="empty-state">There are no class grades available for your account yet.</p>
    <?php else: ?>
        <div class="table-wrap"><table>
            <thead><tr><th>Course</th><th>Section</th><th>Academic year</th><th>Instructor</th><th>Prelim</th><th>Midterm</th><th>Pre-final</th><th>Final</th><th>Final grade</th></tr></thead>
            <tbody><?php foreach ($grades as $grade): ?>
                <tr>
                    <td><strong><?= esc($grade['course_code']) ?></strong><span class="table-sub"><?= esc($grade['course']) ?></span></td>
                    <td><?= esc($grade['section']) ?></td>
                    <td><?= esc($grade['academic_year']) ?><span class="table-sub"><?= esc($grade['semester']) ?></span></td>
                    <td><?= esc($grade['instructor_first_name'] . ' ' . $grade['instructor_last_name']) ?></td>
                    <?php foreach (['prelim', 'midterm', 'pre_final', 'final', 'final_grade'] as $period): ?>
                        <td><?= $grade[$period] === null ? '<span class="muted">—</span>' : esc(number_format((float) $grade[$period], 2)) ?></td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?></tbody>
        </table></div>
        <p class="table-note">The final grade is the arithmetic mean of the four grading periods once all four have been entered.</p>
    <?php endif; ?>
</section>
<?= $this->endSection() ?>
