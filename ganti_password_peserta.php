<?php
session_start();

// Cek otentikasi PESERTA
if (!isset($_SESSION['participant_loggedin']) || $_SESSION['participant_loggedin'] !== true) {
    header("location: login_participant.php?error=Silakan login terlebih dahulu.");
    exit;
}

// Generate CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];

// Ambil pesan error jika ada
$errors = isset($_SESSION['form_errors']) ? $_SESSION['form_errors'] : [];
unset($_SESSION['form_errors']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-B">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ganti Password - Agendain</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap'); body { font-family: 'Poppins', sans-serif; }</style>
</head>
<body class="bg-slate-50 flex items-center justify-center min-h-screen">

    <?php if (!empty($errors)): ?>
    <div class="fixed top-5 right-5 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded shadow-lg max-w-sm z-50" role="alert">
        <strong class="font-bold">Gagal Ganti Password:</strong>
        <ul class="mt-2 list-disc list-inside">
            <?php foreach ($errors as $error): ?><li><?php echo htmlspecialchars($error); ?></li><?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>

    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-green-600">Ganti Password</h1>
            <p class="text-gray-500 mt-2">Perbarui password Anda secara berkala.</p>
        </div>
        <form action="proses_ganti_password_peserta.php" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
            
            <div class="mb-4">
                <label for="password_lama" class="block text-gray-700 font-semibold mb-2">Password Lama</dabel>
                <input type="password" id="password_lama" name="password_lama" class="w-full px-4 py-2 border rounded-lg" required>
            </div>
            <div class="mb-4">
                <label for="password_baru" class="block text-gray-700 font-semibold mb-2">Password Baru</label>
                <input type="password" id="password_baru" name="password_baru" class="w-full px-4 py-2 border rounded-lg" placeholder="Minimal 6 karakter" required>
            </div>
            <div class="mb-6">
                <label for="konfirmasi_password_baru" class="block text-gray-700 font-semibold mb-2">Konfirmasi Password Baru</label>
                <input type="password" id="konfirmasi_password_baru" name="konfirmasi_password_baru" class="w-full px-4 py-2 border rounded-lg" required>
            </div>
            
            <div class="flex items-center justify-end space-x-4">
                <a href="my_events.php" class="text-gray-600 font-medium py-2 px-4 rounded-lg hover:bg-gray-100">Batal</a>
                <button type="submit" class="bg-blue-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-600">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</body>
</html>