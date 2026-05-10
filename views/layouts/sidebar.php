<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

<aside id="sidebar" class="bg-slate-900 w-72 min-h-screen fixed top-0 left-0 z-50 transition-all duration-300 ease-in-out shadow-2xl">
    <div class="p-8 border-b border-slate-800">
        <a href="dashboard.php" class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-emerald-500 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-500/20">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
            <span class="text-2xl font-black text-white tracking-tight">Agendain<span class="text-emerald-500">.</span></span>
        </a>
    </div>
    
    <nav class="mt-8 px-4">
        <p class="text-xs font-black text-slate-500 uppercase tracking-[0.2em] mb-4 px-4">Main Menu</p>
        <ul class="space-y-2">
            <li>
                <a href="dashboard.php" 
                   class="flex items-center px-4 py-3 rounded-2xl font-bold transition-all duration-200
                   <?php echo ($current_page == 'dashboard.php') 
                            ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-500/20' 
                            : 'text-slate-400 hover:bg-slate-800 hover:text-white'; ?>">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    Dashboard
                </a>
            </li>
            
            <li>
                <a href="create_event.php" 
                   class="flex items-center px-4 py-3 rounded-2xl font-bold transition-all duration-200
                   <?php echo ($current_page == 'create_event.php') 
                            ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-500/20' 
                            : 'text-slate-400 hover:bg-slate-800 hover:text-white'; ?>">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Create New Event
                </a>
            </li>

            <li>
                <a href="scan_attendance.php" 
                   class="flex items-center px-4 py-3 rounded-2xl font-bold transition-all duration-200
                   <?php echo ($current_page == 'scan_attendance.php') 
                            ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-500/20' 
                            : 'text-slate-400 hover:bg-slate-800 hover:text-white'; ?>">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                    Check-in Portal
                </a>
            </li>
        </ul>

        <p class="text-xs font-black text-slate-500 uppercase tracking-[0.2em] mt-10 mb-4 px-4">System</p>
        <ul class="space-y-2">
             <li>
                <a href="logout.php" class="flex items-center px-4 py-3 rounded-2xl font-bold text-slate-400 hover:bg-red-500/10 hover:text-red-500 transition-all duration-200">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Sign Out
                </a>
            </li>
        </ul>
    </nav>

    <div class="absolute bottom-8 left-0 w-full px-8">
        <div class="bg-slate-800/50 p-6 rounded-[2rem] border border-slate-700/50">
            <p class="text-slate-400 text-xs font-bold mb-2 uppercase">Logged in as</p>
            <p class="text-white font-bold truncate"><?php echo htmlspecialchars($_SESSION['nama_organisasi']); ?></p>
        </div>
    </div>
</aside>