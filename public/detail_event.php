<?php
require_once __DIR__ . '/../src/bootstrap.php';

use App\Controllers\EventController;
use App\Controllers\ParticipantController;

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

$event_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$user_id = $_SESSION['user_id'];

$eventController = new EventController($pdo);
$participantController = new ParticipantController($pdo);

$event = $eventController->getEventById($user_id, $event_id);
if (!$event) {
    header("location: dashboard.php");
    exit;
}

$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$total_participants = $participantController->countParticipantsByEventId($event_id);
$participants = $participantController->getParticipantsByEventId($event_id, $limit, $offset);
$total_pages = ceil($total_participants / $limit);
$stats = $participantController->getStatsByEventId($event_id);

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($event['nama_event']); ?> - Command Center</title>
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
            <!-- Header Section -->
            <div class="mb-10 flex flex-col md:flex-row justify-between items-start gap-6">
                <div>
                    <div class="flex items-center space-x-2 text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-4">
                        <a href="dashboard.php" class="hover:text-emerald-500">Console</a>
                        <span>/</span>
                        <a href="#" class="hover:text-emerald-500">Campaigns</a>
                        <span>/</span>
                        <span class="text-slate-600">Details</span>
                    </div>
                    <h2 class="text-4xl font-black text-slate-800 mb-2"><?php echo htmlspecialchars($event['nama_event']); ?></h2>
                    <p class="text-slate-500 font-bold flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Scheduled for <?php echo date('l, d F Y - H:i', strtotime($event['tanggal_mulai'])); ?> WIB
                    </p>
                </div>
                <div class="flex space-x-3">
                    <a href="edit_event.php?id=<?php echo $event_id; ?>" class="bg-white border border-slate-100 text-slate-700 font-bold px-6 py-3 rounded-2xl hover:bg-slate-50 transition-all shadow-sm">
                        Edit Campaign
                    </a>
                    <a href="export_participants.php?event_id=<?php echo $event_id; ?>" class="bg-slate-900 text-white font-bold px-6 py-3 rounded-2xl hover:bg-slate-800 transition-all shadow-xl shadow-slate-200">
                        Export Data
                    </a>
                </div>
            </div>

            <?php if ($flash_message): ?>
                <div class="bg-emerald-50 border border-emerald-100 text-emerald-700 px-6 py-4 rounded-[1.5rem] relative mb-10 shadow-sm" role="alert">
                    <span class="font-bold"><?php echo htmlspecialchars($flash_message); ?></span>
                </div>
            <?php endif; ?>

            <div class="grid lg:grid-cols-3 gap-10 mb-10">
                <!-- Left Column: Info & Actions -->
                <div class="lg:col-span-1 space-y-10">
                    <div class="bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm">
                        <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-6">Campaign Media</h4>
                        <?php if (!empty($event['gambar_event'])): ?>
                            <img src="uploads/posters/<?php echo htmlspecialchars($event['gambar_event']); ?>" class="w-full h-auto rounded-[1.5rem] shadow-lg mb-6">
                        <?php else: ?>
                            <div class="w-full aspect-video bg-slate-50 rounded-[1.5rem] flex items-center justify-center mb-6">
                                <p class="text-slate-300 font-bold">No Media Attached</p>
                            </div>
                        <?php endif; ?>
                        <div class="p-6 bg-slate-50 rounded-[1.5rem] border border-slate-100">
                            <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-3">Registration Link</p>
                            <div class="flex items-center space-x-2">
                                <input type="text" readonly value="<?php echo base_url('public/register_event.php?id=' . $event_id); ?>" class="bg-transparent text-slate-600 text-xs font-bold w-full outline-none">
                                <button onclick="copyLink('<?php echo base_url('public/register_event.php?id=' . $event_id); ?>')" class="text-emerald-500 hover:text-emerald-600 font-black text-xs uppercase">Copy</button>
                            </div>
                        </div>
                    </div>

                    <div class="bg-emerald-600 rounded-[2.5rem] p-8 text-white shadow-xl shadow-emerald-100">
                        <h4 class="text-xs font-black text-emerald-200 uppercase tracking-widest mb-6">Certification Hub</h4>
                        <p class="text-sm font-bold mb-8 text-emerald-50">Generate professional digital certificates for all attendees with a single click.</p>
                        <a href="generate_sertifikat.php?event_id=<?php echo $event_id; ?>" 
                           onclick="return confirm('Initiate bulk certification? This will generate PDFs for all present participants.')"
                           class="flex items-center justify-center w-full bg-white text-emerald-600 font-black py-4 rounded-2xl hover:bg-emerald-50 transition-all">
                            Bulk Generate Certificates
                        </a>
                    </div>
                </div>

                <!-- Right Column: Stats & Table -->
                <div class="lg:col-span-2 space-y-10">
                    <!-- Event Stats -->
                    <div class="grid grid-cols-3 gap-6">
                        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm">
                            <p class="text-slate-400 font-bold text-xs uppercase tracking-widest mb-2">Total</p>
                            <p class="text-3xl font-black text-slate-800"><?php echo $stats['total']; ?></p>
                        </div>
                        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm">
                            <p class="text-emerald-500 font-bold text-xs uppercase tracking-widest mb-2">Present</p>
                            <p class="text-3xl font-black text-slate-800"><?php echo $stats['present']; ?></p>
                        </div>
                        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm">
                            <p class="text-red-500 font-bold text-xs uppercase tracking-widest mb-2">Absent</p>
                            <p class="text-3xl font-black text-slate-800"><?php echo $stats['absent']; ?></p>
                        </div>
                    </div>

                    <!-- Participants Table -->
                    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
                        <div class="p-8 border-b border-slate-50 flex justify-between items-center">
                            <h4 class="text-xl font-black text-slate-800">Engagement Records</h4>
                            <div class="flex items-center text-slate-400 text-xs font-bold uppercase tracking-widest">
                                <span class="w-2 h-2 bg-emerald-500 rounded-full mr-2"></span> Real-time Data
                            </div>
                        </div>
                        <div class="overflow-x-auto">
                            <?php if (empty($participants)): ?>
                                <div class="p-20 text-center">
                                    <p class="text-slate-400 font-bold">No participants registered yet.</p>
                                </div>
                            <?php else: ?>
                                <table class="w-full text-left table-auto">
                                    <thead>
                                        <tr class="bg-slate-50 text-slate-400 text-[10px] font-black uppercase tracking-[0.2em]">
                                            <th class="py-4 px-8">Participant</th>
                                            <th class="py-4 px-8">Category</th>
                                            <th class="py-4 px-8 text-center">Attendance</th>
                                            <th class="py-4 px-8 text-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-50">
                                        <?php foreach ($participants as $participant): ?>
                                            <tr class="hover:bg-slate-50/50 transition-colors">
                                                <td class="py-6 px-8">
                                                    <p class="text-slate-800 font-black"><?php echo htmlspecialchars($participant['nama_lengkap']); ?></p>
                                                    <p class="text-slate-400 text-xs font-bold"><?php echo htmlspecialchars($participant['email']); ?></p>
                                                </td>
                                                <td class="py-6 px-8">
                                                    <span class="px-3 py-1 bg-slate-50 text-slate-500 text-[10px] font-black rounded-lg uppercase tracking-widest">
                                                        <?php echo htmlspecialchars($participant['kategori_peserta']); ?>
                                                    </span>
                                                </td>
                                                <td class="py-6 px-8">
                                                    <div class="flex justify-center">
                                                        <?php if ($participant['status_kehadiran']): ?>
                                                            <div class="flex flex-col items-center">
                                                                <span class="px-3 py-1 bg-emerald-50 text-emerald-600 text-[10px] font-black rounded-lg uppercase tracking-widest mb-1">Present</span>
                                                                <?php if (!empty($participant['certificate_id'])): ?>
                                                                    <a href="view_certificate.php?cert_id=<?php echo $participant['certificate_id']; ?>&token=<?php echo $csrf_token; ?>" target="_blank" class="text-[10px] text-emerald-400 hover:text-emerald-600 font-black uppercase underline tracking-widest">View Cert</a>
                                                                <?php endif; ?>
                                                            </div>
                                                        <?php else: ?>
                                                            <a href="update_kehadiran.php?id=<?php echo $participant['participant_id']; ?>&event_id=<?php echo $event_id; ?>&status=1&token=<?php echo $csrf_token; ?>" class="px-3 py-1 bg-red-50 text-red-400 hover:bg-emerald-500 hover:text-white transition-all text-[10px] font-black rounded-lg uppercase tracking-widest">Mark Present</a>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                                <td class="py-6 px-8">
                                                    <div class="flex justify-end space-x-2">
                                                        <a href="delete_participant.php?id=<?php echo $participant['participant_id']; ?>&token=<?php echo $csrf_token; ?>" 
                                                           onclick="return confirm('Remove participant?')"
                                                           class="p-2 text-slate-300 hover:text-red-500 transition-colors">
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
                            <div class="p-8 bg-slate-50/50 flex justify-between items-center border-t border-slate-50">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Records <?php echo $offset + 1; ?>-<?php echo min($offset + $limit, $total_participants); ?> of <?php echo $total_participants; ?></p>
                                <div class="flex space-x-2">
                                    <a href="detail_event.php?id=<?php echo $event_id; ?>&page=<?php echo max(1, $page - 1); ?>" class="p-2 bg-white border border-slate-100 rounded-lg <?php echo $page <= 1 ? 'opacity-30 pointer-events-none' : ''; ?>">
                                        <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
                                    </a>
                                    <a href="detail_event.php?id=<?php echo $event_id; ?>&page=<?php echo min($total_pages, $page + 1); ?>" class="p-2 bg-white border border-slate-100 rounded-lg <?php echo $page >= $total_pages ? 'opacity-30 pointer-events-none' : ''; ?>">
                                        <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
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
    });

    function copyLink(link) {
        navigator.clipboard.writeText(link).then(function() {
            alert('Campaign link copied to clipboard!');
        }, function(err) {
            console.error('Failed to copy: ', err);
        });
    }
    </script>
</body>
</html>
