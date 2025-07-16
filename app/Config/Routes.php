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
    
    // Export routes
    $routes->get('reports/export/events', 'AdminController::exportEvents');
    $routes->get('reports/export/users', 'AdminController::exportUsers');
    $routes->get('reports/export/registrations', 'AdminController::exportRegistrations');
});

// User routes
$routes->group('user', function($routes) {
    $routes->get('dashboard', 'UserController::dashboard');
    $routes->get('events', 'UserController::events');
    $routes->get('events/detail/(:num)', 'UserController::eventDetail/$1');
    $routes->get('events/(:num)', 'UserController::eventDetail/$1'); // Alternative route for compatibility
    $routes->post('events/register/(:num)', 'UserController::registerEvent/$1');
    $routes->post('registrations/cancel/(:num)', 'UserController::cancelRegistration/$1');
    $routes->get('registrations/cancel/(:num)', 'UserController::cancelRegistration/$1'); // Add GET method for testing
    $routes->get('my-events', 'UserController::myEvents');
    
    // Certificate routes
    $routes->get('certificates', 'User\CertificateController::index');
    $routes->get('certificates/view/(:num)', 'User\CertificateController::view/$1');
    $routes->get('certificates/download/(:num)', 'User\CertificateController::download/$1');
    $routes->get('certificates/verify', 'User\CertificateController::verify');
    $routes->post('certificates/verify', 'User\CertificateController::verify');
});

// Payment routes
$routes->group('payment', function($routes) {
    $routes->get('select-method/(:num)', 'PaymentController::selectMethod/$1');
    $routes->post('create', 'PaymentController::createPayment');
    $routes->get('instructions/(:segment)', 'PaymentController::instructions/$1');
    $routes->get('status/(:segment)', 'PaymentController::checkStatus/$1');
    $routes->post('callback', 'PaymentController::callback');
    $routes->get('success/(:segment)', 'PaymentController::success/$1');
    $routes->get('failed/(:segment)', 'PaymentController::failed/$1');
    
    // Development only - simulate payment
    $routes->post('simulate/(:segment)', 'PaymentController::simulatePayment/$1');
});

// Public certificate verification
$routes->get('verify-certificate', 'UserController::verifyCertificate');
$routes->post('verify-certificate', 'UserController::verifyCertificate');
