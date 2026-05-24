<?php $pageTitle = 'Gestión de Usuarios'; ?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <h2 class="fw-bold"><i class="fa fa-users me-2"></i>Gestión de Usuarios</h2>
  <span class="badge bg-secondary fs-6"><?= count($users) ?> usuarios</span>
</div>
<div class="card shadow">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover mb-0 tabla-dt">
        <thead class="table-dark"><tr>
          <th>#</th><th>Nombre</th><th>Email</th><th>Rol</th><th>Verificado</th><th>Activo</th><th>Fecha</th><th>Acciones</th>
        </tr></thead>
        <tbody>
          <?php foreach($users as $u): ?>
          <tr>
            <td><?= e($u['id']) ?></td>
            <td><?= e($u['nombre']) ?></td>
            <td><?= e($u['email']) ?></td>
            <td><span class="badge bg-<?= $u['rol']==='admin'?'danger':($u['rol']==='artista'?'success':($u['rol']==='curador'?'warning':'secondary')) ?>"><?= e($u['rol']) ?></span></td>
            <td>
              <?php if($u['verificado']): ?>
                <i class="fa fa-check-circle text-success" title="Verificado"></i>
              <?php else: ?>
                <form method="POST" action="<?= url('admin/verificar-usuario') ?>" style="display:inline">
                  <?= csrf_field() ?>
                  <input type="hidden" name="id" value="<?= $u['id'] ?>">
                  <button type="submit" class="btn btn-xs btn-outline-warning py-0 px-1"
                          style="font-size:.75rem" title="Verificar manualmente"
                          onclick="return confirm('¿Verificar manualmente a <?= e($u['nombre']) ?>?')">
                    <i class="fa fa-user-check me-1"></i>Verificar
                  </button>
                </form>
              <?php endif; ?>
            </td>
            <td>
              <div class="form-check form-switch">
                <input class="form-check-input toggle-activo" type="checkbox" data-id="<?= $u['id'] ?>" <?= $u['activo'] ? 'checked' : '' ?>>
              </div>
            </td>
            <td><small><?= format_date($u['creado_en'],'d/m/Y') ?></small></td>
            <td>
              <button class="btn btn-sm btn-outline-warning cambiar-rol" data-id="<?= $u['id'] ?>" data-rol="<?= $u['rol'] ?>"><i class="fa fa-user-tag"></i></button>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<script>
const csrf = '<?= csrf_token() ?>';
document.querySelectorAll('.toggle-activo').forEach(el => {
  el.addEventListener('change', function(){
    fetch('<?= url('admin/toggle-usuario') ?>', {
      method:'POST',
      headers:{'Content-Type':'application/x-www-form-urlencoded','X-Requested-With':'XMLHttpRequest'},
      body:`_csrf=${csrf}&id=${this.dataset.id}&activo=${this.checked?1:0}`
    }).then(r=>r.json()).then(d=>{ if(!d.ok) this.checked=!this.checked; });
  });
});
</script>
