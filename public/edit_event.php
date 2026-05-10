<?php
require_once __DIR__ . '/../src/bootstrap.php';

use App\Controllers\EventController;

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: login.php");
    exit;
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];

$event_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$user_id = $_SESSION['user_id'];

$eventController = new EventController($pdo);
$event = $eventController->getEventById($user_id, $event_id);

if (!$event) {
    header("location: dashboard.php");
    exit;
}

$errors = $_SESSION['form_errors'] ?? [];
$input = $_SESSION['form_input'] ?? [];
unset($_SESSION['form_errors']);
unset($_SESSION['form_input']);

// Pre-fill logic
$nama_event = $input['nama_event'] ?? $event['nama_event'];
$deskripsi = $input['deskripsi'] ?? $event['deskripsi'];
$tanggal_mulai = $input['tanggal_mulai'] ?? date('Y-m-d\TH:i', strtotime($event['tanggal_mulai']));
$kategori_event = $input['kategori_event'] ?? $event['kategori_event'];
$gambar_lama = $event['gambar_event'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Campaign - Agendain</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&display=swap');
        body { font-family: 'Outfit', sans-serif; }
        #sidebar { transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        #main-content { transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1); margin-left: 18rem; }
        #sidebar.collapsed { margin-left: -18rem; }
        #main-content.sidebar-collapsed { margin-left: 0; }
    </style>
</head>
<body class="bg-[#f8fafc] flex">

    <?php include __DIR__ . '/../views/layouts/sidebar.php'; ?>

    <div id="main-content" class="flex-grow min-h-screen">
        <?php include __DIR__ . '/../views/layouts/header.php'; ?>

        <main class="p-10">
            <div class="flex items-center space-x-2 text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-10">
                <a href="dashboard.php" class="hover:text-emerald-500">Console</a>
                <span>/</span>
                <a href="detail_event.php?id=<?php echo $event_id; ?>" class="hover:text-emerald-500">Campaigns</a>
                <span>/</span>
                <span class="text-slate-600">Edit</span>
            </div>

            <div class="max-w-4xl mx-auto">
                <div class="mb-10">
                    <h2 class="text-4xl font-black text-slate-800 mb-2">Modify Campaign</h2>
                    <p class="text-slate-500 font-bold">Update your event details and media assets.</p>
                </div>

                <?php if (!empty($errors)): ?>
                    <div class="mb-10 p-6 bg-red-50 text-red-500 rounded-[2rem] border border-red-100 shadow-sm">
                        <div class="flex items-center mb-3">
                             <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                             <span class="font-black uppercase tracking-widest text-[10px]">Update Failed</span>
                        </div>
                        <ul class="list-disc list-inside space-y-1 font-bold">
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo htmlspecialchars($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <div class="bg-white rounded-[3rem] p-12 border border-slate-100 shadow-sm">
                    <form action="proses_edit_event.php?id=<?php echo $event_id; ?>" method="POST" enctype="multipart/form-data" class="space-y-8">
                        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">

                        <div class="grid md:grid-cols-2 gap-10">
                            <div class="space-y-8">
                                <div>
                                    <label for="nama_event" class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3">Event Name</label>
                                    <input type="text" id="nama_event" name="nama_event" required
                                           class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-6 py-4 outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all font-bold text-slate-700"
                                           value="<?php echo htmlspecialchars($nama_event); ?>">
                                </div>

                                <div>
                                    <label for="tanggal_mulai" class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3">Schedule (Date & Time)</label>
                                    <input type="datetime-local" id="tanggal_mulai" name="tanggal_mulai" required
                                           class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-6 py-4 outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all font-bold text-slate-700"
                                           value="<?php echo htmlspecialchars($tanggal_mulai); ?>">
                                </div>

                                <div>
                                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-4">Event Classification</label>
                                    <div class="flex items-center space-x-6">
                                        <label class="flex items-center cursor-pointer group">
                                            <input type="radio" name="kategori_event" value="Kampus" class="hidden" <?php echo ($kategori_event == 'Kampus') ? 'checked' : ''; ?>>
                                            <div class="w-5 h-5 border-2 border-slate-200 rounded-full mr-3 flex items-center justify-center group-hover:border-emerald-500 transition-all">
                                                <div class="w-2.5 h-2.5 bg-emerald-500 rounded-full scale-0 transition-transform <?php echo ($kategori_event == 'Kampus') ? 'scale-100' : ''; ?>"></div>
                                            </div>
                                            <span class="text-sm font-bold text-slate-600 group-hover:text-emerald-600">Campus Event</span>
                                        </label>
                                        <label class="flex items-center cursor-pointer group">
                                            <input type="radio" name="kategori_event" value="Umum" class="hidden" <?php echo ($kategori_event == 'Umum') ? 'checked' : ''; ?>>
                                            <div class="w-5 h-5 border-2 border-slate-200 rounded-full mr-3 flex items-center justify-center group-hover:border-emerald-500 transition-all">
                                                <div class="w-2.5 h-2.5 bg-emerald-500 rounded-full scale-0 transition-transform <?php echo ($kategori_event == 'Umum') ? 'scale-100' : ''; ?>"></div>
                                            </div>
                                            <span class="text-sm font-bold text-slate-600 group-hover:text-emerald-600">Public Event</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-8">
                                <div>
                                    <label for="deskripsi" class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3">Short Description</label>
                                    <textarea id="deskripsi" name="deskripsi" rows="5"
                                              class="w-full bg-slate-50 border border-slate-100 rounded-3xl px-6 py-4 outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all font-bold text-slate-700"><?php echo htmlspecialchars($deskripsi); ?></textarea>
                                </div>

                                <div>
                                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3">Update Media Assets</label>
                                    <div class="relative group">
                                        <input type="file" id="gambar_event" name="gambar_event" accept="image/*" class="hidden">
                                        <label for="gambar_event" class="flex flex-col items-center justify-center w-full aspect-video bg-slate-50 border-2 border-dashed border-slate-200 rounded-[2rem] cursor-pointer hover:bg-emerald-50/50 hover:border-emerald-200 transition-all overflow-hidden relative">
                                            <?php if (!empty($gambar_lama)): ?>
                                                <img src="uploads/posters/<?php echo htmlspecialchars($gambar_lama); ?>" class="absolute inset-0 w-full h-full object-cover opacity-20 group-hover:opacity-10 transition-opacity">
                                            <?php endif; ?>
                                            <div class="relative z-10 flex flex-col items-center">
                                                <svg class="w-10 h-10 text-slate-300 mb-2 group-hover:text-emerald-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                <span class="text-xs font-black text-slate-400 uppercase tracking-widest group-hover:text-emerald-500">Change Media</span>
                                                <p class="text-[10px] text-slate-400 mt-1">Leave empty to keep current poster</p>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="pt-10 flex flex-col md:flex-row items-center justify-end gap-4 border-t border-slate-50">
                            <a href="detail_event.php?id=<?php echo $event_id; ?>" class="w-full md:w-auto text-center text-slate-400 font-black uppercase tracking-widest text-xs px-8 py-4 hover:text-slate-600 transition-colors">Cancel</a>
                            <button type="submit" class="w-full md:w-auto bg-emerald-600 text-white font-black px-12 py-5 rounded-2xl hover:bg-emerald-700 transition-all shadow-xl shadow-emerald-100 transform hover:-translate-y-1">
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const toggleButton = document.getElementById("toggleButton");
        const sidebar = document.getElementById("sidebar");
        const mainContent = document.getElementById("main-content");
        if (toggleButton && sidebar && mainContent) {
            toggleButton.addEventListener("click", function() {
                sidebar.classList.toggle("collapsed");
                mainContent.classList.toggle("sidebar-collapsed");
            });
        }

        // Custom Radio Animation
        const radios = document.querySelectorAll('input[name="kategori_event"]');
        radios.forEach(radio => {
            radio.addEventListener('change', function() {
                document.querySelectorAll('.w-2\\.5').forEach(dot => dot.classList.add('scale-0'));
                if (this.checked) {
                    this.nextElementSibling.querySelector('.w-2\\.5').classList.remove('scale-0');
                }
            });
        });

        // File upload preview hint
        document.getElementById('gambar_event').addEventListener('change', function() {
            const fileName = this.files[0] ? this.files[0].name : 'Change Media';
            this.nextElementSibling.querySelector('span').textContent = fileName;
        });
    });
    </script>

</body>
</html>
