<?php $pageTitle = 'Gestión de Exposiciones'; ?>
<?php if(!isset($exposiciones)) $exposiciones = []; $expos = $exposiciones; ?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <h2 class="fw-bold"><i class="fa fa-building me-2"></i>Gestión de Exposiciones</h2>
  <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalExpo"><i class="fa fa-plus me-2"></i>Nueva Exposición</button>
</div>
<div class="row g-4">
  <?php foreach($expos as $ex): ?>
  <div class="col-md-4">
    <div class="card shadow-sm h-100">
      <div class="card-body">
        <h6 class="fw-bold"><?= e($ex['titulo']) ?></h6>
        <p class="text-muted small"><?= e(truncate($ex['descripcion']??'',80)) ?></p>
        <small class="text-muted">
          <?= $ex['fecha_inicio'] ? format_date($ex['fecha_inicio'],'d/m/Y').' – '.format_date($ex['fecha_fin'],'d/m/Y') : 'Sin fechas' ?>
        </small>
      </div>
      <div class="card-footer d-flex gap-1">
        <span class="badge bg-<?= $ex['publica']?'success':'secondary' ?>"><?= $ex['publica']?'Pública':'Borrador' ?></span>
        <span class="badge bg-info"><?= e($ex['tipo']) ?></span>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
  <?php if(empty($expos)): ?><div class="col-12 text-center text-muted py-4">No hay exposiciones registradas.</div><?php endif; ?>
</div>
