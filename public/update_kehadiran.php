<?php
require_once __DIR__ . '/../src/bootstrap.php';

use App\Controllers\ParticipantController;

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: login.php");
    exit;
}

$participant_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$status = isset($_GET['status']) ? (int)$_GET['status'] : 0;
$event_id = isset($_GET['event_id']) ? (int)$_GET['event_id'] : 0;

if ($participant_id > 0) {
    $participantController = new ParticipantController($pdo);
    $result = $participantController->updateAttendance($_SESSION['user_id'], $participant_id, $status);
}

header("location: detail_event.php?id=" . $event_id);
exit();
