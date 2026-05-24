<?php $pageTitle = 'Historial de Validaciones'; ?>
<h2 class="fw-bold mb-4"><i class="fa fa-history me-2"></i>Historial de Validaciones</h2>
<?php if(!isset($historial)) $historial = []; ?>
<div class="card shadow">
  <div class="card-body p-0 table-responsive">
    <table class="table table-hover mb-0 tabla-dt">
      <thead class="table-dark"><tr><th>Obra</th><th>Estado Anterior</th><th>Nuevo Estado</th><th>Curador</th><th>Nota</th><th>Fecha</th></tr></thead>
      <tbody>
        <?php foreach($historial as $h): ?>
        <tr>
          <td><?= e(truncate($h['titulo'],30)) ?></td>
          <td><span class="badge bg-secondary"><?= e($h['estado_ant']??'nuevo') ?></span></td>
          <td><span class="badge bg-<?= $h['estado_nuevo']==='aprobada'?'success':'danger' ?>"><?= e($h['estado_nuevo']) ?></span></td>
          <td><?= e($h['curador']??'Sistema') ?></td>
          <td class="small"><?= e(truncate($h['nota']??'',40)) ?></td>
          <td class="small"><?= format_date($h['creado_en']) ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
