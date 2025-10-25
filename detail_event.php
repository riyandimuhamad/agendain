<?php
// Mulai session
session_start();

// Cek otentikasi
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: login.php");
    exit;
}

// ---- TAMBAHAN: Generate CSRF token ----
// Generate CSRF token jika belum ada di session
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];
// ------------------------------------

// Sertakan file koneksi database
require_once 'config.php';

// Ambil Flash Message jika ada (misal setelah hapus peserta)
$flash_message = isset($_SESSION['flash_message']) ? $_SESSION['flash_message'] : null;
unset($_SESSION['flash_message']); // Hapus setelah dibaca

// ----------------------------------------------------
// BAGIAN PENGAMBILAN DATA
// ----------------------------------------------------
$event = null;
$participants = [];
$event_id = 0; // Initialize event_id

try {
    // 1. Ambil ID event dari URL dan validasi
    $event_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    if ($event_id <= 0) {
        throw new Exception("ID Event tidak valid.");
    }

    $user_id = $_SESSION['user_id'];

    // 2. Ambil detail event dari database
    $sql_event = "SELECT * FROM events WHERE event_id = :event_id AND user_id = :user_id";
    $stmt_event = $pdo->prepare($sql_event);
    $stmt_event->bindParam(':event_id', $event_id, PDO::PARAM_INT);
    $stmt_event->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt_event->execute();

    if ($stmt_event->rowCount() == 1) {
        $event = $stmt_event->fetch(PDO::FETCH_ASSOC); // Fetch as Assoc Array
    } else {
        throw new Exception("Event tidak ditemukan atau Anda tidak memiliki akses.");
    }
    unset($stmt_event);

    // 3. Ambil daftar peserta untuk event ini
    $sql_participants = "SELECT p.*, c.certificate_id, c.file_path 
                     FROM participants p 
                     LEFT JOIN certificates c ON p.participant_id = c.participant_id 
                     WHERE p.event_id = :event_id 
                     ORDER BY p.registered_at ASC";
    $stmt_participants = $pdo->prepare($sql_participants);
    $stmt_participants->bindParam(':event_id', $event_id, PDO::PARAM_INT);
    $stmt_participants->execute();
    $participants = $stmt_participants->fetchAll(PDO::FETCH_ASSOC); // Fetch as Assoc Array
    unset($stmt_participants);

} catch (PDOException $e) {
    die("ERROR Database: Tidak dapat mengambil data. " . $e->getMessage());
} catch (Exception $e) {
    die("ERROR: " . $e->getMessage());
}
// Tidak perlu unset $pdo di sini, biarkan koneksi terbuka
// unset($pdo);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Event: <?php echo htmlspecialchars($event['nama_event'] ?? 'Tidak Ditemukan'); ?> - Agendain</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap');
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-slate-50">

    <header class="bg-white shadow-md">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-green-600">Agendain</h1>
            <div>
                <a href="dashboard.php" class="text-gray-600 hover:text-green-600 mr-4">Dashboard</a>
                <a href="logout.php" class="bg-red-500 text-white font-semibold px-4 py-2 rounded-lg hover:bg-red-600 transition duration-300">
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

        <div class="mb-8 bg-white p-6 rounded-lg shadow-md">
            <a href="dashboard.php" class="text-green-600 hover:underline mb-4 block">&larr; Kembali ke Dashboard</a>
            <h2 class="text-3xl font-bold text-gray-800"><?php echo htmlspecialchars($event['nama_event'] ?? 'Event Tidak Ditemukan'); ?></h2>
            <p class="text-gray-500">Tanggal: <?php echo isset($event['tanggal_mulai']) ? date('d F Y, H:i', strtotime($event['tanggal_mulai'])) : '-'; ?></p>
            <p class="mt-4 text-gray-700"><?php echo isset($event['deskripsi']) ? nl2br(htmlspecialchars($event['deskripsi'])) : 'Tidak ada deskripsi.'; ?></p>
            <p class="mt-4 text-sm text-gray-700 font-medium">
                Bagikan Link Pendaftaran:
                <a href="register_event.php?id=<?php echo $event_id; ?>" target="_blank" class="text-blue-600 hover:underline break-all">
                    http://agendain.test/register_event.php?id=<?php echo $event_id; ?>
                </a>
                 <button onclick="copyLink('http://agendain.test/register_event.php?id=<?php echo $event_id; ?>')" class="ml-2 text-xs bg-gray-200 px-2 py-1 rounded hover:bg-gray-300">Salin</button>
            </p>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-lg">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-xl">Daftar Peserta (<?php echo count($participants); ?>)</h3>
                <div class="flex space-x-2">
                    <a href="export_participants.php?event_id=<?php echo $event_id; ?>"
                       class="bg-gray-500 text-white font-semibold px-4 py-2 rounded-lg hover:bg-gray-600 transition duration-300 text-sm">
                       Ekspor CSV
                    </a>
                    <a href="generate_sertifikat.php?event_id=<?php echo $event_id; ?>"
                       class="bg-blue-500 text-white font-semibold px-4 py-2 rounded-lg hover:bg-blue-600 transition duration-300 text-sm"
                       onclick="return confirm('Proses ini akan generate sertifikat untuk SEMUA peserta yang hadir. Lanjutkan?');">
                       Generate Sertifikat (Hadir)
                    </a>
                </div>
            </div>

            <?php if (empty($participants)): ?>
                <div class="text-center py-8">
                    <p class="text-gray-500 text-lg">Belum ada peserta yang mendaftar.</p>
                </div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="w-full text-left table-auto">
                        <thead>
                            <tr class="border-b bg-gray-50">
                                <th class="py-2 px-4">Nama Lengkap</th>
                                <th class="py-2 px-4">Email</th>
                                <th class="py-2 px-4">Kategori</th>
                                <th class="py-2 px-4">Kehadiran & Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($participants as $participant): ?>
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="py-3 px-4 font-semibold"><?php echo htmlspecialchars($participant['nama_lengkap']); ?></td>
                                    <td class="py-3 px-4"><?php echo htmlspecialchars($participant['email']); ?></td>
                                    <td class="py-3 px-4"><?php echo htmlspecialchars($participant['kategori_peserta']); ?></td>
                                    <td class="py-3 px-4 whitespace-nowrap">
                                        <div class="flex items-center space-x-2">
                                            <?php if ($participant['status_kehadiran']): ?>
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Hadir</span>

                                                <?php if (!empty($participant['certificate_id'])): ?>
                                                    <a href="view_certificate.php?cert_id=<?php echo $participant['certificate_id']; ?>&token=<?php echo $csrf_token; ?>" 
                                                    target="_blank" 
                                                    class="text-purple-600 hover:underline text-sm font-medium"
                                                    title="Lihat Sertifikat PDF">
                                                    Lihat Sertifikat
                                                    </a>
                                                <?php endif; ?>
                                                <?php else: ?>
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Belum Hadir</span>
                                                <a href="update_kehadiran.php?participant_id=<?php echo $participant['participant_id']; ?>&event_id=<?php echo $event['event_id']; ?>&token=<?php echo $csrf_token; ?>" class="text-blue-600 hover:underline text-sm font-medium">Tandai Hadir</a>
                                            <?php endif; ?>
                                            <a href="delete_participant.php?participant_id=<?php echo $participant['participant_id']; ?>&event_id=<?php echo $event['event_id']; ?>&token=<?php echo $csrf_token; ?>"
                                            class="text-red-600 hover:underline text-sm font-medium"
                                            onclick="return confirm('Apakah Anda yakin...?');">
                                            Hapus
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <script>
        function copyLink(link) {
            navigator.clipboard.writeText(link).then(function() {
                alert('Link pendaftaran berhasil disalin!');
            }, function(err) {
                alert('Gagal menyalin link: ', err);
            });
        }
    </script>

</body>
</html>