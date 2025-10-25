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
                    <img src="PKKMB ITG-744.jpg" alt="Ilustrasi Manajemen Event Kampus" class="w-full h-auto rounded-lg shadow-md">
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

        <section id="cara-kerja" class="py-20 bg-white">
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