<?php
/** @var array $obra */
?>
<div class="card h-100 shadow-sm hover-card">
  <?php if($obra['imagen_principal']): ?>
  <img src="<?= media_url('Originales/imagen/Obras_digitales/'.$obra['imagen_principal']) ?>" class="card-img-top" style="height:200px;object-fit:cover" alt="<?= e($obra['titulo']) ?>">
  <?php else: ?>
  <div class="bg-light d-flex align-items-center justify-content-center" style="height:200px">
    <i class="fa fa-image fa-3x text-muted"></i>
  </div>
  <?php endif; ?>
  <div class="card-body p-3">
    <h6 class="card-title fw-bold mb-1"><?= e(truncate($obra['titulo'],40)) ?></h6>
    <small class="text-muted">por <?= e($obra['artista_nombre']??'') ?></small>
    <div class="d-flex justify-content-between align-items-center mt-2">
      <?php if($obra['precio']): ?>
      <span class="text-success fw-bold"><?= money($obra['precio']) ?></span>
      <?php else: ?>
      <span class="badge bg-secondary">Sin precio</span>
      <?php endif; ?>
      <small class="text-muted"><i class="fa fa-eye me-1"></i><?= number_format($obra['visualizaciones']??0) ?></small>
    </div>
  </div>
  <div class="card-footer bg-transparent border-0 p-2">
    <a href="<?= url('obra/'.$obra['id']) ?>" class="btn btn-sm btn-primary w-100">Ver Obra</a>
  </div>
</div>

