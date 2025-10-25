<?php
session_start();
require_once 'config.php';

// Cek otentikasi
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    die("Akses ditolak. Silakan login."); // Lebih baik tidak redirect otomatis untuk file view
}

// Validasi CSRF Token dari GET
if (!isset($_GET['token']) || !isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_GET['token'])) {
    die("ERROR: Invalid CSRF token.");
}

// Ambil ID sertifikat dari URL
$certificate_id = isset($_GET['cert_id']) ? (int)$_GET['cert_id'] : 0;
if ($certificate_id <= 0) {
    die("ID Sertifikat tidak valid.");
}

$user_id = $_SESSION['user_id']; // ID Panitia yang login

try {
    // Ambil file path dan pastikan event ini milik user yang login
    $sql = "SELECT c.file_path 
            FROM certificates c
            JOIN participants p ON c.participant_id = p.participant_id
            JOIN events e ON p.event_id = e.event_id
            WHERE c.certificate_id = :certificate_id AND e.user_id = :user_id";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':certificate_id', $certificate_id, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() == 1) {
        $certificate = $stmt->fetch();
        $file_path = $certificate['file_path'];

        // Cek apakah file benar-benar ada
        if (file_exists($file_path)) {
            // Set header untuk menampilkan PDF inline di browser
            header('Content-Type: application/pdf');
            header('Content-Disposition: inline; filename="' . basename($file_path) . '"'); // 'inline' untuk view
            header('Content-Transfer-Encoding: binary');
            header('Accept-Ranges: bytes');
            header('Content-Length: ' . filesize($file_path));

            // Bersihkan output buffer dan kirim file
            ob_clean();
            flush();
            readfile($file_path);
            exit;
        } else {
            die("File sertifikat tidak ditemukan di server.");
        }
    } else {
        die("Sertifikat tidak ditemukan atau Anda tidak memiliki izin untuk melihatnya.");
    }
     unset($stmt);

} catch (PDOException $e) {
    die("ERROR Database: " . $e->getMessage());
} finally {
    unset($pdo); // Tutup koneksi
}
?>