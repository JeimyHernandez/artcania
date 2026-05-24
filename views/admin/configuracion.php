<?php $pageTitle = 'Configuración del Sistema'; ?>
<?php if(!isset($configs)) $configs = []; ?>
<h2 class="fw-bold mb-4"><i class="fa fa-cog me-2"></i>Configuración del Sistema</h2>
<div class="card shadow">
  <div class="card-body">
    <form method="POST" action="<?= url('admin/configuracion') ?>">
      <?= csrf_field() ?>
      <div class="row g-3">
        <?php foreach($configs as $cfg): ?>
        <div class="col-md-6">
          <label class="form-label fw-semibold">
            <?= e(ucwords(str_replace('_',' ',$cfg['clave']))) ?>
            <small class="text-muted fw-normal">(<?= e($cfg['tipo']) ?>)</small>
          </label>
          <?php if($cfg['tipo']==='bool'): ?>
          <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="cfg[<?= e($cfg['clave']) ?>]" value="1" <?= $cfg['valor']?'checked':'' ?>>
          </div>
          <?php else: ?>
          <input type="text" name="cfg[<?= e($cfg['clave']) ?>]" class="form-control" value="<?= e($cfg['valor']) ?>">
          <?php endif; ?>
          <?php if($cfg['descripcion']): ?><small class="text-muted"><?= e($cfg['descripcion']) ?></small><?php endif; ?>
        </div>
        <?php endforeach; ?>
      </div>
      <hr>
      <button type="submit" class="btn btn-primary"><i class="fa fa-save me-2"></i>Guardar Cambios</button>
    </form>
  </div>
</div>
