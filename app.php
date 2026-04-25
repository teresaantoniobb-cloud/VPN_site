<?php
require_once 'includes/functions.php';
$slug = trim($_GET['slug'] ?? '');
$app  = $slug ? getAppBySlug($slug) : null;
if (!$app) { header('Location: index.php'); exit; }
$files = getFilesByApp($app['id']);
?>
<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= h($app['name']) ?> — <?= SITE_NAME ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:wght@300;400;500;600&family=JetBrains+Mono:wght@500&display=swap" rel="stylesheet">
<style>
:root{
  --bg:#050810;--bg2:#090d18;--card:#0c1220;--b:rgba(255,255,255,.07);
  --text:#f0f4ff;--muted:#4a5568;--green:#00ff88;
  --ac:<?= $app['color'] ?>;
}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
body{background:var(--bg);color:var(--text);font-family:'DM Sans',sans-serif;min-height:100vh;overflow-x:hidden}
.ambient{position:fixed;inset:0;z-index:0;pointer-events:none;
  background:radial-gradient(ellipse 70% 50% at 50% -10%,color-mix(in srgb, var(--ac) 8%, transparent) 0%,transparent 70%)}
.grid-bg{position:fixed;inset:0;z-index:0;pointer-events:none;
  background-image:linear-gradient(rgba(255,255,255,.022) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.022) 1px,transparent 1px);
  background-size:48px 48px;
  mask-image:radial-gradient(ellipse 100% 80% at 50% 0%,black 30%,transparent 100%)}
.z{position:relative;z-index:1}

