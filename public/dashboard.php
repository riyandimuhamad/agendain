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

$flash_message = $_SESSION['flash_message'] ?? null;
unset($_SESSION['flash_message']);

$eventController = new EventController($pdo);
$userId = $_SESSION['user_id'];
$limit = 5; // Smaller limit for professional look with stats
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$stats = $eventController->getGlobalStats($userId);
$total_events = $eventController->countEventsByUserId($userId);
$events = $eventController->getEventsByUserId($userId, $limit, $offset);
$total_pages = ceil($total_events / $limit);

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Management Center - Agendain</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&display=swap');
        body { font-family: 'Outfit', sans-serif; }
        
        #sidebar { transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        #main-content { transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1); margin-left: 18rem; }
        #sidebar.collapsed { margin-left: -18rem; }
        #main-content.sidebar-collapsed { margin-left: 0; }
        
        .stat-card {
            background: white;
            transition: all 0.3s ease;
            border: 1px solid #f1f5f9;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            border-color: #10b981;
            box-shadow: 0 20px 25px -5px rgba(16, 185, 129, 0.05);
        }
    </style>
</head>
<body class="bg-[#f8fafc] flex">

    <?php include __DIR__ . '/../views/layouts/sidebar.php'; ?>

    <div id="main-content" class="flex-grow min-h-screen">
        <?php include __DIR__ . '/../views/layouts/header.php'; ?>

        <main class="p-10">
            <!-- Breadcrumbs -->
            <div class="flex items-center space-x-2 text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-10">
                <a href="#" class="hover:text-emerald-500">Console</a>
                <span>/</span>
                <span class="text-slate-600">Overview</span>
            </div>

            <?php if ($flash_message): ?>
                <div class="bg-emerald-50 border border-emerald-100 text-emerald-700 px-6 py-4 rounded-[1.5rem] relative mb-10 flex items-center shadow-sm" role="alert">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="font-bold"><?php echo htmlspecialchars($flash_message); ?></span>
                </div>
            <?php endif; ?>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
                <div class="stat-card p-8 rounded-[2.5rem]">
                    <div class="flex justify-between items-start mb-6">
                        <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <span class="text-xs font-black text-emerald-500 bg-emerald-50 px-3 py-1 rounded-full uppercase">Lifetime</span>
                    </div>
                    <p class="text-4xl font-black text-slate-800 mb-1"><?php echo $stats['total_events']; ?></p>
                    <p class="text-slate-500 font-bold uppercase tracking-widest text-xs">Total Active Events</p>
                </div>

                <div class="stat-card p-8 rounded-[2.5rem]">
                    <div class="flex justify-between items-start mb-6">
                        <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </div>
                        <span class="text-xs font-black text-blue-500 bg-blue-50 px-3 py-1 rounded-full uppercase">Registrations</span>
                    </div>
                    <p class="text-4xl font-black text-slate-800 mb-1"><?php echo $stats['total_participants']; ?></p>
                    <p class="text-slate-500 font-bold uppercase tracking-widest text-xs">Total Participants</p>
                </div>

                <div class="stat-card p-8 rounded-[2.5rem]">
                    <div class="flex justify-between items-start mb-6">
                        <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-2xl flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div class="text-right">
                             <span class="text-xs font-black text-purple-500 bg-purple-50 px-3 py-1 rounded-full uppercase">Attendance</span>
                        </div>
                    </div>
                    <p class="text-4xl font-black text-slate-800 mb-1">
                        <?php 
                        $rate = $stats['total_participants'] > 0 ? round(($stats['total_attendance'] / $stats['total_participants']) * 100) : 0;
                        echo $rate . "%";
                        ?>
                    </p>
                    <p class="text-slate-500 font-bold uppercase tracking-widest text-xs">Overall Attendance Rate</p>
                </div>
            </div>

            <!-- Main Table Section -->
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-10 border-b border-slate-50 flex flex-col md:flex-row justify-between items-center gap-6">
                    <div>
                        <h3 class="text-2xl font-black text-slate-800 mb-1">Recent Activities</h3>
                        <p class="text-slate-500 font-bold text-sm">Managing your latest national-scale events.</p>
                    </div>
                    <a href="create_event.php" class="bg-slate-900 text-white font-bold px-8 py-4 rounded-2xl hover:bg-emerald-600 transition-all shadow-xl shadow-slate-200">
                        + New Campaign
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <?php if (empty($events)): ?>
                        <div class="text-center py-20">
                            <p class="text-slate-400 font-bold text-lg">No campaigns initiated yet.</p>
                        </div>
                    <?php else: ?>
                        <table class="w-full text-left table-auto">
                            <thead>
                                <tr class="bg-slate-50 text-slate-400 text-xs font-black uppercase tracking-[0.2em]">
                                    <th class="py-6 px-10">Event Details</th>
                                    <th class="py-6 px-10">Schedule</th>
                                    <th class="py-6 px-10">Category</th>
                                    <th class="py-6 px-10 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                <?php foreach ($events as $event): ?>
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="py-8 px-10">
                                            <div class="flex items-center space-x-4">
                                                <div class="w-12 h-12 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600 font-black">
                                                    <?php echo strtoupper(substr($event['nama_event'], 0, 1)); ?>
                                                </div>
                                                <div>
                                                    <p class="text-slate-800 font-black text-lg"><?php echo htmlspecialchars($event['nama_event']); ?></p>
                                                    <p class="text-slate-400 text-xs font-bold uppercase tracking-widest">ID: #<?php echo $event['event_id']; ?></p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-8 px-10 font-bold text-slate-600">
                                            <?php echo date('D, d M Y', strtotime($event['tanggal_mulai'])); ?>
                                            <p class="text-xs text-slate-400"><?php echo date('H:i', strtotime($event['tanggal_mulai'])); ?> WIB</p>
                                        </td>
                                        <td class="py-8 px-10">
                                            <span class="px-4 py-2 text-xs font-black rounded-xl uppercase tracking-widest
                                                <?php echo $event['kategori_event'] == 'Kampus' ? 'bg-blue-50 text-blue-600' : 'bg-purple-50 text-purple-600'; ?>">
                                                <?php echo htmlspecialchars($event['kategori_event']); ?>
                                            </span>
                                        </td>
                                        <td class="py-8 px-10">
                                            <div class="flex items-center justify-end space-x-2">
                                                <a href="detail_event.php?id=<?php echo $event['event_id']; ?>" class="p-3 bg-slate-50 text-slate-600 rounded-xl hover:bg-emerald-500 hover:text-white transition-all shadow-sm" title="Analytics">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                                                </a>
                                                <a href="edit_event.php?id=<?php echo $event['event_id']; ?>" class="p-3 bg-slate-50 text-slate-600 rounded-xl hover:bg-blue-500 hover:text-white transition-all shadow-sm" title="Edit">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                </a>
                                                <a href="delete_event.php?id=<?php echo $event['event_id']; ?>&token=<?php echo $csrf_token; ?>" 
                                                   class="p-3 bg-slate-50 text-slate-600 rounded-xl hover:bg-red-500 hover:text-white transition-all shadow-sm"
                                                   onclick="return confirm('Archive this campaign?');" title="Delete">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>

                <?php if ($total_pages > 1): ?>
                <div class="p-10 bg-slate-50/50 flex justify-between items-center">
                    <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Page <?php echo $page; ?> of <?php echo $total_pages; ?></p>
                    <div class="flex space-x-3">
                        <a href="dashboard.php?page=<?php echo max(1, $page - 1); ?>" class="p-3 bg-white border border-slate-100 rounded-xl hover:bg-slate-50 transition-all <?php echo $page <= 1 ? 'opacity-50 pointer-events-none' : ''; ?>">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
                        </a>
                        <a href="dashboard.php?page=<?php echo min($total_pages, $page + 1); ?>" class="p-3 bg-white border border-slate-100 rounded-xl hover:bg-slate-50 transition-all <?php echo $page >= $total_pages ? 'opacity-50 pointer-events-none' : ''; ?>">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    </div>
                </div>
                <?php endif; ?>
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
    });
    </script>
</body>
</html>
