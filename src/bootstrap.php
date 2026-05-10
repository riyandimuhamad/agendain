<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Config\Database;

// Initialize Database connection
try {
    $pdo = Database::getInstance();
} catch (Exception $e) {
    die("Error connecting to database: " . $e->getMessage());
}

// Start session if not started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
