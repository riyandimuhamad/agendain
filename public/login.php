<?php
require_once __DIR__ . '/../src/bootstrap.php';

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("location: dashboard.php");
    exit;
}

$flash_message = $_SESSION['flash_message'] ?? null;
unset($_SESSION['flash_message']);

$errors = $_SESSION['login_errors'] ?? [];
$input = $_SESSION['login_input'] ?? [];
unset($_SESSION['login_errors']);
unset($_SESSION['login_input']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - Agendain Console</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&display=swap');
        body { font-family: 'Outfit', sans-serif; }
        .login-gradient {
            background: radial-gradient(circle at 0% 0%, #ecfdf5 0%, #ffffff 50%, #f8fafc 100%);
        }
    </style>
</head>
<body class="login-gradient min-h-screen flex items-center justify-center p-6">

    <div class="w-full max-w-5xl bg-white rounded-[3rem] shadow-[0_50px_100px_-20px_rgba(0,0,0,0.1)] overflow-hidden flex flex-col md:flex-row border border-slate-100">
        <!-- Brand Side -->
        <div class="md:w-1/2 bg-slate-900 p-12 md:p-20 text-white flex flex-col justify-between relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-500/20 rounded-full filter blur-3xl"></div>
            
            <div class="relative z-10">
                <a href="index.php" class="flex items-center space-x-3 mb-20">
                    <div class="w-10 h-10 bg-emerald-500 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <span class="text-2xl font-black tracking-tight">Agendain<span class="text-emerald-500">.</span></span>
                </a>
                <h1 class="text-4xl md:text-6xl font-black leading-tight mb-8">Access the Command Center.</h1>
                <p class="text-slate-400 text-lg font-medium leading-relaxed max-w-sm">Manage your national-scale campaigns with our professional event management ecosystem.</p>
            </div>

            <div class="relative z-10 mt-12 flex items-center space-x-4">
                <div class="flex -space-x-3">
                    <div class="w-10 h-10 rounded-full border-2 border-slate-900 bg-emerald-500 flex items-center justify-center font-bold text-xs">P</div>
                    <div class="w-10 h-10 rounded-full border-2 border-slate-900 bg-blue-500 flex items-center justify-center font-bold text-xs">A</div>
                    <div class="w-10 h-10 rounded-full border-2 border-slate-900 bg-purple-500 flex items-center justify-center font-bold text-xs">N</div>
                </div>
                <p class="text-xs font-black uppercase tracking-widest text-slate-500">Join 500+ Organizations</p>
            </div>
        </div>

        <!-- Form Side -->
        <div class="md:w-1/2 p-12 md:p-20 bg-white">
            <div class="mb-12">
                <h2 class="text-3xl font-black text-slate-800 mb-2">Welcome Back</h2>
                <p class="text-slate-500 font-bold">Please enter your credentials to access your console.</p>
            </div>

            <?php if ($flash_message): ?>
                <div class="mb-8 p-4 bg-emerald-50 text-emerald-600 rounded-2xl text-sm font-bold border border-emerald-100">
                    <?php echo htmlspecialchars($flash_message); ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($errors)): ?>
                <div class="mb-8 p-4 bg-red-50 text-red-500 rounded-2xl text-sm font-bold border border-red-100">
                    <ul class="list-disc list-inside">
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="proses_login.php" method="POST" class="space-y-6">
                <div>
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3">Email Address</label>
                    <input type="email" name="email" required 
                           class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-6 py-4 outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all font-bold text-slate-700"
                           placeholder="name@organization.com"
                           value="<?php echo htmlspecialchars($input['email'] ?? ''); ?>">
                </div>

                <div>
                    <div class="flex justify-between items-center mb-3">
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest">Password</label>
                        <a href="#" class="text-xs font-black text-emerald-500 uppercase tracking-widest hover:text-emerald-600 transition-colors">Forgot?</a>
                    </div>
                    <input type="password" name="password" required 
                           class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-6 py-4 outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all font-bold text-slate-700"
                           placeholder="••••••••">
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full bg-emerald-600 text-white font-black py-5 rounded-2xl hover:bg-emerald-700 transition-all shadow-xl shadow-emerald-100 transform hover:-translate-y-1">
                        Authorize Access
                    </button>
                </div>
            </form>

            <div class="mt-12 text-center">
                <p class="text-slate-500 font-bold">New organization? <a href="register.php" class="text-emerald-600 hover:underline">Create an account</a></p>
            </div>
        </div>
    </div>

</body>
</html>
