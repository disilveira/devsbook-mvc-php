<?php
ini_set('display_errors', 0);
session_start();
require '../vendor/autoload.php';
require '../src/routes.php';

$router->run( $router->routes );