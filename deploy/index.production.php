<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// La app vive en ~/clinica-app/, public_html está al mismo nivel
if (file_exists($maintenance = __DIR__.'/../clinica-app/storage/framework/maintenance.php')) {
    require $maintenance;
}

require __DIR__.'/../clinica-app/vendor/autoload.php';

/** @var Application $app */
$app = require_once __DIR__.'/../clinica-app/bootstrap/app.php';

$app->handleRequest(Request::capture());
