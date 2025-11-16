<?php
// Mulai session
session_start();

// Cek otentikasi
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: login.php");
    exit;
}

// Generate CSRF token jika belum ada
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];

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

// Variabel Paginasi
$total_participants = 0;
$total_pages_participants = 0;
$page_peserta = 0;

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

    // 3. Paginasi Peserta
    $limit_peserta = 10; // 10 peserta per halaman
    $page_peserta = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset_peserta = ($page_peserta > 0) ? ($page_peserta - 1) * $limit_peserta : 0;

    // 3a. Hitung total peserta untuk event ini
    $sql_count_participants = "SELECT COUNT(*) FROM participants WHERE event_id = :event_id";
    $stmt_count_participants = $pdo->prepare($sql_count_participants);
    $stmt_count_participants->bindParam(':event_id', $event_id, PDO::PARAM_INT);
    $stmt_count_participants->execute();
    $total_participants = $stmt_count_participants->fetchColumn();
    $total_pages_participants = ceil($total_participants / $limit_peserta);
    unset($stmt_count_participants);

    // 3b. Ambil data peserta untuk halaman ini (JOIN dengan certificates)
    $sql_participants = "SELECT p.*, c.certificate_id, c.file_path 
                         FROM participants p 
                         LEFT JOIN certificates c ON p.participant_id = c.participant_id 
                         WHERE p.event_id = :event_id 
                         ORDER BY p.registered_at ASC
                         LIMIT :limit OFFSET :offset"; // Tambahkan LIMIT OFFSET
                         
    $stmt_participants = $pdo->prepare($sql_participants);
    $stmt_participants->bindParam(':event_id', $event_id, PDO::PARAM_INT);
    $stmt_participants->bindParam(':limit', $limit_peserta, PDO::PARAM_INT); // Bind LIMIT
    $stmt_participants->bindParam(':offset', $offset_peserta, PDO::PARAM_INT); // Bind OFFSET
    $stmt_participants->execute();
    $participants = $stmt_participants->fetchAll(PDO::FETCH_ASSOC); // Fetch as Assoc Array
    unset($stmt_participants);

} catch (PDOException $e) {
    die("ERROR Database: Tidak dapat mengambil data. " . $e->getMessage());
} catch (Exception $e) {
    die("ERROR: " . $e->getMessage());
}
// unset($pdo); // Kita masih butuh koneksi $pdo di bawah jika ada
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
        
        /* CSS untuk Sidebar Toggle */
        #sidebar { transition: margin-left 0.3s ease-in-out; }
        #main-content {
            transition: margin-left 0.3s ease-in-out;
            margin-left: 16rem; /* 256px */
        }
        #sidebar.collapsed { margin-left: -16rem; /* -256px */ }
        #main-content.sidebar-collapsed { margin-left: 0; }
    </style>
</head>
<body class="bg-slate-50 flex"> 
    <?php include 'sidebar.php'; ?>

    <div id="main-content" class="flex-grow">
        <?php include 'header_panitia.php'; ?>

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
                <?php if (!empty($event['gambar_event'])): ?>
                    <div class="mt-4">
                        <img src="uploads/posters/<?php echo htmlspecialchars($event['gambar_event']); ?>" 
                             alt="Poster <?php echo htmlspecialchars($event['nama_event']); ?>" 
                             class="w-full max-w-lg mx-auto rounded-lg shadow-md">
                    </div>
                <?php endif; ?>
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
                    <h3 class="font-bold text-xl">Daftar Peserta (<?php echo $total_participants; ?>)</h3>
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
                        <p class="text-gray-500 text-lg"><?php echo ($total_participants > 0) ? 'Tidak ada peserta di halaman ini.' : 'Belum ada peserta yang mendaftar.'; ?></p>
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

                <?php if ($total_pages_participants > 1): ?>
                <div class="mt-6 flex justify-between items-center">
                    <div>
                        <?php if ($page_peserta > 1): ?>
                            <a href="detail_event.php?id=<?php echo $event_id; ?>&page=<?php echo $page_peserta - 1; ?>" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-l">
                                &laquo; Sebelumnya
                            </a>
                        <?php else: ?>
                            <span class="bg-gray-200 text-gray-500 font-bold py-2 px-4 rounded-l cursor-not-allowed">
                                &laquo; Sebelumnya
                            </span>
                        <?php endif; ?>
                    </div>
                    <div>
                        <span class="text-gray-600 px-4">Halaman <?php echo $page_peserta; ?> dari <?php echo $total_pages_participants; ?></span>
                    </div>
                    <div>
                         <?php if ($page_peserta < $total_pages_participants): ?>
                            <a href="detail_event.php?id=<?php echo $event_id; ?>&page=<?php echo $page_peserta + 1; ?>" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-r">
                                Berikutnya &raquo;
                            </a>
                        <?php else: ?>
                            <span class="bg-gray-200 text-gray-500 font-bold py-2 px-4 rounded-r cursor-not-allowed">
                                 Berikutnya &raquo;
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
                </div> </main>
        
    </div> <script>
    document.addEventListener("DOMContentLoaded", function() {
        const toggleButton = document.getElementById("toggleButton");
        const sidebar = document.getElementById("sidebar");
        const mainContent = document.getElementById("main-content");

        if (toggleButton && sidebar && mainContent) {
            toggleButton.addEventListener("click", function() {
                sidebar.classList.toggle("collapsed");
                mainContent.classList.toggle("sidebar-collapsed");
            });
        }
    });

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