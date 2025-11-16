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

    // Ambil semua data dari form
    $event_id = isset($_POST['event_id']) ? (int)$_POST['event_id'] : 0;
    $nama_event = trim($_POST['nama_event']);
    $deskripsi = trim($_POST['deskripsi']);
    $tanggal_mulai = trim($_POST['tanggal_mulai']);
    $kategori_event = isset($_POST['kategori_event']) ? trim($_POST['kategori_event']) : '';
    $user_id = $_SESSION['user_id'];
    
    // Ambil nama gambar lama dari hidden input
    $gambar_lama = trim($_POST['gambar_lama']);
    $namaFileGambar = $gambar_lama; // Default, asumsikan gambar tidak berubah

    try {
        // --- 1. PROSES UPLOAD GAMBAR BARU (JIKA ADA) ---
        $uploadDir = 'uploads/posters/'; // Direktori tujuan
        
        // Cek apakah ada file gambar baru yang dikirim
        if (isset($_FILES['gambar_event']) && $_FILES['gambar_event']['error'] == UPLOAD_ERR_OK) {
            // Panggil fungsi upload. Jika gagal, akan melempar Exception
            $namaFileGambar = handleUploadGambar($_FILES['gambar_event'], $uploadDir);

            // Jika upload berhasil DAN ada gambar lama, hapus file gambar lama
            if ($namaFileGambar && !empty($gambar_lama) && file_exists($uploadDir . $gambar_lama)) {
                @unlink($uploadDir . $gambar_lama); // @ untuk menekan error jika file tidak ditemukan
            }
        }
        // Jika tidak ada file baru di-upload, $namaFileGambar akan tetap berisi $gambar_lama.

        // --- 2. VALIDASI INPUT EVENT ---
        if ($event_id <= 0) $errors[] = "ID Event tidak valid.";
        if (empty($nama_event)) $errors[] = "Nama event wajib diisi.";
        if (empty($tanggal_mulai)) $errors[] = "Tanggal & waktu mulai wajib diisi.";
        if (empty($kategori_event)) $errors[] = "Kategori event wajib dipilih.";
        // --- AKHIR VALIDASI ---

        // Jika tidak ada error validasi, update ke database
        if (empty($errors)) {
            
            // --- 3. MODIFIKASI SQL QUERY ---
            $sql = "UPDATE events SET 
                        nama_event = :nama_event, 
                        deskripsi = :deskripsi, 
                        tanggal_mulai = :tanggal_mulai, 
                        kategori_event = :kategori_event, 
                        gambar_event = :gambar_event 
                    WHERE event_id = :event_id AND user_id = :user_id";

            if ($stmt = $pdo->prepare($sql)) {
                $stmt->bindParam(':nama_event', $nama_event, PDO::PARAM_STR);
                $stmt->bindParam(':deskripsi', $deskripsi, PDO::PARAM_STR);
                $stmt->bindParam(':tanggal_mulai', $tanggal_mulai, PDO::PARAM_STR);
                $stmt->bindParam(':kategori_event', $kategori_event, PDO::PARAM_STR);
                $stmt->bindParam(':gambar_event', $namaFileGambar, $namaFileGambar === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
                $stmt->bindParam(':event_id', $event_id, PDO::PARAM_INT);
                $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

                if ($stmt->execute()) {
                     $_SESSION['flash_message'] = "Event berhasil diperbarui.";
                    header("location: dashboard.php");
                    exit();
                } else {
                    $errors[] = "Oops! Terjadi kesalahan server saat menyimpan.";
                }
                unset($stmt);
            }
        }
    } catch (PDOException $e) {
        $errors[] = "Oops! Terjadi kesalahan database: " . $e->getMessage();
    } catch (Exception $e) {
        // Menangkap error dari handleUploadGambar
        $errors[] = $e->getMessage();
    }

    // Jika ada error (validasi atau upload), simpan ke session dan kembali ke form edit
    if (!empty($errors)) {
        $_SESSION['form_errors'] = $errors;
        $_SESSION['form_input'] = $_POST; 
        header("location: edit_event.php?id=" . $event_id); 
        exit();
    }

} else {
    header("location: dashboard.php");
    exit();
}
unset($pdo);
?>