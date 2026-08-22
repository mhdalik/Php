<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Authentication Routes
$routes->get('/', 'Auth::login');
$routes->get('/login', 'Auth::login');
$routes->post('/authenticate', 'Auth::authenticate');
$routes->get('/logout', 'Auth::logout');

// Protected Routes (require login)
$routes->group('', ['filter' => 'auth'], function($routes) {
    // Dashboard
    $routes->get('/dashboard', 'Dashboard::index');

    // Customers
    $routes->get('/customers', 'Customers::index');
    $routes->get('/customers/create', 'Customers::create');
    $routes->post('/customers/store', 'Customers::store');
    $routes->get('/customers/view/(:num)', 'Customers::view/$1');
    $routes->get('/customers/edit/(:num)', 'Customers::edit/$1');
    $routes->post('/customers/update/(:num)', 'Customers::update/$1');
    $routes->get('/customers/delete/(:num)', 'Customers::delete/$1');
    $routes->get('/customers/export', 'Customers::export');
});

// // api routes
// $routes->group('api', function($routes) {
//     $routes->get('customers', 'Api\Customers::index');
//     $routes->get('customers/(:num)', 'Api\Customers::show/$1');
//     $routes->post('customers', 'Api\Customers::create');
//     $routes->put('customers/(:num)', 'Api\Customers::update/$1');
//     $routes->delete('customers/(:num)', 'Api\Customers::delete/$1');
// });
$routes->group('api', function ($routes) {
    $routes->post('login', 'Api\Auth::login');
    $routes->resource('customers', [
        'controller' => 'Api\Customers',
        'only' => ['index', 'show', 'create', 'update', 'delete']
    ]);
});
