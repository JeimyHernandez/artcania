<?php $pageTitle = 'Gestión de Artistas'; ?>
<?php if(!isset($artistas)) $artistas = []; ?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <h2 class="fw-bold"><i class="fa fa-palette me-2"></i>Gestión de Artistas</h2>
  <span class="badge bg-secondary fs-6"><?= count($artistas) ?> artistas</span>
</div>
<div class="row g-4">
  <?php foreach($artistas as $a): ?>
  <div class="col-md-4 col-lg-3">
    <div class="card shadow-sm text-center p-3 h-100">
      <img src="<?= avatar($a['avatar']??'') ?>" class="rounded-circle mx-auto mb-2" width="70" height="70" style="object-fit:cover">
      <h6 class="fw-bold mb-1"><?= e($a['nombre']) ?></h6>
      <small class="text-muted"><?= e($a['email']) ?></small><br>
      <span class="badge bg-<?= $a['verificado']?'success':'warning' ?> mt-1"><?= $a['verificado']?'Verificado':'Pendiente' ?></span>
      <?php if($a['destacado']): ?><span class="badge bg-warning ms-1">⭐ Destacado</span><?php endif; ?>
    </div>
  </div>
  <?php endforeach; ?>
  <?php if(empty($artistas)): ?><div class="col-12 text-center text-muted py-4">No hay artistas registrados.</div><?php endif; ?>
</div>
