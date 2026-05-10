<?php
require_once __DIR__ . '/../src/bootstrap.php';

use App\Controllers\ParticipantController;

$event_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($_SERVER["REQUEST_METHOD"] == "POST" && $event_id > 0) {
    $participantController = new ParticipantController($pdo);
    $result = $participantController->registerForEvent($event_id, $_POST);

    if ($result['success']) {
        $_SESSION['flash_message'] = "Pendaftaran berhasil! Terima kasih telah mendaftar.";
        header("location: registrasi_sukses.php?id=" . $event_id);
        exit();
    } else {
        $_SESSION['form_errors'] = $result['errors'];
        $_SESSION['form_input'] = $_POST;
        header("location: register_event.php?id=" . $event_id);
        exit();
    }
}

header("location: index.php");
exit();
