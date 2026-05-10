<header class="bg-white/80 backdrop-blur-md border-b border-slate-100 sticky top-0 z-[40]">
    <div class="container mx-auto px-10 py-5 flex justify-between items-center">
        
        <div class="flex items-center space-x-6">
            <button id="toggleButton" class="w-10 h-10 flex items-center justify-center bg-slate-50 text-slate-600 rounded-xl hover:bg-emerald-50 hover:text-emerald-600 transition-all focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            </button>
            <h1 class="text-xl font-bold text-slate-800 hidden md:block">Management Control Center</h1>
        </div>
        
        <div class="flex items-center space-x-6">
            <div class="hidden sm:flex flex-col items-end">
                <span class="text-xs font-black text-slate-400 uppercase tracking-widest">Organization</span>
                <span class="text-sm font-bold text-slate-700"><?php echo htmlspecialchars($_SESSION['nama_organisasi']); ?></span>
            </div>
            <div class="w-10 h-10 bg-emerald-100 text-emerald-700 rounded-full flex items-center justify-center font-black text-lg">
                <?php echo strtoupper(substr($_SESSION['nama_organisasi'], 0, 1)); ?>
            </div>
        </div>
    </div>
</header>