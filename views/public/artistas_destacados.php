<?php $pageTitle = 'Artistas'; ?>
<div class="container-xl py-4">
  <div class="d-flex align-items-center justify-content-between mb-4">
    <h1 class="fs-5 font-cinzel mb-0">✦ Artistas Destacados</h1>
    <span class="badge-magic"><?= count($artistas ?? []) ?> artistas</span>
  </div>

  <?php if(!empty($artistas)): ?>
  <div class="row g-3">
    <?php foreach($artistas as $a): ?>
    <div class="col-sm-6 col-md-4 col-xl-3">
      <a href="<?= url('artistas/'.$a['usuario_id']) ?>" class="card-magic d-block text-decoration-none p-3 text-center">
        <img src="<?= avatar($a['avatar'] ?? '') ?>"
             class="rounded-circle mb-3 mx-auto d-block"
             style="width:72px;height:72px;object-fit:cover;border:3px solid var(--purple-mid);box-shadow:0 0 20px var(--purple-glow)"
             alt="<?= e($a['nombre']) ?>">
        <div style="font-weight:600;color:var(--pearl);font-size:.9rem;margin-bottom:.2rem">
          <?= e($a['nombre']) ?>
          <?php if(!empty($a['verificado'])): ?>
            <i class="fa fa-circle-check ms-1" style="color:var(--teal);font-size:.75rem"></i>
          <?php endif; ?>
        </div>
        <?php if(!empty($a['especialidad'])): ?>
          <div style="font-size:.75rem;color:var(--pearl-muted)"><?= e($a['especialidad']) ?></div>
        <?php endif; ?>
        <?php if(!empty($a['pais'])): ?>
          <div style="font-size:.73rem;color:var(--gold);margin-top:.3rem">
            <i class="fa fa-location-dot me-1"></i><?= e($a['pais']) ?>
          </div>
        <?php endif; ?>
        <div class="cat-line mt-2"></div>
      </a>
    </div>
    <?php endforeach; ?>
  </div>
  <?php else: ?>
  <div class="text-center py-5" style="color:var(--pearl-muted)">
    <i class="fa fa-palette fa-4x mb-3" style="opacity:.2;display:block"></i>
    <p>Aún no hay artistas destacados</p>
  </div>
  <?php endif; ?>
</div>

