<?php
namespace App\Controllers;

use PDO;

class AuthController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function login($email, $password) {
        $errors = [];

        // Validasi input
        if (empty($email)) {
            $errors[] = "Alamat email wajib diisi.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Format alamat email tidak valid.";
        }

        if (empty($password)) {
            $errors[] = "Password wajib diisi.";
        }

        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        // Cek database
        try {
            $sql = "SELECT user_id, email, password, nama_organisasi FROM users WHERE email = :email";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                // Sukses
                session_regenerate_id(true);
                $_SESSION['loggedin'] = true;
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['nama_organisasi'] = $user['nama_organisasi'];
                
                return ['success' => true];
            } else {
                $errors[] = "Email atau password salah.";
                return ['success' => false, 'errors' => $errors];
            }
        } catch (\PDOException $e) {
            error_log("Login Error: " . $e->getMessage());
            return ['success' => false, 'errors' => ["Terjadi kesalahan server."]];
        }
    }

    public function register($data) {
        $errors = [];
        $nama = trim($data['nama_organisasi'] ?? '');
        $email = trim($data['email'] ?? '');
        $password = trim($data['password'] ?? '');
        $confirm = trim($data['confirm_password'] ?? '');

        // Validasi
        if (empty($nama)) $errors[] = "Nama organisasi wajib diisi.";
        if (empty($email)) $errors[] = "Email wajib diisi.";
        elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Format email tidak valid.";
        if (empty($password)) $errors[] = "Password wajib diisi.";
        elseif (strlen($password) < 6) $errors[] = "Password minimal 6 karakter.";
        if ($password !== $confirm) $errors[] = "Konfirmasi password tidak cocok.";

        if (!empty($errors)) return ['success' => false, 'errors' => $errors];

        try {
            // Cek email ganda
            $stmt = $this->pdo->prepare("SELECT user_id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                return ['success' => false, 'errors' => ["Email sudah terdaftar."]];
            }

            // Insert user baru
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (nama_organisasi, email, password) VALUES (?, ?, ?)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$nama, $email, $hashed_password]);

            return ['success' => true];
        } catch (\PDOException $e) {
            error_log("Register Error: " . $e->getMessage());
            return ['success' => false, 'errors' => ["Gagal melakukan registrasi."]];
        }
    }

    public function logout() {
        $_SESSION = array();
        session_destroy();
        header("location: login.php");
        exit;
    }
}
