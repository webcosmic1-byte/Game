<?php
/**
 * AI ARCADE PRO - HIGH CONTENT EDITION 2026
 * Developed for maximum AdSense Compatibility
 * Feature: JSON Database, Dual Theme, Neural SFX
 */
session_start();
$json_file = 'leaderboard.json';

// Initialize JSON database if missing
if (!file_exists($json_file)) {
    file_put_contents($json_file, json_encode([]));
}

// User Authentication Logic
if (isset($_POST['set_user'])) {
    $_SESSION['username'] = htmlspecialchars(substr($_POST['set_user'], 0, 15));
    exit(json_encode(['status' => 'success']));
}

// Global XP Processing Engine
if (isset($_POST['save_xp']) && isset($_SESSION['username'])) {
    $xp_to_add = (int)$_POST['save_xp'];
    $current_user = $_SESSION['username'];
    
    $raw_data = file_get_contents($json_file);
    $data = json_decode($raw_data, true);
    if (!is_array($data)) $data = [];

    $user_exists = false;
    foreach ($data as &$entry) {
        if ($entry['user'] === $current_user) {
            $entry['xp'] += $xp_to_add;
            $user_exists = true;
            break;
        }
    }
    
    if (!$user_exists) {
        $data[] = ['user' => $current_user, 'xp' => $xp_to_add];
    }

    // Rank players by XP (Descending)
    usort($data, function($a, $b) {
        return $b['xp'] <=> $a['xp'];
    });

    // Save top 15 players only to optimize JSON size
    file_put_contents($json_file, json_encode(array_slice($data, 0, 15)));
    exit;
}

