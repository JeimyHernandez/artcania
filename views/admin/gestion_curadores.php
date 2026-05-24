<?php $pageTitle = 'Gestión de Curadores'; ?>
<?php $curadores = Database::getInstance()->query("SELECT u.*,c.especialidad FROM curadores c JOIN usuarios u ON u.id=c.usuario_id")->fetchAll(); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <h2 class="fw-bold"><i class="fa fa-eye me-2"></i>Gestión de Curadores</h2>
</div>
<div class="card shadow">
  <div class="card-body p-0 table-responsive">
    <table class="table table-hover mb-0 tabla-dt">
      <thead class="table-dark"><tr><th>#</th><th>Nombre</th><th>Email</th><th>Especialidad</th><th>Estado</th></tr></thead>
      <tbody>
        <?php foreach($curadores as $c): ?>
        <tr>
          <td><?= $c['id'] ?></td><td><?= e($c['nombre']) ?></td>
          <td><?= e($c['email']) ?></td><td><?= e($c['especialidad']??'-') ?></td>
          <td><span class="badge bg-<?= $c['activo']?'success':'danger' ?>"><?= $c['activo']?'Activo':'Inactivo' ?></span></td>
        </tr>
        <?php endforeach; ?>
        <?php if(empty($curadores)): ?><tr><td colspan="5" class="text-center text-muted py-3">Sin curadores registrados</td></tr><?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
