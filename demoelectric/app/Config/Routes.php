<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::index');
$routes->get('/about', 'About::index');
$routes->get('/services', 'Services::index');
$routes->match(['get', 'post'], '/contact', 'Contact::index');
$routes->get('/register', 'Register::index');
$routes->post('/register', 'Register::create');
$routes->get('/login', 'Auth::login');
$routes->post('/login', 'Auth::attemptLogin');
$routes->get('/admin/login', 'Auth::adminLogin');
$routes->post('/admin/login', 'Auth::attemptAdminLogin');
$routes->post('/logout', 'Auth::logout', ['filter' => 'auth']);
$routes->get('/dashboard', 'Auth::dashboard', ['filter' => 'auth']);
$routes->get('/customer/dashboard', 'Customer::index', ['filter' => 'auth:customer']);

$routes->group('', ['filter' => 'auth:admin'], static function ($routes): void {
    $routes->get('/admin/dashboard', 'Dashboard::index');
    $routes->get('/account/new', 'Dashboard::new');
    $routes->post('/account', 'Dashboard::create');
    $routes->get('/account/(:num)', 'Dashboard::show/$1');
    $routes->get('/account/(:num)/edit', 'Dashboard::edit/$1');
    $routes->post('/account/(:num)/update', 'Dashboard::update/$1');
    $routes->get('/account/(:num)/delete', 'Dashboard::confirmDelete/$1');
    $routes->post('/account/(:num)/delete', 'Dashboard::delete/$1');
});
