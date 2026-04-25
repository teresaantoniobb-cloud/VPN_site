<?php
session_start();
require_once '../includes/functions.php';
requireAdmin();
$db   = getDB();
$apps = getApps();
$totalFiles = $db->query("SELECT COUNT(*) FROM vpn_files")->fetchColumn();
$totalDl    = $db->query("SELECT COALESCE(SUM(downloads),0) FROM vpn_files")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Dashboard — Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
<style>
:root{--bg:#050810;--bg2:#090d18;--card:#0c1220;--b:rgba(255,255,255,.07);--cyan:#00e5ff;--green:#00ff88;--orange:#ff6b35;--purple:#bd5fff;--text:#f0f4ff;--muted:#4a5568;--red:#ef4444}
*{box-sizing:border-box;margin:0;padding:0}
body{background:var(--bg);color:var(--text);font-family:'DM Sans',sans-serif;min-height:100vh;display:flex}
.sidebar{width:220px;flex-shrink:0;background:var(--bg2);border-right:1px solid var(--b);min-height:100vh;position:sticky;top:0;display:flex;flex-direction:column;z-index:10}
.sb-logo{padding:22px 18px;border-bottom:1px solid var(--b)}
.sb-logo a{font-family:'Bebas Neue',sans-serif;font-size:1.1rem;letter-spacing:2px;color:var(--cyan);text-decoration:none}
.sb-logo p{font-size:.7rem;color:var(--muted);margin-top:2px}
.sb-nav{flex:1;padding:14px 10px}
.nb{display:flex;align-items:center;gap:9px;padding:9px 10px;border-radius:8px;color:var(--muted);text-decoration:none;font-size:.82rem;font-weight:500;margin-bottom:2px;transition:all .2s;border:1px solid transparent}
.nb:hover,.nb.on{background:rgba(0,229,255,.07);color:var(--cyan);border-color:rgba(0,229,255,.15)}
.sb-foot{padding:14px 10px;border-top:1px solid var(--b)}
.logout{display:block;text-align:center;color:var(--muted);font-size:.78rem;text-decoration:none;padding:8px;border-radius:6px;border:1px solid var(--b);transition:all .2s}
.logout:hover{border-color:var(--red);color:var(--red)}
.main{flex:1;min-width:0}
.topbar{padding:18px 28px;border-bottom:1px solid var(--b);display:flex;align-items:center;justify-content:space-between}
.topbar h1{font-family:'Bebas Neue',sans-serif;font-size:1.2rem;letter-spacing:2px}
.btn{display:inline-flex;align-items:center;gap:6px;padding:9px 18px;border-radius:9px;font-size:.82rem;font-weight:600;border:none;cursor:pointer;text-decoration:none;transition:all .2s;font-family:'DM Sans',sans-serif}
.btn-primary{background:linear-gradient(135deg,var(--cyan),#00b4cc);color:#000}
.btn-primary:hover{filter:brightness(1.1)}
.content{padding:22px 28px}
.stats{display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-bottom:24px}
.scard{background:var(--card);border:1px solid var(--b);border-radius:14px;padding:18px;display:flex;align-items:center;gap:14px}
.s-icon{width:44px;height:44px;border-radius:11px;display:flex;align-items:center;justify-content:center;font-size:1.2rem;flex-shrink:0}
.s-val{font-family:'Bebas Neue',sans-serif;font-size:1.8rem;letter-spacing:1px;line-height:1}
.s-lbl{font-size:.72rem;color:var(--muted);text-transform:uppercase;letter-spacing:.5px;margin-top:2px}
.apps-list{display:flex;flex-direction:column;gap:12px}
.app-row{background:var(--card);border:1px solid var(--b);border-radius:12px;padding:16px 20px;display:flex;align-items:center;gap:16px}
.app-icon{width:42px;height:42px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.2rem;flex-shrink:0}
.app-inf{flex:1}
.app-nm{font-weight:600;font-size:.9rem}
.app-fc{font-size:.78rem;color:var(--muted);margin-top:2px}
.app-acts{display:flex;gap:8px}
.btn-sm{padding:7px 13px;font-size:.75rem;border-radius:7px}
.btn-sec{background:rgba(0,229,255,.08);border:1px solid rgba(0,229,255,.2);color:var(--cyan)}
@media(max-width:768px){.sidebar{display:none}.stats{grid-template-columns:1fr 1fr}}
</style>
</head>
<body>
<aside class="sidebar">
  <div class="sb-logo"><a href="index.php">⚙ ADMIN</a><p><?= SITE_NAME ?></p></div>
  <nav class="sb-nav">
    <a href="index.php" class="nb on">📊 Dashboard</a>
    <a href="upload.php" class="nb">⬆ Enviar Ficheiro</a>
    <a href="files.php" class="nb">📁 Ficheiros</a>
    <a href="../index.php" class="nb">🌐 Ver Site</a>
  </nav>
  <div class="sb-foot"><a href="logout.php" class="logout">⏻ Sair</a></div>
</aside>
<div class="main">
  <div class="topbar">
    <h1>Dashboard</h1>
    <a href="upload.php" class="btn btn-primary">⬆ Novo Ficheiro</a>
  </div>
  <div class="content">
    <div class="stats">
      <div class="scard">
        <div class="s-icon" style="background:rgba(0,229,255,.1);border:1px solid rgba(0,229,255,.2)">📁</div>
        <div><div class="s-val" style="color:var(--cyan)"><?= $totalFiles ?></div><div class="s-lbl">Ficheiros Totais</div></div>
      </div>
      <div class="scard">
        <div class="s-icon" style="background:rgba(0,255,136,.1);border:1px solid rgba(0,255,136,.2)">⬇</div>
        <div><div class="s-val" style="color:var(--green)"><?= number_format($totalDl) ?></div><div class="s-lbl">Downloads</div></div>
      </div>
      <div class="scard">
        <div class="s-icon" style="background:rgba(189,95,255,.1);border:1px solid rgba(189,95,255,.2)">📱</div>
        <div><div class="s-val" style="color:var(--purple)"><?= count($apps) ?></div><div class="s-lbl">Apps VPN</div></div>
      </div>
    </div>

    <div style="font-family:'Bebas Neue',sans-serif;font-size:.85rem;letter-spacing:3px;color:var(--muted);margin-bottom:14px">APPS VPN</div>
    <div class="apps-list">
      <?php foreach ($apps as $app):
        $cnt = countFilesByApp($app['id']);
      ?>
      <div class="app-row">
        <div class="app-icon" style="background:color-mix(in srgb,<?= $app['color'] ?> 10%,transparent);border:1px solid color-mix(in srgb,<?= $app['color'] ?> 20%,transparent)"><?= $app['icon'] ?></div>
        <div class="app-inf">
          <div class="app-nm"><?= h($app['name']) ?></div>
          <div class="app-fc"><?= $cnt ?>/5 ficheiros carregados</div>
        </div>
        <div class="app-acts">
          <a href="upload.php?app=<?= $app['id'] ?>" class="btn btn-sm btn-primary">⬆ Upload</a>
          <a href="files.php?app=<?= $app['id'] ?>" class="btn btn-sm btn-sec">📁 Ver</a>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>
</body>
</html>
