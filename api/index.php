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
    $paths = [
        __DIR__ . '/models/' . $class . '.php',
        __DIR__ . '/controllers/' . $class . '.php',
    ];
    foreach ($paths as $file) {
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Debug: log incoming request (kan fjernes efter fejlsøgning)
error_log("REQUEST_URI: " . $_SERVER['REQUEST_URI']);
error_log("REQUEST_METHOD: " . $_SERVER['REQUEST_METHOD']);

$uri  = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = preg_replace('#^/api#', '', $uri); // dette kan efterlade leading slash

// Debug: skriv i response body så klient kan se
// OBS: dette er kun midlertidigt for fejlfinding
if (isset($_GET['__debug'])) {
    echo json_encode(['debug_uri' => $uri, 'debug_path' => $path, 'method' => $_SERVER['REQUEST_METHOD']]);
    exit;
}

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

    case '/login':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            break;
        }
        $controller = new AuthController($pdo);
        $controller->login();
        break;

    case '/candidates':
        $controller = new CandidateController($pdo);

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $controller->index();
        } else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->store();
        } else {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
        }
        break;

    default:
        http_response_code(404);
        echo json_encode(['error' => 'Not found', 'requested' => $path]);
        break;
}

function health($pdo)
{
    $status = ['status' => 'api ok', 'db' => 'ok'];
    try {
        $pdo->query('SELECT 1');
    } catch (Exception $e) {
        http_response_code(500);
        $status['db'] = 'error';
        $status['error'] = 'DB connection failed: ' . $e->getMessage();
    }
    echo json_encode($status);
}
