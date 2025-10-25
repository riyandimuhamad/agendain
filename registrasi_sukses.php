<?php
require_once 'config.php'; // Sertakan koneksi DB

$event_nama = "event ini"; // Default text
$event_id = isset($_GET['event_id']) ? (int)$_GET['event_id'] : 0;

if ($event_id > 0) {
    try {
        $sql = "SELECT nama_event FROM events WHERE event_id = :event_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':event_id', $event_id, PDO::PARAM_INT);
        $stmt->execute();
        if ($stmt->rowCount() == 1) {
            $event = $stmt->fetch(PDO::FETCH_ASSOC);
            $event_nama = htmlspecialchars($event['nama_event']); // Ambil nama event
        }
        unset($stmt);
    } catch (PDOException $e) {
        // Biarkan default text jika error
    }
}
unset($pdo); // Tutup koneksi
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Berhasil - Agendain</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap');
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 flex items-center justify-center min-h-screen">
    <div class="bg-white p-10 rounded-lg shadow-lg text-center max-w-lg mx-auto">
        <svg class="text-green-500 w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <h1 class="text-3xl font-bold text-green-600 mb-4">Pendaftaran Berhasil!</h1>
        <p class="text-gray-600 mb-6">Terima kasih telah mendaftar event <strong><?php echo $event_nama; ?></strong>. Informasi lebih lanjut mengenai event akan kami kirimkan ke email Anda (jika diperlukan).</p>
        <a href="index.php" class="text-green-600 hover:underline font-semibold">Kembali ke Halaman Utama</a>
    </div>
</body>
</html>