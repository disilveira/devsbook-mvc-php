<?php

use core\Router;

$router = new Router();

// Signin and Signup Routes
$router->get('/', 'HomeController@index');
$router->get('/signin', 'LoginController@signin');
$router->post('/signin', 'LoginController@signinPost');
$router->get('/signup', 'LoginController@signup');
$router->post('/signup', 'LoginController@signupPost');

// Posts Routes
$router->post('/post/new', 'PostController@new');

// Profile Routes
$router->get('/profile/{id}/photos', 'ProfileController@photos');
$router->get('/profile/{id}/friends', 'ProfileController@friends');
$router->get('/profile/{id}/follow', 'ProfileController@follow');
$router->get('/profile/{id}', 'ProfileController@index');
$router->get('/profile', 'ProfileController@index');
$router->get('/friends', 'ProfileController@friends');

// Photos Route
$router->get('/photos', 'ProfileController@photos');

// Search Route
$router->get('/search', 'SearchController@index');

// Confif Routes
$router->get('/config', 'ConfigController@index');
$router->post('/config', 'ConfigController@save');

// Logout Route
$router->get('/logout', 'LoginController@logout');

// AJAX Routes
$router->get('/ajax/like/{id}', 'AjaxController@like');
