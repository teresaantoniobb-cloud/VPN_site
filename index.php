<?php
require_once 'includes/functions.php';
$apps = getApps();
?>
<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= SITE_NAME ?> — Configurações VPN Gratuitas</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Clash+Display:wght@400;500;600;700&family=Cabinet+Grotesk:wght@300;400;500;700;800&display=swap" rel="stylesheet">
<style>
@import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:wght@300;400;500;600&display=swap');
:root {
  --bg:      #050810;
  --bg2:     #090d18;
  --card:    #0c1220;
  --card2:   #101828;
  --b:       rgba(255,255,255,.07);
  --b2:      rgba(255,255,255,.04);
  --cyan:    #00e5ff;
  --green:   #00ff88;
  --orange:  #ff6b35;
  --purple:  #bd5fff;
  --text:    #f0f4ff;
  --muted:   #4a5568;
  --muted2:  #2d3748;
}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
html{scroll-behavior:smooth}
body{
  background:var(--bg);
  color:var(--text);
  font-family:'DM Sans',sans-serif;
  min-height:100vh;
  overflow-x:hidden;
}

/* AMBIENT BACKGROUND */
.ambient{
  position:fixed;inset:0;z-index:0;pointer-events:none;
  background:
    radial-gradient(ellipse 80% 50% at 20% 0%, rgba(0,229,255,.045) 0%, transparent 60%),
    radial-gradient(ellipse 60% 40% at 80% 100%, rgba(189,95,255,.04) 0%, transparent 60%),
    radial-gradient(ellipse 40% 30% at 50% 50%, rgba(0,255,136,.02) 0%, transparent 70%);
}
.grid-bg{
  position:fixed;inset:0;z-index:0;pointer-events:none;
  background-image:
    linear-gradient(rgba(255,255,255,.025) 1px, transparent 1px),
    linear-gradient(90deg, rgba(255,255,255,.025) 1px, transparent 1px);
  background-size:48px 48px;
  mask-image:radial-gradient(ellipse 100% 100% at 50% 0%, black 40%, transparent 100%);
}
.z{position:relative;z-index:1}

/* ── NAV ── */
nav{
  padding:0 32px;
  background:rgba(5,8,16,.8);
  backdrop-filter:blur(20px);
  border-bottom:1px solid var(--b);
  position:sticky;top:0;z-index:100;
}
.nav-inner{
  max-width:1140px;margin:0 auto;
  display:flex;align-items:center;justify-content:space-between;
  height:66px;
}
.logo{
  font-family:'Bebas Neue',sans-serif;
  font-size:1.7rem;letter-spacing:3px;
  color:var(--text);text-decoration:none;
  display:flex;align-items:center;gap:12px;
}
.logo-mark{
  width:38px;height:38px;border-radius:10px;
  background:linear-gradient(135deg,var(--cyan),var(--purple));
  display:flex;align-items:center;justify-content:center;
  font-size:1.1rem;
}
.logo em{color:var(--cyan);font-style:normal}
.nav-badge{
  display:flex;align-items:center;gap:8px;
  background:rgba(0,255,136,.07);
  border:1px solid rgba(0,255,136,.18);
  border-radius:999px;
  padding:7px 16px;
  font-size:.78rem;font-weight:600;
  color:var(--green);
}
.pulse{
  width:7px;height:7px;border-radius:50%;
  background:var(--green);
  box-shadow:0 0 8px var(--green);
  animation:p 2s ease-in-out infinite;
}
@keyframes p{0%,100%{opacity:1;transform:scale(1)}50%{opacity:.4;transform:scale(.7)}}

/* ── HERO ── */
.hero{
  padding:100px 32px 80px;
  text-align:center;
  max-width:1140px;margin:0 auto;
}
.hero-eyebrow{
  display:inline-flex;align-items:center;gap:10px;
  background:rgba(0,229,255,.06);
  border:1px solid rgba(0,229,255,.15);
  border-radius:999px;
  padding:8px 20px;
  font-size:.78rem;font-weight:600;
  color:var(--cyan);letter-spacing:1.5px;
  text-transform:uppercase;
  margin-bottom:32px;
}
.hero h1{
  font-family:'Bebas Neue',sans-serif;
  font-size:clamp(3.5rem,9vw,7rem);
  letter-spacing:2px;
  line-height:.95;
  margin-bottom:24px;
}
.hero h1 .line2{
  display:block;
  background:linear-gradient(90deg, var(--cyan) 0%, var(--purple) 100%);
  -webkit-background-clip:text;-webkit-text-fill-color:transparent;
}
.hero-sub{
  font-size:1rem;color:var(--muted);
  max-width:460px;margin:0 auto 56px;
  line-height:1.8;
}
.hero-stats{
  display:flex;gap:0;justify-content:center;
  border:1px solid var(--b);
  border-radius:16px;
  overflow:hidden;
  max-width:500px;margin:0 auto;
  background:var(--card);
}
.stat{
  flex:1;padding:20px 16px;text-align:center;
  border-right:1px solid var(--b);
}
.stat:last-child{border-right:none}
.stat-n{
  font-family:'Bebas Neue',sans-serif;
  font-size:2.2rem;letter-spacing:1px;
  color:var(--cyan);line-height:1;
}
.stat-l{font-size:.7rem;color:var(--muted);letter-spacing:1px;text-transform:uppercase;margin-top:4px}

