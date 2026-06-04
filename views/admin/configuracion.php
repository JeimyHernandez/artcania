<?php $pageTitle = 'Configuración del Sistema'; ?>
<div class="d-flex align-items-center justify-content-between mb-4">
  <h4 class="font-cinzel mb-0" style="color:var(--gold-light)"><i class="fa fa-gear me-2"></i>Configuración del Sistema</h4>
</div>

<form method="POST" action="<?= url('admin/configuracion') ?>">
  <?= csrf_field() ?>
  <div class="card-magic p-4">
    <?php if(empty($configs)): ?>
      <p style="color:var(--pearl-muted)">No hay configuraciones disponibles en la base de datos.</p>
    <?php else: ?>
      <div class="row g-3">
        <?php foreach($configs as $cfg): ?>
          <div class="col-md-6">
            <label class="form-label-magic"><?= e($cfg['clave']) ?></label>
            <?php if($cfg['tipo'] === 'bool'): ?>
              <select name="cfg[<?= e($cfg['clave']) ?>]" class="form-control form-control-magic">
                <option value="1" <?= $cfg['valor']=='1'?'selected':'' ?>>Activado</option>
                <option value="0" <?= $cfg['valor']!='1'?'selected':'' ?>>Desactivado</option>
              </select>
            <?php else: ?>
              <input type="text" name="cfg[<?= e($cfg['clave']) ?>]"
                     class="form-control form-control-magic"
                     value="<?= e($cfg['valor']) ?>">
            <?php endif; ?>
            <?php if(!empty($cfg['descripcion'])): ?>
              <small style="color:var(--pearl-muted)"><?= e($cfg['descripcion']) ?></small>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      </div>
      <div class="mt-4">
        <button type="submit" class="btn btn-magic">
          <i class="fa fa-floppy-disk me-2"></i>Guardar configuración
        </button>
      </div>
    <?php endif; ?>
  </div>
</form>
