<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$out = [
    'DB_HOST' => env('DB_HOST'),
    'DB_PORT' => env('DB_PORT'),
    'DB_DATABASE' => env('DB_DATABASE'),
    'DB_USERNAME' => env('DB_USERNAME'),
    'DB_PASSWORD' => env('DB_PASSWORD'),
    'connection' => config('database.default'),
];
// include mysql connection config but remove password for safety
$mysql = config('database.connections.mysql');
if (is_array($mysql)) { unset($mysql['password']); }
$out['mysql'] = $mysql;
echo json_encode($out, JSON_PRETTY_PRINT);
