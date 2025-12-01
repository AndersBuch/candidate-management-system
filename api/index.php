<?php

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

$pdo = require __DIR__ . '/config/database.php';

spl_autoload_register(function ($class) {
    $baseDir = __DIR__;
    $paths = [
        $baseDir . '/models/' . $class . '.php',
        $baseDir . '/controllers/' . $class . '.php',
    ];

    foreach ($paths as $file) {
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

$uri  = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = preg_replace('#^/api#', '', $uri);

switch ($path) {
    case '':
    case '/':
        health($pdo);
        break;

    case '/companies':
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            break;
        }
        $controller = new CompanyController($pdo);
        $controller->index();
        break;

    default:
        http_response_code(404);
        echo json_encode(['error' => 'Not found']);
        break;
}

function health(PDO $pdo): void
{
    $status = ['status' => 'api ok', 'db' => 'ok'];

    try {
        $pdo->query('SELECT 1');
    } catch (Throwable $e) {
        http_response_code(500);
        $status['db'] = 'error';
        $status['error'] = 'DB connection failed';
    }

    echo json_encode($status);
}
