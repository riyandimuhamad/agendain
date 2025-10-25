<?php
session_start(); // Mulai session
require_once 'config.php'; // Sertakan koneksi

// Generate CSRF token jika belum ada
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];

// Cek otentikasi & otorisasi
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: login.php");
    exit;
}

// Ambil ID event dari URL
$event_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$user_id = $_SESSION['user_id'];
$event = null; // Data event asli dari DB

// Ambil error & input dari session (jika ada setelah submit gagal)
$errors = isset($_SESSION['form_errors']) ? $_SESSION['form_errors'] : [];
$input = isset($_SESSION['form_input']) ? $_SESSION['form_input'] : [];
unset($_SESSION['form_errors']);
unset($_SESSION['form_input']);

// Ambil data event asli jika ID valid
if ($event_id > 0) {
    $sql = "SELECT * FROM events WHERE event_id = :event_id AND user_id = :user_id";
    try {
        if ($stmt = $pdo->prepare($sql)) {
            $stmt->bindParam(':event_id', $event_id, PDO::PARAM_INT);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            if ($stmt->execute() && $stmt->rowCount() == 1) {
                $event = $stmt->fetch();
            } else {
                die("ERROR: Event tidak ditemukan atau Anda tidak punya akses.");
            }
            unset($stmt);
        }
    } catch (PDOException $e) {
        die("ERROR: Tidak dapat mengambil data event. " . $e->getMessage());
    }
} else {
    die("ERROR: ID Event tidak valid.");
}

// Tentukan nilai awal untuk form: prioritaskan input gagal, baru data asli dari DB
$nama_event_value = isset($input['nama_event']) ? $input['nama_event'] : ($event['nama_event'] ?? '');
$deskripsi_value = isset($input['deskripsi']) ? $input['deskripsi'] : ($event['deskripsi'] ?? '');
// Format tanggal untuk input datetime-local, cek $event ada
$tanggal_mulai_value = isset($input['tanggal_mulai']) ? $input['tanggal_mulai'] : (isset($event['tanggal_mulai']) ? date('Y-m-d\TH:i', strtotime($event['tanggal_mulai'])) : '');
$kategori_event_value = isset($input['kategori_event']) ? $input['kategori_event'] : ($event['kategori_event'] ?? '');

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event - Agendain</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style> @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap'); body { font-family: 'Poppins', sans-serif; } </style>
</head>
<body class="bg-slate-50">

    <?php if (!empty($errors)): ?>
    <div class="fixed top-5 right-5 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded shadow-lg max-w-sm z-50" role="alert">
        <strong class="font-bold">Gagal Memperbarui Event:</strong>
        <ul class="mt-2 list-disc list-inside">
            <?php foreach ($errors as $error): ?><li><?php echo htmlspecialchars($error); ?></li><?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>
    <header class="bg-white shadow-md">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-green-600">Agendain</h1>
            <a href="logout.php" class="bg-red-500 text-white font-semibold px-4 py-2 rounded-lg hover:bg-red-600">Logout</a>
        </div>
    </header>

    <main class="container mx-auto px-6 py-8">
        <h2 class="text-3xl font-bold text-gray-800 mb-6">Edit Event</h2>

        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-2xl mx-auto">
            <form action="proses_edit_event.php" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
                <input type="hidden" name="event_id" value="<?php echo $event['event_id']; ?>">

                <div class="mb-4">
                    <label for="nama_event" class="block text-gray-700 font-semibold mb-2">Nama Event</label>
                    <input type="text" id="nama_event" name="nama_event" class="w-full px-4 py-2 border rounded-lg" value="<?php echo htmlspecialchars($nama_event_value); ?>" required>
                </div>

                <div class="mb-4">
                    <label for="deskripsi" class="block text-gray-700 font-semibold mb-2">Deskripsi</label>
                    <textarea id="deskripsi" name="deskripsi" rows="4" class="w-full px-4 py-2 border rounded-lg"><?php echo htmlspecialchars($deskripsi_value); ?></textarea>
                </div>

                <div class="mb-4">
                    <label for="tanggal_mulai" class="block text-gray-700 font-semibold mb-2">Tanggal & Waktu Mulai</label>
                    <input type="datetime-local" id="tanggal_mulai" name="tanggal_mulai" class="w-full px-4 py-2 border rounded-lg" value="<?php echo htmlspecialchars($tanggal_mulai_value); ?>" required>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">Kategori Event</label>
                    <div class="flex items-center space-x-4">
                        <label class="flex items-center">
                            <input type="radio" name="kategori_event" value="Kampus" class="form-radio" <?php echo ($kategori_event_value == 'Kampus') ? 'checked' : ''; ?>>
                            <span class="ml-2 text-gray-700">Kampus</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="kategori_event" value="Umum" class="form-radio" <?php echo ($kategori_event_value == 'Umum') ? 'checked' : ''; ?>>
                            <span class="ml-2 text-gray-700">Umum</span>
                        </label>
                    </div>
                </div>

                <div class="flex items-center justify-end space-x-4">
                    <a href="dashboard.php" class="text-gray-600 font-medium py-2 px-4 rounded-lg hover:bg-gray-100">Batal</a>
                    <button type="submit" class="bg-blue-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-600">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </main>

</body>
</html>