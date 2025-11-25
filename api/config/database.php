<?php
$secureConfig = '/var/www/buchdesigns.dk/secure/config.php';

if (!file_exists($secureConfig)) {
    http_response_code(500);
    die("❌ Secure config not found at: $secureConfig");
}

require_once $secureConfig;

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (Exception $e) {
    http_response_code(500);
    die("❌ DB Connection error: " . $e->getMessage());
}

return $pdo;