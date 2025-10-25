<?php session_start(); // Mulai session hanya untuk keamanan jika perlu ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unduh Sertifikat - Agendain</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap');
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 flex items-center justify-center min-h-screen">

    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-green-600">Unduh Sertifikat Anda</h1>
            <p class="text-gray-500 mt-2">Masukkan kode unik yang Anda terima untuk mengunduh sertifikat.</p>
        </div>

        <?php if (isset($_GET['error'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline"><?php echo htmlspecialchars($_GET['error']); ?></span>
            </div>
        <?php endif; ?>

        <form action="proses_unduh.php" method="POST">
            <div class="mb-4">
                <label for="unique_code" class="block text-gray-700 font-semibold mb-2">Kode Unik Sertifikat</label>
                <input type="text" id="unique_code" name="unique_code" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Contoh: CERT-ABCDE-123" required>
            </div>
            <div>
                <button type="submit" class="w-full bg-green-500 text-white font-bold py-3 px-4 rounded-lg hover:bg-green-600 transition duration-300">
                    Unduh Sertifikat
                </button>
            </div>
        </form>
         <div class="text-center mt-6">
            <a href="index.php" class="text-sm text-gray-500 hover:text-green-600">Kembali ke Halaman Utama</a>
        </div>
    </div>

</body>
</html>