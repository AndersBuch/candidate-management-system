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

$status = ['status' => 'api ok', 'db' => 'ok'];

try {
    $pdo->query('SELECT 1');
} catch (Throwable $e) {
    http_response_code(500);
    $status['db'] = 'error';
    $status['error'] = 'DB connection failed';
}

echo json_encode($status);