<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');
$routes->get('hello', 'Hello::index');
$routes->get('scv', 'Home::scv');
