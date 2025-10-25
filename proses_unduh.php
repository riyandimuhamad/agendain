<?php
// File ini HANYA berisi logika PHP, tidak ada HTML

require_once 'config.php'; // Sertakan koneksi

// Cek jika request method adalah POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $unique_code = trim($_POST['unique_code']);

    if (empty($unique_code)) {
        header("location: unduh_sertifikat.php?error=Silakan masukkan kode sertifikat Anda.");
        exit;
    }

    try {
        // Cari sertifikat di database
        $sql = "SELECT file_path FROM certificates WHERE unique_code = :unique_code";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':unique_code', $unique_code, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() == 1) {
            $certificate = $stmt->fetch();
            $file_path = $certificate['file_path'];

            // Jika file ada di server, paksa browser untuk mengunduhnya
            if (file_exists($file_path)) {
                // Set header
                header('Content-Description: File Transfer');
                header('Content-Type: application/pdf');
                header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($file_path));
                // Bersihkan output buffer sebelum kirim file
                ob_clean();
                flush();
                // Baca dan kirim file
                readfile($file_path);
                exit; // Penting untuk menghentikan skrip setelah file dikirim
            } else {
                header("location: unduh_sertifikat.php?error=File sertifikat tidak ditemukan di server. Silakan hubungi panitia.");
                exit;
            }
        } else {
            header("location: unduh_sertifikat.php?error=Kode sertifikat yang Anda masukkan tidak valid.");
            exit;
        }
         unset($stmt);

    } catch (PDOException $e) {
         header("location: unduh_sertifikat.php?error=Terjadi kesalahan database.");
         exit;
    }

} else {
    // Jika diakses langsung tanpa POST, redirect ke form unduh
    header("location: unduh_sertifikat.php");
    exit;
}
// Tutup koneksi
unset($pdo);
?>