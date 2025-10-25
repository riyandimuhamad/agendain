<?php
// Pengaturan Database
define('DB_HOST', 'localhost');      // Host database
define('DB_USER', 'root');          // Username database (default Laragon)
define('DB_PASS', '');              // Password database (default Laragon kosong)
define('DB_NAME', 'agendain');      // Nama database Anda

// Membuat koneksi ke database menggunakan PDO
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    // Set mode error PDO ke exception agar error lebih mudah ditangani
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Set default fetch mode ke associative array
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e){
    // Jika koneksi gagal, hentikan skrip dan tampilkan pesan error
    die("KONEKSI GAGAL: " . $e->getMessage());
}
// Jangan tutup koneksi di sini agar variabel $pdo bisa dipakai di file lain
// unset($pdo); 
?>