/* ── SECTION LABEL ── */
.slabel{
  font-family:'Bebas Neue',sans-serif;
  font-size:1rem;letter-spacing:4px;color:var(--muted);
  display:flex;align-items:center;gap:14px;
  margin-bottom:28px;
}
.slabel::after{content:'';flex:1;height:1px;background:var(--b)}

/* ── APPS SECTION ── */
.apps-section{
  padding:60px 32px 80px;
  max-width:1140px;margin:0 auto;
}
.apps-grid{
  display:grid;
  grid-template-columns:repeat(2,1fr);
  gap:20px;
}
.app-card{
  background:var(--card);
  border:1px solid var(--b);
  border-radius:22px;
  overflow:hidden;
  text-decoration:none;
  display:block;
  transition:all .35s cubic-bezier(.25,.8,.25,1);
  position:relative;
}
.app-card::after{
  content:'';
  position:absolute;inset:0;border-radius:22px;
  background:linear-gradient(135deg,
    color-mix(in srgb, var(--ac) 8%, transparent),
    transparent 60%);
  opacity:0;transition:opacity .3s;
}
.app-card:hover{
  transform:translateY(-8px) scale(1.01);
  border-color:color-mix(in srgb, var(--ac) 35%, transparent);
  box-shadow:0 24px 60px rgba(0,0,0,.5),
             0 0 0 1px color-mix(in srgb, var(--ac) 20%, transparent);
}
.app-card:hover::after{opacity:1}

.card-top{
  padding:32px 28px 24px;
  position:relative;z-index:1;
}
.card-glow{
  position:absolute;top:-30px;right:-30px;
  width:160px;height:160px;border-radius:50%;
  background:radial-gradient(circle, var(--ac) 0%, transparent 70%);
  opacity:.08;pointer-events:none;
}
.app-icon-wrap{
  width:60px;height:60px;border-radius:15px;
  display:flex;align-items:center;justify-content:center;
  font-size:1.7rem;margin-bottom:20px;
  background:color-mix(in srgb, var(--ac) 12%, transparent);
  border:1px solid color-mix(in srgb, var(--ac) 20%, transparent);
}
.app-name{
  font-family:'Bebas Neue',sans-serif;
  font-size:1.9rem;letter-spacing:1.5px;
  color:var(--text);line-height:1;margin-bottom:10px;
}
.app-desc{
  font-size:.85rem;color:var(--muted);
  line-height:1.65;
  display:-webkit-box;-webkit-line-clamp:2;
  -webkit-box-orient:vertical;overflow:hidden;
}
.card-bottom{
  padding:18px 28px 24px;
  border-top:1px solid var(--b);
  display:flex;align-items:center;justify-content:space-between;
  position:relative;z-index:1;
}
.file-count{
  display:flex;align-items:center;gap:8px;
  font-size:.82rem;color:var(--muted);
}
.count-badge{
  background:color-mix(in srgb, var(--ac) 15%, transparent);
  border:1px solid color-mix(in srgb, var(--ac) 25%, transparent);
  color:var(--ac);
  border-radius:8px;
  padding:4px 10px;
  font-weight:700;font-size:.82rem;
}
.go-btn{
  display:flex;align-items:center;gap:8px;
  background:color-mix(in srgb, var(--ac) 12%, transparent);
  border:1px solid color-mix(in srgb, var(--ac) 25%, transparent);
  color:var(--ac);
  border-radius:10px;
  padding:9px 18px;
  font-size:.82rem;font-weight:600;
  transition:all .2s;
}
.app-card:hover .go-btn{
  background:var(--ac);
  color:#000;border-color:var(--ac);
}

/* ── HOW TO ── */
.howto-section{
  padding:0 32px 80px;
  max-width:1140px;margin:0 auto;
}
.steps-grid{
  display:grid;
  grid-template-columns:repeat(4,1fr);
  gap:14px;
}
.step-card{
  background:var(--card);
  border:1px solid var(--b);
  border-radius:16px;
  padding:24px 20px;
}
.step-num{
  font-family:'Bebas Neue',sans-serif;
  font-size:2.8rem;letter-spacing:1px;
  opacity:.1;line-height:1;margin-bottom:12px;
  background:linear-gradient(135deg,var(--cyan),var(--purple));
  -webkit-background-clip:text;-webkit-text-fill-color:transparent;
  opacity:1;
}
.step-title{
  font-weight:600;font-size:.9rem;
  margin-bottom:6px;color:var(--text);
}
.step-desc{font-size:.8rem;color:var(--muted);line-height:1.6}

