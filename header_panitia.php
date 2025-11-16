<header class="bg-white shadow-md sticky top-0 z-10">
    <div class="container mx-auto px-6 py-4 flex justify-between items-center">
        
        <button id="toggleButton" class="text-gray-700 hover:text-green-600 focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
        </button>
        
        <div>
            <span class="text-gray-700 mr-4 hidden sm:inline"> Selamat datang, 
                <span class="font-semibold"><?php echo htmlspecialchars($_SESSION['nama_organisasi']); ?></span>!
            </span>
            <a href="logout.php" class="bg-red-500 text-white font-semibold px-4 py-2 rounded-lg hover:bg-red-600 transition duration-300">
                Logout
            </a>
        </div>
    </div>
</header>