<?php
$flash_success  = isset($flash_success)  ? $flash_success  : (Session::getFlash('success') ?: '');
$flash_error    = isset($flash_error)    ? $flash_error    : (Session::getFlash('error')   ?: '');
$flash_errors   = isset($flash_errors)   ? $flash_errors   : Session::getFlash('errors');
$flash_reenviar = Session::getFlash('error_reenviar');
$cfg = artcania_config();
?><!DOCTYPE html>
<html lang="es">
<head>
  
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= isset($pageTitle) ? e($pageTitle).' – ' : '' ?>Artcania</title>
  <link rel="stylesheet" href="<?= asset('css/bootstrap.min.css') ?>">
  <link rel="stylesheet" href="<?= asset('css/fontawesome.min.css') ?>">
  <link rel="stylesheet" href="<?= asset('css/main.css') ?>">
  <style>
    body {
      display:flex; align-items:center; justify-content:center;
      min-height:100vh; padding:1.5rem 1rem;
      background:
        radial-gradient(ellipse at 20% 10%, rgba(124,58,237,.25), transparent 55%),
        radial-gradient(ellipse at 85% 90%, rgba(56,189,248,.18), transparent 55%),
        radial-gradient(ellipse at 50% 50%, rgba(168,85,247,.12), transparent 70%),
        linear-gradient(180deg,#05030f 0%,#0a0620 50%,#05030f 100%);
      overflow:hidden; position:relative;
    }
    /* Canvas estrellas */
    #cosmic-canvas{position:fixed;inset:0;z-index:1;pointer-events:none;}
    /* Nebulosa animada */
    .nebula{position:fixed;border-radius:50%;filter:blur(80px);pointer-events:none;z-index:0;mix-blend-mode:screen;opacity:.55;animation:nebulaDrift 22s ease-in-out infinite alternate}
    .nebula.n1{top:-10%;left:-5%;width:520px;height:520px;background:radial-gradient(circle,#7c3aed,transparent 70%);}
    .nebula.n2{bottom:-15%;right:-10%;width:600px;height:600px;background:radial-gradient(circle,#ec4899,transparent 70%);animation-delay:-7s}
    .nebula.n3{top:40%;left:60%;width:380px;height:380px;background:radial-gradient(circle,#38bdf8,transparent 70%);animation-delay:-14s}
    @keyframes nebulaDrift{
      0%{transform:translate(0,0) scale(1)}
      50%{transform:translate(40px,-30px) scale(1.12)}
      100%{transform:translate(-30px,40px) scale(.95)}
    }
    /* Planetas girando */
    .planet{position:fixed;border-radius:50%;pointer-events:none;z-index:0;box-shadow:inset -20px -30px 60px rgba(0,0,0,.6),0 0 60px rgba(124,58,237,.35)}
    .planet.p1{top:8%;right:6%;width:120px;height:120px;background:radial-gradient(circle at 30% 30%,#c4b5fd,#7c3aed 60%,#3b0764);animation:planetFloat 14s ease-in-out infinite}
    .planet.p2{bottom:10%;left:5%;width:80px;height:80px;background:radial-gradient(circle at 30% 30%,#fce7f3,#ec4899 60%,#831843);animation:planetFloat 18s ease-in-out infinite reverse}
    .planet.p3{top:55%;right:12%;width:50px;height:50px;background:radial-gradient(circle at 30% 30%,#a5f3fc,#0ea5e9 60%,#0c4a6e);animation:planetFloat 12s ease-in-out infinite}
    .planet::after{content:"";position:absolute;inset:-18px;border-radius:50%;border:2px solid rgba(196,181,253,.25);transform:rotate(20deg) scaleY(.28);animation:ringSpin 16s linear infinite}
    .planet.p2::after{border-color:rgba(244,114,182,.3);animation-duration:22s}
    .planet.p3::after{border-color:rgba(125,211,252,.35);animation-duration:10s}
    @keyframes planetFloat{
      0%,100%{transform:translateY(0) rotate(0)}
      50%{transform:translateY(-20px) rotate(180deg)}
    }
    @keyframes ringSpin{to{transform:rotate(380deg) scaleY(.28)}}
    /* Estrella titilando (capa CSS extra) */
    .twinkle{position:fixed;width:2px;height:2px;background:#fff;border-radius:50%;box-shadow:0 0 6px #fff;animation:twinkle 3s ease-in-out infinite;z-index:0;pointer-events:none}
    @keyframes twinkle{0%,100%{opacity:.2;transform:scale(.6)}50%{opacity:1;transform:scale(1.4)}}
    /* Cometa */
    .comet{position:fixed;top:-10%;left:-10%;width:3px;height:3px;background:#fff;border-radius:50%;
      box-shadow:0 0 12px 2px #fff,0 0 30px 4px rgba(168,85,247,.7);z-index:0;pointer-events:none;
      animation:cometFall 8s linear infinite}
    .comet::before{content:"";position:absolute;top:50%;right:0;width:140px;height:2px;
      background:linear-gradient(90deg,transparent,rgba(196,181,253,.9),#fff);transform:translateY(-50%) rotate(45deg);transform-origin:right center;filter:blur(.5px)}
    .comet.c2{animation-delay:-3s;animation-duration:11s;left:30%}
    .comet.c3{animation-delay:-6s;animation-duration:9s;left:60%}
    @keyframes cometFall{
      0%{transform:translate(0,0);opacity:0}
      10%{opacity:1}
      90%{opacity:1}
      100%{transform:translate(120vw,120vh);opacity:0}
    }

    /* ==========================================================
   Luna central decorativa.
   Se muestra detrás del formulario y por encima
   del fondo cósmico animado para que se vea bonito.
    ========================================================== */
.moon-bg{
    position:fixed;
    top:50%;
    left:50%;
    transform:translate(-50%,-50%);

    width:1300px;
    max-width:none;

    opacity:.95;

    z-index:1;

    pointer-events:none;
}
    /* Asegurar el contenido por encima */
    .w-100{position:relative;z-index:2}
  </style>
</head>
<!-- la musicaaaaa :D suena en bucle al terminar-->
<audio id="bgMusic" loop>
    <source src="<?= asset('audio/hijo_de_la_luna.mp3') ?>" type="audio/mpeg">
</audio>
<body>

<!-- =========================================================
     Luna decorativa de fondo.
     Se coloca detrás del formulario principal y encima
     del cielo cósmico para dar profundidad visual.
========================================================= -->
<img
    src="<?= asset('img/luna_plata.png') ?>"
    alt="Luna"
    class="moon-bg"
>
<!-- Fondo cósmico -->
<canvas id="cosmic-canvas"></canvas>
<div class="nebula n1"></div>
<div class="nebula n2"></div>
<div class="nebula n3"></div>
<div class="planet p1"></div>
<div class="planet p2"></div>
<div class="planet p3"></div>
<div class="comet"></div>
<div class="comet c2"></div>
<div class="comet c3"></div>
<div id="twinkle-layer"></div>

<!-- Partículas decorativas suaves -->
<div style="position:fixed;inset:0;pointer-events:none;z-index:1;overflow:hidden">
  <div style="position:absolute;top:12%;left:8%;width:180px;height:180px;border-radius:50%;background:radial-gradient(circle,rgba(124,58,237,.18),transparent 70%);filter:blur(30px)"></div>
  <div style="position:absolute;bottom:15%;right:10%;width:220px;height:220px;border-radius:50%;background:radial-gradient(circle,rgba(201,162,39,.12),transparent 70%);filter:blur(35px)"></div>
</div>


<div class="w-100" style="max-width:440px;position:relative;z-index:2">

  <!-- Flash messages -->
  <?php if($flash_success): ?>
    <div class="alert-success-magic mb-3 d-flex align-items-center gap-2">
      <i class="fa fa-circle-check"></i> <?= e($flash_success) ?>
    </div>
  <?php endif; ?>
  <?php if($flash_error): ?>
    <div class="alert-danger-magic mb-3">
      <i class="fa fa-circle-exclamation me-2"></i><?= e($flash_error) ?>
      <?php if(!empty($flash_reenviar)): ?>
        <div class="mt-2">
          <a href="<?= url('reenviar-verificacion') ?>" style="color:var(--teal);font-size:.83rem">
            <i class="fa fa-envelope me-1"></i>Reenviar correo de verificación
          </a>
        </div>
      <?php endif; ?>
    </div>
  <?php endif; ?>
  <?php if(!empty($flash_errors)): ?>
    <div class="alert-danger-magic mb-3">
      <ul class="mb-0 ps-3" style="font-size:.85rem">
        <?php foreach((array)$flash_errors as $field => $msgs): foreach((array)$msgs as $e): ?><li><?= htmlspecialchars((string)$e) ?></li><?php endforeach; endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <?= $content ?>

</div>

<script>var BASE_URL = <?= json_encode(rtrim($cfg['url'], '/')) ?>;</script>
<script src="<?= asset('js/jquery.min.js') ?>"></script>
<script src="<?= asset('js/bootstrap.bundle.min.js') ?>"></script>

<!-- al hacer click suena la musica se coloco asi por que algunos navegadores bloquean la musica -->
<script>
document.addEventListener('click', function iniciarMusica() {

    const music = document.getElementById('bgMusic');

    music.play().catch(function(){});

    document.removeEventListener(
        'click',
        iniciarMusica
    );

});
</script>

<script>
(function(){
  
  // Twinkle stars (DOM)
  var layer = document.getElementById('twinkle-layer');
  for(var i=0;i<60;i++){
    var s=document.createElement('div');s.className='twinkle';
    s.style.left=Math.random()*100+'vw';
    s.style.top=Math.random()*100+'vh';
    s.style.animationDelay=(Math.random()*3)+'s';
    s.style.animationDuration=(2+Math.random()*4)+'s';
    var sz=Math.random()*2+1;s.style.width=sz+'px';s.style.height=sz+'px';
    layer.appendChild(s);
  }
  // Canvas: starfield + falling stars
  var c=document.getElementById('cosmic-canvas'),ctx=c.getContext('2d');
  var stars=[],shooters=[],W,H;
  function resize(){W=c.width=innerWidth;H=c.height=innerHeight;}
  resize();addEventListener('resize',resize);
  for(var i=0;i<180;i++){
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
      var s=stars[i];s.y+=s.vy;if(s.y>H)s.y=0,s.x=Math.random()*W;
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
</body>
</html>
