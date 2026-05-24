<?php $pageTitle = 'Panel Curador'; ?>
<h2 class="fw-bold mb-4"><i class="fa fa-eye me-2"></i>Panel del Curador</h2>
<div class="row g-4">
  <div class="col-md-6">
    <div class="card shadow">
      <div class="card-header fw-bold"><i class="fa fa-clock text-warning me-2"></i>Obras Pendientes (<?= count($pendientes) ?>)</div>
      <div class="card-body p-0">
        <?php foreach($pendientes as $o): ?>
        <div class="border-bottom p-3">
          <div class="d-flex gap-3">
            <?php if($o['imagen_principal']): ?><img src="<?= media_url('Originales/imagen/Obras_digitales/'.$o['imagen_principal']) ?>" width="60" height="60" style="object-fit:cover;border-radius:6px"><?php endif; ?>
            <div class="flex-grow-1">
              <strong><?= e($o['titulo']) ?></strong>
              <small class="d-block text-muted">por <?= e($o['artista_nombre']) ?> &middot; <?= format_date($o['creado_en'],'d/m/Y') ?></small>
              <p class="small mb-2"><?= e(truncate($o['descripcion']??'',80)) ?></p>
              <form method="POST" action="<?= url('curador/validar') ?>" class="d-flex gap-2 align-items-center">
                <?= csrf_field() ?>
                <input type="hidden" name="obra_id" value="<?= $o['id'] ?>">
                <input type="text" name="nota" class="form-control form-control-sm" placeholder="Nota (opcional)">
                <button name="estado" value="aprobada" class="btn btn-success btn-sm"><i class="fa fa-check"></i> Aprobar</button>
                <button name="estado" value="rechazada" class="btn btn-danger btn-sm"><i class="fa fa-times"></i> Rechazar</button>
              </form>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
        <?php if(empty($pendientes)): ?><div class="p-4 text-center text-muted"><i class="fa fa-check-circle fa-2x text-success mb-2 d-block"></i>No hay obras pendientes</div><?php endif; ?>
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="card shadow">
      <div class="card-header fw-bold"><i class="fa fa-comments text-warning me-2"></i>Comentarios Pendientes (<?= count($comentarios) ?>)</div>
      <div class="card-body p-0">
        <?php foreach($comentarios as $c): ?>
        <div class="border-bottom p-3">
          <strong><?= e($c['nombre']) ?></strong> en <em><?= e($c['titulo']) ?></em>
          <p class="small mb-1 mt-1"><?= e(truncate($c['contenido'],100)) ?></p>
          <small class="text-muted"><?= format_date($c['creado_en']) ?></small>
        </div>
        <?php endforeach; ?>
        <?php if(empty($comentarios)): ?><div class="p-4 text-center text-muted">No hay comentarios pendientes</div><?php endif; ?>
      </div>
    </div>
  </div>
</div>
