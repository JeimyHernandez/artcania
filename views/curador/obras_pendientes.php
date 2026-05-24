<?php $pageTitle = 'Obras Pendientes'; ?>
<h2 class="fw-bold mb-4"><i class="fa fa-clock text-warning me-2"></i>Obras Pendientes de Revisión</h2>
<?php if(!isset($pendientes)) $pendientes = []; ?>
<div class="row g-4">
  <?php foreach($pendientes as $o): ?>
  <div class="col-md-6">
    <div class="card shadow h-100">
      <div class="row g-0">
        <div class="col-4">
          <?php if($o['imagen_principal']): ?>
          <img src="<?= media_url('Originales/imagen/Obras_digitales/'.$o['imagen_principal']) ?>" class="img-fluid rounded-start h-100" style="object-fit:cover">
          <?php else: ?>
          <div class="bg-light h-100 d-flex align-items-center justify-content-center"><i class="fa fa-image fa-2x text-muted"></i></div>
          <?php endif; ?>
        </div>
        <div class="col-8">
          <div class="card-body">
            <h6 class="fw-bold"><?= e($o['titulo']) ?></h6>
            <small class="text-muted d-block mb-1">por <?= e($o['artista_nombre']) ?></small>
            <small class="text-muted d-block mb-2"><?= format_date($o['creado_en']) ?></small>
            <p class="small"><?= e(truncate($o['descripcion']??'',80)) ?></p>
            <form method="POST" action="<?= url('curador/validar') ?>">
              <?= csrf_field() ?>
              <input type="hidden" name="obra_id" value="<?= $o['id'] ?>">
              <input type="text" name="nota" class="form-control form-control-sm mb-2" placeholder="Nota para el artista...">
              <div class="d-flex gap-2">
                <button name="estado" value="aprobada" class="btn btn-success btn-sm flex-grow-1"><i class="fa fa-check me-1"></i>Aprobar</button>
                <button name="estado" value="rechazada" class="btn btn-danger btn-sm flex-grow-1"><i class="fa fa-times me-1"></i>Rechazar</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
  <?php if(empty($pendientes)): ?>
  <div class="col-12 text-center py-5">
    <i class="fa fa-check-circle fa-4x text-success mb-3 d-block"></i>
    <h5>¡Todo al día! No hay obras pendientes.</h5>
  </div>
  <?php endif; ?>
</div>
