<?php
/**
 * AI ARCADE - SECURE ADMIN CONTROLLER v4.0
 * Features: JSON Management, Session Security, Data Purge
 */
session_start();
$json_file = 'leaderboard.json';
$admin_pin = "7785"; // Is PIN ko zaroor badal lena security ke liye

// 1. Authentication Handler
if (isset($_POST['admin_key']) && $_POST['admin_key'] === $admin_pin) {
    $_SESSION['is_admin_active'] = true;
    $_SESSION['admin_login_time'] = time();
}

// 2. Logout Logic
if (isset($_GET['action']) && $_GET['action'] === 'terminate') {
    session_destroy();
    header("Location: admin.php");
    exit;
}

// 3. Secure Delete Logic
if (isset($_GET['delete_user']) && isset($_SESSION['is_admin_active'])) {
    $target = $_GET['delete_user'];
    if (file_exists($json_file)) {
        $data = json_decode(file_get_contents($json_file), true);
        $new_data = array_filter($data, function($item) use ($target) {
            return $item['user'] !== $target;
        });
        file_put_contents($json_file, json_encode(array_values($new_data)));
    }
    header("Location: admin.php?msg=deleted");
    exit;
}

// 4. Global Reset Logic
if (isset($_POST['wipe_database']) && isset($_SESSION['is_admin_active'])) {
    file_put_contents($json_file, json_encode([]));
    header("Location: admin.php?msg=wiped");
    exit;
}

