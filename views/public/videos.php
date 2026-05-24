<?php $pageTitle = 'Galería de Videos';
if (!isset($videos) || !is_array($videos)) { $videos = []; }
?>
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-end flex-wrap mb-4">
    <div>
      <h1 class="cosmic-title" style="font-size:2.2rem;text-align:left">GALERÍA DE VIDEOS</h1>
      <p class="cosmic-subtitle" style="margin-top:.3rem">Explora historias visuales únicas, creadas con imaginación sin límites.</p>
    </div>
    <div class="d-flex gap-2 align-items-center">
      <input type="text" class="input-magic" placeholder="Buscar videos..." style="width:260px">
    </div>
  </div>

  <div class="row g-4 mb-5">
    <div class="col-lg-8">
      <div class="glass-panel p-0 overflow-hidden" style="border-radius:18px">
        <div class="video-card" style="border:none">
          <div class="thumb" style="background-image:linear-gradient(135deg,#2a1458,#7a3df0);aspect-ratio:16/9">
            <div class="play"><i class="fa fa-play"></i></div>
            <div class="duration">03:47</div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-4">
      <div class="glass-panel h-100 d-flex flex-column">
        <small style="color:var(--gold);letter-spacing:.15em">✦ DESTACADO</small>
        <h3 class="mt-2" style="font-family:'Cinzel',serif;color:var(--moon);font-size:1.5rem">La Guardiana<br>de las Estrellas</h3>
        <p style="color:rgba(212,184,255,.75);font-family:'Cormorant Garamond',serif;font-size:1.05rem">
          En un reino donde la magia de las estrellas da vida, una joven guardiana descubre el poder de su destino.
        </p>
        <div class="mt-auto">
          <div class="mb-3" style="color:rgba(212,184,255,.65)"><i class="fa fa-eye me-2"></i>125.8K vistas</div>
          <a href="#" class="btn-magic">Ver detalles</a>
        </div>
      </div>
    </div>
  </div>

  <div class="d-flex flex-wrap gap-2 justify-content-between mb-4">
    <div class="d-flex gap-2 flex-wrap">
      <span class="pill active"><i class="fa fa-user"></i> Personas</span>
      <span class="pill"><i class="fa fa-cube"></i> Cosas</span>
      <span class="pill"><i class="fa fa-paw"></i> Animales</span>
      <span class="pill"><i class="fa fa-sparkles"></i>✦ Animados</span>
    </div>
    <div class="d-flex gap-2">
      <select class="input-magic" style="width:auto"><option>Más recientes</option><option>Más vistos</option></select>
    </div>
  </div>

  <div class="row g-4">
    <?php if(empty($videos)): $videos = [
      ['titulo'=>'Retrato de la Soñadora','vistas'=>'89.3K','dur'=>'02:31'],
      ['titulo'=>'Caminante del Eclipse','vistas'=>'72.1K','dur'=>'03:15'],
      ['titulo'=>'Pequeños Inventos','vistas'=>'56.7K','dur'=>'01:48'],
      ['titulo'=>'El Juramento','vistas'=>'103.2K','dur'=>'02:59'],
      ['titulo'=>'Luz del Bosque','vistas'=>'68.4K','dur'=>'02:12'],
      ['titulo'=>'Criatura del Amanecer','vistas'=>'45.8K','dur'=>'01:37'],
      ['titulo'=>'El Viaje Infinito','vistas'=>'92.6K','dur'=>'03:22'],
      ['titulo'=>'El Hechizo Inesperado','vistas'=>'77.9K','dur'=>'01:53'],
    ]; endif; ?>
    <?php foreach($videos as $i=>$v): ?>
    <div class="col-6 col-md-4 col-lg-3">
      <div class="video-card">
        <div class="thumb" style="background-image:linear-gradient(<?= ($i*47)%360 ?>deg,#2a1458,#7a3df0,#c77dff)">
          <div class="play"><i class="fa fa-play"></i></div>
          <div class="duration"><?= e($v['dur']??'02:00') ?></div>
        </div>
        <div class="meta">
          <h6><?= e($v['titulo']??'Video') ?></h6>
          <small><i class="fa fa-eye me-1"></i><?= e($v['vistas']??'0 vistas') ?> vistas</small>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>
