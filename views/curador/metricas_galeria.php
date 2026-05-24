<?php $pageTitle = 'Métricas de Galería'; ?>
<h2 class="fw-bold mb-4"><i class="fa fa-chart-pie me-2"></i>Métricas de Galería</h2>
<?php
if(!isset($topObras)) $topObras = [];
if(!isset($cats)) $cats = [];
if(!isset($vistas)) $vistas = []; $views = $vistas;
?>
<div class="row g-4 mb-4">
  <div class="col-md-6">
    <div class="card shadow">
      <div class="card-header fw-bold"><i class="fa fa-trophy me-2 text-warning"></i>Top 10 Obras Más Vistas</div>
      <div class="card-body p-0">
        <ul class="list-group list-group-flush">
          <?php foreach($topObras as $i=>$o): ?>
          <li class="list-group-item d-flex justify-content-between align-items-center">
            <div><span class="badge bg-purple me-2">#<?= $i+1 ?></span><?= e(truncate($o['titulo'],30)) ?>
              <small class="text-muted d-block">por <?= e($o['artista']) ?></small></div>
            <span class="badge bg-light text-dark"><i class="fa fa-eye me-1"></i><?= number_format($o['visualizaciones']) ?></span>
          </li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="card shadow">
      <div class="card-header fw-bold"><i class="fa fa-tags me-2 text-info"></i>Categorías Populares</div>
      <div class="card-body p-0">
        <ul class="list-group list-group-flush">
          <?php foreach($cats as $c): ?>
          <li class="list-group-item d-flex justify-content-between">
            <span><?= e($c['nombre']) ?></span>
            <span class="badge bg-info text-dark"><?= number_format($c['total']) ?> obras</span>
          </li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
  </div>
</div>
<div class="card shadow">
  <div class="card-header fw-bold"><i class="fa fa-chart-area me-2"></i>Visualizaciones – Últimos 30 días</div>
  <div class="card-body"><canvas id="chartVistas" height="70"></canvas></div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
new Chart(document.getElementById('chartVistas'),{
  type:'bar',
  data:{labels:[<?= implode(',',array_map(fn($v)=>'"'.$v['fecha'].'"',$views)) ?>],datasets:[{label:'Vistas',data:[<?= implode(',',array_column($views,'total')) ?>],backgroundColor:'rgba(108,52,131,.7)'}]},
  options:{responsive:true,plugins:{legend:{position:'top'}}}
});
</script>
