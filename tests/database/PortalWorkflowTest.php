<?php

use App\Database\Seeds\InitialData;
use App\Libraries\GradeService;
use App\Libraries\PortalData;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;

/**
 * @internal
 */
final class PortalWorkflowTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    protected $namespace = 'App';
    protected $seed = InitialData::class;

    public function testMigrationsAndSeedCreateRoleAccountsAndClassGrades(): void
    {
        $this->assertSame(5, $this->db->table('accounts')->countAllResults());
        $this->assertSame(2, $this->db->table('class_offerings')->countAllResults());
        $this->assertSame(2, $this->db->table('grades')->countAllResults());
    }

    public function testStudentsOnlyReceiveTheirOwnClassGrades(): void
    {
        $portal = new PortalData();

        $this->assertCount(1, $portal->studentGrades(1));
        $this->assertCount(1, $portal->studentGrades(2));
    }

    public function testGradeUpdatesCalculateFinalAverageAndRejectOutOfRangeScores(): void
    {
        $service = new GradeService();
        $errors = $service->saveForOffering(1, [
            1 => [
                'prelim'   => '90',
                'midterm'  => '80',
                'pre_final'=> '85',
                'final'    => '95',
            ],
        ]);

        $this->assertSame([], $errors);
        $grade = $this->db->table('grades')->where('course_enrollment_id', 1)->get()->getRowArray();
        $this->assertEquals(87.5, (float) $grade['final_grade']);

        $errors = $service->saveForOffering(1, [
            1 => [
                'prelim'   => '101',
                'midterm'  => '80',
                'pre_final'=> '85',
                'final'    => '95',
            ],
        ]);

        $this->assertNotSame([], $errors);
        $unchangedGrade = $this->db->table('grades')->where('course_enrollment_id', 1)->get()->getRowArray();
        $this->assertEquals(87.5, (float) $unchangedGrade['final_grade']);
    }
}
