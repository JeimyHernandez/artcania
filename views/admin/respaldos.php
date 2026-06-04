<?php $pageTitle = 'Respaldos del Sistema'; ?>
<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
  <h4 class="font-cinzel mb-0" style="color:var(--gold-light)"><i class="fa fa-database me-2"></i>Respaldos del Sistema</h4>
  <form method="POST" action="<?= url('admin/respaldo/crear') ?>" class="d-inline">
    <?= csrf_field() ?>
    <select name="tipo" class="form-select form-select-sm d-inline-block" style="width:auto;background:var(--card-bg);color:var(--pearl);border:1px solid var(--border)">
      <option value="bd_solo">Solo BD</option>
      <option value="completo">Completo</option>
      <option value="archivos">Solo Archivos</option>
    </select>
    <button type="submit" class="btn btn-magic btn-sm ms-2">
      <i class="fa fa-plus me-1"></i>Crear Respaldo
    </button>
  </form>
</div>

<div class="card-magic p-0">
  <div class="table-responsive">
    <table class="artcania-table w-100">
      <thead>
        <tr><th>#</th><th>Nombre</th><th>Tipo</th><th>Creado por</th><th>Fecha</th></tr>
      </thead>
      <tbody>
        <?php if(empty($respaldos)): ?>
          <tr><td colspan="5" class="text-center py-4" style="color:var(--pearl-muted)">No hay respaldos registrados.</td></tr>
        <?php else: foreach($respaldos as $r): ?>
          <tr>
            <td><?= e($r['id']) ?></td>
            <td style="font-size:.85rem"><?= e($r['nombre']) ?></td>
            <td><span class="badge-magic" style="font-size:.72rem"><?= e($r['tipo']) ?></span></td>
            <td><?= e($r['creado_por'] ?? '—') ?></td>
            <td style="font-size:.8rem;white-space:nowrap"><?= format_date($r['creado_en'] ?? '') ?></td>
          </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>
