<?= $this->extend('Layout/portal') ?>
<?= $this->section('title') ?>Student grade report<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="page-heading no-print">
    <div><p class="eyebrow">REGISTRAR REPORT</p><h1>Student grade report</h1><p class="muted"><?= esc($student['last_name'] . ', ' . $student['first_name']) ?> · Student ID <?= esc((string) $student['student_id']) ?></p></div>
    <button class="button button-primary" type="button" onclick="window.print()">Print report</button>
</div>
<section class="panel report-panel">
    <div class="report-heading">
        <p class="eyebrow">GRADING PORTAL MANAGEMENT SYSTEM</p>
        <h2><?= esc($student['last_name'] . ', ' . $student['first_name']) ?></h2>
        <p>Student ID: <?= esc((string) $student['student_id']) ?> · <?= esc($student['email']) ?></p>
    </div>
    <?php if ($grades === []): ?>
        <p class="empty-state">There are no class grades available for this student.</p>
    <?php else: ?>
        <div class="table-wrap"><table>
            <thead><tr><th>Course</th><th>Section</th><th>Academic year</th><th>Instructor</th><th>Prelim</th><th>Midterm</th><th>Pre-final</th><th>Final</th><th>Final grade</th></tr></thead>
            <tbody><?php foreach ($grades as $grade): ?>
                <tr>
                    <td><strong><?= esc($grade['course_code']) ?></strong><span class="table-sub"><?= esc($grade['course']) ?></span></td>
                    <td><?= esc($grade['section']) ?></td>
                    <td><?= esc($grade['academic_year']) ?><span class="table-sub"><?= esc($grade['semester']) ?></span></td>
                    <td><?= esc($grade['instructor_first_name'] . ' ' . $grade['instructor_last_name']) ?></td>
                    <?php foreach (['prelim', 'midterm', 'pre_final', 'final', 'final_grade'] as $period): ?><td><?= $grade[$period] === null ? '—' : esc(number_format((float) $grade[$period], 2)) ?></td><?php endforeach; ?>
                </tr>
            <?php endforeach; ?></tbody>
        </table></div>
    <?php endif; ?>
</section>
<?= $this->endSection() ?>
