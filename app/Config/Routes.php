<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/user/login', 'User::login', ['filter' => 'loggedIn']);

$routes->get('/user/signup', 'User::signUp', ['filter' => 'loggedIn']);
$routes->get('/login/logout', 'User::logout');
$routes->post('user/create', 'User::create');
// $routes->get('/user/authWithFB', 'User::authWithFB'); 
$routes->post('/user/searchAndLogin', 'User::searchAndLogin');
// $routes->get('/user/home', 'User::home');
$routes->get('/home/email', 'Home::email');
$routes->get('/user/password/reset', 'User::password_Reset');
$routes->post('/user/email', 'User::email');
$routes->get('/user/password/reset/confirm/(:any)', 'User::confirm_Password_Reset/$1');
$routes->post('/user/password/reset/confirm/(:any)', 'User::confirm_Password_Reset/$1');
// $routes->get('/user/dashboard', 'User::dashboard');
$routes->get('/user/dashboard', 'User::dashboard', ['filter' => 'noLogin']);




