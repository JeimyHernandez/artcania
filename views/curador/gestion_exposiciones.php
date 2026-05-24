<?php $pageTitle = 'Gestión de Exposiciones'; ?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <h2 class="fw-bold"><i class="fa fa-building me-2"></i>Gestión de Exposiciones</h2>
  <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalExpo"><i class="fa fa-plus me-2"></i>Nueva</button>
</div>
<?php if(!isset($exposiciones)) $exposiciones = []; $expos = $exposiciones; ?>
<div class="row g-4">
  <?php foreach($expos as $ex): ?>
  <div class="col-md-6">
    <div class="card shadow">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <h5 class="fw-bold"><?= e($ex['titulo']) ?></h5>
            <p class="text-muted small"><?= e(truncate($ex['descripcion']??'',100)) ?></p>
          </div>
          <span class="badge bg-<?= $ex['publica']?'success':'secondary' ?>"><?= $ex['publica']?'Pública':'Borrador' ?></span>
        </div>
        <?php if($ex['fecha_inicio']): ?>
        <p class="small text-muted mb-0"><i class="fa fa-calendar me-1"></i><?= format_date($ex['fecha_inicio'],'d/m/Y') ?> – <?= format_date($ex['fecha_fin'],'d/m/Y') ?></p>
        <?php endif; ?>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
  <?php if(empty($expos)): ?><div class="col-12 text-center text-muted py-4">No hay exposiciones.</div><?php endif; ?>
</div>
<div class="modal fade" id="modalExpo" tabindex="-1">
  <div class="modal-dialog"><div class="modal-content">
    <div class="modal-header"><h5 class="modal-title fw-bold">Nueva Exposición</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
    <form method="POST" action="<?= url('curador/exposicion') ?>">
      <?= csrf_field() ?>
      <div class="modal-body">
        <div class="mb-3"><label class="form-label fw-semibold">Título *</label><input type="text" name="titulo" class="form-control" required></div>
        <div class="mb-3"><label class="form-label fw-semibold">Descripción</label><textarea name="descripcion" class="form-control" rows="3"></textarea></div>
        <div class="row g-2">
          <div class="col"><label class="form-label fw-semibold">Fecha inicio</label><input type="date" name="fecha_inicio" class="form-control"></div>
          <div class="col"><label class="form-label fw-semibold">Fecha fin</label><input type="date" name="fecha_fin" class="form-control"></div>
        </div>
        <div class="mt-3"><label class="form-label fw-semibold">Tipo</label>
          <select name="tipo" class="form-select"><option value="virtual">Virtual</option><option value="presencial">Presencial</option><option value="hibrida">Híbrida</option></select></div>
      </div>
      <div class="modal-footer"><button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button><button type="submit" class="btn btn-primary">Crear</button></div>
    </form>
  </div></div>
</div>
