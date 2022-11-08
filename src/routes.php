<?php
use core\Router;

$router = new Router();

// Signin and Signup Routes
$router->get('/', 'HomeController@index');
$router->get('/signin', 'LoginController@signin');
$router->post('/signin', 'LoginController@signinPost');
$router->get('/signup', 'LoginController@signup');
$router->post('/signup', 'LoginController@signupPost');