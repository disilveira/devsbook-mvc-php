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

// Perfil Routes
$router->get('/profile/{id}', 'ProfileController@index');
$router->get('/profile', 'ProfileController@index');

//$router->get('/search');
//$router->get('/logout');
//$router->get('/friends');
//$router->get('/photos');
//$router->get('/config');
