<?php
session_start(); // Mulai session

// Cek otentikasi PESERTA
if (!isset($_SESSION['participant_loggedin']) || $_SESSION['participant_loggedin'] !== true) {
    header("location: login_participant.php?error=Silakan login terlebih dahulu.");
    exit;
}

// Sertakan koneksi DB
require_once 'config.php';

// Ambil data peserta dari session
$participant_email = $_SESSION['participant_email'];
$participant_nama = $_SESSION['participant_nama'];
$participant_account_id = $_SESSION['participant_account_id'];

// Ambil flash message (jika ada, misal setelah ganti password)
$flash_message = isset($_SESSION['flash_message']) ? $_SESSION['flash_message'] : null;
unset($_SESSION['flash_message']); // Hapus setelah dibaca

// Siapkan array untuk menampung event
$my_events = [];
$fetch_error = null;

try {
    // Query JOIN
    $sql = "SELECT 
                e.event_id, 
                e.nama_event, 
                e.tanggal_mulai, 
                p.status_kehadiran,
                c.certificate_id, 
                c.unique_code,
                c.file_path
            FROM participants p
            JOIN events e ON p.event_id = e.event_id
            LEFT JOIN certificates c ON p.participant_id = c.participant_id
            WHERE p.email = :email 
            ORDER BY e.tanggal_mulai DESC"; 

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $participant_email, PDO::PARAM_STR);
    $stmt->execute();
    $my_events = $stmt->fetchAll(PDO::FETCH_ASSOC);
    unset($stmt);

} catch (PDOException $e) {
    error_log("Database Error: " . $e->getMessage());
    $fetch_error = "Gagal mengambil data event Anda.";
}

unset($pdo); // Tutup koneksi
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Event Saya - Agendain</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap');
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-slate-50">

    <header class="bg-white shadow-md">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-green-600">Agendain <span class="text-sm font-normal text-gray-500">- Peserta</span></h1>
            
            <div class="flex items-center space-x-4">
                <span class="text-gray-700 hidden sm:inline"> Halo, <span class="font-semibold"><?php echo htmlspecialchars($participant_nama); ?></span>!
                </span>
                <a href="ganti_password_peserta.php" class="text-blue-600 hover:underline text-sm font-medium">
                    Ganti Password
                </a>
                <a href="logout_participant.php" class="bg-red-500 text-white font-semibold px-4 py-2 rounded-lg hover:bg-red-600 transition duration-300">
                    Logout
                </a>
            </div>
            </div>
    </header>

    <main class="container mx-auto px-6 py-8">
        
        <?php if ($flash_message): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
                <span class="block sm:inline"><?php echo htmlspecialchars($flash_message); ?></span>
            </div>
        <?php endif; ?>

        <h2 class="text-3xl font-bold text-gray-800 mb-6">Riwayat Event Anda</h2>

        <?php if ($fetch_error): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6" role="alert">
                <?php echo htmlspecialchars($fetch_error); ?>
            </div>
        <?php endif; ?>

        <div class="bg-white p-6 rounded-lg shadow-lg overflow-x-auto">
            <?php if (empty($my_events)): ?>
                <div class="text-center py-12">
                    <p class="text-gray-500 text-xl">Anda belum pernah mendaftar di event manapun.</p>
                    <a href="index.php" class="mt-4 inline-block text-green-600 hover:underline font-semibold">Cari Event Sekarang</a>
                </div>
            <?php else: ?>
                <table class="w-full text-left table-auto min-w-[600px]">
                    <thead>
                        <tr class="border-b bg-gray-50">
                            <th class="py-2 px-4">Nama Event</th>
                            <th class="py-2 px-4">Tanggal</th>
                            <th class="py-2 px-4">Status Kehadiran</th>
                            <th class="py-2 px-4">Sertifikat</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($my_events as $event): ?>
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-3 px-4 font-semibold"><?php echo htmlspecialchars($event['nama_event']); ?></td>
                                <td class="py-3 px-4"><?php echo date('d M Y', strtotime($event['tanggal_mulai'])); ?></td>
                                <td class="py-3 px-4">
                                    <?php if ($event['status_kehadiran']): ?>
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Hadir</span>
                                    <?php else: ?>
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Terdaftar</span>
                                    <?php endif; ?>
                                </td>
                                
                                <td class="py-3 px-4">
                                    <?php 
                                    $event_selesai = strtotime($event['tanggal_mulai']) < time();
                                    
                                    if ($event_selesai && $event['status_kehadiran'] && !empty($event['unique_code'])): 
                                    ?>
                                        <div class="flex flex-col space-y-2 items-start">
                                            <a href="proses_unduh.php?code=<?php echo htmlspecialchars($event['unique_code']); ?>" 
                                               class="bg-blue-500 text-white text-xs font-semibold px-3 py-1 rounded hover:bg-blue-600">
                                               Unduh Sertifikat
                                            </a>
                                            <span class="text-xs text-gray-500">Kode: <code class="font-mono bg-gray-100 p-1 rounded"><?php echo htmlspecialchars($event['unique_code']); ?></code></span>
                                        </div>
                                    <?php elseif ($event_selesai && $event['status_kehadiran'] && empty($event['unique_code'])): ?>
                                        <span class="text-xs text-gray-500 italic">Menunggu Panitia</span>
                                    <?php elseif ($event_selesai && !$event['status_kehadiran']): ?>
                                          <span class="text-xs text-gray-400">-</span>
                                    <?php else: ?>
                                        <span class="text-xs text-gray-400">Belum Tersedia</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </main>

</body>
</html>