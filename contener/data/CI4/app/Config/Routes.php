<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::index', ['as' => 'home']);
$routes->get('boutique', 'Boutique::index');

// Routes Authentification avec alias
$routes->get('login', 'Auth::login', ['as' => 'login']);
$routes->post('login', 'Auth::attemptLogin');
$routes->get('register', 'Auth::register', ['as' => 'register']);
$routes->post('register', 'Auth::attemptRegister');
$routes->get('logout', 'Auth::logout', ['as' => 'logout']);


