<?php
session_start(); // Mulai Session
require_once 'config.php'; // Koneksi DB

$errors = []; // Array error

// Cek jika sudah login, redirect
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("location: dashboard.php");
    exit;
}

// Cek apakah form sudah di-submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validasi input
    if (empty($email)) $errors[] = "Alamat email wajib diisi.";
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Format alamat email tidak valid.";
    if (empty($password)) $errors[] = "Password wajib diisi.";

    // Jika validasi awal lolos, cek ke database
    if (empty($errors)) {
        $sql = "SELECT user_id, email, password, nama_organisasi FROM users WHERE email = :email";
        if ($stmt = $pdo->prepare($sql)) {
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            if ($stmt->execute()) {
                if ($stmt->rowCount() == 1) {
                    $user = $stmt->fetch();
                    // Verifikasi Password
                    if (password_verify($password, $user['password'])) {
                        // Sukses, mulai session baru
                        session_regenerate_id(true); // Keamanan
                        $_SESSION['loggedin'] = true;
                        $_SESSION['user_id'] = $user['user_id'];
                        $_SESSION['nama_organisasi'] = $user['nama_organisasi'];
                        // Arahkan ke dashboard
                        header("location: dashboard.php");
                        exit();
                    } else {
                        $errors[] = "Password yang Anda masukkan salah.";
                    }
                } else {
                    $errors[] = "Akun dengan email tersebut tidak ditemukan.";
                }
            } else {
                $errors[] = "Oops! Terjadi kesalahan server.";
            }
            unset($stmt);
        } else {
            $errors[] = "Oops! Terjadi kesalahan server.";
        }
    }

    // Jika ada error (validasi atau DB check), kembali ke login
    if (!empty($errors)) {
        $_SESSION['login_errors'] = $errors;
        $_SESSION['login_input'] = ['email' => $email]; // Hanya simpan email
        header("location: login.php");
        exit();
    }

} else {
    // Jika diakses langsung, redirect ke login
    header("location: login.php");
    exit();
}
// Tutup koneksi jika proses sampai sini
unset($pdo);
?>