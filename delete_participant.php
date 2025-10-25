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

// Ambil ID dari URL
$participant_id = isset($_GET['participant_id']) ? (int)$_GET['participant_id'] : 0;
$event_id = isset($_GET['event_id']) ? (int)$_GET['event_id'] : 0; // Untuk redirect kembali
$user_id = $_SESSION['user_id']; // Untuk validasi keamanan

// Validasi ID
if ($participant_id > 0 && $event_id > 0) {
    // KEAMANAN TAMBAHAN: Cek apakah event ini milik user yang sedang login
    $sql_check = "SELECT e.user_id FROM participants p JOIN events e ON p.event_id = e.event_id WHERE p.participant_id = :participant_id";
    try {
        if ($stmt_check = $pdo->prepare($sql_check)) {
            $stmt_check->bindParam(':participant_id', $participant_id, PDO::PARAM_INT);
            $stmt_check->execute();
            $owner = $stmt_check->fetch();

            if ($owner && $owner['user_id'] == $user_id) {
                // Jika valid, siapkan query DELETE
                $sql_delete = "DELETE FROM participants WHERE participant_id = :participant_id";
                if ($stmt_delete = $pdo->prepare($sql_delete)) {
                    $stmt_delete->bindParam(':participant_id', $participant_id, PDO::PARAM_INT);

                    if ($stmt_delete->execute()) {
                        // Berhasil, simpan pesan sukses dan kembali ke detail event
                        $_SESSION['flash_message'] = "Peserta berhasil dihapus.";
                        header("location: detail_event.php?id=" . $event_id);
                        exit();
                    } else {
                        echo "Oops! Terjadi kesalahan saat menghapus peserta.";
                    }
                     unset($stmt_delete);
                } else {
                     echo "Oops! Terjadi kesalahan server.";
                }
            } else {
                die("ERROR: Anda tidak memiliki izin untuk menghapus peserta ini.");
            }
             unset($stmt_check);
        } else {
             echo "Oops! Terjadi kesalahan server.";
        }
    } catch (PDOException $e) {
         die("ERROR Database: " . $e->getMessage());
    }
} else {
    die("ERROR: ID Peserta atau Event tidak valid.");
}
// Tutup koneksi
unset($pdo);
?>