<?php

namespace App\Controllers;

use App\Libraries\GradeService;
use App\Libraries\PortalData;
use CodeIgniter\Exceptions\PageNotFoundException;

class AdminController extends BaseController
{
    public function index()
    {
        $portal = new PortalData();

        return view('admin/index', [
            'stats'   => $portal->dashboardStats(),
            'classes' => $portal->classes(),
        ]);
    }

    public function classes()
    {
        return view('admin/classes', [
            'classes' => (new PortalData())->classes(),
        ]);
    }

    public function grades(int $classOfferingId)
    {
        $portal = new PortalData();
        $class = $portal->classOffering($classOfferingId);
        if ($class === null) {
            throw PageNotFoundException::forPageNotFound();
        }

        return view('shared/gradebook', [
            'class'   => $class,
            'roster'  => $portal->classRoster($classOfferingId),
            'postUrl' => site_url('admin/classes/' . $classOfferingId . '/grades'),
            'title'   => 'Encode or update grades',
        ]);
    }

    public function saveGrades(int $classOfferingId)
    {
        if ((new PortalData())->classOffering($classOfferingId) === null) {
            throw PageNotFoundException::forPageNotFound();
        }

        $errors = (new GradeService())->saveForOffering(
            $classOfferingId,
            $this->request->getPost('grades') ?? [],
        );

        if ($errors !== []) {
            return redirect()->to(site_url('admin/classes/' . $classOfferingId . '/grades'))
                ->withInput()
                ->with('gradeErrors', $errors);
        }

        return redirect()->to(site_url('admin/classes/' . $classOfferingId . '/grades'))
            ->with('message', 'Grades saved. Final grades are the average of all four periods.');
    }
}
