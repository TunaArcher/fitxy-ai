<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (is_file(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}


/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override('App\Controllers\Errors::show404');
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

$routes->get('/', 'HomeController::index');
$routes->get('/logout', 'HomeController::logout');
$routes->get('/register', 'HomeController::register');

$routes->match(['get', 'post'], 'calculate', 'CalculateController::index', ['filter' => 'userAuth']);
$routes->get('/callback', 'LineLoginController::callback');

// -----------------------------------------------------------------------------
// Menu
// -----------------------------------------------------------------------------

$routes->group('menu', ['filter' => 'userAuth'], function ($routes) {
    $routes->get('/', 'MenuController::report');
    $routes->post('update', 'MenuController::update');
    $routes->post('delete', 'MenuController::delete');
});

// -----------------------------------------------------------------------------
// Workout
// -----------------------------------------------------------------------------

$routes->group('workout', ['filter' => 'userAuth'], function ($routes) {
    $routes->get('/', 'WorkoutController::index');
    $routes->get('add', 'WorkoutController::add');
    $routes->post('save', 'WorkoutController::save');
    $routes->post('delete', 'WorkoutController::delete');
});

// -----------------------------------------------------------------------------
// Food
// -----------------------------------------------------------------------------

$routes->group('food', ['filter' => 'userAuth'], function ($routes) {
    $routes->get('table', 'FoodController::foodTable');
    $routes->post('generate', 'FoodController::foodGenerate');
    $routes->post('saveTable', 'FoodController::saveTable');
});

// -----------------------------------------------------------------------------
// Webhook
// -----------------------------------------------------------------------------

$routes->get('/webhook/(:any)', 'WebhookController::verifyWebhook/$1'); // Webhook สำหรับยืนยัน Meta Developer
$routes->post('/webhook/(:any)', 'WebhookController::webhook/$1'); // Webhook สำหรับรับข้อมูลจากแพลตฟอร์ม

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
