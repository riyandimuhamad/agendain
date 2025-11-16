<?php
session_start();
require_once 'config.php'; // config.php sekarang juga berisi fungsi handleUploadGambar

// Cek otentikasi
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: login.php");
    exit;
}

$errors = []; // Array untuk error

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validasi CSRF Token
    if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
         die("ERROR: Invalid CSRF token.");
    }

    $nama_event = trim($_POST['nama_event']);
    $deskripsi = trim($_POST['deskripsi']);
    $tanggal_mulai = trim($_POST['tanggal_mulai']);
    $kategori_event = isset($_POST['kategori_event']) ? trim($_POST['kategori_event']) : '';
    $user_id = $_SESSION['user_id'];
    $namaFileGambar = null; // Variabel untuk menyimpan nama file gambar

    try {
        // --- 1. PROSES UPLOAD GAMBAR DULU ---
        // Cek apakah ada file gambar yang dikirim
        if (isset($_FILES['gambar_event']) && $_FILES['gambar_event']['error'] == UPLOAD_ERR_OK) {
            $uploadDir = 'uploads/posters/'; // Direktori tujuan
            $namaFileGambar = handleUploadGambar($_FILES['gambar_event'], $uploadDir);
        }
        // Jika handleUploadGambar melempar Exception (error validasi), akan ditangkap oleh catch di bawah

        // --- 2. VALIDASI INPUT EVENT ---
        if (empty($nama_event)) $errors[] = "Nama event wajib diisi.";
        if (empty($tanggal_mulai)) $errors[] = "Tanggal & waktu mulai wajib diisi.";
        if (empty($kategori_event)) $errors[] = "Kategori event wajib dipilih.";
        // --- AKHIR VALIDASI ---

        // Jika tidak ada error validasi, lanjutkan proses simpan
        if (empty($errors)) {
            // --- 3. MODIFIKASI SQL QUERY ---
            $sql = "INSERT INTO events (user_id, nama_event, deskripsi, tanggal_mulai, kategori_event, gambar_event) 
                    VALUES (:user_id, :nama_event, :deskripsi, :tanggal_mulai, :kategori_event, :gambar_event)";

            if ($stmt = $pdo->prepare($sql)) {
                $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                $stmt->bindParam(':nama_event', $nama_event, PDO::PARAM_STR);
                $stmt->bindParam(':deskripsi', $deskripsi, PDO::PARAM_STR);
                $stmt->bindParam(':tanggal_mulai', $tanggal_mulai, PDO::PARAM_STR);
                $stmt->bindParam(':kategori_event', $kategori_event, PDO::PARAM_STR);
                // Bind parameter gambar_event (bisa jadi null jika tidak di-upload)
                $stmt->bindParam(':gambar_event', $namaFileGambar, PDO::PARAM_STR);

                if ($stmt->execute()) {
                    $_SESSION['flash_message'] = "Event baru berhasil dibuat.";
                    header("location: dashboard.php");
                    exit();
                } else {
                    $errors[] = "Oops! Terjadi kesalahan server saat menyimpan.";
                }
                unset($stmt);
            } else {
                 $errors[] = "Oops! Terjadi kesalahan server.";
            }
        }
    } catch (PDOException $e) {
        // Error spesifik database
        $errors[] = "Oops! Terjadi kesalahan database: " . $e->getMessage();
    } catch (Exception $e) {
        // Error dari handleUploadGambar (misal: file terlalu besar, tipe salah)
        $errors[] = $e->getMessage();
    }

    // Jika ada error (validasi atau upload), simpan ke session dan kembali ke form
    if (!empty($errors)) {
        $_SESSION['form_errors'] = $errors;
        $_SESSION['form_input'] = $_POST;
        header("location: create_event.php");
        exit();
    }

} else {
    header("location: create_event.php");
    exit();
}
unset($pdo);
?>