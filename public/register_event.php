<?php
require_once __DIR__ . '/../src/bootstrap.php';

use App\Controllers\EventController;

$event_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($event_id <= 0) {
    die("ERROR: Invalid Event ID.");
}

$eventController = new EventController($pdo);
// We use a public version of getEvent or just the same one
$event = $eventController->getEventById(null, $event_id); // Modify Controller to allow null userId for public view

if (!$event) {
    die("ERROR: Event not found.");
}

$errors = $_SESSION['form_errors'] ?? [];
$input = $_SESSION['form_input'] ?? [];
unset($_SESSION['form_errors']);
unset($_SESSION['form_input']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration: <?php echo htmlspecialchars($event['nama_event']); ?> - Agendain</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&display=swap');
        body { font-family: 'Outfit', sans-serif; }
        .reg-gradient {
            background: radial-gradient(circle at 100% 0%, #ecfdf5 0%, #ffffff 50%, #f1f5f9 100%);
        }
    </style>
</head>
<body class="reg-gradient min-h-screen py-20 px-6">

    <div class="max-w-4xl mx-auto">
        <!-- Logo / Back Link -->
        <div class="flex justify-center mb-12">
            <a href="index.php" class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-emerald-500 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-200">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <span class="text-2xl font-black tracking-tight text-slate-800">Agendain<span class="text-emerald-500">.</span></span>
            </a>
        </div>

        <div class="bg-white rounded-[3rem] shadow-[0_50px_100px_-20px_rgba(0,0,0,0.05)] overflow-hidden border border-slate-100 flex flex-col md:flex-row">
            
            <!-- Event Summary Side -->
            <div class="md:w-1/3 bg-slate-900 p-12 text-white flex flex-col justify-between">
                <div>
                    <span class="text-[10px] font-black uppercase tracking-[0.2em] text-emerald-400 mb-4 block">Official Registration</span>
                    <h2 class="text-3xl font-black leading-tight mb-6"><?php echo htmlspecialchars($event['nama_event']); ?></h2>
                    <div class="space-y-4">
                        <div class="flex items-start space-x-3">
                            <svg class="w-5 h-5 text-emerald-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <div>
                                <p class="text-xs font-black uppercase tracking-widest text-slate-500 mb-1">Date & Time</p>
                                <p class="text-sm font-bold"><?php echo date('d M Y', strtotime($event['tanggal_mulai'])); ?></p>
                                <p class="text-xs text-slate-400"><?php echo date('H:i', strtotime($event['tanggal_mulai'])); ?> WIB</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <svg class="w-5 h-5 text-emerald-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <div>
                                <p class="text-xs font-black uppercase tracking-widest text-slate-500 mb-1">Location</p>
                                <p class="text-sm font-bold">Online / Venue Provided</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-12 pt-12 border-t border-slate-800">
                    <p class="text-xs text-slate-500 font-medium leading-relaxed">By registering, you agree to our terms of service and privacy policy regarding event participation.</p>
                </div>
            </div>

            <!-- Form Side -->
            <div class="md:w-2/3 p-12 md:p-16">
                <div class="mb-10">
                    <h3 class="text-2xl font-black text-slate-800 mb-2">Participant Information</h3>
                    <p class="text-slate-500 font-bold">Secure your spot in this prestigious event.</p>
                </div>

                <?php if (!empty($errors)): ?>
                    <div class="mb-8 p-6 bg-red-50 text-red-500 rounded-2xl border border-red-100 shadow-sm animate__animated animate__shakeX">
                        <ul class="list-disc list-inside space-y-1 font-bold text-sm">
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo htmlspecialchars($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form action="proses_register_event.php?id=<?php echo $event_id; ?>" method="POST" class="space-y-8">
                    
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-4">Registration Category</label>
                        <div class="flex items-center space-x-6">
                            <label class="flex items-center cursor-pointer group">
                                <input type="radio" name="kategori_peserta" value="Mahasiswa" class="hidden" checked onchange="toggleForm(this.value)">
                                <div class="w-5 h-5 border-2 border-slate-200 rounded-full mr-3 flex items-center justify-center group-hover:border-emerald-500 transition-all">
                                    <div id="dot-mhs" class="w-2.5 h-2.5 bg-emerald-500 rounded-full transition-transform"></div>
                                </div>
                                <span class="text-sm font-bold text-slate-600 group-hover:text-emerald-600">Student (Mahasiswa)</span>
                            </label>
                            <label class="flex items-center cursor-pointer group">
                                <input type="radio" name="kategori_peserta" value="Umum" class="hidden" onchange="toggleForm(this.value)">
                                <div class="w-5 h-5 border-2 border-slate-200 rounded-full mr-3 flex items-center justify-center group-hover:border-emerald-500 transition-all">
                                    <div id="dot-umum" class="w-2.5 h-2.5 bg-emerald-500 rounded-full scale-0 transition-transform"></div>
                                </div>
                                <span class="text-sm font-bold text-slate-600 group-hover:text-emerald-600">Public (Umum)</span>
                            </label>
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label for="nama_lengkap" class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3">Full Name</label>
                            <input type="text" id="nama_lengkap" name="nama_lengkap" required
                                   class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-6 py-4 outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all font-bold text-slate-700"
                                   placeholder="e.g. John Doe"
                                   value="<?php echo htmlspecialchars($input['nama_lengkap'] ?? ''); ?>">
                        </div>
                        <div>
                            <label for="email" class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3">Email Address</label>
                            <input type="email" id="email" name="email" required
                                   class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-6 py-4 outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all font-bold text-slate-700"
                                   placeholder="john@example.com"
                                   value="<?php echo htmlspecialchars($input['email'] ?? ''); ?>">
                        </div>
                    </div>

                    <!-- Dynamic Fields -->
                    <div id="form-mahasiswa" class="grid md:grid-cols-2 gap-6 animate__animated animate__fadeIn">
                        <div>
                            <label for="nim" class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3">Student ID (NIM)</label>
                            <input type="text" id="nim" name="nim"
                                   class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-6 py-4 outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all font-bold text-slate-700"
                                   placeholder="Your NIM"
                                   value="<?php echo htmlspecialchars($input['nim'] ?? ''); ?>">
                        </div>
                        <div>
                            <label for="universitas" class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3">University</label>
                            <input type="text" id="universitas" name="universitas"
                                   class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-6 py-4 outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all font-bold text-slate-700"
                                   placeholder="e.g. ITG Garut"
                                   value="<?php echo htmlspecialchars($input['universitas'] ?? ''); ?>">
                        </div>
                    </div>

                    <div id="form-umum" class="hidden grid md:grid-cols-2 gap-6 animate__animated animate__fadeIn">
                        <div>
                            <label for="instansi" class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3">Organization / Company</label>
                            <input type="text" id="instansi" name="instansi"
                                   class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-6 py-4 outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all font-bold text-slate-700"
                                   placeholder="Your Office"
                                   value="<?php echo htmlspecialchars($input['instansi'] ?? ''); ?>">
                        </div>
                        <div>
                            <label for="nomor_telepon" class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3">Phone Number</label>
                            <input type="tel" id="nomor_telepon" name="nomor_telepon"
                                   class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-6 py-4 outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all font-bold text-slate-700"
                                   placeholder="e.g. 0812..."
                                   value="<?php echo htmlspecialchars($input['nomor_telepon'] ?? ''); ?>">
                        </div>
                    </div>

                    <div class="pt-6">
                        <button type="submit" class="w-full bg-emerald-600 text-white font-black py-5 rounded-2xl hover:bg-emerald-700 transition-all shadow-xl shadow-emerald-100 transform hover:-translate-y-1">
                            Complete Registration
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleForm(kategori) {
            const formMahasiswa = document.getElementById('form-mahasiswa');
            const formUmum = document.getElementById('form-umum');
            const dotMhs = document.getElementById('dot-mhs');
            const dotUmum = document.getElementById('dot-umum');

            if (kategori === 'Mahasiswa') {
                formMahasiswa.classList.remove('hidden');
                formUmum.classList.add('hidden');
                dotMhs.classList.remove('scale-0');
                dotUmum.classList.add('scale-0');
            } else {
                formMahasiswa.classList.add('hidden');
                formUmum.classList.remove('hidden');
                dotMhs.classList.add('scale-0');
                dotUmum.classList.remove('scale-0');
            }
        }
    </script>
</body>
</html>