$current_data = file_exists($json_file) ? json_decode(file_get_contents($json_file), true) : [];
$total_players = count($current_data);
$highest_score = $total_players > 0 ? max(array_column($current_data, 'xp')) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TERMINAL | AI ARCADE ADMIN</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background: #020617; color: #f8fafc; font-family: 'JetBrains Mono', monospace; }
        .glass-panel { background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(239, 68, 68, 0.2); border-radius: 2rem; }
        .stat-card { background: rgba(0, 0, 0, 0.3); border-left: 4px solid #ef4444; }
        .danger-zone { border: 1px dashed #ef4444; }
    </style>
</head>
<body class="p-6 md:p-12 lg:p-20">

    <div class="max-w-4xl mx-auto">
        
        <?php if (!isset($_SESSION['is_admin_active'])): ?>
        <div class="glass-panel p-10 md:p-16 text-center shadow-2xl shadow-red-500/10">
            <div class="mb-8">
                <div class="w-20 h-20 bg-red-600/10 rounded-full flex items-center justify-center mx-auto mb-4 border border-red-500/20">
                    <span class="text-4xl">🔐</span>
                </div>
                <h1 class="text-3xl font-black italic text-red-500 tracking-tighter">RESTRICTED ACCESS</h1>
                <p class="text-slate-500 text-[10px] uppercase tracking-widest mt-2">Level 4 Authorization Required</p>
            </div>

            <form method="POST" class="space-y-6">
                <input type="password" name="admin_key" 
                       class="w-full bg-black border border-slate-800 p-5 rounded-2xl text-center text-4xl tracking-[0.5em] focus:border-red-600 outline-none transition-all" 
                       placeholder="****" autofocus required>
                <button class="w-full bg-red-600 hover:bg-red-500 text-white font-black p-5 rounded-2xl uppercase tracking-[0.2em] transition-all transform hover:scale-105">
                    Verify Credentials
                </button>
            </form>
            <p class="mt-8 text-[10px] text-slate-700">Warning: Unauthorized access attempts are logged. IP: <?= $_SERVER['REMOTE_ADDR'] ?></p>
        </div>

        <?php else: ?>
        <div class="flex flex-col md:flex-row justify-between items-center mb-10 gap-4">
            <div>
                <h1 class="text-4xl font-black italic text-red-500 underline decoration-red-500/30 underline-offset-8">CORE DASHBOARD</h1>
                <p class="text-slate-500 text-xs mt-2 uppercase font-bold tracking-widest">System Engine: JSON Serverless</p>
            </div>
            <div class="flex gap-4">
                <a href="index.php" target="_blank" class="bg-slate-800 px-6 py-3 rounded-xl text-xs font-bold hover:bg-slate-700">VIEW SITE</a>
                <a href="?action=terminate" class="bg-red-600 px-6 py-3 rounded-xl text-xs font-bold hover:bg-red-500">LOGOUT</a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <div class="stat-card p-6 rounded-2xl">
                <p class="text-[10px] text-slate-500 uppercase font-bold">Total Players</p>
                <p class="text-3xl font-black text-white"><?= $total_players ?></p>
            </div>
            <div class="stat-card p-6 rounded-2xl" style="border-color: #22d3ee;">
                <p class="text-[10px] text-slate-500 uppercase font-bold">Peak Score (XP)</p>
                <p class="text-3xl font-black text-cyan-400"><?= number_format($highest_score) ?></p>
            </div>
            <div class="stat-card p-6 rounded-2xl" style="border-color: #fbbf24;">
                <p class="text-[10px] text-slate-500 uppercase font-bold">System Status</p>
                <p class="text-3xl font-black text-yellow-400">OPTIMAL</p>
            </div>
        </div>

        <div class="glass-panel p-8 mb-10">
            <h3 class="text-xs font-black uppercase tracking-widest text-slate-500 mb-8 border-b border-white/5 pb-4">Live Player Registry</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-[10px] text-slate-600 uppercase tracking-widest border-b border-white/5">
                            <th class="pb-4 font-bold">Rank</th>
                            <th class="pb-4 font-bold">Username</th>
                            <th class="pb-4 font-bold">Total XP</th>
                            <th class="pb-4 font-bold text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        <?php if($current_data): foreach($current_data as $index => $player): ?>
                        <tr class="hover:bg-white/5 transition-colors">
                            <td class="py-5 font-mono text-xs opacity-50">#<?= $index + 1 ?></td>
                            <td class="py-5 font-bold text-sm"><?= htmlspecialchars($player['user']) ?></td>
                            <td class="py-5 font-mono text-xs text-cyan-400 font-bold"><?= number_format($player['xp']) ?></td>
                            <td class="py-5 text-right">
                                <a href="?delete_user=<?= urlencode($player['user']) ?>" 
                                   class="text-[10px] bg-red-600/10 text-red-500 border border-red-500/20 px-4 py-2 rounded-lg font-bold hover:bg-red-600 hover:text-white transition-all"
                                   onclick="return confirm('Pakka delete karna hai? Is player ka poora record ud jayega.')">
                                    DELETE
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; else: ?>
                        <tr>
                            <td colspan="4" class="py-20 text-center text-slate-600 italic text-sm">JSON data file is currently empty. Start playing to see data here!</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="danger-zone p-8 rounded-[2.5rem] bg-red-600/5">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                <div>
                    <h4 class="text-red-500 font-black italic text-xl uppercase tracking-tighter">Danger Zone</h4>
                    <p class="text-slate-500 text-xs">This action will permanently wipe the leaderboard.json file. It cannot be undone.</p>
                </div>
                <form method="POST">
                    <button name="wipe_database" 
                            class="bg-red-600 hover:bg-red-500 text-white font-black px-10 py-4 rounded-2xl text-xs uppercase tracking-widest shadow-xl shadow-red-600/20 transition-all"
                            onclick="return confirm('BRAVO! Are you absolutely sure? This will delete EVERYONE.')">
                        WIPE ALL DATA
                    </button>
                </form>
            </div>
        </div>

        <footer class="mt-20 text-center pb-10">
            <p class="text-[10px] text-slate-800 uppercase tracking-[0.5em]">Arcade Admin Engine v4.0.2 | Built for High Content Performance</p>
        </footer>
        <?php endif; ?>

    </div>

</body>
</html>
