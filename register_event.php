<?php
// Sertakan file koneksi database
require_once 'config.php';

// Ambil ID event dari URL dan validasi
$event_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($event_id <= 0) {
    die("ERROR: Event tidak ditemukan.");
}

// Ambil detail event dari database untuk ditampilkan di halaman
$event = null;
try {
    $sql_event_fetch = "SELECT nama_event FROM events WHERE event_id = :event_id";
    $stmt_event_fetch = $pdo->prepare($sql_event_fetch);
    $stmt_event_fetch->bindParam(':event_id', $event_id, PDO::PARAM_INT);
    $stmt_event_fetch->execute();
    if ($stmt_event_fetch->rowCount() == 1) {
        $event = $stmt_event_fetch->fetch();
    } else {
        die("ERROR: Event tidak ditemukan.");
    }
    unset($stmt_event_fetch);
} catch (PDOException $e) {
    die("ERROR Database: " . $e->getMessage());
}

// Ambil error & input dari session (jika ada setelah submit gagal)
session_start(); // Pastikan session dimulai
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
    <title>Pendaftaran: <?php echo htmlspecialchars($event['nama_event'] ?? 'Event'); ?> - Agendain</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap');
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 flex items-center justify-center min-h-screen py-12">

    <?php if (!empty($errors)): ?>
    <div class="fixed top-5 right-5 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded shadow-lg max-w-sm z-50" role="alert">
        <strong class="font-bold">Gagal Mendaftar:</strong>
        <ul class="mt-2 list-disc list-inside">
            <?php foreach ($errors as $error): ?>
                <li><?php echo htmlspecialchars($error); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-lg">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-green-600">Formulir Pendaftaran</h1>
            <p class="text-gray-600 mt-2 text-xl font-semibold"><?php echo htmlspecialchars($event['nama_event'] ?? 'Event Tidak Ditemukan'); ?></p>
        </div>

        <form action="proses_register_event.php?id=<?php echo $event_id; ?>" method="POST">

            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Saya adalah:</label>
                <div class="flex items-center space-x-4">
                    <label class="flex items-center">
                        <input type="radio" name="kategori_peserta" value="Mahasiswa" class="form-radio text-green-500" onchange="toggleForm(this.value)" <?php echo (isset($input['kategori_peserta']) && $input['kategori_peserta'] == 'Mahasiswa') ? 'checked' : (!isset($input['kategori_peserta']) ? 'checked' : ''); ?>>
                        <span class="ml-2 text-gray-700">Mahasiswa</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="kategori_peserta" value="Umum" class="form-radio text-green-500" onchange="toggleForm(this.value)" <?php echo (isset($input['kategori_peserta']) && $input['kategori_peserta'] == 'Umum') ? 'checked' : ''; ?>>
                        <span class="ml-2 text-gray-700">Umum</span>
                    </label>
                </div>
            </div>

            <div class="mb-4">
                <label for="nama_lengkap" class="block text-gray-700 font-semibold mb-2">Nama Lengkap</label>
                <input type="text" id="nama_lengkap" name="nama_lengkap" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" value="<?php echo isset($input['nama_lengkap']) ? htmlspecialchars($input['nama_lengkap']) : ''; ?>" required>
            </div>
            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-semibold mb-2">Alamat Email</label>
                <input type="email" id="email" name="email" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" value="<?php echo isset($input['email']) ? htmlspecialchars($input['email']) : ''; ?>" required>
            </div>

            <div id="form-mahasiswa" class="hidden"> <div class="mb-4">
                    <label for="nim" class="block text-gray-700 font-semibold mb-2">NIM (Nomor Induk Mahasiswa)</label>
                    <input type="text" id="nim" name="nim" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" value="<?php echo isset($input['nim']) ? htmlspecialchars($input['nim']) : ''; ?>">
                </div>
                <div class="mb-4">
                    <label for="universitas" class="block text-gray-700 font-semibold mb-2">Asal Universitas</label>
                    <input type="text" id="universitas" name="universitas" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" value="<?php echo isset($input['universitas']) ? htmlspecialchars($input['universitas']) : ''; ?>">
                </div>
            </div>

            <div id="form-umum" class="hidden"> <div class="mb-4">
                    <label for="instansi" class="block text-gray-700 font-semibold mb-2">Instansi / Perusahaan</label>
                    <input type="text" id="instansi" name="instansi" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" value="<?php echo isset($input['instansi']) ? htmlspecialchars($input['instansi']) : ''; ?>">
                </div>
                <div class="mb-4">
                    <label for="nomor_telepon" class="block text-gray-700 font-semibold mb-2">Nomor Telepon</label>
                    <input type="tel" id="nomor_telepon" name="nomor_telepon" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" value="<?php echo isset($input['nomor_telepon']) ? htmlspecialchars($input['nomor_telepon']) : ''; ?>">
                </div>
            </div>

            <div class="mt-6">
                <button type="submit" class="w-full bg-green-500 text-white font-bold py-3 px-4 rounded-lg hover:bg-green-600 transition duration-300">
                    Daftar Event
                </button>
            </div>
        </form>
    </div>

    <script>
        function toggleForm(kategori) {
            const formMahasiswa = document.getElementById('form-mahasiswa');
            const formUmum = document.getElementById('form-umum');
            // Dapatkan input di dalam form yang disembunyikan
            const nimInput = document.getElementById('nim');
            const univInput = document.getElementById('universitas');
            const instansiInput = document.getElementById('instansi');
            const telInput = document.getElementById('nomor_telepon');

            if (kategori === 'Mahasiswa') {
                formMahasiswa.classList.remove('hidden');
                formUmum.classList.add('hidden');
                // Set input yang disembunyikan jadi tidak required (jika ada) dan bersihkan nilainya
                nimInput.required = true; // Atau sesuai kebutuhan
                univInput.required = true; // Atau sesuai kebutuhan
                instansiInput.required = false;
                telInput.required = false;
               // instansiInput.value = ''; // Kosongkan jika disembunyikan
               // telInput.value = ''; // Kosongkan jika disembunyikan
            } else if (kategori === 'Umum') {
                formMahasiswa.classList.add('hidden');
                formUmum.classList.remove('hidden');
                 // Set input yang disembunyikan jadi tidak required dan bersihkan nilainya
                nimInput.required = false;
                univInput.required = false;
                instansiInput.required = false; // Sesuaikan jika ini wajib
                telInput.required = false;      // Sesuaikan jika ini wajib
               // nimInput.value = '';
               // univInput.value = '';
            }
        }

        // Panggil fungsi saat halaman dimuat untuk menyesuaikan tampilan awal
        document.addEventListener('DOMContentLoaded', function() {
            const selectedCategoryInput = document.querySelector('input[name="kategori_peserta"]:checked');
            if (selectedCategoryInput) {
                 toggleForm(selectedCategoryInput.value);
            } else {
                 // Default jika tidak ada yg checked (seharusnya mahasiswa checked by default)
                 toggleForm('Mahasiswa');
            }
        });
    </script>

</body>
</html>