nav{padding:0 32px;background:rgba(5,8,16,.85);backdrop-filter:blur(20px);border-bottom:1px solid var(--b);position:sticky;top:0;z-index:100}
.nav-inner{max-width:1060px;margin:0 auto;display:flex;align-items:center;justify-content:space-between;height:64px}
.logo{font-family:'Bebas Neue',sans-serif;font-size:1.6rem;letter-spacing:3px;color:var(--text);text-decoration:none;display:flex;align-items:center;gap:10px}
.logo-mark{width:34px;height:34px;border-radius:8px;background:linear-gradient(135deg,var(--ac),color-mix(in srgb,var(--ac) 50%,#a855f7));display:flex;align-items:center;justify-content:center;font-size:.9rem}
.logo em{color:var(--ac);font-style:normal}
.back{display:flex;align-items:center;gap:6px;color:var(--muted);text-decoration:none;font-size:.83rem;font-weight:500;padding:7px 14px;border-radius:8px;border:1px solid var(--b);background:rgba(255,255,255,.03);transition:all .2s}
.back:hover{color:var(--text);border-color:rgba(255,255,255,.14)}

/* PAGE HERO */
.page-hero{max-width:1060px;margin:0 auto;padding:52px 32px 44px;display:flex;align-items:flex-end;gap:24px;flex-wrap:wrap}
.big-icon{width:80px;height:80px;border-radius:20px;display:flex;align-items:center;justify-content:center;font-size:2.2rem;background:color-mix(in srgb,var(--ac) 10%,transparent);border:1px solid color-mix(in srgb,var(--ac) 18%,transparent);flex-shrink:0}
.page-title{font-family:'Bebas Neue',sans-serif;font-size:clamp(2rem,5vw,3.2rem);letter-spacing:1.5px;line-height:1;margin-bottom:8px}
.page-sub{font-size:.88rem;color:var(--muted);line-height:1.6}
.page-pills{display:flex;gap:8px;flex-wrap:wrap;margin-top:12px}
.pill{background:rgba(255,255,255,.04);border:1px solid var(--b);border-radius:8px;padding:5px 12px;font-size:.75rem;color:var(--muted)}
.pill strong{color:var(--text)}

/* DIVIDER */
.divider{max-width:1060px;margin:0 auto;padding:0 32px 28px}
.div-line{height:1px;background:linear-gradient(90deg,color-mix(in srgb,var(--ac) 40%,transparent),transparent)}

/* FILES */
.files-wrap{max-width:1060px;margin:0 auto;padding:0 32px 72px;display:flex;flex-direction:column;gap:16px}
.file-card{
  background:var(--card);
  border:1px solid var(--b);
  border-radius:18px;
  overflow:hidden;
  transition:all .3s;
}
.file-card:hover{border-color:color-mix(in srgb,var(--ac) 30%,transparent);transform:translateX(4px)}
.fc-top{
  padding:20px 24px;
  display:flex;align-items:center;gap:16px;
}
.fc-num{
  font-family:'Bebas Neue',sans-serif;
  font-size:3rem;letter-spacing:1px;line-height:1;
  background:linear-gradient(135deg,var(--ac),color-mix(in srgb,var(--ac) 40%,#fff));
  -webkit-background-clip:text;-webkit-text-fill-color:transparent;
  opacity:.18;flex-shrink:0;width:48px;
}
.fc-info{flex:1;min-width:0}
.fc-title{font-weight:600;font-size:.95rem;margin-bottom:3px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.fc-meta{display:flex;gap:10px;flex-wrap:wrap}
.fc-tag{font-size:.73rem;color:var(--muted);display:flex;align-items:center;gap:4px}
.fc-actions{display:flex;gap:8px;flex-shrink:0}

/* PASSWORD REVEAL ROW */
.fc-pwd-row{
  padding:14px 24px 18px;
  border-top:1px solid rgba(255,255,255,.04);
  display:flex;align-items:center;gap:12px;flex-wrap:wrap;
}
.pwd-label{font-size:.72rem;color:var(--muted);font-weight:600;text-transform:uppercase;letter-spacing:1px;white-space:nowrap}
.pwd-val{
  font-family:'JetBrains Mono',monospace;
  font-size:.88rem;
  color:var(--ac);
  background:color-mix(in srgb,var(--ac) 8%,transparent);
  border:1px solid color-mix(in srgb,var(--ac) 15%,transparent);
  border-radius:8px;
  padding:6px 14px;
  filter:blur(5px);user-select:none;
  transition:filter .3s;flex:1;
  word-break:break-all;
}
.pwd-val.show{filter:none;user-select:text}

/* BUTTONS */
.btn{
  display:inline-flex;align-items:center;gap:7px;
  padding:9px 16px;border-radius:10px;
  font-size:.8rem;font-weight:600;
  border:1px solid var(--b);
  background:rgba(255,255,255,.04);
  color:var(--text);cursor:pointer;
  transition:all .2s;font-family:'DM Sans',sans-serif;
  text-decoration:none;white-space:nowrap;
}
.btn:hover{border-color:rgba(255,255,255,.18);background:rgba(255,255,255,.08)}
.btn-dl{
  background:var(--ac);
  border-color:var(--ac);
  color:#000;font-weight:700;
}
.btn-dl:hover{filter:brightness(1.1);transform:scale(1.02)}
.btn-pwd{
  background:color-mix(in srgb,var(--ac) 12%,transparent);
  border-color:color-mix(in srgb,var(--ac) 25%,transparent);
  color:var(--ac);
}
.btn-pwd:hover{background:color-mix(in srgb,var(--ac) 20%,transparent)}
.btn-copy{background:rgba(0,255,136,.08);border-color:rgba(0,255,136,.2);color:var(--green)}
.btn-copy:hover{background:rgba(0,255,136,.16)}
.btn-copy.ok{background:var(--green);color:#000;border-color:var(--green)}

/* EMPTY */
.empty{text-align:center;padding:80px 20px;color:var(--muted)}
.empty-icon{font-size:3.5rem;opacity:.3;margin-bottom:16px}
.empty h3{font-size:1.1rem;color:var(--text);margin-bottom:8px}

/* FOOTER */
footer{border-top:1px solid var(--b);padding:28px 32px;max-width:1060px;margin:0 auto;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px}
.footer-logo{font-family:'Bebas Neue',sans-serif;font-size:1.2rem;letter-spacing:3px;color:var(--muted)}
.footer-logo em{color:var(--ac);font-style:normal}
footer p{font-size:.75rem;color:var(--muted)}

@media(max-width:600px){
  nav{padding:0 16px}.page-hero,.divider,.files-wrap,footer{padding-left:16px;padding-right:16px}
  .fc-top{flex-wrap:wrap}.fc-actions{width:100%}.btn-dl,.btn-pwd{flex:1;justify-content:center}
  .fc-pwd-row{flex-direction:column;align-items:flex-start}
  .pwd-val{width:100%}
}
</style>
</head>
<body>
<div class="ambient"></div>
<div class="grid-bg"></div>

<nav>
  <div class="nav-inner z">
    <a href="index.php" class="logo">
      <div class="logo-mark"><?= $app['icon'] ?></div>
      VPN<em>Free</em>
    </a>
    <a href="index.php" class="back">← Voltar</a>
  </div>
</nav>

<div class="page-hero z">
  <div class="big-icon"><?= $app['icon'] ?></div>
  <div>
    <div class="page-title"><?= h($app['name']) ?></div>
    <div class="page-sub"><?= h($app['description'] ?? '') ?></div>
    <div class="page-pills">
      <div class="pill">📦 <strong><?= count($files) ?></strong> ficheiros disponíveis</div>
      <div class="pill">✅ Sempre gratuito</div>
      <?php if (count($files) > 0 && !empty($files[0]['server'])): ?>
      <div class="pill">🌍 <?= h($files[0]['server']) ?></div>
      <?php endif; ?>
    </div>
  </div>
</div>

<div class="divider z"><div class="div-line"></div></div>

<div class="files-wrap z">
  <?php if (empty($files)): ?>
  <div class="empty">
    <div class="empty-icon">📭</div>
    <h3>Sem ficheiros de momento</h3>
    <p>O administrador ainda não carregou ficheiros para esta app.</p>
  </div>
  <?php else: ?>
  <?php foreach ($files as $i => $f): ?>
  <div class="file-card">
    <div class="fc-top">
      <div class="fc-num">0<?= $i+1 ?></div>
      <div class="fc-info">
        <div class="fc-title"><?= h($f['title']) ?></div>
        <div class="fc-meta">
          <?php if ($f['server']): ?><span class="fc-tag">🌍 <?= h($f['server']) ?></span><?php endif; ?>
          <span class="fc-tag">📦 <?= formatBytes($f['file_size']) ?></span>
          <span class="fc-tag">⬇ <?= number_format($f['downloads']) ?> downloads</span>
        </div>
        <?php if ($f['description']): ?>
        <div style="font-size:.8rem;color:var(--muted);margin-top:6px;line-height:1.5"><?= h($f['description']) ?></div>
        <?php endif; ?>
      </div>
      <div class="fc-actions">
        <?php if (!empty($f['password'])): ?>
        <button class="btn btn-pwd" onclick="togglePwd(<?= $f['id'] ?>, this)">🔑 Password</button>
        <?php endif; ?>
        <a href="download.php?id=<?= $f['id'] ?>" class="btn btn-dl">⬇ Download</a>
      </div>
    </div>
    <?php if (!empty($f['password'])): ?>
    <div class="fc-pwd-row" id="pwd-row-<?= $f['id'] ?>" style="display:none">
      <span class="pwd-label">🔒 Palavra-passe:</span>
      <div class="pwd-val" id="pwd-val-<?= $f['id'] ?>"><?= h($f['password']) ?></div>
      <button class="btn btn-copy" onclick="copyPwd(<?= $f['id'] ?>, this)" id="copy-<?= $f['id'] ?>">📋 Copiar</button>
    </div>
    <?php endif; ?>
  </div>
  <?php endforeach; ?>
  <?php endif; ?>
</div>

<footer class="z">
  <div class="footer-logo">VPN<em>Free</em></div>
  <p>© <?= date('Y') ?> <?= SITE_NAME ?></p>
</footer>

<script>
function togglePwd(id, btn) {
  const row = document.getElementById('pwd-row-' + id);
  const val = document.getElementById('pwd-val-' + id);
  if (row.style.display === 'none') {
    row.style.display = 'flex';
    setTimeout(() => val.classList.add('show'), 30);
    btn.textContent = '🙈 Ocultar';
  } else {
    val.classList.remove('show');
    setTimeout(() => { row.style.display = 'none'; btn.textContent = '🔑 Password'; }, 300);
  }
}

function copyPwd(id, btn) {
  const text = document.getElementById('pwd-val-' + id).textContent.trim();
  navigator.clipboard.writeText(text).then(() => {
    btn.textContent = '✅ Copiado!';
    btn.classList.add('ok');
    setTimeout(() => { btn.textContent = '📋 Copiar'; btn.classList.remove('ok'); }, 2000);
  }).catch(() => {
    const t = document.createElement('textarea');
    t.value = text; document.body.appendChild(t); t.select();
    document.execCommand('copy'); document.body.removeChild(t);
    btn.textContent = '✅ Copiado!';
    setTimeout(() => btn.textContent = '📋 Copiar', 2000);
  });
}
</script>
</body>
</html>
