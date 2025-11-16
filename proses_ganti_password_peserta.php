<?php
session_start();
require_once 'config.php';

// Cek otentikasi
if (!isset($_SESSION['participant_loggedin']) || $_SESSION['participant_loggedin'] !== true) {
    header("location: login_participant.php");
    exit;
}

$errors = [];

// Cek metode POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validasi CSRF
    if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die("ERROR: Invalid CSRF token.");
    }

    $password_lama = $_POST['password_lama'];
    $password_baru = $_POST['password_baru'];
    $konfirmasi_password_baru = $_POST['konfirmasi_password_baru'];
    $account_id = $_SESSION['participant_account_id'];

    // Validasi input
    if (empty($password_lama) || empty($password_baru) || empty($konfirmasi_password_baru)) {
        $errors[] = "Semua kolom wajib diisi.";
    }
    if (strlen($password_baru) < 6) {
        $errors[] = "Password baru minimal harus 6 karakter.";
    }
    if ($password_baru !== $konfirmasi_password_baru) {
        $errors[] = "Password baru dan konfirmasi tidak cocok.";
    }

    // Jika validasi awal lolos, cek password lama
    if (empty($errors)) {
        try {
            // Ambil password hash yang sekarang dari DB
            $sql_check = "SELECT password FROM participant_accounts WHERE account_id = :account_id";
            $stmt_check = $pdo->prepare($sql_check);
            $stmt_check->bindParam(':account_id', $account_id, PDO::PARAM_INT);
            $stmt_check->execute();
            
            if ($stmt_check->rowCount() == 1) {
                $user = $stmt_check->fetch();
                $hashed_password_lama = $user['password'];

                // Verifikasi password lama
                if (password_verify($password_lama, $hashed_password_lama)) {
                    // Jika cocok, hash password baru
                    $hashed_password_baru = password_hash($password_baru, PASSWORD_DEFAULT);
                    
                    // Update password di database
                    $sql_update = "UPDATE participant_accounts SET password = :password_baru WHERE account_id = :account_id";
                    $stmt_update = $pdo->prepare($sql_update);
                    $stmt_update->bindParam(':password_baru', $hashed_password_baru, PDO::PARAM_STR);
                    $stmt_update->bindParam(':account_id', $account_id, PDO::PARAM_INT);
                    
                    if ($stmt_update->execute()) {
                        // Sukses
                        $_SESSION['flash_message'] = "Password Anda berhasil diperbarui.";
                        header("location: my_events.php");
                        exit();
                    } else {
                        $errors[] = "Gagal memperbarui password di database.";
                    }
                } else {
                    $errors[] = "Password lama yang Anda masukkan salah.";
                }
            } else {
                $errors[] = "Akun tidak ditemukan.";
            }
             unset($stmt_check);
        } catch (PDOException $e) {
            $errors[] = "Kesalahan database: " . $e->getMessage();
        }
    }

    // Jika ada error, kembali ke form ganti password
    if (!empty($errors)) {
        $_SESSION['form_errors'] = $errors;
        header("location: ganti_password_peserta.php");
        exit();
    }
    
} else {
    // Jika diakses langsung
    header("location: ganti_password_peserta.php");
    exit();
}

unset($pdo);
?>