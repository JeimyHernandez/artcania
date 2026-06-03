<?php
$flash_success  = isset($flash_success)  ? $flash_success  : (Session::getFlash('success') ?: '');
$flash_error    = isset($flash_error)    ? $flash_error    : (Session::getFlash('error') ?: '');
$flash_reenviar = Session::getFlash('error_reenviar');
$cfg  = artcania_config();
$user = Auth::user();
$currentUri = ltrim(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH), '/');
$basePath   = ltrim(parse_url($cfg['url'], PHP_URL_PATH), '/');
$currentPage = $basePath ? ltrim(substr($currentUri, strlen($basePath)), '/') : $currentUri;
?><!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= isset($pageTitle) ? e($pageTitle).' – ' : '' ?>Artcania</title>
  <link rel="icon" href="<?= asset('img/favicon.svg') ?>" type="image/svg+xml">
  <link rel="stylesheet" href="<?= asset('css/bootstrap.min.css') ?>">
  <link rel="stylesheet" href="<?= asset('css/fontawesome.min.css') ?>">
  <link rel="stylesheet" href="<?= asset('css/main.css') ?>">
  <style>
    body{
      background:
        radial-gradient(ellipse at 20% 10%, rgba(124,58,237,.22), transparent 55%),
        radial-gradient(ellipse at 85% 90%, rgba(56,189,248,.16), transparent 55%),
        radial-gradient(ellipse at 50% 50%, rgba(168,85,247,.10), transparent 70%),
        linear-gradient(180deg,#05030f 0%,#0a0620 50%,#05030f 100%) !important;
      position:relative;
    }
    #cosmic-canvas{position:fixed;inset:0;z-index:0;pointer-events:none;}
    .cosmic-nebula{position:fixed;border-radius:50%;filter:blur(80px);pointer-events:none;z-index:0;mix-blend-mode:screen;opacity:.5;animation:nebulaDrift 22s ease-in-out infinite alternate}
    .cosmic-nebula.n1{top:-10%;left:-5%;width:520px;height:520px;background:radial-gradient(circle,#7c3aed,transparent 70%);}
    .cosmic-nebula.n2{bottom:-15%;right:-10%;width:600px;height:600px;background:radial-gradient(circle,#ec4899,transparent 70%);animation-delay:-7s}
    .cosmic-nebula.n3{top:40%;left:60%;width:380px;height:380px;background:radial-gradient(circle,#38bdf8,transparent 70%);animation-delay:-14s}
    @keyframes nebulaDrift{0%{transform:translate(0,0) scale(1)}50%{transform:translate(40px,-30px) scale(1.12)}100%{transform:translate(-30px,40px) scale(.95)}}
    .cosmic-planet{position:fixed;border-radius:50%;pointer-events:none;z-index:0;box-shadow:inset -20px -30px 60px rgba(0,0,0,.6),0 0 60px rgba(124,58,237,.35)}
    .cosmic-planet.p1{top:8%;right:6%;width:120px;height:120px;background:radial-gradient(circle at 30% 30%,#c4b5fd,#7c3aed 60%,#3b0764);animation:planetFloat 14s ease-in-out infinite}
    .cosmic-planet.p2{bottom:10%;left:5%;width:80px;height:80px;background:radial-gradient(circle at 30% 30%,#fce7f3,#ec4899 60%,#831843);animation:planetFloat 18s ease-in-out infinite reverse}
    .cosmic-planet.p3{top:55%;right:12%;width:50px;height:50px;background:radial-gradient(circle at 30% 30%,#a5f3fc,#0ea5e9 60%,#0c4a6e);animation:planetFloat 12s ease-in-out infinite}
    .cosmic-planet::after{content:"";position:absolute;inset:-18px;border-radius:50%;border:2px solid rgba(196,181,253,.25);transform:rotate(20deg) scaleY(.28);animation:ringSpin 16s linear infinite}
    .cosmic-planet.p2::after{border-color:rgba(244,114,182,.3);animation-duration:22s}
    .cosmic-planet.p3::after{border-color:rgba(125,211,252,.35);animation-duration:10s}
    @keyframes planetFloat{0%,100%{transform:translateY(0) rotate(0)}50%{transform:translateY(-20px) rotate(180deg)}}
    @keyframes ringSpin{to{transform:rotate(380deg) scaleY(.28)}}
    .cosmic-twinkle{position:fixed;width:2px;height:2px;background:#fff;border-radius:50%;box-shadow:0 0 6px #fff;animation:twinkleStar 3s ease-in-out infinite;z-index:0;pointer-events:none}
    @keyframes twinkleStar{0%,100%{opacity:.2;transform:scale(.6)}50%{opacity:1;transform:scale(1.4)}}
    .cosmic-comet{position:fixed;top:-10%;left:-10%;width:3px;height:3px;background:#fff;border-radius:50%;
      box-shadow:0 0 12px 2px #fff,0 0 30px 4px rgba(168,85,247,.7);z-index:0;pointer-events:none;
      animation:cometFall 8s linear infinite}
    .cosmic-comet::before{content:"";position:absolute;top:50%;right:0;width:140px;height:2px;
      background:linear-gradient(90deg,transparent,rgba(196,181,253,.9),#fff);transform:translateY(-50%) rotate(45deg);transform-origin:right center;filter:blur(.5px)}
    .cosmic-comet.c2{animation-delay:-3s;animation-duration:11s;left:30%}
    .cosmic-comet.c3{animation-delay:-6s;animation-duration:9s;left:60%}
    @keyframes cometFall{0%{transform:translate(0,0);opacity:0}10%{opacity:1}90%{opacity:1}100%{transform:translate(120vw,120vh);opacity:0}}

    .artcania-navbar { position: sticky; top:0; z-index: 1030; }
    main, .footer-magic { position: relative; z-index: 2; }

    /* ── DROPDOWN USUARIO (jQuery custom, position fixed) ── */
    #user-dropdown-menu {
      display: none;
      position: fixed;
      z-index: 99999;
      background: #16162a;
      border: 1px solid rgba(201,162,39,.35);
      border-radius: 14px;
      min-width: 200px;
      padding: .4rem;
      box-shadow: 0 20px 60px rgba(0,0,0,.75), 0 0 0 1px rgba(124,58,237,.15);
      list-style: none;
      margin: 0;
    }
    #user-dropdown-menu.open { display: block; }
    #user-dropdown-menu .dropdown-item {
      color: rgba(240,234,255,.8);
      font-size: .85rem;
      padding: .55rem 1rem;
      border-radius: 8px;
      display: flex;
      align-items: center;
      transition: background .15s;
      text-decoration: none;
    }
    #user-dropdown-menu .dropdown-item:hover,
    #user-dropdown-menu .dropdown-item:focus {
      background: rgba(124,58,237,.22);
      color: #fff;
    }
    #user-dropdown-menu .dropdown-item-danger { color: #f87171; }
    #user-dropdown-menu .dropdown-item-danger:hover {
      background: rgba(248,113,113,.15);
      color: #fca5a5;
    }
    #user-dropdown-menu hr {
      border-color: rgba(124,58,237,.25);
      margin: .3rem .5rem;
    }
  </style>
</head>
<body>

<!-- Fondo cósmico global -->
<canvas id="cosmic-canvas"></canvas>
<div class="cosmic-nebula n1"></div>
<div class="cosmic-nebula n2"></div>
<div class="cosmic-nebula n3"></div>
<div class="cosmic-planet p1"></div>
<div class="cosmic-planet p2"></div>
<div class="cosmic-planet p3"></div>
<div class="cosmic-comet"></div>
<div class="cosmic-comet c2"></div>
<div class="cosmic-comet c3"></div>
<div id="cosmic-twinkle-layer"></div>

<!-- NAVBAR -->
<nav class="artcania-navbar navbar navbar-expand-lg">
  <div class="container-xl">

    <a class="navbar-brand d-flex align-items-center gap-2" href="<?= url('') ?>">
      <span class="brand-gem"><i class="fa-solid fa-gem" style="font-size:1.1rem"></i></span>
      ARTCANIA
    </a>

    <button class="navbar-toggler border-0 ms-auto me-2" type="button"
            data-bs-toggle="collapse" data-bs-target="#navPublic">
      <i class="fa fa-bars" style="color:var(--pearl-dim)"></i>
    </button>

    <div class="collapse navbar-collapse" id="navPublic">
      <ul class="navbar-nav mx-auto gap-1">
        <li class="nav-item"><a class="nav-link <?= $currentPage==='' ? 'active':'' ?>" href="<?= url('') ?>">Inicio</a></li>
        <li class="nav-item"><a class="nav-link <?= $currentPage==='galeria' ? 'active':'' ?>" href="<?= url('galeria') ?>">Galería</a></li>
        <li class="nav-item"><a class="nav-link <?= $currentPage==='artistas' ? 'active':'' ?>" href="<?= url('artistas') ?>">Artistas</a></li>
        <li class="nav-item"><a class="nav-link <?= $currentPage==='exposiciones' ? 'active':'' ?>" href="<?= url('exposiciones') ?>">Exposiciones</a></li>
        <li class="nav-item"><a class="nav-link <?= $currentPage==='subastas' ? 'active':'' ?>" href="<?= url('subastas') ?>">Subastas</a></li>
        <li class="nav-item"><a class="nav-link <?= $currentPage==='about' ? 'active':'' ?>" href="<?= url('about') ?>">Nosotros</a></li>
      </ul>

      <div class="d-flex align-items-center gap-2 mt-2 mt-lg-0">
        <?php if($user): ?>
          <button class="navbar-notif-btn" id="notifBtn" title="Notificaciones">
            <i class="fa fa-bell" style="font-size:.9rem"></i>
            <span class="badge-dot d-none" id="notifDot"></span>
          </button>

          <div class="dropdown">
            <button type="button"
                    id="userMenuBtn"
                    class="btn border-0 bg-transparent d-flex align-items-center gap-2 p-0"
                    style="cursor:pointer;outline:none">
              <img src="<?= avatar($user['avatar'] ?? '') ?>"
                   class="navbar-avatar"
                   style="width:32px;height:32px;border-radius:50%;object-fit:cover;border:2px solid rgba(201,162,39,.5)"
                   alt="<?= e($user['nombre']) ?>">
              <span style="font-size:.85rem;color:var(--pearl-dim)"><?= e($user['nombre']) ?></span>
              <i class="fa fa-chevron-down" style="font-size:.6rem;color:var(--pearl-muted)"></i>
            </button>

            <ul id="user-dropdown-menu">
              <!-- Cabecera -->
              <li>
                <div style="padding:.5rem 1rem .6rem;border-bottom:1px solid rgba(124,58,237,.2);margin-bottom:.3rem">
                  <div style="font-size:.8rem;font-weight:600;color:var(--pearl)"><?= e($user['nombre']) ?></div>
                  <div style="font-size:.72rem;color:var(--pearl-muted)"><?= e($user['email'] ?? '') ?></div>
                </div>
              </li>

              <!-- Panel según rol -->
              <?php if(Auth::isAdmin()): ?>
              <li><a class="dropdown-item" href="<?= url('admin/dashboard') ?>">
                <i class="fa fa-gauge me-2" style="color:#e8c65a;width:16px"></i>Panel Admin
              </a></li>
              <?php elseif(Auth::rol()==='artista'): ?>
              <li><a class="dropdown-item" href="<?= url('artista/dashboard') ?>">
                <i class="fa fa-palette me-2" style="color:#a78bfa;width:16px"></i>Mi Studio
              </a></li>
              <?php elseif(Auth::rol()==='curador'): ?>
              <li><a class="dropdown-item" href="<?= url('curador/dashboard') ?>">
                <i class="fa fa-eye me-2" style="color:var(--teal);width:16px"></i>Panel Curador
              </a></li>
              <?php endif; ?>

              <li><a class="dropdown-item" href="<?= url('perfil') ?>">
                <i class="fa fa-user me-2" style="color:#a78bfa;width:16px"></i>Mi Perfil
              </a></li>
              <li><a class="dropdown-item" href="<?= url('notificaciones') ?>">
                <i class="fa fa-bell me-2" style="color:#a78bfa;width:16px"></i>Notificaciones
              </a></li>
              <li><a class="dropdown-item" href="<?= url('mis-favoritos') ?>">
                <i class="fa fa-heart me-2" style="color:#f87171;width:16px"></i>Mis Favoritos
              </a></li>
              <li><a class="dropdown-item" href="<?= url('chat') ?>">
                <i class="fa fa-comments me-2" style="color:#34d399;width:16px"></i>Chat
              </a></li>

              <li><hr class="dropdown-divider"></li>

              <li><a class="dropdown-item dropdown-item-danger" href="<?= url('logout') ?>">
                <i class="fa fa-right-from-bracket me-2" style="width:16px"></i>Cerrar sesión
              </a></li>
            </ul>
          </div>

        <?php else: ?>
          <a href="<?= url('login') ?>" class="btn btn-outline-magic btn-sm">Iniciar sesión</a>
          <a href="<?= url('registro') ?>" class="btn btn-magic btn-sm">Registrarse</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</nav>

<!-- Flash messages -->
<?php if($flash_success || $flash_error): ?>
<div class="container-xl pt-3">
  <?php if($flash_success): ?>
    <div class="alert-success-magic d-flex align-items-center gap-2" role="alert">
      <i class="fa fa-circle-check"></i> <?= e($flash_success) ?>
    </div>
  <?php endif; ?>
  <?php if($flash_error): ?>
    <div class="alert-danger-magic d-flex align-items-center gap-2 flex-wrap" role="alert">
      <span><i class="fa fa-circle-exclamation me-1"></i><?= e($flash_error) ?></span>
      <?php if(!empty($flash_reenviar)): ?>
        <a href="<?= url('reenviar-verificacion') ?>" class="ms-auto" style="color:var(--teal);font-size:.85rem">
          <i class="fa fa-envelope me-1"></i>Reenviar verificación
        </a>
      <?php endif; ?>
    </div>
  <?php endif; ?>
</div>
<?php endif; ?>

<!-- Contenido principal -->
<main><?= $content ?></main>

<!-- FOOTER -->
<footer class="footer-magic">
  <div class="container-xl">
    <div class="row g-4 mb-2">
      <div class="col-md-4">
        <div class="footer-brand mb-2">✦ ARTCANIA</div>
        <p style="font-size:.83rem;color:var(--pearl-muted);line-height:1.7;max-width:260px">
          Un mundo donde el arte no tiene límites. Explora, crea e inspírate.
        </p>
      </div>
      <div class="col-md-2">
        <p class="footer-brand mb-2" style="font-size:.85rem">Explorar</p>
        <ul class="list-unstyled mb-0">
          <li class="mb-1"><a href="<?= url('galeria') ?>" class="footer-link">Galería</a></li>
          <li class="mb-1"><a href="<?= url('artistas') ?>" class="footer-link">Artistas</a></li>
          <li class="mb-1"><a href="<?= url('exposiciones') ?>" class="footer-link">Exposiciones</a></li>
          <li class="mb-1"><a href="<?= url('subastas') ?>" class="footer-link">Subastas</a></li>
        </ul>
      </div>
      <div class="col-md-2">
        <p class="footer-brand mb-2" style="font-size:.85rem">Comunidad</p>
        <ul class="list-unstyled mb-0">
          <li class="mb-1"><a href="<?= url('fanarts') ?>" class="footer-link">FanArts</a></li>
          <li class="mb-1"><a href="<?= url('videos') ?>" class="footer-link">Videos</a></li>
          <li class="mb-1"><a href="<?= url('about') ?>" class="footer-link">Sobre nosotros</a></li>
        </ul>
      </div>
      <div class="col-md-4 text-md-end">
        <p class="footer-brand mb-2" style="font-size:.85rem">Únete</p>
        <div class="d-flex gap-2 justify-content-md-end">
          <a href="<?= url('registro') ?>" class="btn btn-magic btn-sm">Crear cuenta</a>
          <a href="<?= url('login') ?>" class="btn btn-outline-magic btn-sm">Ingresar</a>
        </div>
      </div>
    </div>
    <div class="footer-copy text-center">
      © <?= date('Y') ?> Artcania · Todos los derechos reservados ·
      <a href="<?= url('about') ?>" class="footer-link">Privacidad</a>
    </div>
  </div>
</footer>

<!-- Scripts -->
<script>var BASE_URL = <?= json_encode(rtrim($cfg['url'], '/')) ?>;</script>
<script src="<?= asset('js/jquery.min.js') ?>"></script>
<script src="<?= asset('js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= asset('js/main.js') ?>"></script>
<script>
(function(){
  var layer = document.getElementById('cosmic-twinkle-layer');
  if(layer){
    for(var i=0;i<70;i++){
      var s=document.createElement('div');s.className='cosmic-twinkle';
      s.style.left=Math.random()*100+'vw';
      s.style.top=Math.random()*100+'vh';
      s.style.animationDelay=(Math.random()*3)+'s';
      s.style.animationDuration=(2+Math.random()*4)+'s';
      var sz=Math.random()*2+1;s.style.width=sz+'px';s.style.height=sz+'px';
      layer.appendChild(s);
    }
  }
  var c=document.getElementById('cosmic-canvas');if(!c)return;
  var ctx=c.getContext('2d');
  var stars=[],shooters=[],W,H;
  function resize(){W=c.width=innerWidth;H=c.height=innerHeight;}
  resize();addEventListener('resize',resize);
  for(var i=0;i<200;i++){
    stars.push({x:Math.random()*W,y:Math.random()*H,r:Math.random()*1.3+.2,
      vy:Math.random()*.25+.05,a:Math.random(),da:(Math.random()*.02+.005)*(Math.random()<.5?-1:1),
      hue:Math.random()<.3?280:(Math.random()<.5?200:0)});
  }
  function spawnShooter(){
    shooters.push({x:Math.random()*W,y:-20,vx:(Math.random()*2+3),vy:(Math.random()*3+5),len:Math.random()*80+60,life:1});
  }
  setInterval(spawnShooter,1400);
  function tick(){
    ctx.clearRect(0,0,W,H);
    for(var i=0;i<stars.length;i++){
      var s=stars[i];s.y+=s.vy;if(s.y>H){s.y=0;s.x=Math.random()*W;}
      s.a+=s.da;if(s.a>1||s.a<.1)s.da*=-1;
      var col=s.hue===0?'255,255,255':(s.hue===280?'196,181,253':'165,243,252');
      ctx.beginPath();ctx.fillStyle='rgba('+col+','+s.a.toFixed(2)+')';
      ctx.shadowBlur=6;ctx.shadowColor='rgba('+col+',.8)';
      ctx.arc(s.x,s.y,s.r,0,6.28);ctx.fill();
    }
    ctx.shadowBlur=0;
    for(var i=shooters.length-1;i>=0;i--){
      var sh=shooters[i];sh.x+=sh.vx;sh.y+=sh.vy;sh.life-=.012;
      var g=ctx.createLinearGradient(sh.x,sh.y,sh.x-sh.vx*sh.len/8,sh.y-sh.vy*sh.len/8);
      g.addColorStop(0,'rgba(255,255,255,'+sh.life+')');
      g.addColorStop(.4,'rgba(196,181,253,'+(sh.life*.7)+')');
      g.addColorStop(1,'rgba(124,58,237,0)');
      ctx.strokeStyle=g;ctx.lineWidth=2;ctx.lineCap='round';
      ctx.beginPath();ctx.moveTo(sh.x,sh.y);
      ctx.lineTo(sh.x-sh.vx*sh.len/8,sh.y-sh.vy*sh.len/8);ctx.stroke();
      if(sh.life<=0||sh.x>W+50||sh.y>H+50)shooters.splice(i,1);
    }
    requestAnimationFrame(tick);
  }
  tick();
})();
</script>
<script>
// ── Dropdown usuario (jQuery puro, position:fixed, sin Bootstrap Dropdown) ──
$(function(){
  var $btn  = $('#userMenuBtn');
  var $menu = $('#user-dropdown-menu');
  if(!$btn.length || !$menu.length) return;

  // Mover el menú al body para escapar cualquier stacking context
  $menu.appendTo('body');

  function positionMenu(){
    var r = $btn[0].getBoundingClientRect();
    var menuW = $menu.outerWidth();
    var left  = r.right - menuW;
    if(left < 8) left = 8;
    $menu.css({ top: (r.bottom + 6) + 'px', left: left + 'px' });
  }

  $btn.on('click', function(e){
    e.stopPropagation();
    if($menu.hasClass('open')){
      $menu.removeClass('open');
    } else {
      positionMenu();
      $menu.addClass('open');
    }
  });

  $(document).on('click', function(e){
    if(!$(e.target).closest('#user-dropdown-menu').length){
      $menu.removeClass('open');
    }
  });

  $(window).on('resize scroll', function(){
    if($menu.hasClass('open')) positionMenu();
  });
});
</script>
</body>
</html>
