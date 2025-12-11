<?php
$pdo = require __DIR__ . '/config/database.php';

$email = 'test@domain.com';
$full_name = 'Test User';
$role = 'admin';
$password = 'test123';

// Hash password én gang
$hashed = password_hash($password, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("INSERT INTO `User` (email, full_name, role, password) VALUES (?, ?, ?, ?)");
$stmt->execute([$email, $full_name, $role, $hashed]);

echo "Bruger oprettet!";
