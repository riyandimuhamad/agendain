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

<?php
// ... (kode koneksi database Anda di atas) ...
// unset($pdo); // Pastikan baris ini tidak ada

/**
 * Fungsi untuk menangani upload gambar event.
 * @param array $fileInput Data file dari $_FILES['nama_input']
 * @param string $uploadDir Direktori tujuan (misal: 'uploads/posters/')
 * @return string|null Nama file yang berhasil di-upload, atau null jika gagal/tidak ada file.
 * @throws Exception Jika terjadi error validasi (tipe file, ukuran file).
 */
function handleUploadGambar($fileInput, $uploadDir) {
    // Cek jika tidak ada file yang di-upload atau ada error
    if (!isset($fileInput) || $fileInput['error'] !== UPLOAD_ERR_OK) {
        // UPLOAD_ERR_NO_FILE berarti user tidak meng-upload gambar, ini tidak apa-apa
        if ($fileInput['error'] == UPLOAD_ERR_NO_FILE) {
            return null; // Tidak ada file di-upload, kembalikan null
        }
        // Error upload lainnya
        throw new Exception("Error saat meng-upload file. Kode: " . $fileInput['error']);
    }

    // 1. Validasi Ukuran File (Contoh: 2MB)
    $maxSize = 2 * 1024 * 1024; // 2MB in bytes
    if ($fileInput['size'] > $maxSize) {
        throw new Exception("Ukuran file terlalu besar. Maksimal 2MB.");
    }

    // 2. Validasi Tipe File (MIME Type)
    $allowedTypes = [
        'image/jpeg' => '.jpg',
        'image/png'  => '.png',
        'image/jpg'  => '.jpg'
    ];
    $fileMimeType = mime_content_type($fileInput['tmp_name']);

    if (!isset($allowedTypes[$fileMimeType])) {
        throw new Exception("Tipe file tidak diizinkan. Hanya .jpg, .jpeg, atau .png.");
    }

    // 3. Buat Nama File yang Unik
    // Format: event_timestamp_namaacak.ext (misal: event_1678886400_abc123.jpg)
    $fileExtension = $allowedTypes[$fileMimeType];
    $newFileName = 'event_' . time() . '_' . bin2hex(random_bytes(8)) . $fileExtension;
    $targetPath = $uploadDir . $newFileName;

    // 4. Pindahkan File
    if (move_uploaded_file($fileInput['tmp_name'], $targetPath)) {
        return $newFileName; // Berhasil, kembalikan nama file baru
    } else {
        throw new Exception("Gagal memindahkan file yang di-upload.");
    }
}

?>