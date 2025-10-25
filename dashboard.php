<?php
// Mulai session
session_start();

// Cek apakah pengguna sudah login. Jika tidak, redirect ke halaman login.
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: login.php");
    exit;
}

// Generate CSRF token jika belum ada
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];

// Sertakan koneksi DB
require_once 'config.php';

// Ambil flash message jika ada
$flash_message = isset($_SESSION['flash_message']) ? $_SESSION['flash_message'] : null;
unset($_SESSION['flash_message']); // Hapus setelah dibaca

// ---- BAGIAN PAGINASI & PENGAMBILAN DATA EVENT ----
$events = [];
$user_id = $_SESSION['user_id'];
$limit = 10; // Jumlah event per halaman
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1; // Halaman saat ini
$offset = ($page > 0) ? ($page - 1) * $limit : 0; // Hitung offset

// 1. Hitung total event
$sql_count = "SELECT COUNT(*) FROM events WHERE user_id = :user_id";
$total_events = 0;
$total_pages = 0;
try {
    $stmt_count = $pdo->prepare($sql_count);
    $stmt_count->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt_count->execute();
    $total_events = $stmt_count->fetchColumn();
    $total_pages = ceil($total_events / $limit);
    unset($stmt_count);
} catch (PDOException $e) {
     echo "Oops! Error menghitung event: " . $e->getMessage();
}


// 2. Ambil data event untuk halaman saat ini
$sql_events = "SELECT event_id, nama_event, tanggal_mulai, kategori_event
               FROM events
               WHERE user_id = :user_id
               ORDER BY created_at DESC
               LIMIT :limit OFFSET :offset";

try {
    if ($stmt_events = $pdo->prepare($sql_events)) {
        $stmt_events->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt_events->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt_events->bindParam(':offset', $offset, PDO::PARAM_INT);
        if ($stmt_events->execute()) {
            $events = $stmt_events->fetchAll(PDO::FETCH_ASSOC); // Fetch as Assoc Array
        } else {
            echo "Oops! Terjadi kesalahan saat mengambil data event.";
        }
        unset($stmt_events);
    }
} catch (PDOException $e) {
     echo "Oops! Error mengambil event: " . $e->getMessage();
}
// Tidak perlu unset $pdo di sini, biarkan koneksi terbuka sampai akhir script
// ---- AKHIR BAGIAN PAGINASI ----
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Panitia - Agendain</title>
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
                <span class="text-gray-700 mr-4">
                    Selamat datang,
                    <span class="font-semibold"><?php echo htmlspecialchars($_SESSION['nama_organisasi']); ?></span>!
                </span>
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

        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-bold text-gray-800">Dashboard Event Anda</h2>
            <a href="create_event.php" class="bg-green-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-green-600 transition duration-300">
                + Buat Event Baru
            </a>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-lg overflow-x-auto"> <?php if (empty($events)): ?>
                <div class="text-center py-8">
                    <p class="text-gray-500 text-lg">Anda belum membuat event apapun. Silakan buat event pertama Anda!</p>
                </div>
            <?php else: ?>
                <table class="w-full text-left table-auto min-w-[768px]"> <thead>
                        <tr class="border-b bg-gray-50">
                            <th class="py-2 px-4">Nama Event</th>
                            <th class="py-2 px-4">Tanggal</th>
                            <th class="py-2 px-4">Kategori</th>
                            <th class="py-2 px-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($events as $event): ?>
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-3 px-4 font-semibold"><?php echo htmlspecialchars($event['nama_event']); ?></td>
                                <td class="py-3 px-4"><?php echo date('d M Y, H:i', strtotime($event['tanggal_mulai'])); ?></td>
                                <td class="py-3 px-4">
                                    <span class="px-2 py-1 text-sm rounded-full <?php echo $event['kategori_event'] == 'Kampus' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800'; ?>">
                                        <?php echo htmlspecialchars($event['kategori_event']); ?>
                                    </span>
                                </td>
                                <td class="py-3 px-4 whitespace-nowrap"> <div class="flex items-center space-x-3"> <a href="detail_event.php?id=<?php echo $event['event_id']; ?>" class="text-green-600 hover:underline font-medium text-sm">Detail</a>
                                        <a href="edit_event.php?id=<?php echo $event['event_id']; ?>" class="text-blue-600 hover:underline font-medium text-sm">Edit</a>
                                        <a href="delete_event.php?id=<?php echo $event['event_id']; ?>&token=<?php echo $csrf_token; ?>"
                                           class="text-red-600 hover:underline font-medium text-sm"
                                           onclick="return confirm('Apakah Anda yakin ingin menghapus event ini? Semua data peserta terkait akan ikut terhapus.');">
                                           Hapus
                                        </a>
                                        <a href="register_event.php?id=<?php echo $event['event_id']; ?>"
                                           target="_blank"
                                           title="Buka Link Pendaftaran (untuk dibagikan)"
                                           class="text-purple-600 hover:underline font-medium text-sm flex items-center space-x-1">
                                           <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                             <path stroke-linecap="round" stroke-linejoin="round" d="M8.684 13.342C8.886 12.938 9 12.482 9 12s-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z" />
                                           </svg>
                                           <span>Bagikan</span>
                                        </a>
                                    </div>
                                </td>
                                </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>

            <?php if ($total_pages > 1): ?>
            <div class="mt-6 flex justify-between items-center">
                <div>
                    <?php if ($page > 1): ?>
                        <a href="dashboard.php?page=<?php echo $page - 1; ?>" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-l">
                            &laquo; Sebelumnya
                        </a>
                    <?php else: ?>
                        <span class="bg-gray-200 text-gray-500 font-bold py-2 px-4 rounded-l cursor-not-allowed">
                            &laquo; Sebelumnya
                        </span>
                    <?php endif; ?>
                </div>
                <div>
                    <span class="text-gray-600 px-4">Halaman <?php echo $page; ?> dari <?php echo $total_pages; ?></span>
                </div>
                <div>
                     <?php if ($page < $total_pages): ?>
                        <a href="dashboard.php?page=<?php echo $page + 1; ?>" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-r">
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

</body>
</html>