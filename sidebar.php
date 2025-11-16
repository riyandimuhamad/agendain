<?php
// Dapatkan nama file saat ini untuk menandai link aktif
$current_page = basename($_SERVER['PHP_SELF']);
?>

<aside id="sidebar" class="bg-white w-64 min-h-screen shadow-lg fixed top-0 left-0 z-20 transition-all duration-300 ease-in-out">
    <div class="p-4 border-b">
        <a href="dashboard.php" class="text-2xl font-bold text-green-600">Agendain</a>
    </div>
    
    <nav class="mt-4">
        <ul>
            <li class="m-2">
                <a href="dashboard.php" 
                   class="flex items-center px-4 py-2 rounded-lg font-medium transition-colors duration-200
                   <?php echo ($current_page == 'dashboard.php') 
                            ? 'bg-green-50 text-green-700' 
                            : 'text-gray-700 hover:bg-gray-100'; ?>">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    Dashboard
                </a>
            </li>
            
            <li class="m-2">
                <a href="create_event.php" 
                   class="flex items-center px-4 py-2 rounded-lg font-medium transition-colors duration-200
                   <?php echo ($current_page == 'create_event.php') 
                            ? 'bg-green-50 text-green-700' 
                            : 'text-gray-700 hover:bg-gray-100'; ?>">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Buat Event Baru
                </a>
            </li>
            
            </ul>
    </nav>
</aside>