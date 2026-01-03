<?php

error_reporting(E_ALL);
ini_set('display_errors', 0); // IMPORTANT on live
ini_set('log_errors', 1);

header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: GET, POST, PATCH, DELETE, OPTIONS, PUT");

// Preflight
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

$method = $_SERVER['REQUEST_METHOD'];
$uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path   = preg_replace('#^/api#', '', $uri); // fjerner /api prefix

// Debug: log incoming request (kan fjernes)
error_log("REQUEST_URI: " . $_SERVER['REQUEST_URI']);
error_log("REQUEST_METHOD: " . $_SERVER['REQUEST_METHOD']);

// Debug: skriv i response body så klient kan se
if (isset($_GET['__debug'])) {
    echo json_encode([
        'debug_uri' => $uri,
        'debug_path' => $path,
        'method' => $_SERVER['REQUEST_METHOD']
    ]);
    exit;
}

switch ($path) {
    case '':
    case '/':
        health($pdo);
        break;

    case '/companies':
        if ($method !== 'GET') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            break;
        }
        $controller = new CompanyController($pdo);
        $controller->index();
        break;

    case '/login':
        if ($method !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            break;
        }
        $controller = new AuthController($pdo);
        $controller->login();
        break;

    case '/candidates':
        $controller = new CandidateController($pdo);
        if ($method === 'GET') {
            // du kan vælge at holde index() til generel liste eller ikke bruge den
            $controller->index();
        } elseif ($method === 'POST') {
            $controller->store();
        } else {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
        }
        break;

    case '/candidates/count':
        if ($method !== 'GET') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            break;
        }
        $controller = new CandidateController($pdo);
        $controller->count();
        break;

    case '/candidates/recent':
        if ($method !== 'GET') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            break;
        }
        $days = isset($_GET['days']) ? (int)$_GET['days'] : 30;
        $controller = new CandidateController($pdo);
        $controller->countRecent($days);
        break;

    case (preg_match('#^/candidates/(\d+)$#', $path, $m) ? true : false):
        $controller = new CandidateController($pdo);
        $id = (int)$m[1];

        if ($method === 'PUT') {
            $controller->update($id); // brug update-metoden i CandidateController
        } elseif ($method === 'PATCH') {
            $controller->updateStatus($id); // opdater status
        } elseif ($method === 'DELETE') {
            $controller->destroy($id);
        } else {
            http_response_code(405);
            echo json_encode(["error" => "Method not allowed"]);
        }
        break;

    case '/candidates/deleted':
        if ($method !== 'GET') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            break;
        }
        $days = isset($_GET['days']) ? (int)$_GET['days'] : 7; // default 7 dage
        $controller = new CandidateController($pdo);
        $controller->countDeleted($days);
        break;

    case (preg_match('#^/applications/(\d+)/documents$#', $path, $m) ? true : false):
    if ($method !== 'GET') {
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        break;
    }
    require_once __DIR__ . '/controllers/DocumentController.php';
    $controller = new DocumentController($pdo);
    $controller->listByApplication((int)$m[1]);
    break;

    case (preg_match('#^/documents/(\d+)$#', $path, $m) ? true : false):
        require_once __DIR__ . '/controllers/DocumentController.php';
        $controller = new DocumentController($pdo);

        if ($method === 'DELETE') {
            $controller->delete((int)$m[1]);
        } else {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
        }
        break;

    case (preg_match('#^/documents/(\d+)/download$#', $path, $m) ? true : false):
        if ($method !== 'GET') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            break;
        }
        require_once __DIR__ . '/controllers/DocumentController.php';
        $controller = new DocumentController($pdo);
        $controller->download((int)$m[1]);
        break;


    default:
        // 👇 NYT: /jobs/{jobId}/candidates
        if (preg_match('#^/jobs/(\d+)/candidates$#', $path, $matches)) {
            if ($method !== 'GET') {
                http_response_code(405);
                echo json_encode(['error' => 'Method not allowed']);
                break;
            }

            $jobId = (int)$matches[1];
            $controller = new JobController($pdo);
            $controller->candidates($jobId);
            break;
        }

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
