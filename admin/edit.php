<?php
// admin/edit.php
session_start();
require_once '../includes/functions.php';
requireAdmin();
$id   = (int)($_GET['id'] ?? 0);
$db   = getDB();
$s    = $db->prepare("SELECT * FROM vpn_files WHERE id=?"); $s->execute([$id]); $file = $s->fetch();
if (!$file) { header('Location: files.php'); exit; }
$apps = getApps();
$msg  = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title  = trim($_POST['title'] ?? '');
    $desc   = trim($_POST['description'] ?? '');
    $appId  = (int)($_POST['app_id'] ?? $file['app_id']);
    $pass   = trim($_POST['password'] ?? '');
    $server = trim($_POST['server'] ?? '');
    $sort   = (int)($_POST['sort_order'] ?? 0);

    $db->prepare("UPDATE vpn_files SET title=?,description=?,app_id=?,password=?,server=?,sort_order=? WHERE id=?")
       ->execute([$title,$desc,$appId,$pass,$server,$sort,$id]);

    if (!empty($_FILES['vpn_file']['name'])) {
        $f2  = $_FILES['vpn_file'];
        $ext = strtolower(pathinfo($f2['name'], PATHINFO_EXTENSION));
        $ok  = ['ehi','npx','ovpn','conf','zip','json','txt','vnn','bin','cfg'];
        if (in_array($ext, $ok) && $f2['size'] <= MAX_FILE_SIZE) {
            $nn = uniqid('vpn_',true).'.'.$ext;
            if (move_uploaded_file($f2['tmp_name'], UPLOAD_DIR.$nn)) {
                @unlink(UPLOAD_DIR.$file['filename']);
                $db->prepare("UPDATE vpn_files SET filename=?,original_name=?,file_size=? WHERE id=?")->execute([$nn,$f2['name'],$f2['size'],$id]);
            }
        }
    }
    $msg = 'Guardado!';
    $s->execute([$id]); $file = $s->fetch();
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Editar — Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
<style>
:root{--bg:#050810;--bg2:#090d18;--card:#0c1220;--b:rgba(255,255,255,.07);--cyan:#00e5ff;--green:#00ff88;--text:#f0f4ff;--muted:#4a5568;--red:#ef4444}
*{box-sizing:border-box;margin:0;padding:0}
body{background:var(--bg);color:var(--text);font-family:'DM Sans',sans-serif;min-height:100vh;display:flex}
.sidebar{width:220px;flex-shrink:0;background:var(--bg2);border-right:1px solid var(--b);min-height:100vh;position:sticky;top:0;display:flex;flex-direction:column}
.sb-logo{padding:22px 18px;border-bottom:1px solid var(--b)}.sb-logo a{font-family:'Bebas Neue',sans-serif;font-size:1.1rem;letter-spacing:2px;color:var(--cyan);text-decoration:none}.sb-logo p{font-size:.7rem;color:var(--muted);margin-top:2px}
.sb-nav{flex:1;padding:14px 10px}.nb{display:flex;align-items:center;gap:9px;padding:9px 10px;border-radius:8px;color:var(--muted);text-decoration:none;font-size:.82rem;font-weight:500;margin-bottom:2px;transition:all .2s;border:1px solid transparent}.nb:hover,.nb.on{background:rgba(0,229,255,.07);color:var(--cyan);border-color:rgba(0,229,255,.15)}
.sb-foot{padding:14px 10px;border-top:1px solid var(--b)}.logout{display:block;text-align:center;color:var(--muted);font-size:.78rem;text-decoration:none;padding:8px;border-radius:6px;border:1px solid var(--b)}.logout:hover{border-color:var(--red);color:var(--red)}
.main{flex:1;min-width:0}.topbar{padding:18px 28px;border-bottom:1px solid var(--b);display:flex;align-items:center;gap:16px}.topbar h1{font-family:'Bebas Neue',sans-serif;font-size:1.2rem;letter-spacing:2px}
.content{padding:22px 28px;max-width:660px}
.card{background:var(--card);border:1px solid var(--b);border-radius:14px;padding:24px;margin-bottom:16px}.ct{font-size:.85rem;font-weight:600;color:var(--cyan);margin-bottom:16px}
.fg{display:flex;flex-direction:column;gap:6px;margin-bottom:13px}.fgrid{display:grid;grid-template-columns:1fr 1fr;gap:13px}
label{font-size:.73rem;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:.5px}
input,select,textarea{background:rgba(255,255,255,.04);border:1px solid var(--b);border-radius:10px;padding:10px 13px;color:var(--text);font-size:.87rem;font-family:'DM Sans',sans-serif;outline:none;transition:all .2s;width:100%}
input:focus,select:focus,textarea:focus{border-color:var(--cyan);box-shadow:0 0 0 3px rgba(0,229,255,.07)}
select option{background:#0c1220}
textarea{resize:vertical;min-height:72px}
.btn{display:inline-flex;align-items:center;gap:6px;padding:10px 20px;border-radius:9px;font-size:.85rem;font-weight:600;border:none;cursor:pointer;transition:all .2s;font-family:'DM Sans',sans-serif;text-decoration:none}
.btn-primary{background:linear-gradient(135deg,var(--cyan),#00b4cc);color:#000}.btn-primary:hover{filter:brightness(1.1)}
.btn-back{background:rgba(255,255,255,.05);border:1px solid var(--b);color:var(--muted)}
.alert{border-radius:10px;padding:11px 15px;font-size:.83rem;margin-bottom:16px;background:rgba(0,255,136,.08);border:1px solid rgba(0,255,136,.25);color:var(--green)}
@media(max-width:768px){.sidebar{display:none}.fgrid{grid-template-columns:1fr}}
</style>
</head>
<body>
<aside class="sidebar">
  <div class="sb-logo"><a href="index.php">⚙ ADMIN</a><p><?= SITE_NAME ?></p></div>
  <nav class="sb-nav">
    <a href="index.php" class="nb">📊 Dashboard</a>
    <a href="upload.php" class="nb">⬆ Enviar Ficheiro</a>
    <a href="files.php" class="nb on">📁 Ficheiros</a>
    <a href="../index.php" class="nb">🌐 Ver Site</a>
  </nav>
  <div class="sb-foot"><a href="logout.php" class="logout">⏻ Sair</a></div>
</aside>
<div class="main">
  <div class="topbar">
    <a href="files.php" class="btn btn-back">← Voltar</a>
    <h1>Editar Ficheiro</h1>
  </div>
  <div class="content">
    <?php if ($msg): ?><div class="alert">✅ <?= h($msg) ?></div><?php endif; ?>
    <form method="POST" enctype="multipart/form-data">
      <div class="card">
        <div class="ct">📋 Informações</div>
        <div class="fgrid">
          <div class="fg" style="grid-column:1/-1">
            <label>App VPN</label>
            <select name="app_id">
              <?php foreach ($apps as $a): ?>
              <option value="<?= $a['id'] ?>" <?= $a['id']==$file['app_id']?'selected':'' ?>><?= $a['icon'] ?> <?= h($a['name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="fg" style="grid-column:1/-1">
            <label>Título *</label>
            <input type="text" name="title" value="<?= h($file['title']) ?>" required>
          </div>
          <div class="fg">
            <label>Palavra-passe</label>
            <input type="text" name="password" value="<?= h($file['password']) ?>">
          </div>
          <div class="fg">
            <label>Servidor</label>
            <input type="text" name="server" value="<?= h($file['server']) ?>">
          </div>
          <div class="fg">
            <label>Ordem</label>
            <input type="number" name="sort_order" value="<?= $file['sort_order'] ?>" min="0" max="99">
          </div>
          <div class="fg" style="grid-column:1/-1">
            <label>Descrição</label>
            <textarea name="description"><?= h($file['description'] ?? '') ?></textarea>
          </div>
        </div>
      </div>
      <div class="card">
        <div class="ct">📦 Substituir Ficheiro (opcional)</div>
        <p style="font-size:.8rem;color:var(--muted);margin-bottom:12px">Actual: <strong style="color:var(--cyan)"><?= h($file['original_name']) ?></strong> (<?= formatBytes($file['file_size']) ?>)</p>
        <input type="file" name="vpn_file" accept=".ehi,.npx,.ovpn,.conf,.zip,.json,.txt,.vnn,.bin,.cfg">
      </div>
      <button type="submit" class="btn btn-primary">💾 Guardar</button>
    </form>
  </div>
</div>
</body>
</html>
