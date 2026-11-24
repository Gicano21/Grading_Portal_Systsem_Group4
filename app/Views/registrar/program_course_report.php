<?= $this->extend('Layout/portal') ?>
<?= $this->section('title') ?>Program course grade report<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="page-heading no-print">
    <div><p class="eyebrow">REGISTRAR REPORT</p><h1>Program course grade report</h1><p class="muted"><?= esc($program['program_code'] . ' · ' . $course['course_code']) ?></p></div>
    <button class="button button-primary" type="button" onclick="window.print()">Print report</button>
</div>
<section class="panel report-panel">
    <div class="report-heading">
        <p class="eyebrow">GRADING PORTAL MANAGEMENT SYSTEM</p>
        <h2><?= esc($program['program_code'] . ' — ' . $program['program']) ?></h2>
        <p><?= esc($course['course_code'] . ' — ' . $course['course']) ?></p>
    </div>
    <?php if ($grades === []): ?>
        <p class="empty-state">No grades are available for this program and course.</p>
    <?php else: ?>
        <div class="table-wrap"><table>
            <thead><tr><th>Student</th><th>Section / term</th><th>Instructor</th><th>Prelim</th><th>Midterm</th><th>Pre-final</th><th>Final</th><th>Final grade</th></tr></thead>
            <tbody><?php foreach ($grades as $grade): ?>
                <tr>
                    <td><?= esc($grade['last_name'] . ', ' . $grade['first_name']) ?><span class="table-sub">ID <?= esc((string) $grade['student_id']) ?></span></td>
                    <td><?= esc($grade['section'] . ' · ' . $grade['academic_year'] . ' ' . $grade['semester']) ?></td>
                    <td><?= esc($grade['instructor_first_name'] . ' ' . $grade['instructor_last_name']) ?></td>
                    <?php foreach (['prelim', 'midterm', 'pre_final', 'final', 'final_grade'] as $period): ?><td><?= $grade[$period] === null ? '—' : esc(number_format((float) $grade[$period], 2)) ?></td><?php endforeach; ?>
                </tr>
            <?php endforeach; ?></tbody>
        </table></div>
    <?php endif; ?>
</section>
<?= $this->endSection() ?>
