<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Home route - redirect to login
$routes->get('/', function() {
    return redirect()->to('/auth/login');
});

// Authentication routes
$routes->group('auth', function($routes) {
    $routes->get('login', 'AuthController::login');
    $routes->post('login', 'AuthController::processLogin');
    $routes->get('register', 'AuthController::register');
    $routes->post('register', 'AuthController::processRegister');
    $routes->get('logout', 'AuthController::logout');
    $routes->get('profile', 'AuthController::profile');
    $routes->post('profile', 'AuthController::updateProfile');
});

// Admin routes
$routes->group('admin', function($routes) {
    $routes->get('dashboard', 'AdminController::dashboard');
    
    // Event management
    $routes->get('events', 'AdminController::events');
    $routes->get('events/create', 'AdminController::createEvent');
    $routes->post('events/store', 'AdminController::storeEvent');
    $routes->get('events/edit/(:num)', 'AdminController::editEvent/$1');
    $routes->post('events/update/(:num)', 'AdminController::updateEvent/$1');
    $routes->get('events/delete/(:num)', 'AdminController::deleteEvent/$1');
    $routes->get('events/registrations/(:num)', 'AdminController::eventRegistrations/$1');
    $routes->post('registrations/update-status/(:num)', 'AdminController::updateRegistrationStatus/$1');
    
    // User management
    $routes->get('users', 'AdminController::users');
    $routes->get('users/toggle-status/(:num)', 'AdminController::toggleUserStatus/$1');
    
    // Reports
    $routes->get('reports', 'AdminController::reports');
});

// User routes
$routes->group('user', function($routes) {
    $routes->get('dashboard', 'UserController::dashboard');
    $routes->get('events', 'UserController::events');
    $routes->get('events/detail/(:num)', 'UserController::eventDetail/$1');
    $routes->post('events/register/(:num)', 'UserController::registerEvent/$1');
    $routes->post('events/cancel/(:num)', 'UserController::cancelRegistration/$1');
    $routes->get('my-events', 'UserController::myEvents');
    
    // Certificate routes
    $routes->get('certificates', 'User\CertificateController::index');
    $routes->get('certificates/view/(:num)', 'User\CertificateController::view/$1');
    $routes->get('certificates/download/(:num)', 'User\CertificateController::download/$1');
});

// Public certificate verification
$routes->get('verify-certificate', 'UserController::verifyCertificate');
