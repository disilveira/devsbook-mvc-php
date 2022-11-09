<?php
ini_set('display_errors', 1);
error_reporting(E_ERROR | E_PARSE);
session_start();

require '../vendor/autoload.php';
require '../src/routes.php';

$router->run($router->routes);
