<?php
session_start();
require_once '../includes/functions.php';
requireAdmin();
$apps   = getApps();
$selApp = (int)($_GET['app'] ?? 0);
$db     = getDB();

$where  = $selApp ? "WHERE f.app_id=$selApp" : '';
$files  = $db->query("SELECT f.*,a.name app_name,a.icon app_icon,a.color app_color FROM vpn_files f JOIN apps a ON a.id=f.app_id $where ORDER BY f.app_id,f.sort_order,f.created_at DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Ficheiros — Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
<style>
:root{--bg:#050810;--bg2:#090d18;--card:#0c1220;--b:rgba(255,255,255,.07);--cyan:#00e5ff;--green:#00ff88;--text:#f0f4ff;--muted:#4a5568;--red:#ef4444;--purple:#bd5fff}
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
.topbar{padding:18px 28px;border-bottom:1px solid var(--b);display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap}
.topbar h1{font-family:'Bebas Neue',sans-serif;font-size:1.2rem;letter-spacing:2px}
.filter-btns{display:flex;gap:6px;flex-wrap:wrap}
.fbtn{padding:6px 13px;border-radius:7px;font-size:.75rem;font-weight:600;text-decoration:none;border:1px solid var(--b);background:rgba(255,255,255,.03);color:var(--muted);transition:all .2s}
.fbtn:hover,.fbtn.on{background:rgba(0,229,255,.08);border-color:rgba(0,229,255,.2);color:var(--cyan)}
.content{padding:22px 28px;overflow-x:auto}
.card{background:var(--card);border:1px solid var(--b);border-radius:14px;overflow:hidden}
table{width:100%;border-collapse:collapse;min-width:700px}
th{padding:9px 14px;text-align:left;font-size:.7rem;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:.5px;border-bottom:1px solid var(--b);background:rgba(255,255,255,.02)}
td{padding:11px 14px;font-size:.82rem;border-bottom:1px solid rgba(255,255,255,.03);vertical-align:middle}
tr:last-child td{border-bottom:none}
tr:hover td{background:rgba(255,255,255,.018)}
.pwd-val{font-family:monospace;background:rgba(189,95,255,.08);border:1px solid rgba(189,95,255,.18);color:var(--purple);padding:3px 9px;border-radius:6px;font-size:.78rem}
.btn{display:inline-flex;align-items:center;gap:4px;padding:6px 11px;border-radius:7px;font-size:.74rem;font-weight:600;text-decoration:none;border:1px solid transparent;cursor:pointer;transition:all .2s;font-family:'DM Sans',sans-serif}
.btn-edit{background:rgba(0,229,255,.07);border-color:rgba(0,229,255,.18);color:var(--cyan)}
.btn-del{background:rgba(239,68,68,.07);border-color:rgba(239,68,68,.18);color:var(--red)}
.btn-del:hover{background:rgba(239,68,68,.18)}
.acts{display:flex;gap:6px}
.app-chip{display:inline-flex;align-items:center;gap:5px;padding:3px 9px;border-radius:6px;font-size:.73rem;font-weight:600}
@media(max-width:768px){.sidebar{display:none}.content{padding:14px}}
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
    <h1>Ficheiros (<?= count($files) ?>)</h1>
    <div class="filter-btns">
      <a href="files.php" class="fbtn <?= !$selApp?'on':'' ?>">Todos</a>
      <?php foreach($apps as $a): ?>
      <a href="files.php?app=<?= $a['id'] ?>" class="fbtn <?= $selApp==$a['id']?'on':'' ?>"><?= $a['icon'] ?> <?= h($a['name']) ?></a>
      <?php endforeach; ?>
    </div>
  </div>
  <div class="content">
    <div class="card">
      <table>
        <thead><tr><th>Título</th><th>App</th><th>Password</th><th>Servidor</th><th>Tamanho</th><th>Downloads</th><th>Ordem</th><th>Acções</th></tr></thead>
        <tbody>
        <?php foreach ($files as $f): ?>
        <tr>
          <td><?= h($f['title']) ?></td>
          <td><span class="app-chip" style="background:color-mix(in srgb,<?= $f['app_color'] ?> 12%,transparent);color:<?= $f['app_color'] ?>"><?= $f['app_icon'] ?> <?= h($f['app_name']) ?></span></td>
          <td><?= $f['password'] ? '<span class="pwd-val">'.h($f['password']).'</span>' : '<span style="color:var(--muted)">—</span>' ?></td>
          <td style="color:var(--muted)"><?= $f['server'] ? h($f['server']) : '—' ?></td>
          <td style="color:var(--muted)"><?= formatBytes($f['file_size']) ?></td>
          <td><?= $f['downloads'] ?></td>
          <td><?= $f['sort_order'] ?></td>
          <td>
            <div class="acts">
              <a href="edit.php?id=<?= $f['id'] ?>" class="btn btn-edit">✏ Editar</a>
              <a href="delete.php?id=<?= $f['id'] ?>" class="btn btn-del" onclick="return confirm('Eliminar este ficheiro?')">🗑</a>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($files)): ?>
        <tr><td colspan="8" style="text-align:center;padding:40px;color:var(--muted)">Nenhum ficheiro encontrado.</td></tr>
        <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
</body>
</html>
