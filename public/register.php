<?php
require_once __DIR__ . '/../src/bootstrap.php';

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("location: dashboard.php");
    exit;
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];

$errors = $_SESSION['register_errors'] ?? [];
$input = $_SESSION['register_input'] ?? [];
unset($_SESSION['register_errors']);
unset($_SESSION['register_input']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account - Agendain Console</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&display=swap');
        body { font-family: 'Outfit', sans-serif; }
        .login-gradient {
            background: radial-gradient(circle at 100% 100%, #ecfdf5 0%, #ffffff 50%, #f8fafc 100%);
        }
    </style>
</head>
<body class="login-gradient min-h-screen flex items-center justify-center p-6">

    <div class="w-full max-w-5xl bg-white rounded-[3rem] shadow-[0_50px_100px_-20px_rgba(0,0,0,0.1)] overflow-hidden flex flex-col md:flex-row-reverse border border-slate-100">
        <!-- Brand Side -->
        <div class="md:w-1/2 bg-emerald-600 p-12 md:p-20 text-white flex flex-col justify-between relative overflow-hidden">
            <div class="absolute top-0 left-0 w-64 h-64 bg-white/10 rounded-full filter blur-3xl"></div>
            
            <div class="relative z-10">
                <a href="index.php" class="flex items-center space-x-3 mb-20">
                    <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <span class="text-2xl font-black tracking-tight">Agendain<span class="text-white/50">.</span></span>
                </a>
                <h1 class="text-4xl md:text-6xl font-black leading-tight mb-8">Start Your Journey Today.</h1>
                <p class="text-emerald-100 text-lg font-medium leading-relaxed max-w-sm">Empower your organization with tools designed for national-scale event excellence.</p>
            </div>

            <div class="relative z-10 mt-12">
                <div class="flex items-center space-x-4">
                    <div class="flex -space-x-3">
                        <div class="w-10 h-10 rounded-full border-2 border-emerald-600 bg-white/20 flex items-center justify-center font-bold text-xs">01</div>
                        <div class="w-10 h-10 rounded-full border-2 border-emerald-600 bg-white/20 flex items-center justify-center font-bold text-xs">02</div>
                        <div class="w-10 h-10 rounded-full border-2 border-emerald-600 bg-white/20 flex items-center justify-center font-bold text-xs">03</div>
                    </div>
                    <p class="text-xs font-black uppercase tracking-widest text-emerald-200">Scale without limits</p>
                </div>
            </div>
        </div>

        <!-- Form Side -->
        <div class="md:w-1/2 p-12 md:p-20 bg-white">
            <div class="mb-12">
                <h2 class="text-3xl font-black text-slate-800 mb-2">Create Account</h2>
                <p class="text-slate-500 font-bold">Join the ecosystem and start managing events like a pro.</p>
            </div>

            <?php if (!empty($errors)): ?>
                <div class="mb-8 p-6 bg-red-50 text-red-500 rounded-[1.5rem] text-sm font-bold border border-red-100 shadow-sm animate__animated animate__shakeX">
                    <div class="flex items-center mb-2">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        <span class="uppercase tracking-widest text-[10px] font-black">Attention Required</span>
                    </div>
                    <ul class="list-disc list-inside space-y-1">
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="proses_register.php" method="POST" class="space-y-6">
                <div>
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3">Organization Name</label>
                    <input type="text" name="nama_organisasi" required 
                           class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-6 py-4 outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all font-bold text-slate-700"
                           placeholder="e.g. Creative Media Center"
                           value="<?php echo htmlspecialchars($input['nama_organisasi'] ?? ''); ?>">
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3">Work Email</label>
                    <input type="email" name="email" required 
                           class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-6 py-4 outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all font-bold text-slate-700"
                           placeholder="admin@organization.com"
                           value="<?php echo htmlspecialchars($input['email'] ?? ''); ?>">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3">Password</label>
                        <input type="password" name="password" required 
                               class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-6 py-4 outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all font-bold text-slate-700"
                               placeholder="••••••••">
                    </div>
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3">Confirm</label>
                        <input type="password" name="confirm_password" required 
                               class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-6 py-4 outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all font-bold text-slate-700"
                               placeholder="••••••••">
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full bg-slate-900 text-white font-black py-5 rounded-2xl hover:bg-emerald-600 transition-all shadow-xl shadow-slate-100 transform hover:-translate-y-1">
                        Register Organization
                    </button>
                </div>
            </form>

            <div class="mt-12 text-center">
                <p class="text-slate-500 font-bold">Already part of Agendain? <a href="login.php" class="text-emerald-600 hover:underline">Sign In</a></p>
            </div>
        </div>
    </div>

</body>
</html>
