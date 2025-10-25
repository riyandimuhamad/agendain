<?php
session_start();
require_once 'config.php';

// Cek otentikasi
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: login.php");
    exit;
}

// Ambil ID event dan validasi
$event_id = isset($_GET['event_id']) ? (int)$_GET['event_id'] : 0;
if ($event_id <= 0) die("ID Event tidak valid.");

$user_id = $_SESSION['user_id'];
$event = null; // Untuk menyimpan nama event

try {
    // Keamanan: Pastikan event ini milik user yang login
    $sql_check = "SELECT user_id, nama_event FROM events WHERE event_id = :event_id";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->bindParam(':event_id', $event_id, PDO::PARAM_INT);
    $stmt_check->execute();
    $event = $stmt_check->fetch();
    unset($stmt_check);

    if (!$event || $event['user_id'] != $user_id) {
        throw new Exception("Anda tidak memiliki izin untuk mengakses data event ini.");
    }

    // Ambil semua data peserta untuk event ini
    $sql_participants = "SELECT nama_lengkap, email, kategori_peserta, nim, universitas, instansi, nomor_telepon, status_kehadiran, registered_at
                         FROM participants WHERE event_id = :event_id ORDER BY registered_at ASC";
    $stmt_participants = $pdo->prepare($sql_participants);
    $stmt_participants->bindParam(':event_id', $event_id, PDO::PARAM_INT);
    $stmt_participants->execute();
    $participants = $stmt_participants->fetchAll();
    unset($stmt_participants);

} catch (PDOException $e) {
    die("ERROR Database: " . $e->getMessage());
} catch (Exception $e) {
    die("ERROR: " . $e->getMessage());
}


// Nama file CSV (dinamis berdasarkan nama event)
$filename = "peserta_" . preg_replace('/[^A-Za-z0-9-]+/', '_', strtolower($event['nama_event'])) . "_" . date('Ymd') . ".csv";

// Set header agar browser tahu ini adalah file CSV untuk diunduh
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');

// Buka output stream PHP
$output = fopen('php://output', 'w');

// Tulis header kolom CSV
fputcsv($output, ['Nama Lengkap', 'Email', 'Kategori', 'NIM', 'Universitas', 'Instansi', 'No Telepon', 'Status Kehadiran', 'Waktu Daftar']);

// Tulis data peserta ke CSV
if (!empty($participants)) {
    foreach ($participants as $row) {
        $status_kehadiran_text = $row['status_kehadiran'] ? 'Hadir' : 'Tidak Hadir';
        $waktu_daftar = date('Y-m-d H:i:s', strtotime($row['registered_at']));

        fputcsv($output, [
            $row['nama_lengkap'],
            $row['email'],
            $row['kategori_peserta'],
            $row['nim'] ?? '', // Gunakan null coalescing operator untuk handle NULL
            $row['universitas'] ?? '',
            $row['instansi'] ?? '',
            $row['nomor_telepon'] ?? '',
            $status_kehadiran_text,
            $waktu_daftar
        ]);
    }
}

// Tutup output stream dan koneksi DB
fclose($output);
unset($pdo);
exit(); // Penting
?>