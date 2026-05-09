<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// Authentication Routes
$routes->get('/', 'AuthController::login'); 
$routes->get('/login', 'AuthController::login');
$routes->post('/loginProcess', 'AuthController::loginProcess');
$routes->get('/register', 'AuthController::register');
$routes->post('/registerProcess', 'AuthController::registerProcess');
$routes->get('/logout', 'AuthController::logout');

$routes->group('contacts', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'ContactController::index');
    $routes->post('store', 'ContactController::store');
    $routes->delete('delete/(:num)', 'ContactController::delete/$1'); 
    $routes->get('edit/(:num)', 'ContactController::edit/$1');
    $routes->post('update/(:num)', 'ContactController::update/$1');
});
