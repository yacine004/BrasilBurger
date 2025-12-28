<?php

// Suppress PHP 8.4 deprecation warnings from vendor code
error_reporting(E_ALL & ~E_DEPRECATED);
ini_set('display_errors', '0');

use App\Kernel;
use Symfony\Component\HttpFoundation\Request;

require_once dirname(__DIR__).'/config/bootstrap.php';

$kernel = new Kernel($_SERVER['APP_ENV'], (bool) $_SERVER['APP_DEBUG']);
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
