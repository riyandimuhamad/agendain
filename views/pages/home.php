<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendain - The Professional Event Management Ecosystem</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;800&display=swap');
        body { font-family: 'Outfit', sans-serif; }
        .glass-nav {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
        .hero-gradient {
            background: radial-gradient(circle at 10% 20%, rgb(239, 252, 247) 0%, rgb(239, 252, 247) 90%);
        }
        .gradient-text {
            background: linear-gradient(90deg, #10b981 0%, #059669 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .card-hover:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
    </style>
</head>
<body class="bg-[#fcfdfd] text-slate-900">

<!-- Navigation -->
<nav class="glass-nav fixed top-0 w-full z-[100] border-b border-emerald-100/50">
    <div class="container mx-auto px-6 py-4 flex justify-between items-center">
        <a href="index.php" class="flex items-center space-x-2">
            <div class="w-10 h-10 bg-emerald-500 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-200">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
            <span class="text-2xl font-extrabold tracking-tight text-slate-800">Agendain<span class="text-emerald-500">.</span></span>
        </a>
        
        <div class="hidden lg:flex items-center space-x-10 font-semibold text-slate-600">
            <a href="#features" class="hover:text-emerald-600 transition-colors">Solutions</a>
            <a href="#events" class="hover:text-emerald-600 transition-colors">Explore Events</a>
            <a href="#process" class="hover:text-emerald-600 transition-colors">How it works</a>
        </div>

        <div class="flex items-center space-x-4">
            <a href="login.php" class="text-slate-600 hover:text-emerald-600 font-bold px-4 py-2 transition-all">Login</a>
            <a href="register.php" class="bg-emerald-600 text-white font-bold px-6 py-3 rounded-2xl hover:bg-emerald-700 transition-all shadow-xl shadow-emerald-200 hover:scale-105 active:scale-95">
                Get Started Free
            </a>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<header class="pt-32 pb-20 hero-gradient">
    <div class="container mx-auto px-6 grid lg:grid-cols-2 gap-16 items-center">
        <div class="animate__animated animate__fadeInLeft">
            <div class="inline-flex items-center space-x-2 bg-emerald-100 text-emerald-700 px-4 py-2 rounded-full text-sm font-bold mb-6">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                </span>
                <span>Trusted by 500+ Organizations</span>
            </div>
            <h1 class="text-5xl lg:text-7xl font-extrabold text-slate-900 leading-[1.1] mb-6">
                Elevate Your Event <span class="gradient-text">Management</span> Experience.
            </h1>
            <p class="text-xl text-slate-600 mb-10 leading-relaxed max-w-xl">
                The all-in-one ecosystem for national-scale events. From automated registration to instant digital certification, we handle the complexity so you can focus on the impact.
            </p>
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="register.php" class="bg-slate-900 text-white text-center font-bold px-10 py-4 rounded-2xl hover:bg-slate-800 transition-all shadow-2xl shadow-slate-200">
                    Host an Event
                </a>
                <a href="#events" class="bg-white border-2 border-slate-100 text-slate-700 text-center font-bold px-10 py-4 rounded-2xl hover:bg-slate-50 transition-all">
                    Browse Events
                </a>
            </div>
            <div class="mt-12 flex items-center space-x-8">
                <div>
                    <p class="text-2xl font-bold text-slate-900">10k+</p>
                    <p class="text-sm text-slate-500 font-semibold">Active Users</p>
                </div>
                <div class="h-8 w-px bg-slate-200"></div>
                <div>
                    <p class="text-2xl font-bold text-slate-900">1.2k+</p>
                    <p class="text-sm text-slate-500 font-semibold">Successful Events</p>
                </div>
            </div>
        </div>
        <div class="relative animate__animated animate__fadeInRight">
            <div class="absolute -top-10 -left-10 w-40 h-40 bg-emerald-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-pulse"></div>
            <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-blue-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30"></div>
            <img src="assets/img/PKKMB ITG-744.jpg" alt="Professional Management" class="relative rounded-[2.5rem] shadow-[0_50px_100px_-20px_rgba(0,0,0,0.15)] border-8 border-white">
        </div>
    </div>
</header>

<!-- Features Section -->
<section id="features" class="py-24 bg-white">
    <div class="container mx-auto px-6">
        <div class="text-center max-w-3xl mx-auto mb-20">
            <h2 class="text-sm font-extrabold text-emerald-500 uppercase tracking-widest mb-3">Enterprise Solutions</h2>
            <p class="text-4xl font-bold text-slate-900 mb-6">Designed for Seamless Operations</p>
            <p class="text-lg text-slate-600">Powerful tools that scale with your event size, ensuring a premium experience for every participant.</p>
        </div>
        
        <div class="grid md:grid-cols-3 gap-10">
            <div class="p-10 bg-[#f8fafb] rounded-[2rem] card-hover transition-all duration-300 border border-slate-50">
                <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center shadow-lg mb-8">
                    <svg class="w-8 h-8 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                </div>
                <h3 class="text-2xl font-bold text-slate-900 mb-4">Secure Registration</h3>
                <p class="text-slate-600 leading-relaxed">Integrated verification systems to ensure only legitimate participants join your events.</p>
            </div>
            
            <div class="p-10 bg-[#f8fafb] rounded-[2rem] card-hover transition-all duration-300 border border-slate-50">
                <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center shadow-lg mb-8">
                    <svg class="w-8 h-8 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="text-2xl font-bold text-slate-900 mb-4">Real-time Analytics</h3>
                <p class="text-slate-600 leading-relaxed">Monitor registration growth and attendance rates in real-time through a sleek dashboard.</p>
            </div>
            
            <div class="p-10 bg-[#f8fafb] rounded-[2rem] card-hover transition-all duration-300 border border-slate-50">
                <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center shadow-lg mb-8">
                    <svg class="w-8 h-8 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                </div>
                <h3 class="text-2xl font-bold text-slate-900 mb-4">Smart Certification</h3>
                <p class="text-slate-600 leading-relaxed">Automatically generate high-quality digital certificates with unique verification codes.</p>
            </div>
        </div>
    </div>
</section>

<!-- Events Section -->
<section id="events" class="py-24 bg-slate-50">
    <div class="container mx-auto px-6">
        <div class="flex flex-col md:flex-row justify-between items-end mb-16 gap-8">
            <div class="max-w-2xl">
                <h2 class="text-sm font-extrabold text-emerald-500 uppercase tracking-widest mb-3">Public Portal</h2>
                <p class="text-4xl font-bold text-slate-900 mb-4">Featured National Events</p>
                <p class="text-lg text-slate-600">Discover and join the most prestigious events happening across the country.</p>
            </div>
            <form action="index.php" method="GET" class="flex w-full md:w-auto bg-white rounded-2xl shadow-xl shadow-slate-100 p-2 overflow-hidden">
                <input type="text" name="search" class="flex-grow px-6 py-3 border-none focus:ring-0 outline-none text-slate-700" 
                       placeholder="Search events..." value="<?php echo htmlspecialchars($search_keyword); ?>">
                <button type="submit" class="bg-emerald-500 text-white px-8 py-3 rounded-xl font-bold hover:bg-emerald-600 transition shadow-lg shadow-emerald-100">Find</button>
            </form>
        </div>

        <div class="grid md:grid-cols-3 gap-10">
            <?php if (empty($public_events)): ?>
                <div class="col-span-full py-20 bg-white rounded-[2rem] border-2 border-dashed border-slate-200 flex flex-col items-center">
                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.691.346a6 6 0 01-3.86.517l-2.388-.477a2 2 0 00-1.022.547l-1.168 1.168a2 2 0 00-.586 1.414v1a2 2 0 002 2h14a2 2 0 002-2v-1a2 2 0 00-.586-1.414l-1.168-1.168z"></path></svg>
                    </div>
                    <p class="text-xl font-bold text-slate-400">No events found matching your criteria</p>
                </div>
            <?php else: ?>
                <?php foreach ($public_events as $event): ?>
                    <div class="group bg-white rounded-[2.5rem] overflow-hidden shadow-[0_20px_50px_rgba(0,0,0,0.03)] border border-slate-100 hover:shadow-[0_30px_60px_rgba(16,185,129,0.1)] transition-all duration-500">
                        <div class="h-64 w-full relative overflow-hidden">
                            <?php if (!empty($event['gambar_event'])): ?>
                                <img src="uploads/posters/<?php echo htmlspecialchars($event['gambar_event']); ?>" 
                                        class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                            <?php else: ?>
                                <div class="bg-emerald-50 h-full w-full flex items-center justify-center">
                                    <span class="text-emerald-400 font-bold">Agendain Exclusive</span>
                                </div>
                            <?php endif; ?>
                            <div class="absolute top-6 left-6 bg-white/90 backdrop-blur-md px-4 py-2 rounded-xl text-emerald-600 text-xs font-black uppercase tracking-widest">
                                Upcoming
                            </div>
                        </div>
                        <div class="p-8">
                            <p class="text-emerald-500 text-sm font-bold mb-3 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <?php echo date('d M Y', strtotime($event['tanggal_mulai'])); ?>
                            </p>
                            <h3 class="text-2xl font-bold text-slate-800 mb-4 group-hover:text-emerald-600 transition-colors"><?php echo htmlspecialchars($event['nama_event']); ?></h3>
                            <p class="text-slate-500 text-sm mb-8 line-clamp-2 leading-relaxed"><?php echo htmlspecialchars($event['deskripsi']); ?></p>
                            <a href="register_event.php?id=<?php echo $event['event_id']; ?>" 
                                class="flex items-center justify-center w-full bg-slate-900 text-white font-bold py-4 rounded-2xl hover:bg-emerald-600 transition-all group-hover:shadow-xl group-hover:shadow-emerald-100">
                                Register Now
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-24 bg-slate-900 text-white overflow-hidden relative">
    <div class="absolute top-0 right-0 w-96 h-96 bg-emerald-500/10 rounded-full filter blur-[120px]"></div>
    <div class="container mx-auto px-6 relative z-10">
        <div class="grid md:grid-cols-4 gap-12 text-center">
            <div>
                <p class="text-5xl font-black text-emerald-400 mb-2">99%</p>
                <p class="text-slate-400 font-bold uppercase tracking-widest text-xs">Satisfaction</p>
            </div>
            <div>
                <p class="text-5xl font-black text-emerald-400 mb-2">24/7</p>
                <p class="text-slate-400 font-bold uppercase tracking-widest text-xs">Priority Support</p>
            </div>
            <div>
                <p class="text-5xl font-black text-emerald-400 mb-2">Instant</p>
                <p class="text-slate-400 font-bold uppercase tracking-widest text-xs">Certification</p>
            </div>
            <div>
                <p class="text-5xl font-black text-emerald-400 mb-2">Secure</p>
                <p class="text-slate-400 font-bold uppercase tracking-widest text-xs">Cloud Native</p>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="bg-white border-t border-slate-100 pt-20 pb-10">
    <div class="container mx-auto px-6">
        <div class="grid lg:grid-cols-4 gap-12 mb-16">
            <div class="col-span-2">
                <a href="#" class="flex items-center space-x-2 mb-8">
                    <div class="w-8 h-8 bg-emerald-500 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <span class="text-2xl font-black tracking-tight text-slate-800">Agendain.</span>
                </a>
                <p class="text-slate-500 max-w-sm leading-relaxed font-medium">Empowering event organizers with enterprise-grade tools. Made for national impact, built for campus scalability.</p>
            </div>
            <div>
                <h4 class="font-black text-slate-800 mb-6 uppercase tracking-widest text-xs">Product</h4>
                <ul class="space-y-4 text-slate-500 font-bold">
                    <li><a href="#" class="hover:text-emerald-500">Features</a></li>
                    <li><a href="#" class="hover:text-emerald-500">Integrations</a></li>
                    <li><a href="#" class="hover:text-emerald-500">Pricing</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-black text-slate-800 mb-6 uppercase tracking-widest text-xs">Company</h4>
                <ul class="space-y-4 text-slate-500 font-bold">
                    <li><a href="#" class="hover:text-emerald-500">About Us</a></li>
                    <li><a href="#" class="hover:text-emerald-500">Contact</a></li>
                    <li><a href="#" class="hover:text-emerald-500">Privacy Policy</a></li>
                </ul>
            </div>
        </div>
        <div class="pt-10 border-t border-slate-50 flex flex-col md:flex-row justify-between items-center gap-6">
            <p class="text-slate-400 font-bold text-sm">&copy; <?php echo date("Y"); ?> Agendain Ecosystem. All rights reserved.</p>
            <div class="flex space-x-6">
                <a href="#" class="text-slate-400 hover:text-emerald-500"><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg></a>
                <a href="#" class="text-slate-400 hover:text-emerald-500"><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg></a>
            </div>
        </div>
    </div>
</footer>

</body>
</html>
