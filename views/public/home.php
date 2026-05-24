<?php $pageTitle = 'Inicio'; ?>

<!-- ── HERO ──────────────────────────────────────────────────────────── -->
<section class="hero-magic">
  <div class="container hero-content">
    <div class="row align-items-center g-5">
      <div class="col-lg-6 fade-up">
        <p class="mb-2" style="color:var(--teal);font-size:.82rem;letter-spacing:.12em;text-transform:uppercase;font-weight:600">
          ✦ &nbsp;Plataforma de Arte Digital
        </p>
        <h1 class="hero-title mb-3">
          Un mundo donde el arte<br>no tiene límites
        </h1>
        <p class="hero-subtitle mb-4">
          Descubre obras únicas, conecta con artistas extraordinarios y sé parte de una comunidad mágica donde la creatividad no tiene fronteras.
        </p>
        <div class="d-flex flex-wrap gap-3">
          <a href="<?= url('galeria') ?>" class="btn-magic btn">
            <i class="fa fa-compass me-2"></i>Explorar Galería
          </a>
          <?php if (!Auth::check()): ?>
          <a href="<?= url('registro') ?>" class="btn-magic-outline btn">
            <i class="fa fa-magic me-2"></i>Únete gratis
          </a>
          <?php endif; ?>
        </div>
      </div>
      <div class="col-lg-6 d-none d-lg-block">
        <div style="position:relative">
          <!-- Orbe decorativo -->
          <div style="width:380px;height:380px;border-radius:50%;
                      background:radial-gradient(ellipse at 40% 40%, rgba(92,77,155,.35), rgba(93,214,192,.12), transparent 70%);
                      border:1px solid rgba(166,189,255,.12);
                      display:flex;align-items:center;justify-content:center;
                      margin:0 auto;
                      box-shadow:0 0 80px rgba(92,77,155,.2), inset 0 0 60px rgba(93,214,192,.05);
                      animation:pulse-glow 4s ease-in-out infinite">
            <div style="font-size:7rem;filter:drop-shadow(0 0 30px rgba(166,189,255,.4))">🎨</div>
          </div>
          <!-- Badges flotantes -->
          <div style="position:absolute;top:20px;left:0;background:rgba(20,27,45,.9);
                      border:1px solid rgba(166,189,255,.15);border-radius:12px;padding:.6rem .9rem;
                      font-size:.78rem;color:var(--pearl)">
            <i class="fa fa-palette me-1" style="color:var(--teal)"></i>
            Arte Digital
          </div>
          <div style="position:absolute;bottom:40px;right:0;background:rgba(20,27,45,.9);
                      border:1px solid rgba(93,214,192,.2);border-radius:12px;padding:.6rem .9rem;
                      font-size:.78rem;color:var(--pearl)">
            <i class="fa fa-star me-1" style="color:#f0c040"></i>
            Fan Arts
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ── STATS ──────────────────────────────────────────────────────────── -->
<div class="stats-banner">
  <div class="container">
    <div class="row text-center g-3">
      <div class="col-6 col-md-3 stat-item">
        <h3><?= number_format($stat_obras ?? 0) ?>+</h3>
        <small>Obras Publicadas</small>
      </div>
      <div class="col-6 col-md-3 stat-item">
        <h3><?= number_format($stat_artistas ?? 0) ?>+</h3>
        <small>Artistas Activos</small>
      </div>
      <div class="col-6 col-md-3 stat-item">
        <h3><?= number_format($stat_usuarios ?? 0) ?>+</h3>
        <small>Exploradores</small>
      </div>
      <div class="col-6 col-md-3 stat-item">
        <h3><?= number_format($stat_subastas ?? 0) ?>+</h3>
        <small>Subastas Cerradas</small>
      </div>
    </div>
  </div>
</div>

<!-- ── CATEGORÍAS ─────────────────────────────────────────────────────── -->
<section class="section-magic">
  <div class="container">
    <h2 class="section-title mb-1">Explorar Categorías</h2>
    <p class="mb-4 small" style="color:rgba(244,247,251,.4)">Encuentra el arte que conecta contigo</p>
    <div class="row g-3">
      <?php
      $cats = array(
        array('icon'=>'✏️','nombre'=>'Ilustración',  'url'=>'galeria?categoria=ilustracion'),
        array('icon'=>'🎬','nombre'=>'Animación',     'url'=>'videos'),
        array('icon'=>'💜','nombre'=>'Fan Arts',       'url'=>'fanarts'),
        array('icon'=>'▶️','nombre'=>'Videos',        'url'=>'videos'),
        array('icon'=>'📖','nombre'=>'Historias',      'url'=>'galeria?categoria=historia'),
        array('icon'=>'🏆','nombre'=>'Subastas',       'url'=>'subastas'),
      );
      foreach($cats as $c): ?>
      <div class="col-6 col-md-4 col-lg-2">
        <a href="<?= url($c['url']) ?>" class="cat-card">
          <span class="cat-icon"><?= $c['icon'] ?></span>
          <span><?= $c['nombre'] ?></span>
        </a>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ── OBRAS DESTACADAS ───────────────────────────────────────────────── -->
