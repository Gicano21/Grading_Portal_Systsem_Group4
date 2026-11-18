<?php

namespace App\Controllers;

use App\Libraries\GradeService;
use App\Libraries\PortalData;
use CodeIgniter\Exceptions\PageNotFoundException;

class InstructorController extends BaseController
{
    public function classes()
    {
        $user = session('user');

        return view('instructor/classes', [
            'classes' => (new PortalData())->classes((int) $user['role_id']),
        ]);
    }

    public function grades(int $classOfferingId)
    {
        $user = session('user');
        $portal = new PortalData();
        $class = $portal->classOffering($classOfferingId, (int) $user['role_id']);
        if ($class === null) {
            throw PageNotFoundException::forPageNotFound();
        }

        return view('shared/gradebook', [
            'class'   => $class,
            'roster'  => $portal->classRoster($classOfferingId),
            'postUrl' => site_url('instructor/classes/' . $classOfferingId . '/grades'),
            'title'   => 'Encode or update grades',
        ]);
    }

    public function saveGrades(int $classOfferingId)
    {
        $user = session('user');
        if ((new PortalData())->classOffering($classOfferingId, (int) $user['role_id']) === null) {
            throw PageNotFoundException::forPageNotFound();
        }

        $errors = (new GradeService())->saveForOffering(
            $classOfferingId,
            $this->request->getPost('grades') ?? [],
        );
        if ($errors !== []) {
            return redirect()->to(site_url('instructor/classes/' . $classOfferingId . '/grades'))
                ->withInput()
                ->with('gradeErrors', $errors);
        }

        return redirect()->to(site_url('instructor/classes/' . $classOfferingId . '/grades'))
            ->with('message', 'Grades saved. Final grades are the average of all four periods.');
    }
}
