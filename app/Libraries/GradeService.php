<?php

namespace App\Libraries;

class GradeService
{
    private const PERIODS = ['prelim', 'midterm', 'pre_final', 'final'];

    public function saveForOffering(int $classOfferingId, mixed $submittedGrades): array
    {
        if (! is_array($submittedGrades)) {
            return ['The submitted grade data is invalid.'];
        }

        $db = db_connect();
        $roster = (new PortalData())->classRoster($classOfferingId);
        $allowedIds = array_fill_keys(array_map(
            static fn (array $row): string => (string) $row['course_enrollment_id'],
            $roster,
        ), true);
        $updates = [];
        $errors = [];

        foreach ($submittedGrades as $courseEnrollmentId => $values) {
            $courseEnrollmentId = (string) $courseEnrollmentId;
            if (! isset($allowedIds[$courseEnrollmentId]) || ! is_array($values)) {
                $errors[] = 'A submitted grade does not belong to this class.';
                continue;
            }

            $grade = [];
            foreach (self::PERIODS as $period) {
                $value = trim((string) ($values[$period] ?? ''));
                if ($value === '') {
                    $grade[$period] = null;
                    continue;
                }

                if (! is_numeric($value) || (float) $value < 0 || (float) $value > 100) {
                    $errors[] = 'Grades must be numbers from 0 to 100.';
                    continue 2;
                }

                $grade[$period] = round((float) $value, 2);
            }

            $grade['final_grade'] = in_array(null, $grade, true)
                ? null
                : round(array_sum($grade) / count(self::PERIODS), 2);
            $updates[(int) $courseEnrollmentId] = $grade;
        }

        if ($errors !== []) {
            return $errors;
        }

        $studentIds = [];
        foreach ($roster as $student) {
            $studentIds[(int) $student['course_enrollment_id']] = (int) $student['student_id'];
        }

        $db->transStart();
        foreach ($updates as $courseEnrollmentId => $grade) {
            $record = $db->table('grades')
                ->where('course_enrollment_id', $courseEnrollmentId)
                ->where('student_id', $studentIds[$courseEnrollmentId])
                ->get()
                ->getRowArray();

            $grade['student_id'] = $studentIds[$courseEnrollmentId];
            if ($record === null) {
                $grade['course_enrollment_id'] = $courseEnrollmentId;
                $db->table('grades')->insert($grade);
            } else {
                $db->table('grades')
                    ->where('grade_id', $record['grade_id'])
                    ->update($grade);
            }
        }
        $db->transComplete();

        if (! $db->transStatus()) {
            return ['Grades could not be saved. Please try again.'];
        }

        return [];
    }

    public function deleteForOffering(int $classOfferingId, int $courseEnrollmentId): bool
    {
        $studentId = null;
        foreach ((new PortalData())->classRoster($classOfferingId) as $student) {
            if ((int) $student['course_enrollment_id'] === $courseEnrollmentId) {
                $studentId = (int) $student['student_id'];
                break;
            }
        }

        if ($studentId === null) {
            return false;
        }

        $db = db_connect();
        $grade = $db->table('grades')
            ->where('course_enrollment_id', $courseEnrollmentId)
            ->where('student_id', $studentId)
            ->get()
            ->getRowArray();

        if ($grade === null) {
            return false;
        }

        return $db->table('grades')->where('grade_id', $grade['grade_id'])->delete();
    }
}
