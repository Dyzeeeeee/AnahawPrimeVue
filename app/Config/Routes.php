<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');



$routes->get('getAllMenuItems', 'MenuController::getAllMenuItems');
$routes->post('addMenuItem', 'MenuController::addMenuItem');
$routes->put('archiveMenuItem/(:num)', 'MenuController::archiveMenuItem/$1');
$routes->put('unarchiveMenuItem/(:num)', 'MenuController::unarchiveMenuItem/$1');


$routes->get('getAllCategories', 'CategoryController::getAllCategories');
$routes->post('addCategory', 'CategoryController::addCategory');


$routes->get('getAllSessions', 'SessionController::getAllSessions');
$routes->post('addSession', 'SessionController::addSession');


$routes->get('getAllCustomers', 'CustomerController::getAllCustomers');
$routes->post('addCustomer', 'CustomerController::addCustomer');
$routes->put('archiveCustomer/(:num)', 'CustomerController::archiveCustomer/$1');
$routes->put('unarchiveCustomer/(:num)', 'CustomerController::unarchiveCustomer/$1');


$routes->post('createAccount', 'AccountController::createAccount');
$routes->post('login', 'AccountController::login');
$routes->post('logout', 'AccountController::logout');


$routes->get('getAllOrders', 'OrderController::getAllOrders');
$routes->post('createNewOrder', 'OrderController::createNewOrder');
$routes->put('addCustomerToOrder/(:num)', 'OrderController::addCustomerToOrder/$1');
$routes->put('changeService/(:num)', 'OrderController::changeService/$1');
$routes->put('addCustomerPayment/(:num)', 'OrderController::addCustomerPayment/$1');
$routes->get('getOrderDetails/(:segment)', 'OrderController::getOrderDetails/$1');
$routes->get('getAllSessionOrders/(:segment)', 'OrderController::getAllSessionOrders/$1');

$routes->post('addMenuItemToOrder', 'OrderDetailsController::addMenuItemToOrder');
$routes->get('getOrderItems/(:segment)', 'OrderDetailsController::getOrderItems/$1');


$routes->get('facebook/login', 'FacebookAuth::login');
$routes->get('facebook/callback', 'FacebookAuth::callback');

$routes->get('getAllFoodStocks', 'FoodStockController::getAllFoodStocks');