/* ── FOOTER ── */
footer{
  border-top:1px solid var(--b);
  padding:32px;
  max-width:1140px;margin:0 auto;
  display:flex;align-items:center;justify-content:space-between;
  flex-wrap:wrap;gap:16px;
}
.footer-logo{
  font-family:'Bebas Neue',sans-serif;
  font-size:1.3rem;letter-spacing:3px;
  color:var(--muted);
}
.footer-logo em{color:var(--cyan);font-style:normal}
footer p{font-size:.78rem;color:var(--muted2)}

@media(max-width:768px){
  .apps-grid{grid-template-columns:1fr}
  .steps-grid{grid-template-columns:repeat(2,1fr)}
  .hero h1{font-size:3.2rem}
  nav{padding:0 16px}
  .hero,.apps-section,.howto-section{padding-left:16px;padding-right:16px}
}
@media(max-width:480px){
  .steps-grid{grid-template-columns:1fr}
  .hero-stats{flex-direction:column}
  .stat{border-right:none;border-bottom:1px solid var(--b)}
  .stat:last-child{border-bottom:none}
}
</style>
</head>
<body>
<div class="ambient"></div>
<div class="grid-bg"></div>

<nav>
  <div class="nav-inner z">
    <a href="index.php" class="logo">
      <div class="logo-mark">🛡</div>
      VPN<em>Free</em>
    </a>
    <div class="nav-badge">
      <span class="pulse"></span>
      100% Gratuito
    </div>
  </div>
</nav>

<!-- HERO -->
<section class="hero z">
  <div class="hero-eyebrow">🇦🇴 Angola · Sempre Actualizado</div>
  <h1>
    CONFIGURAÇÕES
    <span class="line2">VPN GRÁTIS</span>
  </h1>
  <p class="hero-sub">Ficheiros prontos para importar nas tuas apps VPN favoritas. Sem registo, sem pagamento, sempre gratuito.</p>
  <div class="hero-stats">
    <div class="stat">
      <div class="stat-n"><?= count($apps) ?></div>
      <div class="stat-l">Apps VPN</div>
    </div>
    <div class="stat">
      <div class="stat-n">20</div>
      <div class="stat-l">Ficheiros</div>
    </div>
    <div class="stat">
      <div class="stat-n">0 Kz</div>
      <div class="stat-l">Custo</div>
    </div>
  </div>
</section>

<!-- APPS -->
<section class="apps-section z">
  <div class="slabel">Escolhe a tua aplicação</div>
  <div class="apps-grid">
    <?php foreach ($apps as $app):
      $count = countFilesByApp($app['id']);
    ?>
    <a href="app.php?slug=<?= h($app['slug']) ?>" class="app-card" style="--ac:<?= $app['color'] ?>">
      <div class="card-glow"></div>
      <div class="card-top">
        <div class="app-icon-wrap"><?= $app['icon'] ?></div>
        <div class="app-name"><?= h($app['name']) ?></div>
        <div class="app-desc"><?= h($app['description'] ?? '') ?></div>
      </div>
      <div class="card-bottom">
        <div class="file-count">
          <span class="count-badge"><?= $count ?></span>
          ficheiro<?= $count!=1?'s':'' ?> disponíve<?= $count!=1?'is':'l' ?>
        </div>
        <div class="go-btn">Ver ficheiros →</div>
      </div>
    </a>
    <?php endforeach; ?>
  </div>
</section>

<!-- HOW TO -->
<section class="howto-section z">
  <div class="slabel">Como usar</div>
  <div class="steps-grid">
    <div class="step-card">
      <div class="step-num">01</div>
      <div class="step-title">Escolhe a app</div>
      <div class="step-desc">Selecciona a aplicação VPN que tens instalada no teu telemóvel.</div>
    </div>
    <div class="step-card">
      <div class="step-num">02</div>
      <div class="step-title">Escolhe o ficheiro</div>
      <div class="step-desc">Vês até 5 ficheiros disponíveis. Escolhe qualquer um e carrega em Download.</div>
    </div>
    <div class="step-card">
      <div class="step-num">03</div>
      <div class="step-title">Importa na app</div>
      <div class="step-desc">Abre a tua app VPN, vai a Importar e selecciona o ficheiro descarregado.</div>
    </div>
    <div class="step-card">
      <div class="step-num">04</div>
      <div class="step-title">Usa a password</div>
      <div class="step-desc">Se a configuração tiver password, carrega no botão 🔑 para ver e copiar.</div>
    </div>
  </div>
</section>

<footer class="z">
  <div class="footer-logo">VPN<em>Free</em></div>
  <p>© <?= date('Y') ?> <?= SITE_NAME ?> · Configurações sempre gratuitas</p>
</footer>
</body>
</html>
