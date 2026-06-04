<?php $pageTitle = e($artista['nombre'] ?? 'Perfil'); ?>
<div class="container-xl py-4">

  <!-- Header artista -->
  <div class="card-magic p-4 mb-4 overflow-hidden" style="position:relative">
    <div style="position:absolute;top:0;right:0;width:300px;height:100%;background:linear-gradient(90deg,transparent,rgba(124,58,237,.1));pointer-events:none"></div>
    <div class="d-flex align-items-center gap-4 flex-wrap">
      <img src="<?= avatar($artista['avatar'] ?? '') ?>"
           class="rounded-circle flex-shrink-0"
           style="width:96px;height:96px;object-fit:cover;border:3px solid var(--gold);box-shadow:0 0 25px var(--gold-glow)"
           alt="<?= e($artista['nombre'] ?? '') ?>">
      <div class="flex-grow-1">
        <div class="d-flex align-items-center gap-2 mb-1">
          <h2 class="fs-4 mb-0"><?= e($artista['nombre'] ?? '') ?></h2>
          <?php if(!empty($artista['verificado'])): ?>
            <span class="badge-teal" style="font-size:.7rem"><i class="fa fa-circle-check me-1"></i>Verificado</span>
          <?php endif; ?>
        </div>
        <?php if(!empty($artista['especialidad'])): ?>
          <div style="color:var(--pearl-muted);font-size:.88rem;margin-bottom:.4rem"><?= e($artista['especialidad']) ?></div>
        <?php endif; ?>
        <?php if(!empty($artista['pais'])): ?>
          <div style="color:var(--gold);font-size:.8rem"><i class="fa fa-location-dot me-1"></i><?= e($artista['pais']) ?></div>
        <?php endif; ?>
        <?php if(!empty($artista['descripcion'])): ?>
          <p style="font-size:.85rem;color:var(--pearl-dim);margin-top:.75rem;max-width:600px;line-height:1.6">
            <?= nl2br(e($artista['descripcion'])) ?>
          </p>
        <?php endif; ?>
      </div>
      <?php if(Auth::check() && Auth::id() !== (int)($artista['id'] ?? 0)): ?>
      <a href="<?= url('chat') ?>" class="btn btn-magic flex-shrink-0">
        <i class="fa fa-paper-plane me-1"></i>Contactar
      </a>
      <?php endif; ?>
    </div>
  </div>

  <!-- Obras del artista -->
  <h5 class="font-cinzel mb-3">✦ Obras (<?= count($obras ?? []) ?>)</h5>
  <?php if(!empty($obras)): ?>
  <div class="gallery-grid">
    <?php foreach($obras as $o): ?>
    <div class="gallery-item" onclick="location.href='<?= url('galeria/'.$o['id']) ?>'">
      <?php if(!empty($o['imagen_principal'])): ?>
        <img src="<?= media_url('Originales/imagen/Obras_digitales/'.$o['imagen_principal']) ?>"
             alt="<?= e($o['titulo']) ?>" loading="lazy"
             onerror="this.src='<?= url('resources/img/placeholder.png') ?>'">
      <?php else: ?>
        <div style="height:180px;background:var(--purple-soft);display:flex;align-items:center;justify-content:center;font-size:2rem">🎨</div>
      <?php endif; ?>
      <div class="gallery-item-overlay">
        <div class="obra-title"><?= e(truncate($o['titulo'],35)) ?></div>
        <div style="font-size:.7rem;color:var(--pearl-muted)">
          <i class="fa fa-eye me-1"></i><?= number_format($o['visualizaciones'] ?? 0) ?>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  <?php else: ?>
  <div class="text-center py-5" style="color:var(--pearl-muted)">
    <i class="fa fa-images fa-3x mb-2" style="opacity:.2;display:block"></i>
    <p>Este artista aún no ha publicado obras</p>
  </div>
  <?php endif; ?>
</div>
