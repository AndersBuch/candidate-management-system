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
        listCompaniesWithJobs($pdo);
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

function listCompaniesWithJobs(PDO $pdo): void
{
    $sql = "
        SELECT 
            c.id   AS company_id,
            c.name AS company_name,
            j.id   AS job_id,
            j.title,
            j.public_id
        FROM Company c
        LEFT JOIN Job j ON j.company_id = c.id
        ORDER BY c.name, j.title
    ";

    $stmt = $pdo->query($sql);
    $rows = $stmt->fetchAll();

    $companies = [];

    foreach ($rows as $row) {
        $companyId = (int) $row['company_id'];

        if (!isset($companies[$companyId])) {
            $companies[$companyId] = [
                'id'        => $companyId,
                'name'      => $row['company_name'],
                'positions' => [],
            ];
        }

        if (!empty($row['job_id'])) {
            $companies[$companyId]['positions'][] = [
                'id'            => (int) $row['job_id'],
                'name'          => $row['title'],
                // public_id i databasen = applicationId i frontend
                'applicationId' => $row['public_id'],
            ];
        }
    }

    echo json_encode(array_values($companies));
}
