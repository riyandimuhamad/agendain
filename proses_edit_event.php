<?php
session_start(); // Mulai session
require_once 'config.php'; // Sertakan koneksi

// Cek otentikasi
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: login.php");
    exit;
}

$errors = []; // Array untuk error

// Cek apakah request method adalah POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // ==== VALIDASI CSRF TOKEN ====
    if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
         die("ERROR: Invalid CSRF token.");
    }
    // =============================

    // Ambil semua data dari form
    $event_id = isset($_POST['event_id']) ? (int)$_POST['event_id'] : 0;
    $nama_event = trim($_POST['nama_event']);
    $deskripsi = trim($_POST['deskripsi']);
    $tanggal_mulai = trim($_POST['tanggal_mulai']);
    $kategori_event = isset($_POST['kategori_event']) ? trim($_POST['kategori_event']) : '';
    $user_id = $_SESSION['user_id']; // Ambil user ID dari session untuk keamanan

    // --- VALIDASI INPUT EVENT ---
    if ($event_id <= 0) $errors[] = "ID Event tidak valid.";
    if (empty($nama_event)) $errors[] = "Nama event wajib diisi.";
    if (empty($tanggal_mulai)) {
        $errors[] = "Tanggal & waktu mulai wajib diisi.";
    } elseif (strtotime($tanggal_mulai) === false) { // Cek format tanggal valid
        $errors[] = "Format tanggal & waktu mulai tidak valid.";
    }
    if (empty($kategori_event)) $errors[] = "Kategori event wajib dipilih.";
    // --- AKHIR VALIDASI ---

    // Jika tidak ada error validasi, lanjutkan proses update
    if (empty($errors)) {
        $sql = "UPDATE events SET nama_event = :nama_event, deskripsi = :deskripsi, tanggal_mulai = :tanggal_mulai, kategori_event = :kategori_event
                WHERE event_id = :event_id AND user_id = :user_id"; // Pastikan user_id cocok

        try {
            if ($stmt = $pdo->prepare($sql)) {
                $stmt->bindParam(':nama_event', $nama_event, PDO::PARAM_STR);
                $stmt->bindParam(':deskripsi', $deskripsi, PDO::PARAM_STR);
                $stmt->bindParam(':tanggal_mulai', $tanggal_mulai, PDO::PARAM_STR);
                $stmt->bindParam(':kategori_event', $kategori_event, PDO::PARAM_STR);
                $stmt->bindParam(':event_id', $event_id, PDO::PARAM_INT);
                $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

                if ($stmt->execute()) {
                    // Cek apakah ada baris yang terpengaruh
                    if ($stmt->rowCount() > 0) {
                         $_SESSION['flash_message'] = "Event berhasil diperbarui.";
                    } else {
                         $_SESSION['flash_message'] = "Tidak ada perubahan data yang disimpan."; // Pesan netral
                    }
                    header("location: dashboard.php"); // Kembali ke dashboard
                    exit();
                } else {
                    $errors[] = "Oops! Terjadi kesalahan server saat menyimpan.";
                }
                unset($stmt); // Tutup statement
            } else {
                 $errors[] = "Oops! Terjadi kesalahan server.";
            }
        } catch (PDOException $e) {
             $errors[] = "Oops! Terjadi kesalahan database: " . $e->getMessage();
        }
    }

    // Jika ada error (validasi atau SQL), simpan ke session dan kembali ke form edit
    if (!empty($errors)) {
        $_SESSION['form_errors'] = $errors;
        $_SESSION['form_input'] = $_POST; // Simpan input agar form terisi kembali
        // Penting: Kirim kembali ID event agar form edit tahu event mana yg diedit
        header("location: edit_event.php?id=" . $event_id);
        exit();
    }

} else {
    // Jika halaman diakses langsung (bukan POST), redirect ke dashboard
    header("location: dashboard.php");
    exit();
}
// Tutup koneksi jika proses sampai sini
unset($pdo);
?>