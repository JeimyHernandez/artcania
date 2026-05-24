<?php $pageTitle = 'Reportes de Contenido'; ?>
<h2 class="fw-bold mb-4"><i class="fa fa-flag me-2"></i>Reportes de Contenido</h2>
<?php if(!isset($reportes)) $reportes = []; ?>
<div class="card shadow">
  <div class="card-body p-0 table-responsive">
    <table class="table table-hover mb-0 tabla-dt">
      <thead class="table-dark"><tr><th>#</th><th>Reportador</th><th>Tipo</th><th>Motivo</th><th>Estado</th><th>Fecha</th></tr></thead>
      <tbody>
        <?php foreach($reportes as $r): ?>
        <tr>
          <td><?= $r['id'] ?></td>
          <td><?= e($r['reportador']) ?></td>
          <td><span class="badge bg-info"><?= e($r['tipo_contenido']) ?></span></td>
          <td><?= e(truncate($r['motivo'],40)) ?></td>
          <td><span class="badge bg-<?= $r['estado']==='pendiente'?'warning':($r['estado']==='accionado'?'danger':'secondary') ?>"><?= e($r['estado']) ?></span></td>
          <td class="small"><?= format_date($r['creado_en']) ?></td>
        </tr>
        <?php endforeach; ?>
        <?php if(empty($reportes)): ?><tr><td colspan="6" class="text-center text-muted py-3">No hay reportes pendientes.</td></tr><?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