<?php if (!empty($obras)): ?>
<section class="section-magic" style="padding-top:0">
  <div class="container">
    <div class="d-flex justify-content-between align-items-end mb-4">
      <div>
        <h2 class="section-title mb-1">Obras Destacadas</h2>
        <p class="small mb-0" style="color:rgba(244,247,251,.4)">Las creaciones más recientes</p>
      </div>
      <a href="<?= url('galeria') ?>" class="btn-magic-outline btn btn-sm">
        Ver todas <i class="fa fa-arrow-right ms-1"></i>
      </a>
    </div>
    <div class="row g-3">
      <?php foreach($obras as $o): ?>
      <div class="col-6 col-md-4 col-lg-3">
        <div class="card-magic h-100">
          <?php if(!empty($o['imagen_principal'])): ?>
            <img src="<?= media_url('Originales/imagen/Obras_digitales/'.$o['imagen_principal']) ?>"
                 class="card-img-top" alt="<?= e($o['titulo']) ?>">
          <?php else: ?>
            <div class="card-img-placeholder">
              <i class="fa fa-image" style="font-size:2.5rem;color:rgba(166,189,255,.2)"></i>
            </div>
          <?php endif; ?>
          <div class="card-body d-flex flex-column">
            <h6 class="card-title"><?= e($o['titulo']) ?></h6>
            <p class="card-text small flex-grow-1"><?= e($o['artista_nombre'] ?? '') ?></p>
            <?php if(!empty($o['precio'])): ?>
              <div class="price-tag mb-2">$<?= number_format($o['precio'],2) ?></div>
            <?php endif; ?>
            <a href="<?= url('obra/'.$o['id']) ?>" class="btn-card btn">
              Ver obra <i class="fa fa-arrow-right ms-1"></i>
            </a>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ── ARTISTAS DESTACADOS ────────────────────────────────────────────── -->
<?php if (!empty($artistas)): ?>
<section class="section-magic" style="padding-top:0">
  <div class="container">
    <div class="d-flex justify-content-between align-items-end mb-4">
      <div>
        <h2 class="section-title mb-1">Artistas Destacados</h2>
        <p class="small mb-0" style="color:rgba(244,247,251,.4)">Mentes creativas del mundo Artcania</p>
      </div>
      <a href="<?= url('artistas') ?>" class="btn-magic-outline btn btn-sm">
        Ver todos <i class="fa fa-arrow-right ms-1"></i>
      </a>
    </div>
    <div class="row g-3">
      <?php foreach($artistas as $a): ?>
      <div class="col-6 col-md-3">
        <a href="<?= url('artistas/'.$a['usuario_id']) ?>" class="text-decoration-none">
          <div class="artist-card">
            <?php if(!empty($a['avatar'])): ?>
              <img src="<?= media_url('Originales/imagen/Avatares/'.$a['avatar']) ?>"
                   class="artist-avatar" alt="<?= e($a['nombre']) ?>">
            <?php else: ?>
              <div class="artist-avatar-placeholder">
                <?= mb_strtoupper(mb_substr($a['nombre'],0,1)) ?>
              </div>
            <?php endif; ?>
            <h6 class="mb-1" style="font-family:'Cinzel',serif;font-size:.88rem;color:var(--pearl)">
              <?= e($a['nombre']) ?>
            </h6>
            <small style="color:rgba(244,247,251,.4)"><?= e($a['especialidad'] ?? 'Artista') ?></small>
          </div>
        </a>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ── SUBASTAS ACTIVAS ───────────────────────────────────────────────── -->
<?php if (!empty($subastas)): ?>
<section class="section-magic" style="padding-top:0">
  <div class="container">
    <div class="d-flex justify-content-between align-items-end mb-4">
      <div>
        <h2 class="section-title mb-1">Subastas Activas</h2>
        <p class="small mb-0" style="color:rgba(244,247,251,.4)">Ofertas en tiempo real</p>
      </div>
      <a href="<?= url('subastas') ?>" class="btn-magic-outline btn btn-sm">
        Ver todas <i class="fa fa-arrow-right ms-1"></i>
      </a>
    </div>
    <div class="row g-3">
      <?php foreach($subastas as $s): ?>
      <div class="col-md-6 col-lg-4">
        <div class="card-magic">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-2">
              <h6 class="card-title mb-0"><?= e($s['titulo'] ?? '') ?></h6>
              <span class="badge" style="background:rgba(93,214,192,.15);color:var(--teal);border:1px solid rgba(93,214,192,.25)">
                <i class="fa fa-circle" style="font-size:.45rem;vertical-align:middle;animation:pulse-glow 1.5s infinite"></i>
                VIVO
              </span>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-3">
              <div>
                <small style="color:rgba(244,247,251,.4);font-size:.72rem">Oferta actual</small>
                <div class="price-tag">$<?= number_format($s['precio_actual'] ?? $s['precio_inicial'] ?? 0, 2) ?></div>
              </div>
              <a href="<?= url('subasta/'.$s['id']) ?>" class="btn-card btn" style="width:auto;padding:.4rem 1rem">
                Ofertar <i class="fa fa-gavel ms-1"></i>
              </a>
            </div>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ── CTA FINAL ──────────────────────────────────────────────────────── -->
<?php if (!Auth::check()): ?>
<section class="section-magic" style="padding-top:0;padding-bottom:6rem">
  <div class="container">
    <div class="text-center p-5" style="background:linear-gradient(135deg,rgba(92,77,155,.2),rgba(93,214,192,.08));
         border:1px solid rgba(166,189,255,.1);border-radius:20px">
      <div style="font-size:3rem;margin-bottom:1rem">✦</div>
      <h2 style="font-family:'Cinzel',serif;color:var(--pearl);margin-bottom:.75rem">
        ¿Listo para explorar el reino del arte?
      </h2>
      <p style="color:rgba(244,247,251,.5);max-width:500px;margin:0 auto 2rem">
        Únete a miles de artistas y amantes del arte que ya forman parte de Artcania.
      </p>
      <div class="d-flex justify-content-center gap-3 flex-wrap">
        <a href="<?= url('registro') ?>" class="btn-magic btn">
          <i class="fa fa-magic me-2"></i>Comenzar gratis
        </a>
        <a href="<?= url('galeria') ?>" class="btn-magic-outline btn">
          <i class="fa fa-images me-2"></i>Ver galería
        </a>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>
