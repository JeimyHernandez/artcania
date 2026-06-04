<?php $pageTitle = 'Bitácora de Auditoría'; ?>
<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
  <h4 class="font-cinzel mb-0" style="color:var(--gold-light)">
    <i class="fa fa-scroll me-2"></i>Bitácora de Auditoría
  </h4>
  <!-- Botones de exportación -->
  <div class="d-flex gap-2 flex-wrap">
    <a href="<?= url('reporte/bitacora/pdf') ?>" target="_blank"
       class="btn btn-sm btn-outline-magic"
       title="Abre en el navegador — usa Ctrl+P para guardar como PDF">
      <i class="fa fa-file-pdf me-1" style="color:#f87171"></i>PDF
    </a>
    <a href="<?= url('reporte/bitacora/excel') ?>"
       class="btn btn-sm btn-outline-magic"
       title="Descargar como Excel (.xlsx)">
      <i class="fa fa-file-excel me-1" style="color:#4ade80"></i>Excel
    </a>
    <a href="<?= url('reporte/bitacora/word') ?>"
       class="btn btn-sm btn-outline-magic"
       title="Descargar como Word (.docx)">
      <i class="fa fa-file-word me-1" style="color:#60a5fa"></i>Word
    </a>
  </div>
</div>

<!-- Filtros -->
<form method="GET" action="<?= url('admin/bitacora') ?>" class="card-magic p-3 mb-4">
  <div class="row g-2 align-items-end">
    <div class="col-sm-3">
      <label class="form-label-magic">Desde</label>
      <input type="date" name="desde" class="form-control form-control-magic"
             value="<?= e($filters['desde'] ?? '') ?>">
    </div>
    <div class="col-sm-3">
      <label class="form-label-magic">Hasta</label>
      <input type="date" name="hasta" class="form-control form-control-magic"
             value="<?= e($filters['hasta'] ?? '') ?>">
    </div>
    <div class="col-sm-3">
      <label class="form-label-magic">Acción</label>
      <input type="text" name="accion" class="form-control form-control-magic"
             placeholder="LOGIN, OBRA_CREADA…"
             value="<?= e($filters['accion'] ?? '') ?>">
    </div>
    <div class="col-sm-3">
      <button type="submit" class="btn btn-magic w-100">
        <i class="fa fa-filter me-1"></i>Filtrar
      </button>
    </div>
  </div>
</form>

<div class="card-magic p-0">
  <div class="table-responsive">
    <table class="artcania-table w-100">
      <thead>
        <tr>
          <th>#</th><th>Usuario</th><th>Email</th>
          <th>Acción</th><th>Detalle</th><th>IP</th><th>Fecha</th>
        </tr>
      </thead>
      <tbody>
        <?php if(empty($logs)): ?>
          <tr><td colspan="7" class="text-center py-4" style="color:var(--pearl-muted)">No hay registros.</td></tr>
        <?php else: foreach($logs as $l): ?>
          <tr>
            <td><?= e($l['id']) ?></td>
            <td><?= e($l['nombre'] ?? '—') ?></td>
            <td style="font-size:.8rem"><?= e($l['email'] ?? '—') ?></td>
            <td><span class="badge-magic" style="font-size:.72rem"><?= e($l['accion']) ?></span></td>
            <td style="font-size:.8rem;max-width:220px;word-break:break-word">
              <?= e(mb_substr($l['detalle'] ?? '', 0, 80)) ?>
            </td>
            <td style="font-size:.8rem"><?= e($l['ip'] ?? '—') ?></td>
            <td style="font-size:.8rem;white-space:nowrap"><?= format_date($l['creado_en']) ?></td>
          </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Paginación -->
<?php if(($pages ?? 1) > 1): ?>
<nav class="mt-3 d-flex justify-content-center gap-1 flex-wrap">
  <?php for($i=1; $i<=$pages; $i++): ?>
    <a href="<?= url('admin/bitacora') ?>?page=<?= $i ?>&desde=<?= urlencode($filters['desde']??'') ?>&hasta=<?= urlencode($filters['hasta']??'') ?>&accion=<?= urlencode($filters['accion']??'') ?>"
       class="btn btn-sm <?= $i===$page ? 'btn-magic' : 'btn-outline-magic' ?>"><?= $i ?></a>
  <?php endfor; ?>
</nav>
<p class="text-center mt-2" style="color:var(--pearl-muted);font-size:.8rem">
  <?= number_format($total??0) ?> registros en total
</p>
<?php endif; ?>
