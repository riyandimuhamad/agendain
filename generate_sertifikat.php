<?php
use Dompdf\Dompdf;
use Dompdf\Options;

session_start();
require_once 'config.php';
// Pastikan folder vendor ada dan autoload bisa diakses
if (!file_exists('vendor/autoload.php')) {
     die("ERROR: Composer dependencies not installed. Run 'composer install'.");
}
require 'vendor/autoload.php';

// Cek otentikasi
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: login.php");
    exit;
}

// Ambil event ID dan validasi
$event_id = isset($_GET['event_id']) ? (int)$_GET['event_id'] : 0;
if ($event_id <= 0) die("ID Event tidak valid.");
$user_id = $_SESSION['user_id']; // Keamanan

try {
    // Ambil data event & cek kepemilikan
    $sql_event = "SELECT nama_event, tanggal_mulai, user_id FROM events WHERE event_id = ?";
    $stmt_event = $pdo->prepare($sql_event);
    $stmt_event->execute([$event_id]);
    $event = $stmt_event->fetch();
    unset($stmt_event);
    if (!$event || $event['user_id'] != $user_id) die("ERROR: Event tidak ditemukan atau Anda tidak punya akses.");

    // Ambil semua peserta yang HADIR
    $sql_participants = "SELECT participant_id, nama_lengkap FROM participants WHERE event_id = ? AND status_kehadiran = 1";
    $stmt_participants = $pdo->prepare($sql_participants);
    $stmt_participants->execute([$event_id]);
    $participants = $stmt_participants->fetchAll();
    unset($stmt_participants);

    if (empty($participants)) {
         $_SESSION['flash_message'] = "Tidak ada peserta yang hadir untuk generate sertifikat.";
         header("location: detail_event.php?id=" . $event_id);
         exit();
    }

    // Muat template HTML
    $template_path = "sertifikat_template.html";
    if (!file_exists($template_path)) die("ERROR: Template sertifikat tidak ditemukan.");
    $template = file_get_contents($template_path);

    // Pastikan folder sertifikat ada dan bisa ditulis
    $sertifikat_folder = 'sertifikat';
    if (!is_dir($sertifikat_folder)) mkdir($sertifikat_folder, 0755, true);
    if (!is_writable($sertifikat_folder)) die("ERROR: Folder 'sertifikat' tidak bisa ditulis.");


    // Loop melalui setiap peserta yang hadir
    foreach ($participants as $participant) {
        // Ganti placeholder
        $html = str_replace(
            ['{{NAMA_PESERTA}}', '{{NAMA_EVENT}}', '{{TANGGAL_EVENT}}'],
            [
                htmlspecialchars($participant['nama_lengkap']),
                htmlspecialchars($event['nama_event']),
                date('d F Y', strtotime($event['tanggal_mulai']))
            ],
            $template
        );

        // Konfigurasi Dompdf
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true); // Jika template pakai gambar online
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        // Simpan file PDF
        $filename = "sertifikat_" . $event_id . "_" . $participant['participant_id'] . ".pdf";
        $file_path = $sertifikat_folder . "/" . $filename;
        if (file_put_contents($file_path, $dompdf->output()) === false) {
             throw new Exception("Gagal menyimpan file PDF untuk peserta ID: " . $participant['participant_id']);
        }

        // Simpan data sertifikat ke database (atau update jika sudah ada)
        $unique_code = 'CERT-' . strtoupper(bin2hex(random_bytes(5))) . '-' . $participant['participant_id'];
        $sql_upsert_cert = "INSERT INTO certificates (participant_id, unique_code, file_path) VALUES (?, ?, ?)
                            ON DUPLICATE KEY UPDATE unique_code = VALUES(unique_code), file_path = VALUES(file_path)";
        $stmt_upsert_cert = $pdo->prepare($sql_upsert_cert);
        $stmt_upsert_cert->execute([$participant['participant_id'], $unique_code, $file_path]);
        unset($stmt_upsert_cert);
    }

    // Set flash message sukses dan redirect
    $_SESSION['flash_message'] = "Sertifikat berhasil di-generate untuk " . count($participants) . " peserta yang hadir.";
    header("location: detail_event.php?id=" . $event_id);
    exit();

} catch (PDOException $e) {
    die("ERROR Database: " . $e->getMessage());
} catch (Exception $e) {
    die("ERROR: " . $e->getMessage());
} finally {
     // Tutup koneksi
    unset($pdo);
}
?>