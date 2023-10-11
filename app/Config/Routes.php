<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/user/login', 'User::login');
$routes->get('/user/signup', 'User::signUp');
