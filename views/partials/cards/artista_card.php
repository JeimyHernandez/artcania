<?php
/** @var array $artista */
?>
<div class="card text-center shadow-sm hover-card h-100">
  <div class="card-body p-4">
    <img src="<?= avatar($artista['avatar']??'') ?>" class="rounded-circle mb-3 shadow" width="80" height="80" style="object-fit:cover">
    <h5 class="fw-bold mb-1"><?= e($artista['nombre']) ?></h5>
    <p class="text-muted small mb-2"><?= e($artista['especialidad']??'Artista') ?></p>
    <?php if(!empty($artista['pais'])): ?><span class="badge bg-light text-dark"><?= e($artista['pais']) ?></span><?php endif; ?>
    <?php if($artista['destacado']??false): ?><span class="badge bg-warning text-dark ms-1">⭐</span><?php endif; ?>
  </div>
  <div class="card-footer bg-transparent">
    <a href="<?= url('buscar?q='.urlencode($artista['nombre'])) ?>" class="btn btn-sm btn-outline-purple w-100">Ver Obras</a>
  </div>
</div>
