<?php
$pageTitle = 'Exposiciones';
if (!isset($exposiciones) || !is_array($exposiciones)) {
    $exposiciones = [];
}
?>
<div class="container py-5">
  <h2 class="fw-bold mb-4"><i class="fa fa-building text-primary me-2"></i>Exposiciones Virtuales</h2>
  <div class="row g-4">
    <?php foreach($exposiciones as $expo): ?>
    <div class="col-md-6 col-lg-4">
      <div class="card shadow hover-card h-100">
        <div class="card-body">
          <h5 class="fw-bold"><?= e($expo['titulo']??'Exposición') ?></h5>
          <p class="text-muted"><?= e(truncate($expo['descripcion']??'',100)) ?></p>
          <?php if(!empty($expo['fecha_inicio'])): ?>
          <p class="small"><i class="fa fa-calendar me-1 text-primary"></i><?= format_date($expo['fecha_inicio'],'d/m/Y') ?> – <?= format_date($expo['fecha_fin'],'d/m/Y') ?></p>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
    <?php if(empty($exposiciones)): ?><div class="col-12 text-center py-5 text-muted"><i class="fa fa-building fa-3x mb-3 d-block"></i>No hay exposiciones programadas.</div><?php endif; ?>
  </div>
</div>
