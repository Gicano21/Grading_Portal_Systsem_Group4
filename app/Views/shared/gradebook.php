<?= $this->extend('Layout/portal') ?>
<?= $this->section('title') ?><?= esc($title) ?><?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="page-heading">
    <div>
        <p class="eyebrow"><?= session('user.role') === 'Admin' ? 'SYSTEM ADMINISTRATION' : 'INSTRUCTOR WORKSPACE' ?></p>
        <h1><?= esc($title) ?></h1>
        <p class="muted"><?= esc($class['course_code'] . ' · ' . $class['course'] . ' · ' . $class['section'] . ' · ' . $class['academic_year']) ?></p>
    </div>
    <a class="button button-secondary" href="<?= site_url(session('user.role') === 'Admin' ? 'admin/classes' : 'instructor/classes') ?>">Back to classes</a>
</div>
<section class="panel">
    <?php if ($roster === []): ?>
        <p class="empty-state">This class does not have any enrolled students yet.</p>
    <?php else: ?>
        <form method="post" action="<?= esc($postUrl) ?>">
            <?= csrf_field() ?>
            <div class="table-wrap"><table class="grade-table">
                <thead><tr><th>Student</th><th>Prelim</th><th>Midterm</th><th>Pre-final</th><th>Final</th><th>Calculated final grade</th></tr></thead>
                <tbody><?php foreach ($roster as $student): $id = (int) $student['course_enrollment_id']; ?>
                    <tr>
                        <td><strong><?= esc($student['last_name'] . ', ' . $student['first_name']) ?></strong><span class="table-sub"><?= esc($student['email']) ?></span></td>
                        <?php foreach (['prelim', 'midterm', 'pre_final', 'final'] as $period): ?>
                            <td><input aria-label="<?= esc(ucwords(str_replace('_', ' ', $period)) . ' grade for ' . $student['first_name'] . ' ' . $student['last_name']) ?>" type="number" name="grades[<?= $id ?>][<?= esc($period) ?>]" min="0" max="100" step="0.01" value="<?= esc((string) old('grades.' . $id . '.' . $period, $student[$period] ?? '')) ?>"></td>
                        <?php endforeach; ?>
                        <td><?= $student['final_grade'] === null ? '<span class="muted">Pending</span>' : esc(number_format((float) $student['final_grade'], 2)) ?></td>
                    </tr>
                <?php endforeach; ?></tbody>
            </table></div>
            <div class="form-actions"><span class="table-note">Enter scores from 0 to 100. The final grade is the arithmetic mean and remains pending until all four scores are present.</span><button class="button button-primary" type="submit">Save grades</button></div>
        </form>
    <?php endif; ?>
</section>
<?= $this->endSection() ?>
