<?php
session_start(); // Mulai session

// Jika sudah login, redirect ke dashboard
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("location: dashboard.php");
    exit;
}

// Ambil flash message (jika ada, misal dari registrasi)
$flash_message = isset($_SESSION['flash_message']) ? $_SESSION['flash_message'] : null;
unset($_SESSION['flash_message']);

// Ambil error login (jika ada)
$errors = isset($_SESSION['login_errors']) ? $_SESSION['login_errors'] : [];
$input = isset($_SESSION['login_input']) ? $_SESSION['login_input'] : [];
unset($_SESSION['login_errors']);
unset($_SESSION['login_input']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Panitia - Agendain</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap');
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 flex items-center justify-center min-h-screen">

    <?php if ($flash_message): ?>
    <div class="fixed top-5 right-5 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded shadow-lg max-w-sm z-50" role="alert">
        <span class="block sm:inline"><?php echo htmlspecialchars($flash_message); ?></span>
    </div>
    <?php endif; ?>
    <?php if (!empty($errors)): ?>
    <div class="fixed top-20 right-5 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded shadow-lg max-w-sm z-50" role="alert">
        <strong class="font-bold">Login Gagal:</strong>
        <ul class="mt-2 list-disc list-inside">
            <?php foreach ($errors as $error): ?>
                <li><?php echo htmlspecialchars($error); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-green-600">Selamat Datang Kembali!</h1>
            <p class="text-gray-500 mt-2">Login untuk melanjutkan ke dashboard Anda.</p>
        </div>

        <form action="proses_login.php" method="POST">
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
                    placeholder="••••••••"
                    required>
            </div>

            <div>
                <button type="submit" class="w-full bg-green-500 text-white font-bold py-3 px-4 rounded-lg hover:bg-green-600 transition duration-300">
                    Login
                </button>
            </div>
        </form>

        <div class="text-center mt-6">
            <p class="text-gray-600">
                Belum punya akun?
                <a href="register.php" class="text-green-600 hover:underline font-semibold">Daftar di sini</a>
            </p>
        </div>
    </div>

</body>
</html>