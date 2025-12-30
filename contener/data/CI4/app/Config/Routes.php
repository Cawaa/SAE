<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Route pour l'accueil
$routes->get('/', 'Home::index', ['as' => 'home']);

// --- IMPORTANT : Charger les routes par défaut de Shield ---
// Cela crée automatiquement /login, /register, /logout, etc.
service('auth')->routes($routes);

// Routes protégées par le filtre 'authfilter'
$routes->group("", ['filter' => 'authfilter'], static function ($routes) {
    // Ajoutez ici toutes les routes qui nécessitent d'être connecté
    $routes->post('product/create', 'Product::create');
    $routes->post('product/doUpdate', 'Product::doUpdate');
    $routes->get('product/delete/(:num)', 'Product::delete/$1');
    
    // Si vous voulez que la boutique soit protégée :
    // $routes->get('boutique', 'Boutique::index');
});

// Route publique pour la boutique (si elle ne doit pas être protégée)
$routes->get('boutique', 'Boutique::index');