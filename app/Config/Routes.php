<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// $routes->get('/', 'Home::index');
$routes->get('/', 'Auth::index');
$routes->get('/auth', 'Auth::index'); // Maps GET requests to the 'index' method of the 'Auth' controller
$routes->post('/auth/login', 'Auth::login'); // Maps POST requests for login to the 'login' method
$routes->get('/auth/logout', 'Auth::logout'); // Maps GET requests for logout to the 'logout' method

$routes->group('', ['filter' => 'auth'], function ($routes) {
    $routes->get('/dashboard', 'Dashboard::index');
    $routes->get('/angsuran-kavling', 'AngsuranKavling::index');
    $routes->get('/angsuran-rumah', 'AngsuranRumah::index');
    $routes->get('/summary', 'Summary::index');
    $routes->get('/renovasi', 'Renovasi::index');
    $routes->get('/hutang', 'Hutang::index');
});

$routes->group('angsuran-kavling', ['filter' => 'auth'], function ($routes) {
    $routes->post('import', 'AngsuranKavling::import');
    $routes->get('delete/(:num)', 'AngsuranKavling::delete/$1');
});

// Can't find a route for 'GET: angsuran-rumah/delete/24'.
$routes->group('angsuran-rumah', ['filter' => 'auth'], function ($routes) {
    $routes->post('import', 'AngsuranRumah::import');
    $routes->get('delete/(:num)', 'AngsuranRumah::delete/$1');
});

$routes->group('hutang', ['filter' => 'auth'], function ($routes) {
    $routes->post('create', 'Hutang::create');
    $routes->get('lunasi/(:num)', 'Hutang::lunasi/$1');
    $routes->post('update/(:num)', 'Hutang::update/$1');
});
