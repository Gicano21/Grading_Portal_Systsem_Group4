<?= $this->extend('Layout/portal') ?>
<?= $this->section('title') ?>Search grades<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="page-heading">
    <div><p class="eyebrow">REGISTRAR REPORTS</p><h1>Search student grades</h1><p class="muted">Search a student or prepare a program and course grade report.</p></div>
    <a class="button button-secondary" href="<?= site_url('registrar') ?>">Back to workspace</a>
</div>
<div class="workspace-grid">
    <section class="panel">
        <p class="eyebrow">STUDENT REPORT</p><h2>Find a student</h2>
        <form method="get" action="<?= site_url('registrar/grades') ?>" class="form-stack">
            <label for="q">Student name, email, or ID</label>
            <input id="q" name="q" value="<?= esc($query) ?>" maxlength="100" required>
            <button class="button button-primary" type="submit">Search</button>
        </form>
    </section>
    <section class="panel">
        <p class="eyebrow">PROGRAM &amp; COURSE REPORT</p><h2>Print a grade group</h2>
        <form method="get" action="<?= site_url('registrar/program-course-report') ?>" class="form-stack">
            <label for="program_id">Program</label>
            <select id="program_id" name="program_id" required>
                <option value="">Select a program</option>
                <?php foreach ($programs as $program): ?><option value="<?= esc((string) $program['program_id']) ?>"><?= esc($program['program_code'] . ' — ' . $program['program']) ?></option><?php endforeach; ?>
            </select>
            <label for="course_id">Course</label>
            <select id="course_id" name="course_id" required>
                <option value="">Select a course</option>
                <?php foreach ($courses as $course): ?><option value="<?= esc((string) $course['course_id']) ?>"><?= esc($course['course_code'] . ' — ' . $course['course']) ?></option><?php endforeach; ?>
            </select>
            <button class="button button-primary" type="submit">View report</button>
        </form>
    </section>
</div>
<?php if ($query !== ''): ?>
    <section class="panel">
        <h2>Search results</h2>
        <?php if ($students === []): ?>
            <p class="empty-state">No students matched that search.</p>
        <?php else: ?>
            <div class="table-wrap"><table>
                <thead><tr><th>Student</th><th>Email</th><th>Student ID</th><th></th></tr></thead>
                <tbody><?php foreach ($students as $student): ?>
                    <tr>
                        <td><?= esc($student['last_name'] . ', ' . $student['first_name']) ?></td>
                        <td><?= esc($student['email']) ?></td>
                        <td><?= esc((string) $student['student_id']) ?></td>
                        <td><a class="button button-small button-secondary" href="<?= site_url('registrar/students/' . $student['student_id'] . '/report') ?>">View &amp; print grades</a></td>
                    </tr>
                <?php endforeach; ?></tbody>
            </table></div>
        <?php endif; ?>
    </section>
<?php endif; ?>
<?= $this->endSection() ?>
