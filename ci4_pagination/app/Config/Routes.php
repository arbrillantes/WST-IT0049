<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// Redirect root to login
$routes->get('/', 'Home::index');
$routes->get('account/(:num)', 'Home::viewAccount/$1');