<?php
require_once __DIR__ . '/../src/bootstrap.php';

use App\Controllers\EventController;

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $eventController = new EventController($pdo);
    $result = $eventController->create($_SESSION['user_id'], $_POST, $_FILES);

    if ($result['success']) {
        $_SESSION['flash_message'] = "Event berhasil dibuat!";
        header("location: dashboard.php");
        exit();
    } else {
        $_SESSION['form_errors'] = $result['errors'];
        $_SESSION['form_input'] = $_POST;
        header("location: create_event.php");
        exit();
    }
}

header("location: create_event.php");
exit();
