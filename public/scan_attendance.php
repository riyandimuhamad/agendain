<?php
require_once __DIR__ . '/../src/bootstrap.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check-in Portal - Agendain</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/html5-qrcode"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&display=swap');
        body { font-family: 'Outfit', sans-serif; }
        #sidebar { transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        #main-content { transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1); margin-left: 18rem; }
        #sidebar.collapsed { margin-left: -18rem; }
        #main-content.sidebar-collapsed { margin-left: 0; }
        #reader {
            width: 100%;
            border-radius: 2rem;
            overflow: hidden;
            border: none !important;
        }
        #reader__dashboard_section_csr button {
            background-color: #10b981 !important;
            color: white !important;
            padding: 10px 20px !important;
            border-radius: 12px !important;
            font-weight: bold !important;
            border: none !important;
            margin-top: 10px !important;
        }
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
                <span class="text-slate-600">Attendance Scan</span>
            </div>

            <div class="max-w-4xl mx-auto">
                <div class="mb-10 text-center">
                    <h2 class="text-4xl font-black text-slate-800 mb-2">Check-in Portal</h2>
                    <p class="text-slate-500 font-bold">Scan participant QR codes for instant attendance verification.</p>
                </div>

                <div class="grid md:grid-cols-2 gap-10">
                    <!-- Scanner Side -->
                    <div class="bg-white rounded-[3rem] p-8 border border-slate-100 shadow-sm">
                        <div id="reader" class="mb-6"></div>
                        <div class="p-6 bg-slate-50 rounded-[2rem] border border-slate-100 text-center">
                            <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Scanner Status</p>
                            <p id="status" class="text-sm font-bold text-slate-600 italic">Waiting for camera permission...</p>
                        </div>
                    </div>

                    <!-- Result Side -->
                    <div class="space-y-8">
                        <div class="bg-emerald-600 rounded-[3rem] p-10 text-white shadow-xl shadow-emerald-100">
                            <h4 class="text-xs font-black text-emerald-200 uppercase tracking-widest mb-6">Validation Result</h4>
                            <div id="result-content" class="min-h-[200px] flex flex-col justify-center items-center text-center">
                                <div class="w-20 h-20 bg-white/10 rounded-full flex items-center justify-center mb-6">
                                    <svg class="w-10 h-10 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                                </div>
                                <p class="text-emerald-100 font-bold">Ready to scan. Please point the camera at the participant's QR code.</p>
                            </div>
                        </div>

                        <div class="bg-white rounded-[3rem] p-10 border border-slate-100 shadow-sm">
                            <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-6">Recent Check-ins</h4>
                            <div id="history" class="space-y-4">
                                <p class="text-slate-400 text-sm font-bold italic">No scans recorded in this session.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        const html5QrCode = new Html5Qrcode("reader");
        const statusEl = document.getElementById('status');
        const resultContent = document.getElementById('result-content');
        const historyEl = document.getElementById('history');

        const qrConfig = { fps: 10, qrbox: { width: 250, height: 250 } };

        function onScanSuccess(decodedText, decodedResult) {
            // In a real app, decodedText would be "AG-E17-UID-XXXX"
            // We would call an API like `proses_checkin.php?code=`
            statusEl.textContent = "QR Code detected!";
            statusEl.className = "text-sm font-black text-emerald-500 uppercase tracking-widest";

            // Simulate API Call
            resultContent.innerHTML = `
                <div class="animate__animated animate__bounceIn">
                    <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <h3 class="text-2xl font-black mb-1">Check-in Success!</h3>
                    <p class="text-emerald-100 font-bold mb-4">${decodedText}</p>
                    <p class="text-xs font-black uppercase tracking-widest bg-white/20 px-4 py-2 rounded-full inline-block">Attendance Recorded</p>
                </div>
            `;

            // Add to History
            const now = new Date().toLocaleTimeString();
            if (historyEl.querySelector('p.italic')) historyEl.innerHTML = '';
            const item = document.createElement('div');
            item.className = "flex justify-between items-center p-4 bg-slate-50 rounded-2xl border border-slate-100 animate__animated animate__fadeInDown";
            item.innerHTML = `
                <div>
                    <p class="text-slate-800 font-bold text-sm">Scan Successful</p>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">${decodedText}</p>
                </div>
                <span class="text-[10px] font-black text-slate-400">${now}</span>
            `;
            historyEl.prepend(item);

            // Pause for 3 seconds before next scan
            html5QrCode.pause();
            setTimeout(() => {
                html5QrCode.resume();
                statusEl.textContent = "Scanning...";
                statusEl.className = "text-sm font-bold text-slate-600";
            }, 3000);
        }

        html5QrCode.start({ facingMode: "environment" }, qrConfig, onScanSuccess)
            .then(() => {
                statusEl.textContent = "Scanning...";
                statusEl.className = "text-sm font-bold text-slate-600";
            })
            .catch(err => {
                statusEl.textContent = "Camera access denied or not found.";
                statusEl.className = "text-sm font-bold text-red-500";
            });

        // Sidebar Toggle
        const toggleButton = document.getElementById("toggleButton");
        const sidebar = document.getElementById("sidebar");
        const mainContent = document.getElementById("main-content");
        if (toggleButton && sidebar && mainContent) {
            toggleButton.addEventListener("click", function() {
                sidebar.classList.toggle("collapsed");
                mainContent.classList.toggle("sidebar-collapsed");
            });
        }
    </script>
</body>
</html>
