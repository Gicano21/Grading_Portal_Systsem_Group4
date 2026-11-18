<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'AuthController::login');
$routes->get('login', 'AuthController::login');
$routes->post('login', 'AuthController::authenticate');
$routes->post('logout', 'AuthController::logout', ['filter' => 'auth']);
$routes->get('dashboard', 'DashboardController::index', ['filter' => 'auth']);

$routes->group('student', ['filter' => 'auth:Student'], static function ($routes): void {
    $routes->get('grades', 'StudentController::grades');
});

$routes->group('instructor', ['filter' => 'auth:Instructor'], static function ($routes): void {
    $routes->get('classes', 'InstructorController::classes');
    $routes->get('classes/(:num)/grades', 'InstructorController::grades/$1');
    $routes->post('classes/(:num)/grades', 'InstructorController::saveGrades/$1');
});

$routes->group('admin', ['filter' => 'auth:Admin'], static function ($routes): void {
    $routes->get('/', 'AdminController::index');
    $routes->get('classes', 'AdminController::classes');
    $routes->get('classes/(:num)/grades', 'AdminController::grades/$1');
    $routes->post('classes/(:num)/grades', 'AdminController::saveGrades/$1');
});

$routes->group('registrar', ['filter' => 'auth:Registrar'], static function ($routes): void {
    $routes->get('/', 'RegistrarController::index');
    $routes->post('sections', 'RegistrarController::createSection');
    $routes->post('classes', 'RegistrarController::createClass');
    $routes->post('enrollments', 'RegistrarController::enrollStudent');
    $routes->post('classes/(:num)/instructor', 'RegistrarController::assignFaculty/$1');
    $routes->get('classes/(:num)/report', 'RegistrarController::report/$1');
});