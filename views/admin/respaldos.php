<?php $pageTitle = 'Respaldos'; ?>
<h2 class="fw-bold mb-4"><i class="fa fa-database me-2"></i>Respaldos del Sistema</h2>
<div class="row g-4">
  <div class="col-md-5">
    <div class="card shadow">
      <div class="card-header fw-bold"><i class="fa fa-plus-circle me-2"></i>Crear Respaldo</div>
      <div class="card-body">
        <p class="text-muted small">Genera un respaldo de la base de datos. Los respaldos se guardan en el servidor.</p>
        <form method="POST" action="<?= url('admin/respaldo/crear') ?>">
          <?= csrf_field() ?>
          <div class="mb-3">
            <label class="form-label fw-semibold">Tipo</label>
            <select name="tipo" class="form-select">
              <option value="bd_solo">Solo Base de Datos</option>
              <option value="completo">Completo (BD + Media)</option>
            </select>
          </div>
          <button type="submit" class="btn btn-danger w-100"><i class="fa fa-download me-2"></i>Generar Respaldo</button>
        </form>
      </div>
    </div>
  </div>
  <div class="col-md-7">
    <div class="card shadow">
      <div class="card-header fw-bold"><i class="fa fa-history me-2"></i>Historial de Respaldos</div>
      <div class="card-body">
        <?php if(!isset($respaldos)) $respaldos = []; ?>
        <?php if(empty($respaldos)): ?>
        <p class="text-muted">No hay respaldos registrados.</p>
        <?php else: ?>
        <ul class="list-group list-group-flush">
          <?php foreach($respaldos as $r): ?>
          <li class="list-group-item d-flex justify-content-between">
            <div><strong><?= e($r['nombre']) ?></strong><br><small class="text-muted"><?= format_date($r['creado_en']) ?></small></div>
            <span class="badge bg-secondary align-self-center"><?= e($r['tipo']) ?></span>
          </li>
          <?php endforeach; ?>
        </ul>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
