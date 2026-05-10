<?php
require_once __DIR__ . '/../src/bootstrap.php';

use App\Controllers\EventController;

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: login.php");
    exit;
}

$event_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($_SERVER["REQUEST_METHOD"] == "POST" && $event_id > 0) {
    $eventController = new EventController($pdo);
    $result = $eventController->update($_SESSION['user_id'], $event_id, $_POST, $_FILES);

    if ($result['success']) {
        $_SESSION['flash_message'] = "Event berhasil diperbarui!";
        header("location: detail_event.php?id=" . $event_id);
        exit();
    } else {
        $_SESSION['form_errors'] = $result['errors'];
        header("location: edit_event.php?id=" . $event_id);
        exit();
    }
}

header("location: dashboard.php");
exit();
