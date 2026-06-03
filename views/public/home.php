<?php $pageTitle = 'Inicio'; ?>

<!-- HERO -->
<section class="hero-magic hero-centered">
  <div class="container-xl">
    <div class="row justify-content-center">
      <div class="col-lg-9 col-xl-8 text-center">
        <div class="divider-magic mb-3 mx-auto" style="max-width:200px">✦</div>
        <h1 class="hero-title mb-3">
          El <em>universo</em><br>donde el arte<br>no tiene <em>límites</em>
        </h1>
        <p class="hero-sub mb-4 mx-auto">
          Explora, conecta e inspírate con una comunidad apasionada por la creatividad y la imaginación.
        </p>
        <a href="<?= url('galeria') ?>" class="btn btn-magic px-4 py-2" style="font-size:.95rem">
          <i class="fa fa-compass me-2"></i>Explorar
        </a>
      </div>
    </div>
  </div>
</section>

<!-- Categorías -->
<section class="py-5">
  <div class="container-xl">
    <div class="text-center mb-4">
      <h2 class="fs-4" style="letter-spacing:.08em">Descubre el universo creativo</h2>
    </div>
    <div class="row g-3 justify-content-center">
      <?php
      $cats = [
        ['icon'=>'🎨','name'=>'Arte Digital', 'url'=>url('galeria?categoria=1')],
        ['icon'=>'🖼️','name'=>'Pintura',      'url'=>url('galeria?categoria=2')],
        ['icon'=>'📸','name'=>'Fotografía',   'url'=>url('galeria?categoria=4')],
        ['icon'=>'✏️','name'=>'Ilustración',  'url'=>url('galeria?categoria=5')],
        ['icon'=>'🌀','name'=>'Arte Abstracto','url'=>url('galeria?categoria=6')],
        ['icon'=>'🎭','name'=>'Técnica Mixta','url'=>url('galeria?categoria=9')],
      ];
      foreach($cats as $c): ?>
      <div class="col-6 col-sm-4 col-md-2">
        <a href="<?= $c['url'] ?>" class="category-card text-decoration-none">
          <span class="cat-icon"><?= $c['icon'] ?></span>
          <div class="cat-name"><?= $c['name'] ?></div>
          <div class="cat-line"></div>
        </a>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Stats -->
<div class="container-xl">
  <div class="stats-banner">
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

<!-- Obras recientes -->
<section class="py-5">
  <div class="container-xl">
    <div class="d-flex align-items-center justify-content-between mb-4">
      <h2 class="fs-5 mb-0">✦ Obras Recientes</h2>
      <a href="<?= url('galeria') ?>" class="btn btn-outline-magic btn-sm">Ver galería →</a>
    </div>
    <?php if(!empty($obras)): ?>
    <div class="gallery-grid">
      <?php foreach($obras as $o): ?>
      <div class="gallery-item" onclick="location.href='<?= url('galeria/'.$o['id']) ?>'">
        <?php if(!empty($o['imagen_principal'])): ?>
          <img src="<?= media_url('Originales/imagen/Obras_digitales/'.$o['imagen_principal']) ?>"
               alt="<?= e($o['titulo']) ?>" loading="lazy"
               onerror="this.src='<?= url('resources/img/placeholder.png') ?>'">
        <?php else: ?>
          <div style="height:200px;background:var(--purple-soft);display:flex;align-items:center;justify-content:center;font-size:2rem">🎨</div>
        <?php endif; ?>
        <button class="gallery-fav-btn fav-btn <?= Auth::check()?'':'d-none' ?>"
                data-id="<?= $o['id'] ?>" title="Favorito">
          <i class="fa fa-heart"></i>
        </button>
        <div class="gallery-item-overlay">
          <div class="obra-title"><?= e(truncate($o['titulo'], 35)) ?></div>
          <div class="obra-artist">✦ <?= e($o['artista_nombre'] ?? '') ?></div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="text-center py-5" style="color:var(--pearl-muted)">
      <i class="fa fa-image fa-3x mb-3" style="opacity:.3"></i>
      <p>Aún no hay obras publicadas</p>
    </div>
    <?php endif; ?>
  </div>
</section>

<!-- Artistas destacados -->
<?php if(!empty($artistas)): ?>
<section class="py-4" style="background:rgba(124,58,237,.04);border-top:1px solid var(--border);border-bottom:1px solid var(--border)">
  <div class="container-xl">
    <div class="d-flex align-items-center justify-content-between mb-4">
      <h2 class="fs-5 mb-0">✦ Artistas Destacados</h2>
      <a href="<?= url('artistas') ?>" class="btn btn-outline-magic btn-sm">Ver todos →</a>
    </div>
    <div class="row g-3">
      <?php foreach($artistas as $a): ?>
      <div class="col-6 col-md-3">
        <a href="<?= url('artistas/'.$a['usuario_id']) ?>" class="card-magic d-block text-decoration-none text-center p-3">
          <img src="<?= avatar($a['avatar'] ?? '') ?>"
               class="rounded-circle mb-2 border"
               style="width:64px;height:64px;object-fit:cover;border-color:var(--purple-mid)!important"
               alt="<?= e($a['nombre']) ?>">
          <div style="font-size:.9rem;font-weight:600;color:var(--pearl)"><?= e($a['nombre']) ?></div>
          <div style="font-size:.75rem;color:var(--pearl-muted)"><?= e($a['especialidad'] ?? '') ?></div>
        </a>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- Subastas activas -->
<?php if(!empty($subastas)): ?>
<section class="py-5">
  <div class="container-xl">
    <div class="d-flex align-items-center justify-content-between mb-4">
      <h2 class="fs-5 mb-0">✦ Subastas Activas</h2>
      <a href="<?= url('subastas') ?>" class="btn btn-outline-magic btn-sm">Ver todas →</a>
    </div>
    <div class="row g-3">
      <?php foreach(array_slice($subastas, 0, 4) as $s): ?>
      <div class="col-md-6 col-lg-3">
        <div class="card-magic">
          <?php if(!empty($s['imagen_principal'])): ?>
            <img src="<?= media_url('Originales/imagen/Obras_digitales/'.$s['imagen_principal']) ?>"
                 class="card-img-top" alt="<?= e($s['titulo'] ?? '') ?>" style="height:160px;object-fit:cover"
                 onerror="this.src='<?= url('resources/img/placeholder.png') ?>'">
          <?php endif; ?>
          <div class="card-body">
            <div class="card-title mb-1"><?= e(truncate($s['titulo'] ?? 'Subasta', 28)) ?></div>
            <div class="d-flex align-items-center justify-content-between mb-2">
              <span style="font-size:.78rem;color:var(--pearl-muted)">Precio actual</span>
              <span class="badge-gold">$<?= number_format($s['precio_actual'] ?? $s['precio_inicial'] ?? 0, 2) ?></span>
            </div>
            <div style="font-size:.75rem;color:var(--pearl-muted)" class="mb-2">
              <i class="fa fa-clock me-1" style="color:var(--gold)"></i>
              Cierra: <?= format_date($s['fecha_fin'] ?? '') ?>
            </div>
            <a href="<?= url('subastas') ?>" class="btn btn-magic btn-sm w-100">
              <i class="fa fa-gavel me-1"></i>Ver subasta
            </a>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

