<?php $pageTitle = 'Métricas de Arte'; ?>
<h2 class="fw-bold mb-4"><i class="fa fa-chart-line me-2"></i>Métricas de Arte</h2>
<?php
if(!isset($obras)) $obras = [];
$totalVistas = array_sum(array_column($obras,'visualizaciones'));
$totalObras  = count($obras);
$aprobadas   = count(array_filter($obras,fn($o)=>$o['estado']==='aprobada'));
?>
<div class="row g-4 mb-4">
  <div class="col-6 col-md-3"><div class="card bg-primary text-white shadow"><div class="card-body text-center"><h3><?=$totalObras?></h3><small>Total Obras</small></div></div></div>
  <div class="col-6 col-md-3"><div class="card bg-success text-white shadow"><div class="card-body text-center"><h3><?=$aprobadas?></h3><small>Aprobadas</small></div></div></div>
  <div class="col-6 col-md-3"><div class="card bg-info text-white shadow"><div class="card-body text-center"><h3><?=number_format($totalVistas)?></h3><small>Total Vistas</small></div></div></div>
  <div class="col-6 col-md-3"><div class="card bg-warning text-white shadow"><div class="card-body text-center"><h3><?= $totalObras>0 ? number_format($totalVistas/$totalObras,1) : 0 ?></h3><small>Prom. Vistas</small></div></div></div>
</div>
<div class="card shadow">
  <div class="card-header fw-bold"><i class="fa fa-table me-2"></i>Detalle por Obra</div>
  <div class="card-body p-0 table-responsive">
    <table class="table table-hover mb-0 tabla-dt">
      <thead class="table-light"><tr><th>Título</th><th>Estado</th><th>Vistas</th><th>Valoración</th><th>Precio</th><th>Fecha</th></tr></thead>
      <tbody>
        <?php foreach($obras as $o): ?>
        <tr>
          <td><?= e(truncate($o['titulo'],35)) ?></td>
          <td><span class="badge bg-<?= $o['estado']==='aprobada'?'success':($o['estado']==='pendiente'?'warning':'danger') ?>"><?= e($o['estado']) ?></span></td>
          <td><i class="fa fa-eye text-muted me-1"></i><?= number_format($o['visualizaciones']) ?></td>
          <td><?php if($o['valoracion_promedio']>0): ?><span class="text-warning">★</span> <?= number_format($o['valoracion_promedio'],1) ?><?php else: ?>-<?php endif; ?></td>
          <td><?= $o['precio'] ? money($o['precio']) : '-' ?></td>
          <td class="small"><?= format_date($o['creado_en'],'d/m/Y') ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<div class="mt-3 d-flex gap-2">
  <a href="<?= url('reporte/estadisticas/pdf') ?>" class="btn btn-outline-danger btn-sm" target="_blank"><i class="fa fa-file-pdf me-1"></i>Exportar PDF</a>
  <a href="<?= url('reporte/estadisticas/excel') ?>" class="btn btn-outline-success btn-sm" target="_blank"><i class="fa fa-file-excel me-1"></i>Exportar Excel</a>
</div>