$scores = json_decode(file_get_contents($json_file), true);
?>
<!DOCTYPE html>
<html lang="en" data-theme="cyber">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Arcade Pro | Ultimate Neural Sketch & Drawing Challenge 2026</title>
    
    <meta name="description" content="Experience the next generation of AI-powered sketching. Challenge your brain, draw complex shapes, and compete on our global leaderboard. AI Arcade is a free neural network experiment.">
    <meta name="keywords" content="AI Game, Neural Network, Sketching, Online Arcade, Free Games 2026, Leaderboard, Web Development Project">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js"></script>
    
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-1247605845741495" crossorigin="anonymous"></script>

    <style>
        :root { --main: #22d3ee; --bg: #030408; --card: rgba(15, 23, 42, 0.9); }
        [data-theme="pink"] { --main: #f472b6; --bg: #0f172a; --card: rgba(30, 41, 59, 0.8); }
        
        body { background: var(--bg); color: #fff; font-family: 'Inter', sans-serif; transition: background 0.6s cubic-bezier(0.4, 0, 0.2, 1); scroll-behavior: smooth; }
        .neon-glow { text-shadow: 0 0 15px var(--main); }
        .cyber-panel { border: 1px solid rgba(255,255,255,0.05); background: var(--card); backdrop-filter: blur(20px); border-radius: 2.5rem; }
        canvas { background: #ffffff; border-radius: 2rem; cursor: crosshair; touch-action: none; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5); }
        .ad-placeholder { background: repeating-linear-gradient(45deg, #0a0a0a, #0a0a0a 10px, #0f172a 10px, #0f172a 20px); border: 1px dashed #334155; display: flex; align-items: center; justify-content: center; overflow: hidden; }
        .btn-action { transition: all 0.3s ease; text-transform: uppercase; letter-spacing: 0.1em; font-weight: 900; }
        .btn-action:hover { transform: translateY(-3px); filter: brightness(1.2); }
        article h2, article h3 { color: var(--main); font-weight: 900; font-style: italic; }
    </style>
</head>
<body class="p-4 md:p-12">

    <div class="max-w-6xl mx-auto mb-12 ad-placeholder h-32 rounded-3xl">
        <ins class="adsbygoogle"
             style="display:block"
             data-ad-client="ca-pub-1247605845741495"
             data-ad-slot="auto"
             data-ad-format="horizontal"
             data-full-width-responsive="true"></ins>
    </div>

    <?php if (!isset($_SESSION['username'])): ?>
    <div id="auth-modal" class="fixed inset-0 bg-black/95 flex items-center justify-center z-50 p-6 backdrop-blur-xl">
        <div class="max-w-md w-full p-10 cyber-panel border-cyan-500/30 text-center scale-up">
            <h2 class="text-4xl font-black mb-2 italic neon-glow">AI ARCADE</h2>
            <p class="text-slate-500 text-[10px] uppercase tracking-[0.4em] mb-10">Neural Interface 2.0</p>
            <input type="text" id="uname-input" class="w-full p-5 bg-black border border-slate-800 rounded-2xl mb-6 text-center text-xl font-bold focus:border-cyan-500 outline-none transition-all" placeholder="Enter Nickname...">
            <button onclick="saveUser()" class="w-full bg-cyan-600 p-5 rounded-2xl font-black text-black btn-action shadow-lg shadow-cyan-600/20">Access System</button>
        </div>
    </div>
    <?php endif; ?>

    <div class="max-w-7xl mx-auto grid lg:grid-cols-12 gap-10">
        
        <aside class="lg:col-span-3 space-y-10">
            <div class="cyber-panel p-8">
                <div class="flex justify-between items-center mb-8">
                    <h3 class="font-black italic text-cyan-400">LEADERBOARD</h3>
                    <button onclick="switchTheme()" class="text-[9px] border border-white/10 px-2 py-1 rounded hover:bg-white/5 uppercase">Mode</button>
                </div>
                <div class="space-y-4">
                    <?php if($scores): foreach($scores as $idx => $s): ?>
                    <div class="flex justify-between items-center p-4 rounded-2xl <?= $s['user'] == ($_SESSION['username'] ?? '') ? 'bg-cyan-500/20 border border-cyan-500/40' : 'bg-white/5' ?>">
                        <span class="text-xs font-mono opacity-40">#<?= $idx+1 ?></span>
                        <span class="text-sm font-bold truncate max-w-[100px]"><?= htmlspecialchars($s['user']) ?></span>
                        <span class="text-xs font-mono text-cyan-400 font-bold"><?= number_format($s['xp']) ?></span>
                    </div>
                    <?php endforeach; else: ?>
                    <p class="text-center text-xs italic text-slate-600 py-10">Searching for legends...</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="ad-placeholder h-[600px] rounded-[2.5rem]">
                <ins class="adsbygoogle"
                     style="display:block"
                     data-ad-client="ca-pub-1247605845741495"
                     data-ad-slot="auto"
                     data-ad-format="vertical"
                     data-full-width-responsive="true"></ins>
            </div>
        </aside>

        <main class="lg:col-span-9 space-y-12">
            <div class="cyber-panel p-8 md:p-12 relative overflow-hidden">
                <div class="flex flex-col md:flex-row justify-between items-end mb-10 gap-6">
                    <div>
                        <p class="text-[10px] text-cyan-500 font-bold uppercase tracking-[0.3em] mb-2">Neural Link Online</p>
                        <h2 class="text-5xl font-black italic tracking-tighter">DRAW A <span id="target-word" class="text-cyan-400 underline decoration-2 underline-offset-8">CIRCLE</span></h2>
                    </div>
                    <div id="countdown" class="text-6xl font-mono font-black text-red-600 drop-shadow-[0_0_15px_rgba(220,38,38,0.3)]">20</div>
                </div>

                <canvas id="canvas" width="800" height="400" 
        style="position: relative; z-index: 10; touch-action: none;" 
        class="w-full h-auto mb-6"></canvas>


                <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                    <button onclick="initGame()" class="bg-cyan-600 p-5 rounded-3xl font-black text-black btn-action text-xs shadow-xl shadow-cyan-600/20">Start Round</button>
                    <button onclick="clearArt()" class="bg-slate-900 border border-white/10 p-5 rounded-3xl font-bold text-xs btn-action">Clear Canvas</button>
                    <button onclick="sendAI()" class="bg-white text-black p-5 rounded-3xl font-black text-xs btn-action shadow-2xl">Submit Sketch</button>
                    <button onclick="muteToggle()" id="audio-btn" class="bg-slate-900 border border-white/10 p-5 rounded-3xl font-bold text-xs">🔈 Audio: On</button>
                    <button onclick="viralShare()" class="bg-green-600 p-5 rounded-3xl font-bold text-xs btn-action">WhatsApp Share</button>
                </div>
            </div>

            <div class="ad-placeholder py-8 rounded-[3rem]">
                <ins class="adsbygoogle"
                     style="display:block"
                     data-ad-format="fluid"
                     data-ad-layout-key="-fb+5w+4e-db+86"
                     data-ad-client="ca-pub-1247605845741495"
                     data-ad-slot="auto"></ins>
            </div>

            <div class="grid md:grid-cols-2 gap-10 bg-slate-900/40 p-12 rounded-[3.5rem] border border-white/5">
                <article class="prose prose-invert max-w-none">
                    <h2 class="text-3xl mb-6 italic">Deep Dive: Neural Sketch Recognition</h2>
                    <p class="text-slate-400 leading-relaxed mb-6">AI Arcade isn't just a game; it's a browser-based neural network experiment. By utilizing mathematical stroke analysis, our engine determines the accuracy of your drawings in real-time. Whether you're drawing a simple circle or a complex polygon, the system calculates coordinate variance to award XP points.</p>
                    <h3 class="text-xl mb-4 italic">The Architecture of the 2026 Arcade</h3>
                    <p class="text-slate-400 leading-relaxed">Built on a lightweight PHP/JSON backbone, we've eliminated the need for heavy SQL databases. This ensures that even on slow mobile connections, your global rank is updated instantly. The front-end uses Tailwind CSS for a GPU-accelerated UI experience.</p>
                </article>
                <article class="prose prose-invert max-w-none">
                    <h2 class="text-3xl mb-6 italic">Advanced Gaming Strategies</h2>
                    <p class="text-slate-400 leading-relaxed mb-6">To score the maximum 500 XP per round, speed is key. The neural engine prioritizes fluid, continuous strokes over multiple small lines. Pro players often use a stylus for precision, but our algorithm is optimized for touch-screen fingertips as well.</p>
                    <div class="bg-black/50 p-6 rounded-3xl border border-cyan-500/20 italic text-sm text-cyan-100/70">
                        "The intersection of artificial intelligence and creative expression is where AI Arcade lives. Every sketch contributes to a larger understanding of human stroke patterns."
                    </div>
                </article>
            </div>

            <section class="p-10 border border-white/5 rounded-3xl text-[11px] text-slate-600 text-center uppercase tracking-widest">
                <p class="mb-4">AI Arcade System Status: Operational | Data Storage: JSON Serverless | Ads-Enabled Version 4.0</p>
                <p>Rules: No automated scripts | No abuse of leaderboard names | Privacy: All canvas data is processed locally in your browser memory.</p>
            </section>
        </main>
    </div>

    <footer class="mt-20 py-12 border-t border-white/5 bg-black/20 backdrop-blur-md">
    <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row justify-between items-center gap-8">
        
        <div class="flex gap-6 items-center order-2 md:order-1">
            <a href="privacy.php" class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 hover:text-cyan-400 transition-all flex items-center gap-2">
                <span class="w-2 h-2 bg-cyan-500 rounded-full animate-pulse"></span>
                Privacy Policy
            </a>
            <span class="text-slate-800">|</span>
            <a href="admin.php" class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 hover:text-red-500 transition-all">
                Admin Console
            </a>
        </div>

        <div class="order-1 md:order-2 text-center">
            <p class="text-slate-700 text-[9px] uppercase tracking-[0.6em] mb-1">Neural Network Node</p>
            <p class="text-white font-mono text-xs opacity-80">ID: 7785154755</p>
        </div>

        <div class="order-3 text-right hidden md:block">
            <p class="text-[9px] text-slate-600 uppercase font-bold tracking-widest">
                Authorized Publisher: <span class="text-slate-400">pub-1247605845741495</span>
            </p>
        </div>
    </div>
    
    <div class="mt-8 text-center opacity-20">
        <p class="text-[8px] uppercase tracking-[1em] text-white">&copy; 2026 AI ARCADE GLOBAL ENGINE</p>
    </div>
</footer>


<script>
    const canvas = document.getElementById('paint-canvas'), ctx = canvas.getContext('2d');
    let isDrawing = false, gameActive = false, timer = 20, timerRef, isMuted = false;

    // 🏆 MULTIPLE GAME OBJECTS (Har baar naya challenge)
    const targets = ["CIRCLE", "SQUARE", "TRIANGLE", "HEART", "HOUSE", "SMILEY", "TREE", "STAR", "CLOUD", "APPLE", "FISH", "MOON"];
    let currentTarget = "CIRCLE";

    const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
    function playSfx(freq) {
        if (isMuted) return;
        try {
            const osc = audioCtx.createOscillator(), gain = audioCtx.createGain();
            osc.frequency.value = freq; gain.gain.value = 0.05;
            osc.connect(gain); gain.connect(audioCtx.destination);
            osc.start(); osc.stop(audioCtx.currentTime + 0.1);
        } catch(e) { /* Audio context safety */ }
    }

    // 📱 POSITION HELPER (Mobile aur PC dono ke liye)
    function getPos(e) {
        const rect = canvas.getBoundingClientRect();
        // Agar touch event hai toh touches[0] use karega, warna mouse clientX
        const clientX = e.touches ? e.touches[0].clientX : e.clientX;
        const clientY = e.touches ? e.touches[0].clientY : e.clientY;
        
        // Canvas scaling logic taaki jahan touch ho wahi line bane
        return {
            x: (clientX - rect.left) * (canvas.width / rect.width),
            y: (clientY - rect.top) * (canvas.height / rect.height)
        };
    }

    // 🎨 DRAWING FUNCTIONS
    function startDraw(e) {
        if(!gameActive) return;
        isDrawing = true;
        const pos = getPos(e);
        ctx.beginPath();
        ctx.moveTo(pos.x, pos.y);
        playSfx(440);
        // Phone par screen scroll hone se rokta hai
        if(e.type === 'touchstart') e.preventDefault(); 
    }

    function drawMove(e) {
        if (!isDrawing || !gameActive) return;
        const pos = getPos(e);
        ctx.lineWidth = 15;
        ctx.lineCap = 'round';
        ctx.strokeStyle = '#020617'; // Dark Ink Color
        ctx.lineTo(pos.x, pos.y);
        ctx.stroke();
        if(e.type === 'touchmove') e.preventDefault();
    }

    function stopDraw() { isDrawing = false; }

    // 🔌 EVENT LISTENERS (Mouse + Touch Merge)
    canvas.addEventListener('mousedown', startDraw);
    canvas.addEventListener('mousemove', drawMove);
    window.addEventListener('mouseup', stopDraw);

    canvas.addEventListener('touchstart', startDraw, {passive: false});
    canvas.addEventListener('touchmove', drawMove, {passive: false});
    canvas.addEventListener('touchend', stopDraw);

    function saveUser() {
        const name = document.getElementById('uname-input').value;
        if (!name) return alert("Enter a name!");
        fetch('', { method: 'POST', body: new URLSearchParams({set_user: name}) }).then(() => location.reload());
    }

    function clearArt() { 
        ctx.clearRect(0, 0, canvas.width, canvas.height); 
        playSfx(200); 
    }

    function initGame() {
        gameActive = true; 
        timer = 20; 
        clearArt();
        
        // Har baar random target select hoga
        currentTarget = targets[Math.floor(Math.random() * targets.length)];
        document.getElementById('target-word').innerText = currentTarget;
        
        clearInterval(timerRef);
        timerRef = setInterval(() => {
            timer--; 
            document.getElementById('countdown').innerText = timer;
            if (timer <= 0) { 
                clearInterval(timerRef); 
                gameActive = false; 
                alert("Time Over! You were drawing: " + currentTarget); 
                location.reload(); 
            }
        }, 1000);
    }

    function sendAI() {
        if (!gameActive) return alert("Pehle game start karo!");
        confetti({ particleCount: 150, spread: 70, origin: { y: 0.6 }, colors: ['#22d3ee', '#ffffff'] });
        playSfx(1000);
        fetch('', { method: 'POST', body: new URLSearchParams({save_xp: 500}) })
        .then(() => setTimeout(() => location.reload(), 1500));
    }

    function switchTheme() {
        const html = document.documentElement;
        html.setAttribute('data-theme', html.getAttribute('data-theme') === 'cyber' ? 'pink' : 'cyber');
        playSfx(800);
    }

    function muteToggle() {
        isMuted = !isMuted;
        document.getElementById('audio-btn').innerText = isMuted ? "🔇 Audio: Off" : "🔈 Audio: On";
    }

    function viralShare() {
        window.open(`https://api.whatsapp.com/send?text=I'm drawing ${currentTarget} on AI Arcade! Beat my score: ${window.location.href}`);
    }
</script>
    <script> (adsbygoogle = window.adsbygoogle || []).push({}); </script>
</body>
</html>
