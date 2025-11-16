<?php
// Sertakan file koneksi database
require_once 'config.php';

// Siapkan array kosong untuk event
$public_events = [];

try {
    // ==== PERUBAHAN DI SINI: Tambahkan 'gambar_event' ke query ====
    $sql = "SELECT event_id, nama_event, deskripsi, tanggal_mulai, gambar_event 
            FROM events 
            WHERE kategori_event = 'Umum' AND tanggal_mulai >= CURDATE() 
            ORDER BY tanggal_mulai ASC
            LIMIT 3"; // Kita batasi 3 event terbaru

    if ($stmt = $pdo->prepare($sql)) {
        if ($stmt->execute()) {
            $public_events = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            // Gagal eksekusi, tapi jangan hentikan halaman
            error_log("Gagal mengambil event publik.");
        }
        unset($stmt);
    }
} catch (PDOException $e) {
    // Gagal koneksi/prepare, tapi jangan hentikan halaman
    error_log("Error DB: " " . $e->getMessage());
}
// Kita tidak unset($pdo) di sini, biarkan sampai akhir script
?>

<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendain - Platform Manajemen Event Kampus</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap');
        body {
            font-family: 'Poppins', sans-serif;
        }
        /* Style untuk membatasi teks deskripsi */
        .line-clamp-3 {
           overflow: hidden;
           display: -webkit-box;
           -webkit-line-clamp: 3;
           -webkit-box-orient: vertical;  
        }
    </style>
</head>
<body class="bg-white text-gray-800">

<header class="bg-white sticky top-0 z-50 shadow-md">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <a href="index.php" class="text-2xl font-bold text-green-600">
                Agendain
            </a>
            <div class="flex items-center space-x-6">
                <nav class="hidden md:flex items-center space-x-8">
                    <a href="#fitur" class="text-gray-600 hover:text-green-600 transition">Fitur</a>
                    <a href="#event-terbaru" class="text-gray-600 hover:text-green-600 transition">Event Terbaru</a>
                    <a href="#cara-kerja" class="text-gray-600 hover:text-green-600 transition">Cara Kerja</a>
                    <a href="#kontak" class="text-gray-600 hover:text-green-600 transition">Kontak</a>
                </nav>
                <div class="flex items-center space-x-4">
                    <a href="login.php" class="text-gray-600 hover:text-green-600 transition font-medium">
                        Login Panitia
                    </a>
                    <a href="register.php" class="bg-green-500 text-white font-semibold px-5 py-2 rounded-lg hover:bg-green-600 transition duration-300">
                        Daftar Panitia
                    </a>
                </div>
            </div>
        </div>
    </header>

    <main>
        <section class="bg-white">
            <div class="container mx-auto px-6 py-20 grid md:grid-cols-2 gap-12 items-center">
                <div class="text-center">
                    <img src="images/PKKMB ITG-744.jpg" alt="Ilustrasi Manajemen Event Kampus" class="w-full h-auto rounded-lg shadow-md">
                </div>
                <div class="text-center md:text-left">
                    <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 leading-tight">
                        Manajemen Event Kampus Jadi Lebih Mudah.
                    </h1>
                    <p class="mt-4 text-lg text-gray-600">
                        Dari pendaftaran peserta hingga distribusi sertifikat, <span class="font-semibold text-gray-800">Agendain</span> membuat semuanya terorganisir dalam satu platform.
                    </p>
                    <div class="mt-8 flex justify-center md:justify-start gap-4">
                        <a href="register.php" class="bg-green-500 text-white font-semibold px-8 py-3 rounded-lg hover:bg-green-600 transition duration-300">
                            Daftar Gratis
                        </a>
                         <a href="#fitur" class="bg-white border border-gray-300 text-gray-700 font-semibold px-8 py-3 rounded-lg hover:bg-gray-100 transition duration-300">
                            Lihat Fitur
                        </a>
                    </div>
                </div>
            </div>
        </section>
        
        <section id="fitur" class="py-20 bg-slate-50">
            <div class="container mx-auto px-6">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900">Platform Lengkap untuk Semua Kebutuhan Acara</h2>
                    <p class="mt-3 text-lg text-gray-600">Fitur yang dirancang untuk menyederhanakan pekerjaan panitia.</p>
                </div>
                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <div class="bg-white p-6 rounded-lg shadow-lg text-center transition-all duration-300 ease-in-out hover:-translate-y-2 hover:shadow-2xl">
                        <h3 class="font-bold text-xl mb-2 text-green-600">Manajemen Event</h3>
                        <p class="text-gray-600">Buat dan kelola semua detail acara Anda dalam satu dashboard terpusat.</p>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-lg text-center transition-all duration-300 ease-in-out hover:-translate-y-2 hover:shadow-2xl">
                        <h3 class="font-bold text-xl mb-2 text-green-600">Pendaftaran Otomatis</h3>
                        <p class="text-gray-600">Sebarkan link pendaftaran dan biarkan sistem mencatat semua peserta secara real-time.</p>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-lg text-center transition-all duration-300 ease-in-out hover:-translate-y-2 hover:shadow-2xl">
                        <h3 class="font-bold text-xl mb-2 text-green-600">Sertifikat Digital</h3>
                        <p class="text-gray-600">Generate dan kirim sertifikat ke semua peserta hanya dengan beberapa klik.</p>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-lg text-center transition-all duration-300 ease-in-out hover:-translate-y-2 hover:shadow-2xl">
                        <h3 class="font-bold text-xl mb-2 text-green-600">Laporan Kehadiran</h3>
                        <p class="text-gray-600">Unduh laporan kehadiran yang akurat untuk keperluan evaluasi dan dokumentasi.</p>
                    </div>
                </div>
            </div>
        </section>

        <section id="event-terbaru" class="py-20 bg-white">
            <div class="container mx-auto px-6">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900">Event Publik Terbaru</h2>
                    <p class="mt-3 text-lg text-gray-600">Daftar dan ikuti event menarik yang akan datang.</p>
                </div>
                
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php if (empty($public_events)): ?>
                        <div class="col-span-full text-center py-12">
                            <p class="text-gray-500 text-xl">Oops! Belum ada event publik yang akan datang.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($public_events as $event): ?>
                            <div class="bg-white rounded-lg shadow-lg overflow-hidden flex flex-col transition-all duration-300 ease-in-out hover:shadow-2xl">
                                
                                <div class="h-48 w-full">
                                    <?php if (!empty($event['gambar_event'])): ?>
                                        <img src="uploads/posters/<?php echo htmlspecialchars($event['gambar_event']); ?>" 
                                             alt="Poster <?php echo htmlspecialchars($event['nama_event']); ?>" 
                                             class="w-full h-48 object-cover">
                                    <?php else: ?>
                                        <div class="bg-green-100 h-48 w-full flex items-center justify-center">
                                            <span class="text-green-600 text-lg font-semibold">Agendain Event</span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="p-6 flex flex-col flex-grow">
                                    <h3 class="font-bold text-xl mb-2 text-gray-800"><?php echo htmlspecialchars($event['nama_event']); ?></h3>
                                    <p class="text-sm text-gray-500 mb-1">
                                        <?php echo date('d F Y, H:i', strtotime($event['tanggal_mulai'])); ?> WIB
                                    </p>
                                    <p class="text-gray-600 text-sm mb-4 flex-grow line-clamp-3">
                                        <?php echo htmlspecialchars($event['deskripsi']); ?>
                                    </p>
                                    <a href="register_event.php?id=<?php echo $event['event_id']; ?>" 
                                       class="mt-auto block w-full text-center bg-green-500 text-white font-semibold py-2 px-4 rounded-lg hover:bg-green-600 transition duration-300">
                                        Daftar Sekarang
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <section id="cara-kerja" class="py-20 bg-slate-50">
            <div class="container mx-auto px-6">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900">Sangat Mudah Digunakan</h2>
                </div>
                <div class="grid md:grid-cols-3 gap-8 text-center">
                    <div class="p-4">
                        <div class="bg-green-500 text-white rounded-full w-16 h-16 flex items-center justify-center text-2xl font-bold mx-auto mb-4">1</div>
                        <h3 class="text-xl font-bold mb-2">Buat Kegiatan</h3>
                        <p class="text-gray-600">Isi detail acara Anda dalam beberapa menit.</p>
                    </div>
                    <div class="p-4">
                        <div class="bg-green-500 text-white rounded-full w-16 h-16 flex items-center justify-center text-2xl font-bold mx-auto mb-4">2</div>
                        <h3 class="text-xl font-bold mb-2">Sebarkan Link</h3>
                        <p class="text-gray-600">Bagikan link pendaftaran unik ke audiens Anda.</p>
                    </div>
                    <div class="p-4">
                        <div class="bg-green-500 text-white rounded-full w-16 h-16 flex items-center justify-center text-2xl font-bold mx-auto mb-4">3</div>
                        <h3 class="text-xl font-bold mb-2">Pantau & Unduh</h3>
                        <p class="text-gray-600">Lihat data real-time dan unduh hasilnya.</p>
                    </div>
                </div>
            </div>
        </section>

        <section id="cta" class="py-20 bg-green-50">
            <div class="container mx-auto px-6 text-center">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900">Siap Membuat Event Anda Sukses?</h2>
                <p class="mt-3 text-lg text-gray-600 max-w-2xl mx-auto">
                    Coba <span class="font-semibold text-gray-800">Agendain</span> sekarang dan rasakan kemudahan mengelola acara dari awal hingga akhir.
                </p>
                <div class="mt-8">
                    <a href="register.php" class="bg-green-500 text-white font-semibold px-8 py-3 rounded-lg hover:bg-green-600 transition duration-300 shadow-lg">
                        Daftarkan Organisasi Anda (Gratis)
                    </a>
                </div>
            </div>
        </section>
    </main>

    <footer id="kontak" class="bg-white text-center">
        <div class="bg-green-500 text-white py-8">
            <div class="container mx-auto px-6">
                <p>&copy; <?php echo date("Y"); ?> Agendain. Dibuat untuk menyederhanakan event di kampus Anda.</p>
            </div>
        </div>
        <div class="py-4">
            <div class="container mx-auto px-6 text-sm text-gray-500">
                 <p>Platform Manajemen Event</p>
            </div>
        </div>
    </footer>

</body>
</html>