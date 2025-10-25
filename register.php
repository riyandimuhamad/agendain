<?php
session_start(); // Mulai session untuk membaca pesan error

// Jika sudah login, redirect ke dashboard
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("location: dashboard.php");
    exit;
}

// Generate CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];

// Ambil error & input dari session (jika ada setelah submit gagal)
$errors = isset($_SESSION['register_errors']) ? $_SESSION['register_errors'] : [];
$input = isset($_SESSION['register_input']) ? $_SESSION['register_input'] : [];
unset($_SESSION['register_errors']);
unset($_SESSION['register_input']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun Panitia - Agendain</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap');
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 flex items-center justify-center min-h-screen">

    <?php if (!empty($errors)): ?>
    <div class="fixed top-5 right-5 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded shadow-lg max-w-sm z-50" role="alert">
        <strong class="font-bold">Oops! Ada kesalahan:</strong>
        <ul class="mt-2 list-disc list-inside">
            <?php foreach ($errors as $error): ?>
                <li><?php echo htmlspecialchars($error); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-green-600">Buat Akun Agendain</h1>
            <p class="text-gray-500 mt-2">Daftarkan organisasimu untuk mulai mengelola event.</p>
        </div>

        <form action="proses_register.php" method="POST">
             <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">

            <div class="mb-4">
                <label for="nama_organisasi" class="block text-gray-700 font-semibold mb-2">Nama Organisasi</label>
                <input
                    type="text"
                    id="nama_organisasi"
                    name="nama_organisasi"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                    placeholder="Contoh: BEM Fakultas Ilmu Komputer"
                    value="<?php echo isset($input['nama_organisasi']) ? htmlspecialchars($input['nama_organisasi']) : ''; ?>"
                    required>
            </div>

            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-semibold mb-2">Alamat Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                    placeholder="panitia@email.com"
                    value="<?php echo isset($input['email']) ? htmlspecialchars($input['email']) : ''; ?>"
                    required>
            </div>

            <div class="mb-6">
                <label for="password" class="block text-gray-700 font-semibold mb-2">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                    placeholder="•••••••• (minimal 6 karakter)"
                    required>
            </div>

            <div>
                <button type="submit" class="w-full bg-green-500 text-white font-bold py-3 px-4 rounded-lg hover:bg-green-600 transition duration-300">
                    Daftar
                </button>
            </div>
        </form>

        <div class="text-center mt-6">
            <p class="text-gray-600">
                Sudah punya akun?
                <a href="login.php" class="text-green-600 hover:underline font-semibold">Login di sini</a>
            </p>
        </div>
    </div>

</body>
</html>