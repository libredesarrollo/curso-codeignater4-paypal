<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');


$routes->group('paypal', function ($routes) {
    $routes->get('', '\App\Controllers\Paypal\PaymentPaypal::index');
    $routes->post('process/(:alphanum)', '\App\Controllers\Paypal\PaymentPaypal::process/$1');
});