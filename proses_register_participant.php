<?php
session_start();
require_once 'config.php';

$errors = [];
$input = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input = $_POST;
    $nama_lengkap = trim($_POST['nama_lengkap']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validasi
    if (empty($nama_lengkap)) $errors[] = "Nama lengkap wajib diisi.";
    if (empty($email)) {
        $errors[] = "Email wajib diisi.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Format email tidak valid.";
    } else {
        // Cek email duplikat
        try {
            $sql_check = "SELECT account_id FROM participant_accounts WHERE email = :email";
            $stmt_check = $pdo->prepare($sql_check);
            $stmt_check->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt_check->execute();
            if ($stmt_check->rowCount() > 0) $errors[] = "Email ini sudah terdaftar sebagai akun peserta.";
            unset($stmt_check);
        } catch (PDOException $e) {
            $errors[] = "Gagal memeriksa email: " . $e->getMessage();
        }
    }
    if (empty($password)) {
        $errors[] = "Password wajib diisi.";
    } elseif (strlen($password) < 6) {
        $errors[] = "Password minimal 6 karakter.";
    }

    // Jika tidak ada error
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        try {
            $sql = "INSERT INTO participant_accounts (nama_lengkap, email, password) VALUES (:nama, :email, :pass)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':nama', $nama_lengkap, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':pass', $hashed_password, PDO::PARAM_STR);

            if ($stmt->execute()) {
                $_SESSION['flash_message'] = "Akun peserta berhasil dibuat! Silakan login.";
                header("location: login_participant.php");
                exit();
            } else {
                $errors[] = "Gagal menyimpan akun.";
            }
            unset($stmt);
        } catch (PDOException $e) {
            $errors[] = "Kesalahan database: " . $e->getMessage();
        }
    }

    // Jika ada error
    if (!empty($errors)) {
        $_SESSION['register_errors'] = $errors;
        $_SESSION['register_input'] = $input;
        header("location: register_participant.php");
        exit();
    }
} else {
    header("location: register_participant.php");
    exit();
}
unset($pdo);
?>