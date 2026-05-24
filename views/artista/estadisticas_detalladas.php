<?php $pageTitle = 'Estadísticas Detalladas'; ?>
<h2 class="fw-bold mb-4"><i class="fa fa-chart-bar me-2"></i>Estadísticas Detalladas</h2>
<?php if(!isset($vistas)) $vistas = []; $stats = $vistas; ?>
<div class="card shadow mb-4">
  <div class="card-header fw-bold d-flex justify-content-between">
    <span><i class="fa fa-chart-area me-2"></i>Visualizaciones últimos 30 días</span>
    <a href="<?= url('reporte/estadisticas/excel') ?>" class="btn btn-sm btn-outline-success" target="_blank"><i class="fa fa-file-excel me-1"></i>Excel</a>
  </div>
  <div class="card-body">
    <canvas id="chartVistas" height="80"></canvas>
    <div class="table-responsive mt-4">
      <table class="table table-sm table-striped tabla-dt">
        <thead class="table-light"><tr><th>Fecha</th><th>Visualizaciones</th></tr></thead>
        <tbody>
          <?php foreach($stats as $s): ?>
          <tr><td><?= e($s['fecha']) ?></td><td><?= number_format($s['total']) ?></td></tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
const labels = [<?= implode(',', array_map(fn($s)=>'"'.$s['fecha'].'"', $stats)) ?>];
const data   = [<?= implode(',', array_column($stats,'total')) ?>];
new Chart(document.getElementById('chartVistas'), {
  type:'line',
  data:{ labels, datasets:[{label:'Visualizaciones',data,borderColor:'#6c3483',backgroundColor:'rgba(108,52,131,.12)',tension:.4,fill:true}]},
  options:{responsive:true,plugins:{legend:{position:'top'}}}
});
</script>
