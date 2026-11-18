<?= $this->extend('Layout/portal') ?>
<?= $this->section('title') ?>Registrar workspace<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="page-heading"><div><p class="eyebrow">ACADEMIC RECORDS</p><h1>Registrar workspace</h1><p class="muted">Set up class offerings, enroll students, and prepare grade reports.</p></div></div>
<section class="stats-grid stats-grid-three">
    <?php foreach (['students' => 'Students', 'courses' => 'Courses', 'classes' => 'Class offerings'] as $key => $label): ?>
        <article class="stat-card"><span><?= esc($label) ?></span><strong><?= esc((string) $stats[$key]) ?></strong><small>In the system</small></article>
    <?php endforeach; ?>
</section>
<div class="workspace-grid">
    <section class="panel">
        <p class="eyebrow">SCHEDULE</p><h2>Create a class offering</h2>
        <form method="post" action="<?= site_url('registrar/classes') ?>" class="form-stack">
            <?= csrf_field() ?>
            <label for="course_id">Course</label>
            <select id="course_id" name="course_id" required><option value="">Select a course</option><?php foreach ($courses as $course): ?><option value="<?= esc((string) $course['course_id']) ?>"><?= esc($course['course_code'] . ' — ' . $course['course']) ?></option><?php endforeach; ?></select>
            <label for="section_id">Section</label>
            <select id="section_id" name="section_id" required><option value="">Select a section</option><?php foreach ($sections as $section): ?><option value="<?= esc((string) $section['section_id']) ?>"><?= esc($section['section']) ?></option><?php endforeach; ?></select>
            <label for="academic_year_id">Academic year and semester</label>
            <select id="academic_year_id" name="academic_year_id" required><option value="">Select an academic term</option><?php foreach ($academicYears as $year): ?><option value="<?= esc((string) $year['academic_id']) ?>"><?= esc($year['academic_year'] . ' · ' . $year['semester']) ?></option><?php endforeach; ?></select>
            <label for="instructor_id">Instructor</label>
            <select id="instructor_id" name="instructor_id" required><option value="">Select an instructor</option><?php foreach ($instructors as $instructor): ?><option value="<?= esc((string) $instructor['instructor_id']) ?>"><?= esc($instructor['first_name'] . ' ' . $instructor['last_name']) ?></option><?php endforeach; ?></select>
            <button class="button button-primary" type="submit">Create offering</button>
        </form>
    </section>
    <section class="panel">
        <p class="eyebrow">CLASS SECTIONS</p><h2>Add a section</h2>
        <form method="post" action="<?= site_url('registrar/sections') ?>" class="form-stack">
            <?= csrf_field() ?>
            <label for="section">Section name</label>
            <input id="section" name="section" maxlength="100" placeholder="e.g. BSCS 1-A" required>
            <button class="button button-secondary" type="submit">Add section</button>
        </form>
        <p class="table-note">Create a section before using it in a class offering.</p>
    </section>
    <section class="panel panel-wide">
        <p class="eyebrow">STUDENT REGISTRATION</p><h2>Enroll a student in a class</h2>
        <form method="post" action="<?= site_url('registrar/enrollments') ?>" class="form-grid">
            <?= csrf_field() ?>
            <div><label for="student_id">Student</label><select id="student_id" name="student_id" required><option value="">Select a student</option><?php foreach ($students as $student): ?><option value="<?= esc((string) $student['student_id']) ?>"><?= esc($student['last_name'] . ', ' . $student['first_name'] . ' · ' . $student['email']) ?></option><?php endforeach; ?></select></div>
            <div><label for="program_id">Program</label><select id="program_id" name="program_id" required><option value="">Select a program</option><?php foreach ($programs as $program): ?><option value="<?= esc((string) $program['program_id']) ?>"><?= esc($program['program_code'] . ' — ' . $program['program']) ?></option><?php endforeach; ?></select></div>
            <div><label for="year_level">Year level</label><input id="year_level" name="year_level" type="number" min="1" max="10" value="1" required></div>
            <div><label for="class_offering_id">Class offering</label><select id="class_offering_id" name="class_offering_id" required><option value="">Select a class</option><?php foreach ($classes as $class): ?><option value="<?= esc((string) $class['class_offering_id']) ?>"><?= esc($class['course_code'] . ' · ' . $class['section'] . ' · ' . $class['academic_year']) ?></option><?php endforeach; ?></select></div>
            <div class="form-grid-action"><button class="button button-primary" type="submit">Enroll student</button></div>
        </form>
    </section>
</div>
<section class="panel">
    <div class="section-heading"><div><p class="eyebrow">CURRENT SCHEDULE</p><h2>Class offerings</h2></div></div>
    <?php if ($classes === []): ?><p class="empty-state">No offerings yet. Create one above to get started.</p><?php else: ?>
        <div class="table-wrap"><table>
            <thead><tr><th>Course</th><th>Section</th><th>Academic year</th><th>Instructor</th><th>Students</th><th>Assign faculty</th><th>Report</th></tr></thead>
            <tbody><?php foreach ($classes as $class): ?>
                <tr>
                    <td><strong><?= esc($class['course_code']) ?></strong><span class="table-sub"><?= esc($class['course']) ?></span></td>
                    <td><?= esc($class['section']) ?></td><td><?= esc($class['academic_year']) ?><span class="table-sub"><?= esc($class['semester']) ?></span></td>
                    <td><?= esc($class['instructor_first_name'] . ' ' . $class['instructor_last_name']) ?></td>
                    <td><?= esc((string) $class['student_count']) ?></td>
                    <td><form method="post" action="<?= site_url('registrar/classes/' . $class['class_offering_id'] . '/instructor') ?>" class="inline-form">
                        <?= csrf_field() ?>
                        <select name="instructor_id" aria-label="Instructor for <?= esc($class['course_code'] . ' ' . $class['section']) ?>"><?php foreach ($instructors as $instructor): ?><option value="<?= esc((string) $instructor['instructor_id']) ?>" <?= (int) $class['instructor_id'] === (int) $instructor['instructor_id'] ? 'selected' : '' ?>><?= esc($instructor['first_name'] . ' ' . $instructor['last_name']) ?></option><?php endforeach; ?></select>
                        <button class="button button-small button-secondary" type="submit">Save</button>
                    </form></td>
                    <td><a class="text-link" href="<?= site_url('registrar/classes/' . $class['class_offering_id'] . '/report') ?>">Print report</a></td>
                </tr>
            <?php endforeach; ?></tbody>
        </table></div>
    <?php endif; ?>
</section>
<?= $this->endSection() ?>
