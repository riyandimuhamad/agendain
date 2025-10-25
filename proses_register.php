<?php
session_start(); // Mulai session untuk CSRF token dan flash messages
require_once 'config.php'; // Sertakan koneksi database

$errors = []; // Array untuk menampung pesan error

// Cek apakah form sudah di-submit dengan metode POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validasi CSRF Token
    if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
         die("ERROR: Invalid CSRF token.");
    }
    // Opsi: Hapus token setelah divalidasi
    // unset($_SESSION['csrf_token']);

    // Ambil data dari formulir
    $nama_organisasi = trim($_POST['nama_organisasi']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // --- VALIDASI LEBIH DETAIL ---
    if (empty($nama_organisasi)) $errors[] = "Nama organisasi wajib diisi.";
    if (empty($email)) {
        $errors[] = "Alamat email wajib diisi.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Format alamat email tidak valid.";
    } else {
        // Cek apakah email sudah terdaftar
        $sql_check = "SELECT user_id FROM users WHERE email = :email";
        if ($stmt_check = $pdo->prepare($sql_check)) {
            $stmt_check->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt_check->execute();
            if ($stmt_check->rowCount() > 0) $errors[] = "Alamat email ini sudah terdaftar.";
            unset($stmt_check);
        }
    }
    if (empty($password)) {
        $errors[] = "Password wajib diisi.";
    } elseif (strlen($password) < 6) { // Minimal 6 karakter
        $errors[] = "Password minimal harus 6 karakter.";
    }
    // --- AKHIR VALIDASI ---

    // Jika tidak ada error, lanjutkan proses
    if (empty($errors)) {
        // HASH PASSWORD
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Siapkan SQL Query
        $sql = "INSERT INTO users (nama_organisasi, email, password) VALUES (:nama, :email, :pass)";
        if ($stmt = $pdo->prepare($sql)) {
            $stmt->bindParam(':nama', $nama_organisasi, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':pass', $hashed_password, PDO::PARAM_STR);

            if ($stmt->execute()) {
                $_SESSION['flash_message'] = "Registrasi berhasil! Silakan login.";
                header("location: login.php");
                exit();
            } else {
                $errors[] = "Oops! Terjadi kesalahan server saat menyimpan.";
            }
            unset($stmt);
        } else {
            $errors[] = "Oops! Terjadi kesalahan server.";
        }
    }

    // Jika ada error, simpan ke session dan kembali ke form registrasi
    if (!empty($errors)) {
        $_SESSION['register_errors'] = $errors;
        $_SESSION['register_input'] = $_POST;
        header("location: register.php");
        exit();
    }

} else {
    // Jika diakses langsung, redirect ke register
    header("location: register.php");
    exit();
}
// Tutup koneksi jika proses sampai sini
unset($pdo);
?>