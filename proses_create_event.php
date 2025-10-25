<?php
session_start(); // Mulai session
require_once 'config.php'; // Sertakan koneksi database

// Cek otentikasi (pastikan user sudah login)
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: login.php");
    exit;
}

$errors = []; // Array untuk menampung pesan error

// Cek apakah request method adalah POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // ==== VALIDASI CSRF TOKEN ====
    if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
         die("ERROR: Invalid CSRF token.");
    }
    // =============================

    // Ambil data dari formulir
    $nama_event = trim($_POST['nama_event']);
    $deskripsi = trim($_POST['deskripsi']); // Deskripsi boleh kosong
    $tanggal_mulai = trim($_POST['tanggal_mulai']);
    $kategori_event = isset($_POST['kategori_event']) ? trim($_POST['kategori_event']) : ''; // Cek isset untuk radio
    $user_id = $_SESSION['user_id'];

    // --- VALIDASI INPUT EVENT ---
    if (empty($nama_event)) $errors[] = "Nama event wajib diisi.";
    if (empty($tanggal_mulai)) {
        $errors[] = "Tanggal & waktu mulai wajib diisi.";
    } elseif (strtotime($tanggal_mulai) === false) { // Cek format tanggal valid
        $errors[] = "Format tanggal & waktu mulai tidak valid.";
    }
    if (empty($kategori_event)) $errors[] = "Kategori event wajib dipilih.";
    // --- AKHIR VALIDASI ---

    // Jika tidak ada error validasi, lanjutkan proses simpan
    if (empty($errors)) {
        $sql = "INSERT INTO events (user_id, nama_event, deskripsi, tanggal_mulai, kategori_event)
                VALUES (:user_id, :nama_event, :deskripsi, :tanggal_mulai, :kategori_event)";

        try {
            if ($stmt = $pdo->prepare($sql)) {
                $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                $stmt->bindParam(':nama_event', $nama_event, PDO::PARAM_STR);
                $stmt->bindParam(':deskripsi', $deskripsi, PDO::PARAM_STR);
                $stmt->bindParam(':tanggal_mulai', $tanggal_mulai, PDO::PARAM_STR);
                $stmt->bindParam(':kategori_event', $kategori_event, PDO::PARAM_STR);

                if ($stmt->execute()) {
                    // Berhasil, set flash message dan redirect ke dashboard
                    $_SESSION['flash_message'] = "Event baru berhasil dibuat.";
                    header("location: dashboard.php");
                    exit();
                } else {
                    $errors[] = "Oops! Terjadi kesalahan server saat menyimpan.";
                }
                unset($stmt); // Tutup statement
            } else {
                 $errors[] = "Oops! Terjadi kesalahan server.";
            }
        } catch (PDOException $e) {
             $errors[] = "Oops! Terjadi kesalahan database: " . $e->getMessage(); // Tampilkan error DB jika terjadi
        }
    }

    // Jika ada error (validasi atau SQL), simpan ke session dan kembali ke form
    if (!empty($errors)) {
        $_SESSION['form_errors'] = $errors;
        $_SESSION['form_input'] = $_POST; // Simpan input agar form terisi kembali
        header("location: create_event.php");
        exit();
    }

} else {
    // Jika halaman diakses langsung (bukan POST), redirect ke form
    header("location: create_event.php");
    exit();
}
// Tutup koneksi jika proses sampai sini
unset($pdo);
?>