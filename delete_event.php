<?php
session_start(); // Mulai session
require_once 'config.php'; // Sertakan koneksi

// Cek otentikasi
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: login.php");
    exit;
}

// ==== VALIDASI CSRF TOKEN DARI GET ====
if (!isset($_GET['token']) || !isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_GET['token'])) {
    die("ERROR: Invalid CSRF token.");
}
// =====================================

// Ambil ID event dari URL
$event_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$user_id = $_SESSION['user_id']; // Ambil user ID dari session

// Validasi ID Event
if ($event_id > 0) {
    // KEAMANAN: Periksa apakah event ini milik user yang sedang login
    $sql_check = "SELECT user_id FROM events WHERE event_id = :event_id";
    try {
        if ($stmt_check = $pdo->prepare($sql_check)) {
            $stmt_check->bindParam(':event_id', $event_id, PDO::PARAM_INT);
            $stmt_check->execute();
            $event = $stmt_check->fetch();

            if ($event && $event['user_id'] == $user_id) {
                // Lanjutkan proses penghapusan
                $sql_delete = "DELETE FROM events WHERE event_id = :event_id";
                if ($stmt_delete = $pdo->prepare($sql_delete)) {
                    $stmt_delete->bindParam(':event_id', $event_id, PDO::PARAM_INT);
                    if ($stmt_delete->execute()) {
                        $_SESSION['flash_message'] = "Event berhasil dihapus.";
                        header("location: dashboard.php");
                        exit();
                    } else {
                        echo "Oops! Terjadi kesalahan saat menghapus event.";
                    }
                    unset($stmt_delete);
                } else {
                     echo "Oops! Terjadi kesalahan server.";
                }
            } else {
                die("ERROR: Anda tidak memiliki izin untuk menghapus event ini.");
            }
            unset($stmt_check);
        } else {
             echo "Oops! Terjadi kesalahan server.";
        }
    } catch (PDOException $e) {
         die("ERROR: Tidak dapat memverifikasi event. " . $e->getMessage());
    }
} else {
    die("ERROR: ID Event tidak valid.");
}

// Tutup koneksi
unset($pdo);
?>