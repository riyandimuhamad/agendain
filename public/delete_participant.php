<?php
require_once __DIR__ . '/../src/bootstrap.php';

use App\Controllers\ParticipantController;

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: login.php");
    exit;
}

$participant_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($participant_id > 0) {
    $participantController = new ParticipantController($pdo);
    $result = $participantController->delete($_SESSION['user_id'], $participant_id);
    
    if ($result['success']) {
        $_SESSION['flash_message'] = "Peserta berhasil dihapus.";
        header("location: detail_event.php?id=" . $result['event_id']);
        exit();
    }
}

header("location: dashboard.php");
exit();
