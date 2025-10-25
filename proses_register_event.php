<?php
// Sertakan file koneksi database
require_once 'config.php';
session_start(); // Mulai session untuk menyimpan error dan input

$errors = []; // Array untuk error
$input = []; // Array untuk menyimpan input

// Ambil ID event dari URL dan pastikan valid
$event_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($event_id <= 0) {
    // Redirect ke index jika ID event tidak valid, lebih baik daripada die()
    header("location: index.php?error=event_invalid");
    exit;
}

// Cek apakah form sudah disubmit dengan metode POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Simpan input asli untuk repopulate
    $input = $_POST;

    // Ambil data umum dari formulir
    $nama_lengkap = trim($_POST['nama_lengkap']);
    $email = trim($_POST['email']);
    $kategori_peserta = isset($_POST['kategori_peserta']) ? trim($_POST['kategori_peserta']) : '';

    // Ambil data kondisional
    $nim = ($kategori_peserta == 'Mahasiswa' && isset($_POST['nim'])) ? trim($_POST['nim']) : null;
    $universitas = ($kategori_peserta == 'Mahasiswa' && isset($_POST['universitas'])) ? trim($_POST['universitas']) : null;
    $instansi = ($kategori_peserta == 'Umum' && isset($_POST['instansi'])) ? trim($_POST['instansi']) : null;
    $nomor_telepon = ($kategori_peserta == 'Umum' && isset($_POST['nomor_telepon'])) ? trim($_POST['nomor_telepon']) : null;

    // --- VALIDASI PENDAFTARAN ---
    if (empty($nama_lengkap)) $errors[] = "Nama lengkap wajib diisi.";
    if (empty($email)) {
        $errors[] = "Alamat email wajib diisi.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Format alamat email tidak valid.";
    } else {
        // Cek apakah email sudah terdaftar di event ini
        try {
            $sql_check_reg = "SELECT participant_id FROM participants WHERE event_id = :event_id AND email = :email";
            $stmt_check_reg = $pdo->prepare($sql_check_reg);
            $stmt_check_reg->bindParam(':event_id', $event_id, PDO::PARAM_INT);
            $stmt_check_reg->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt_check_reg->execute();
            if ($stmt_check_reg->rowCount() > 0) $errors[] = "Anda sudah terdaftar di event ini dengan email tersebut.";
            unset($stmt_check_reg);
        } catch (PDOException $e) {
            $errors[] = "Gagal memeriksa pendaftaran: " . $e->getMessage();
        }
    }
    // ... (Validasi kategori, nim, universitas, dll. tetap sama) ...
    if (empty($kategori_peserta)) $errors[] = "Kategori peserta wajib dipilih.";
    // ... dst ...
    // --- AKHIR VALIDASI ---

    // Jika tidak ada error validasi, lanjutkan proses
    if (empty($errors)) {
        $account_created = false; // Flag untuk menandai jika akun baru dibuat
        $default_password = 'password123'; // Password default (TIDAK AMAN!)

        try {
            // ---- CEK & BUAT AKUN PESERTA JIKA BELUM ADA ----
            $sql_check_acc = "SELECT account_id FROM participant_accounts WHERE email = :email";
            $stmt_check_acc = $pdo->prepare($sql_check_acc);
            $stmt_check_acc->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt_check_acc->execute();

            if ($stmt_check_acc->rowCount() == 0) {
                // Akun belum ada, buat baru
                $hashed_password = password_hash($default_password, PASSWORD_DEFAULT);
                $sql_create_acc = "INSERT INTO participant_accounts (nama_lengkap, email, password) VALUES (:nama, :email, :pass)";
                $stmt_create_acc = $pdo->prepare($sql_create_acc);
                $stmt_create_acc->bindParam(':nama', $nama_lengkap, PDO::PARAM_STR);
                $stmt_create_acc->bindParam(':email', $email, PDO::PARAM_STR);
                $stmt_create_acc->bindParam(':pass', $hashed_password, PDO::PARAM_STR);
                if ($stmt_create_acc->execute()) {
                    $account_created = true;
                } else {
                    throw new Exception("Gagal membuat akun peserta baru.");
                }
                unset($stmt_create_acc);
            }
            unset($stmt_check_acc);
            // ---------------------------------------------

            // ---- SIMPAN PENDAFTARAN EVENT ----
            $sql_insert_participant = "INSERT INTO participants (event_id, nama_lengkap, email, kategori_peserta, nim, universitas, instansi, nomor_telepon)
                                       VALUES (:event_id, :nama_lengkap, :email, :kategori_peserta, :nim, :universitas, :instansi, :nomor_telepon)";
            $stmt_insert = $pdo->prepare($sql_insert_participant);
            // Bind semua parameter...
            $stmt_insert->bindParam(':event_id', $event_id, PDO::PARAM_INT);
            $stmt_insert->bindParam(':nama_lengkap', $nama_lengkap, PDO::PARAM_STR);
            $stmt_insert->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt_insert->bindParam(':kategori_peserta', $kategori_peserta, PDO::PARAM_STR);
            $stmt_insert->bindParam(':nim', $nim, PDO::PARAM_STR);
            $stmt_insert->bindParam(':universitas', $universitas, PDO::PARAM_STR);
            $stmt_insert->bindParam(':instansi', $instansi, PDO::PARAM_STR);
            $stmt_insert->bindParam(':nomor_telepon', $nomor_telepon, PDO::PARAM_STR);

            if ($stmt_insert->execute()) {
                // Pendaftaran event berhasil
                if ($account_created) {
                    $_SESSION['flash_message'] = "Pendaftaran event berhasil! Akun baru telah dibuat. Silakan login dengan password default: '{$default_password}' (Disarankan segera ganti password).";
                } else {
                    $_SESSION['flash_message'] = "Pendaftaran event berhasil! Silakan login untuk melihat detail.";
                }
                header("location: login_participant.php"); // Arahkan ke login peserta
                exit();
            } else {
                throw new Exception("Gagal menyimpan pendaftaran event.");
            }
            unset($stmt_insert);
            // -------------------------------

        } catch (PDOException $e) {
            $errors[] = "Kesalahan database: " . $e->getMessage();
        } catch (Exception $e) {
             $errors[] = "Terjadi kesalahan: " . $e->getMessage();
        }
    }

    // Jika ada error (validasi atau proses simpan), kembali ke form pendaftaran
    if (!empty($errors)) {
        $_SESSION['form_errors'] = $errors;
        $_SESSION['form_input'] = $input;
        header("location: register_event.php?id=" . $event_id);
        exit();
    }

} else {
    // Jika diakses langsung, redirect
    header("location: index.php");
    exit();
}
// Tutup koneksi jika proses sampai sini
unset($pdo);
?>