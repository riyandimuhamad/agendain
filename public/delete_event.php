<?php
require_once __DIR__ . '/../src/bootstrap.php';

use App\Controllers\EventController;

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: login.php");
    exit;
}

$event_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($event_id > 0) {
    $eventController = new EventController($pdo);
    $result = $eventController->delete($_SESSION['user_id'], $event_id);
    
    if ($result['success']) {
        $_SESSION['flash_message'] = "Event berhasil dihapus.";
    } else {
        $_SESSION['flash_message'] = "Gagal menghapus event: " . implode(", ", $result['errors']);
    }
}

header("location: dashboard.php");
exit();
