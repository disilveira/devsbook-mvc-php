<?php
use core\Router;

$router = new Router();

// Signin and Signup Routes
$router->get('/', 'HomeController@index');
$router->get('/signin', 'LoginController@signin');
$router->post('/signin', 'LoginController@signinPost');
$router->get('/signup', 'LoginController@signup');
$router->post('/signup', 'LoginController@signupPost');

//$router->get('/search');
//$router->get('/perfil');
//$router->get('/logout');
//$router->get('/friends');
//$router->get('/photos');
//$router->get('/config');
