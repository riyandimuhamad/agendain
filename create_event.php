<?php
session_start(); // Mulai session

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

// Ambil error & input dari session (jika ada setelah submit gagal)
$errors = isset($_SESSION['form_errors']) ? $_SESSION['form_errors'] : [];
$input = isset($_SESSION['form_input']) ? $_SESSION['form_input'] : [];
unset($_SESSION['form_errors']);
unset($_SESSION['form_input']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Event Baru - Agendain</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap');
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-slate-50">

    <?php if (!empty($errors)): ?>
    <div class="fixed top-5 right-5 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded shadow-lg max-w-sm z-50" role="alert">
        <strong class="font-bold">Gagal Membuat Event:</strong>
        <ul class="mt-2 list-disc list-inside">
            <?php foreach ($errors as $error): ?>
                <li><?php echo htmlspecialchars($error); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>
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
        <h2 class="text-3xl font-bold text-gray-800 mb-6">Buat Event Baru</h2>

        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-2xl mx-auto">
            <form action="proses_create_event.php" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">

                <div class="mb-4">
                    <label for="nama_event" class="block text-gray-700 font-semibold mb-2">Nama Event</label>
                    <input type="text" id="nama_event" name="nama_event" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Contoh: Seminar Technopreneurship 2025" value="<?php echo isset($input['nama_event']) ? htmlspecialchars($input['nama_event']) : ''; ?>" required>
                </div>

                <div class="mb-4">
                    <label for="deskripsi" class="block text-gray-700 font-semibold mb-2">Deskripsi</label>
                    <textarea id="deskripsi" name="deskripsi" rows="4" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Jelaskan tentang event Anda..."><?php echo isset($input['deskripsi']) ? htmlspecialchars($input['deskripsi']) : ''; ?></textarea>
                </div>

                <div class="mb-4">
                    <label for="tanggal_mulai" class="block text-gray-700 font-semibold mb-2">Tanggal & Waktu Mulai</label>
                    <input type="datetime-local" id="tanggal_mulai" name="tanggal_mulai" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" value="<?php echo isset($input['tanggal_mulai']) ? htmlspecialchars($input['tanggal_mulai']) : ''; ?>" required>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">Kategori Event</label>
                    <div class="flex items-center space-x-4">
                        <label class="flex items-center">
                            <input type="radio" name="kategori_event" value="Kampus" class="form-radio text-green-500" <?php echo (isset($input['kategori_event']) && $input['kategori_event'] == 'Kampus') ? 'checked' : (!isset($input['kategori_event']) ? 'checked' : ''); ?>>
                            <span class="ml-2 text-gray-700">Kampus</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="kategori_event" value="Umum" class="form-radio text-green-500" <?php echo (isset($input['kategori_event']) && $input['kategori_event'] == 'Umum') ? 'checked' : ''; ?>>
                            <span class="ml-2 text-gray-700">Umum</span>
                        </label>
                    </div>
                </div>

                <div class="flex items-center justify-end space-x-4">
                    <a href="dashboard.php" class="text-gray-600 font-medium py-2 px-4 rounded-lg hover:bg-gray-100 transition duration-300">Batal</a>
                    <button type="submit" class="bg-green-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-green-600 transition duration-300">
                        Buat Event
                    </button>
                </div>
            </form>
        </div>
    </main>

</body>
</html>