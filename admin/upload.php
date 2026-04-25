<?php
session_start();
require_once '../includes/functions.php';
requireAdmin();
$apps = getApps();
$preApp = (int)($_GET['app'] ?? 0);
$msg = $err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $appId  = (int)($_POST['app_id'] ?? 0);
    $title  = trim($_POST['title'] ?? '');
    $desc   = trim($_POST['description'] ?? '');
    $pass   = trim($_POST['password'] ?? '');
    $server = trim($_POST['server'] ?? '');
    $sort   = (int)($_POST['sort_order'] ?? 0);

    if (!$appId || !$title || empty($_FILES['vpn_file']['name'])) {
        $err = 'Preenche todos os campos obrigatórios e selecciona um ficheiro.';
    } else {
        // Verificar limite de 5 ficheiros
        $cnt = countFilesByApp($appId);
        if ($cnt >= 5) {
            $err = 'Esta app já tem 5 ficheiros. Elimina um antes de adicionar novo.';
        } else {
            $f   = $_FILES['vpn_file'];
            $ext = strtolower(pathinfo($f['name'], PATHINFO_EXTENSION));
            $allowed = ['ehi','npv','maya','conf','zip','json','txt','bdnet','apnalite','cfg'];
            if (!in_array($ext, $allowed)) {
                $err = 'Extensão não permitida: .'.$ext;
            } elseif ($f['size'] > MAX_FILE_SIZE) {
                $err = 'Ficheiro demasiado grande (máx 50MB).';
            } else {
                $newName = uniqid('vpn_',true).'.'.$ext;
                if (!is_dir(UPLOAD_DIR)) mkdir(UPLOAD_DIR, 0755, true);
                if (move_uploaded_file($f['tmp_name'], UPLOAD_DIR.$newName)) {
                    getDB()->prepare("INSERT INTO vpn_files (app_id,title,description,filename,original_name,file_size,password,server,sort_order) VALUES (?,?,?,?,?,?,?,?,?)")
                        ->execute([$appId,$title,$desc,$newName,$f['name'],$f['size'],$pass,$server,$sort]);
                    $msg = 'Ficheiro carregado com sucesso!';
                    $preApp = $appId;
                } else {
                    $err = 'Erro ao mover ficheiro. Verifica permissões da pasta uploads/.';
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Upload — Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
<style>
:root{--bg:#050810;--bg2:#090d18;--card:#0c1220;--b:rgba(255,255,255,.07);--cyan:#00e5ff;--green:#00ff88;--text:#f0f4ff;--muted:#4a5568;--red:#ef4444}
*{box-sizing:border-box;margin:0;padding:0}
body{background:var(--bg);color:var(--text);font-family:'DM Sans',sans-serif;min-height:100vh;display:flex}
.sidebar{width:220px;flex-shrink:0;background:var(--bg2);border-right:1px solid var(--b);min-height:100vh;position:sticky;top:0;display:flex;flex-direction:column}
.sb-logo{padding:22px 18px;border-bottom:1px solid var(--b)}
.sb-logo a{font-family:'Bebas Neue',sans-serif;font-size:1.1rem;letter-spacing:2px;color:var(--cyan);text-decoration:none}
.sb-logo p{font-size:.7rem;color:var(--muted);margin-top:2px}
.sb-nav{flex:1;padding:14px 10px}
.nb{display:flex;align-items:center;gap:9px;padding:9px 10px;border-radius:8px;color:var(--muted);text-decoration:none;font-size:.82rem;font-weight:500;margin-bottom:2px;transition:all .2s;border:1px solid transparent}
.nb:hover,.nb.on{background:rgba(0,229,255,.07);color:var(--cyan);border-color:rgba(0,229,255,.15)}
.sb-foot{padding:14px 10px;border-top:1px solid var(--b)}
.logout{display:block;text-align:center;color:var(--muted);font-size:.78rem;text-decoration:none;padding:8px;border-radius:6px;border:1px solid var(--b)}
.logout:hover{border-color:var(--red);color:var(--red)}
.main{flex:1;min-width:0}
.topbar{padding:18px 28px;border-bottom:1px solid var(--b)}
.topbar h1{font-family:'Bebas Neue',sans-serif;font-size:1.2rem;letter-spacing:2px}
.content{padding:22px 28px;max-width:680px}
.card{background:var(--card);border:1px solid var(--b);border-radius:14px;padding:24px;margin-bottom:18px}
.ct{font-size:.85rem;font-weight:600;color:var(--cyan);margin-bottom:18px}
.fg{display:flex;flex-direction:column;gap:6px;margin-bottom:14px}
.fgrid{display:grid;grid-template-columns:1fr 1fr;gap:14px}
label{font-size:.73rem;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:.5px}
input,select,textarea{background:rgba(255,255,255,.04);border:1px solid var(--b);border-radius:10px;padding:10px 13px;color:var(--text);font-size:.87rem;font-family:'DM Sans',sans-serif;outline:none;transition:all .2s;width:100%}
input:focus,select:focus,textarea:focus{border-color:var(--cyan);box-shadow:0 0 0 3px rgba(0,229,255,.07)}
select option{background:#0c1220}
textarea{resize:vertical;min-height:72px}
.drop{border:2px dashed rgba(0,229,255,.2);border-radius:12px;padding:36px;text-align:center;cursor:pointer;transition:all .2s;background:rgba(0,229,255,.02)}
.drop:hover,.drop.on{border-color:var(--cyan);background:rgba(0,229,255,.05)}
.drop-ico{font-size:2.2rem;margin-bottom:8px}
.drop p{font-size:.82rem;color:var(--muted);margin-top:6px}
.btn{display:inline-flex;align-items:center;gap:6px;padding:11px 22px;border-radius:10px;font-size:.87rem;font-weight:600;border:none;cursor:pointer;transition:all .2s;font-family:'DM Sans',sans-serif}
.btn-primary{background:linear-gradient(135deg,var(--cyan),#00b4cc);color:#000}
.btn-primary:hover{filter:brightness(1.1);transform:translateY(-1px)}
.alert{border-radius:10px;padding:11px 15px;font-size:.83rem;margin-bottom:16px}
.ok{background:rgba(0,255,136,.08);border:1px solid rgba(0,255,136,.25);color:var(--green)}
.er{background:rgba(239,68,68,.08);border:1px solid rgba(239,68,68,.25);color:var(--red)}
.hint{font-size:.72rem;color:var(--muted);margin-top:4px}
@media(max-width:768px){.sidebar{display:none}.fgrid{grid-template-columns:1fr}}
</style>
</head>
<body>
<aside class="sidebar">
  <div class="sb-logo"><a href="index.php">⚙ ADMIN</a><p><?= SITE_NAME ?></p></div>
  <nav class="sb-nav">
    <a href="index.php" class="nb">📊 Dashboard</a>
    <a href="upload.php" class="nb on">⬆ Enviar Ficheiro</a>
    <a href="files.php" class="nb">📁 Ficheiros</a>
    <a href="../index.php" class="nb">🌐 Ver Site</a>
  </nav>
  <div class="sb-foot"><a href="logout.php" class="logout">⏻ Sair</a></div>
</aside>
<div class="main">
  <div class="topbar"><h1>Enviar Ficheiro VPN</h1></div>
  <div class="content">
    <?php if ($msg): ?><div class="alert ok">✅ <?= h($msg) ?></div><?php endif; ?>
    <?php if ($err): ?><div class="alert er">⚠ <?= h($err) ?></div><?php endif; ?>
    <form method="POST" enctype="multipart/form-data">
      <div class="card">
        <div class="ct">📋 Detalhes</div>
        <div class="fgrid">
          <div class="fg" style="grid-column:1/-1">
            <label>App VPN *</label>
            <select name="app_id" required>
              <option value="">Seleccionar app...</option>
              <?php foreach ($apps as $a): ?>
              <option value="<?= $a['id'] ?>" <?= $a['id']==$preApp?'selected':'' ?>><?= $a['icon'] ?> <?= h($a['name']) ?> (<?= countFilesByApp($a['id']) ?>/5)</option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="fg" style="grid-column:1/-1">
            <label>Título *</label>
            <input type="text" name="title" placeholder="Ex: Configuração Angola TPA #1" required>
          </div>
          <div class="fg">
            <label>Palavra-passe (opcional)</label>
            <input type="text" name="password" placeholder="Deixa vazio se não tiver">
          </div>
          <div class="fg">
            <label>Servidor / Operadora</label>
            <input type="text" name="server" placeholder="Ex: UNITEL, MOVICEL">
          </div>
          <div class="fg">
            <label>Ordem de exibição</label>
            <input type="number" name="sort_order" value="0" min="0" max="99">
            <span class="hint">0 = primeiro · 4 = último</span>
          </div>
          <div class="fg" style="grid-column:1/-1">
            <label>Descrição (opcional)</label>
            <textarea name="description" placeholder="Notas sobre esta configuração..."></textarea>
          </div>
        </div>
      </div>
      <div class="card">
        <div class="ct">📦 Ficheiro</div>
        <div class="drop" id="drop" onclick="document.getElementById('fi').click()">
          <div class="drop-ico">⬆️</div>
          <strong id="fn">Clica ou arrasta o ficheiro aqui</strong>
          <p>.ehi · .npx · .ovpn · .conf · .zip · .json · .txt · .vnn · .bin · .cfg — Máx 50MB</p>
        </div>
        <input type="file" id="fi" name="vpn_file" style="display:none" required
          accept=".ehi,.npx,.ovpn,.conf,.zip,.json,.txt,.vnn,.bin,.cfg"
          onchange="document.getElementById('fn').textContent='📄 '+this.files[0].name">
      </div>
      <button type="submit" class="btn btn-primary">⬆ Enviar Ficheiro</button>
    </form>
  </div>
</div>
<script>
const d=document.getElementById('drop');
d.addEventListener('dragover',e=>{e.preventDefault();d.classList.add('on')});
d.addEventListener('dragleave',()=>d.classList.remove('on'));
d.addEventListener('drop',e=>{e.preventDefault();d.classList.remove('on');const fi=document.getElementById('fi');fi.files=e.dataTransfer.files;document.getElementById('fn').textContent='📄 '+fi.files[0].name});
</script>
</body>
</html>
