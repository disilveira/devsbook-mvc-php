<?php
use core\Router;

$router = new Router();

// Signin and Signup Routes
$router->get('/', 'HomeController@index');
$router->get('/login', 'LoginController@signin');
$router->post('/login', 'LoginController@signinPost');
$router->get('/cadastro', 'LoginController@signup');