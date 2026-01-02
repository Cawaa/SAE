<?php

namespace Config;

use CodeIgniter\Config\Services;
use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes = Services::routes();

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('HomeController');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// $routes->setAutoRoute(true); // en TD : routes explicites

/*
|--------------------------------------------------------------------------
| Public pages
|--------------------------------------------------------------------------
*/
$routes->get('/', 'HomeController::index');

/*
|--------------------------------------------------------------------------
| Beats / Boutique (public)
|--------------------------------------------------------------------------
*/
$routes->get('/beats', 'BeatController::index');
$routes->get('/boutique', 'BeatController::index'); // alias boutique
$routes->get('/search', 'BeatController::search');  // alias
$routes->get('/beats/search', 'BeatController::search'); // optionnel alias

// Aliases legacy (si liens/vues utilisent encore /listings)
$routes->get('/listings', 'BeatController::index');

// IMPORTANT : routes "spécifiques" AVANT /beats/(:num)
$routes->get('/beats/create', 'BeatController::createForm'); // (si non loggé, controller redirect)
$routes->post('/beats/create', 'BeatController::create');

// Legacy create
$routes->get('/listings/create', 'BeatController::createForm');
$routes->post('/listings/create', 'BeatController::create');

// Pages "show" (générique) en dernier
$routes->get('/beats/(:num)', 'BeatController::show/$1');
$routes->get('/listings/(:num)', 'BeatController::show/$1');

/*
|--------------------------------------------------------------------------
| Auth (maison)
|--------------------------------------------------------------------------
*/
$routes->get('/login', 'AuthController::loginForm');
$routes->post('/login', 'AuthController::login');
$routes->get('/register', 'AuthController::registerForm');
$routes->post('/register', 'AuthController::register');
$routes->get('/logout', 'AuthController::logout');

/*Page des artistes*/
$routes->get('/artistes', 'ArtistController::index');
$routes->get('/artists', 'ArtistController::index');

/*
|--------------------------------------------------------------------------
| Protected routes (need session login)
|--------------------------------------------------------------------------
*/
$routes->group('', ['filter' => 'auth'], static function (RouteCollection $routes) {

    // Espace perso beats
    $routes->get('/my/beats', 'BeatController::myBeats');
    $routes->get('/my/listings', 'BeatController::myBeats'); // legacy

    // CRUD beats (spécifiques d'abord)
    $routes->get('/beats/(:num)/edit', 'BeatController::editForm/$1');
    $routes->post('/beats/(:num)/edit', 'BeatController::update/$1');
    $routes->post('/beats/(:num)/delete', 'BeatController::delete/$1');

    // Legacy CRUD listings
    $routes->get('/listings/(:num)/edit', 'BeatController::editForm/$1');
    $routes->post('/listings/(:num)/edit', 'BeatController::update/$1');
    $routes->post('/listings/(:num)/delete', 'BeatController::delete/$1');

    // Favorites
    $routes->get('/favorites', 'FavoriteController::index');
    $routes->post('/favorites/(:num)/toggle', 'FavoriteController::toggle/$1');
    // compat anciennes routes add/remove
    $routes->post('/favorites/(:num)/add', 'FavoriteController::add/$1');
    $routes->post('/favorites/(:num)/remove', 'FavoriteController::remove/$1');

    // Conversations / messages
    $routes->get('/conversations', 'ConversationsController::index');
    $routes->get('/conversations/(:num)', 'ConversationsController::show/$1');
    $routes->post('/conversations/start/(:num)', 'ConversationsController::start/$1'); // beat_id
    $routes->post('/conversations/(:num)/message', 'MessageController::send/$1');

    /*
    |--------------------------------------------------------------------------
    | Cart (public: invité + connecté)
    |--------------------------------------------------------------------------
    */
    $routes->get('/cart', 'CartController::show');
    $routes->get('/panier', 'CartController::show');

    $routes->post('/cart/add/(:num)', 'CartController::add/$1');
    $routes->post('/cart/remove/(:num)', 'CartController::remove/$1');
    $routes->post('/cart/remove-line/(:num)', 'CartController::removeLine/$1');

    $routes->get('/cart/checkout', 'CartController::checkoutForm');
    $routes->post('/cart/checkout', 'CartController::checkout');

});
