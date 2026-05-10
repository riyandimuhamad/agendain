<?php
require_once __DIR__ . '/../src/bootstrap.php';

use App\Controllers\EventController;

$event_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$eventController = new EventController($pdo);
$event = $eventController->getEventById(null, $event_id);

if (!$event) {
    header("location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Successful - Agendain</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- QRCode JS for instant generation -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&display=swap');
        body { font-family: 'Outfit', sans-serif; }
        .success-gradient {
            background: radial-gradient(circle at 50% 50%, #ecfdf5 0%, #ffffff 100%);
        }
        .confetti-slow {
            animation: confetti-fall 4s linear infinite;
        }
        @keyframes confetti-fall {
            0% { transform: translateY(-10vh) rotate(0deg); opacity: 1; }
            100% { transform: translateY(100vh) rotate(360deg); opacity: 0; }
        }

        /* Print Specific Styles */
        @media print {
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            body { 
                background: white !important; 
                padding: 0 !important;
                margin: 0 !important;
                display: block !important;
            }
            .confetti-slow, button, a, .fixed, .max-w-2xl > div:first-child, .space-y-6 { 
                display: none !important; 
            }
            .max-w-2xl {
                max-width: 100% !important;
                width: 100% !important;
                box-shadow: none !important;
                border: none !important;
                padding: 0 !important;
                margin: 0 !important;
                display: block !important;
            }
            #ticket-card {
                margin: 20px auto !important;
                border: 2px solid #10b981 !important;
                border-radius: 20px !important;
                width: 600px !important;
                display: block !important;
                background-color: #f8fafc !important;
                padding: 30px !important;
                page-break-inside: avoid;
                box-shadow: none !important;
                position: relative !important;
                visibility: visible !important;
                overflow: hidden !important;
            }
            #ticket-card > div.flex {
                display: block !important;
            }
            #ticket-card .flex-grow {
                width: 70% !important;
                float: left !important;
                display: block !important;
            }
            #ticket-card .animate__animated {
                width: 25% !important;
                float: right !important;
                display: block !important;
                animation: none !important;
            }
            #ticket-card::after {
                content: "";
                display: table !important;
                clear: both !important;
            }
            #ticket-card .bg-emerald-500 {
                position: absolute !important;
                top: 0 !important;
                left: 0 !important;
                width: 8px !important;
                height: 100% !important;
                background-color: #10b981 !important;
                display: block !important;
            }
            #qrcode {
                display: block !important;
                margin-top: 10px !important;
            }
            #qrcode canvas, #qrcode img {
                display: block !important;
                width: 130px !important;
                height: 130px !important;
            }
            h1, h2, h4, p, span {
                color: #1e293b !important;
                visibility: visible !important;
                margin-bottom: 5px !important;
            }
            .text-emerald-600 {
                color: #059669 !important;
            }
        }
    </style>
</head>
<body class="success-gradient min-h-screen flex items-center justify-center p-6 md:p-12">

    <!-- Decorative Elements -->
    <div class="fixed top-0 left-0 w-full h-full pointer-events-none opacity-20">
        <div class="absolute top-10 left-10 w-4 h-4 bg-emerald-400 rounded-full confetti-slow"></div>
        <div class="absolute top-20 right-20 w-3 h-3 bg-blue-400 rounded-full confetti-slow" style="animation-delay: 1s"></div>
        <div class="absolute top-40 left-1/2 w-4 h-4 bg-purple-400 rounded-full confetti-slow" style="animation-delay: 2s"></div>
    </div>

    <div class="max-w-2xl w-full bg-white rounded-[3rem] shadow-[0_50px_100px_-20px_rgba(16,185,129,0.1)] p-10 md:p-16 text-center relative z-10 border border-emerald-50 my-10">
        
        <div class="mb-8 animate__animated animate__zoomIn">
            <div class="w-24 h-24 bg-emerald-500 rounded-full flex items-center justify-center mx-auto shadow-2xl shadow-emerald-200 mb-8">
                <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <h1 class="text-4xl md:text-5xl font-black text-slate-800 mb-4 tracking-tight">Pendaftaran Berhasil!</h1>
            <p class="text-slate-500 font-bold text-lg">Selamat, Anda telah terdaftar secara resmi di <br><span class="text-emerald-600"><?php echo htmlspecialchars($event['nama_event']); ?></span></p>
        </div>

        <!-- Ticket Card -->
        <div id="ticket-card" class="bg-slate-50 rounded-[2.5rem] p-10 mb-10 border border-slate-100 relative overflow-hidden text-left">
            <div class="absolute top-0 left-0 w-2 h-full bg-emerald-500"></div>
            
            <div class="flex flex-col md:flex-row items-center justify-between gap-8">
                <div class="flex-grow">
                    <div class="flex items-center space-x-2 mb-6">
                         <div class="w-6 h-6 bg-emerald-500 rounded flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                         </div>
                         <span class="text-lg font-black text-slate-800 tracking-tight">Agendain<span class="text-emerald-500">.</span> Ticket</span>
                    </div>

                    <p class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-2">Digital Boarding Pass</p>
                    <h2 class="text-2xl font-black text-slate-800 mb-2"><?php echo htmlspecialchars($event['nama_event']); ?></h2>
                    <p class="text-sm font-bold text-emerald-600 mb-6">Official Participant</p>
                    
                    <div class="space-y-3">
                        <div class="flex items-center space-x-3 text-slate-600 font-bold text-xs">
                            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span><?php echo date('d M Y', strtotime($event['tanggal_mulai'])); ?></span>
                        </div>
                        <div class="flex items-center space-x-3 text-slate-600 font-bold text-xs">
                            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span><?php echo date('H:i', strtotime($event['tanggal_mulai'])); ?> WIB</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-4 rounded-3xl shadow-sm border border-slate-100 animate__animated animate__fadeInRight">
                    <div id="qrcode"></div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <p class="text-slate-500 font-medium text-sm leading-relaxed max-w-md mx-auto">
                Konfirmasi rincian pendaftaran juga telah dikirimkan ke email Anda. Silakan simpan halaman ini atau ambil screenshot tiket digital Anda.
            </p>
            
            <div class="flex flex-col sm:flex-row justify-center gap-4 pt-4">
                <button onclick="window.print()" class="bg-slate-900 text-white font-black px-10 py-5 rounded-2xl hover:bg-slate-800 transition-all shadow-xl shadow-slate-200">
                    Cetak Tiket
                </button>
                <a href="index.php" class="bg-white border-2 border-slate-100 text-slate-700 font-black px-10 py-5 rounded-2xl hover:bg-slate-50 transition-all">
                    Halaman Utama
                </a>
            </div>
        </div>
    </div>

    <script>
        // Generate QR Code using Participant Data (Simulated for Success Page)
        // In real app, we use participant_id or a unique hash
        const eventId = "<?php echo $event_id; ?>";
        const qrcode = new QRCode(document.getElementById("qrcode"), {
            text: "AG-E" + eventId + "-UID" + Math.random().toString(36).substr(2, 9).toUpperCase(),
            width: 150,
            height: 150,
            colorDark : "#064e3b",
            colorLight : "#ffffff",
            correctLevel : QRCode.CorrectLevel.H
        });
    </script>

</body>
</html>
