<?php
/**
 * POST-DEPLOY — Ejecutar UNA SOLA VEZ después del primer despliegue.
 * URL: https://bs-estetic.com/post-deploy.php?token=BS_DEPLOY_2025
 * ELIMINAR este archivo del servidor después de usarlo.
 */

// Token de seguridad — cámbialo antes de subir si quieres más seguridad
define('DEPLOY_TOKEN', 'BS_DEPLOY_2025');

if (!isset($_GET['token']) || $_GET['token'] !== DEPLOY_TOKEN) {
    http_response_code(403);
    die('<h2>403 Acceso denegado</h2>');
}

header('Content-Type: text/plain; charset=utf-8');
echo "=== POST-DEPLOY bs-estetic.com ===\n\n";

define('LARAVEL_START', microtime(true));

$appPath = __DIR__ . '/../clinica-app';

if (!file_exists("$appPath/vendor/autoload.php")) {
    die("ERROR: No se encontró $appPath/vendor/autoload.php\n");
}

require "$appPath/vendor/autoload.php";
$app = require_once "$appPath/bootstrap/app.php";

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

// 1. Migraciones
echo "▸ Ejecutando migraciones...\n";
$exitCode = $kernel->call('migrate', ['--force' => true]);
echo $kernel->output();
echo ($exitCode === 0) ? "✔ Migraciones completadas\n\n" : "✘ Error en migraciones (código $exitCode)\n\n";

// 2. Seeders (solo primera vez — comenta esta sección en deploys posteriores)
echo "▸ Ejecutando seeders...\n";
$exitCode = $kernel->call('db:seed', ['--force' => true]);
echo $kernel->output();
echo ($exitCode === 0) ? "✔ Seeders completados\n\n" : "✘ Error en seeders (código $exitCode)\n\n";

// 3. Storage link
echo "▸ Creando storage link...\n";
$exitCode = $kernel->call('storage:link');
echo $kernel->output();
echo ($exitCode === 0) ? "✔ Storage link creado\n\n" : "⚠ Storage link (puede ya existir)\n\n";

// 4. Cache
echo "▸ Cacheando configuración...\n";
$kernel->call('config:cache');
$kernel->call('route:cache');
$kernel->call('view:cache');
echo "✔ Caché lista\n\n";

echo "=================================\n";
echo "✔ POST-DEPLOY COMPLETADO\n";
echo "IMPORTANTE: Elimina este archivo del servidor ahora.\n";
echo "  cPanel → File Manager → public_html → borrar post-deploy.php\n";
