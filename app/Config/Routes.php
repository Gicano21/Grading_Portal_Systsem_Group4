<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'AuthController::login');
$routes->get('login', 'AuthController::login');
$routes->post('login', 'AuthController::authenticate');
$routes->get('admin', 'AdminAuthController::login');
$routes->post('admin', 'AdminAuthController::authenticate');
$routes->post('logout', 'AuthController::logout', ['filter' => 'session']);
$routes->get('dashboard', 'DashboardController::index', ['filter' => 'session']);
$routes->get('account/password', 'AuthController::password', ['filter' => 'session']);
$routes->post('account/password', 'AuthController::updatePassword', ['filter' => 'session']);

$routes->group('student', ['filter' => ['session', 'group:student']], static function ($routes): void {
    $routes->get('grades', 'StudentController::grades');
});

$routes->group('instructor', ['filter' => ['session', 'group:instructor']], static function ($routes): void {
    $routes->get('classes', 'InstructorController::classes');
    $routes->get('classes/(:num)/grades', 'InstructorController::grades/$1');
    $routes->post('classes/(:num)/grades/(:num)/delete', 'InstructorController::deleteGrade/$1/$2');
    $routes->post('classes/(:num)/grades', 'InstructorController::saveGrades/$1');
});

$routes->group('admin', ['filter' => ['session', 'group:admin']], static function ($routes): void {
    $routes->get('dashboard', 'AdminController::index');
    $routes->get('records/(:segment)', 'AdminController::records/$1');
    $routes->post('records/(:segment)', 'AdminController::createRecord/$1');
    $routes->get('records/(:segment)/(:num)/edit', 'AdminController::editRecord/$1/$2');
    $routes->post('records/(:segment)/(:num)', 'AdminController::updateRecord/$1/$2');
    $routes->post('records/(:segment)/(:num)/delete', 'AdminController::deleteRecord/$1/$2');
});

$routes->group('registrar', ['filter' => ['session', 'group:registrar']], static function ($routes): void {
    $routes->get('/', 'RegistrarController::index');
    $routes->post('sections', 'RegistrarController::createSection');
    $routes->post('classes', 'RegistrarController::createClass');
    $routes->post('enrollments', 'RegistrarController::enrollStudent');
    $routes->post('classes/(:num)/instructor', 'RegistrarController::assignFaculty/$1');
    $routes->get('classes/(:num)/report', 'RegistrarController::report/$1');
    $routes->get('grades', 'RegistrarController::searchGrades');
    $routes->get('students/(:num)/report', 'RegistrarController::studentReport/$1');
    $routes->get('program-course-report', 'RegistrarController::programCourseReport');
});