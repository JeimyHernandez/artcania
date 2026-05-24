<?php $pageTitle = 'Dashboard Admin'; ?>
<h2 class="fw-bold mb-4"><i class="fa fa-tachometer-alt text-warning me-2"></i>Panel de Administración</h2>
<div class="row g-4 mb-5">
  <div class="col-6 col-md-3"><div class="card bg-primary text-white shadow"><div class="card-body"><div class="d-flex justify-content-between align-items-center"><div><h3 class="fw-bold"><?=number_format($stats['usuarios'])?></h3><small>Usuarios</small></div><i class="fa fa-users fa-2x opacity-50"></i></div></div></div></div>
  <div class="col-6 col-md-3"><div class="card bg-success text-white shadow"><div class="card-body"><div class="d-flex justify-content-between align-items-center"><div><h3 class="fw-bold"><?=number_format($stats['obras'])?></h3><small>Obras</small></div><i class="fa fa-images fa-2x opacity-50"></i></div></div></div></div>
  <div class="col-6 col-md-3"><div class="card bg-warning text-white shadow"><div class="card-body"><div class="d-flex justify-content-between align-items-center"><div><h3 class="fw-bold"><?=number_format($stats['artistas'])?></h3><small>Artistas</small></div><i class="fa fa-palette fa-2x opacity-50"></i></div></div></div></div>
  <div class="col-6 col-md-3"><div class="card bg-danger text-white shadow"><div class="card-body"><div class="d-flex justify-content-between align-items-center"><div><h3 class="fw-bold"><?= money($stats['ventas']) ?></h3><small>Ventas</small></div><i class="fa fa-dollar-sign fa-2x opacity-50"></i></div></div></div></div>
</div>
<div class="row g-4">
  <div class="col-md-6">
    <div class="card shadow"><div class="card-header fw-bold"><i class="fa fa-chart-line me-2"></i>Acciones Rápidas</div>
    <div class="card-body d-grid gap-2">
      <a href="<?=url('admin/usuarios')?>" class="btn btn-outline-primary"><i class="fa fa-users me-2"></i>Gestionar Usuarios</a>
      <a href="<?=url('admin/bitacora')?>" class="btn btn-outline-secondary"><i class="fa fa-list-alt me-2"></i>Ver Bitácora</a>
      <a href="<?=url('reporte/bitacora/pdf')?>" class="btn btn-outline-danger" target="_blank"><i class="fa fa-file-pdf me-2"></i>Bitácora en PDF</a>
      <a href="<?=url('reporte/compras/excel')?>" class="btn btn-outline-success" target="_blank"><i class="fa fa-file-excel me-2"></i>Compras en Excel</a>
      <a href="<?=url('reporte/estadisticas/pdf')?>" class="btn btn-outline-warning" target="_blank"><i class="fa fa-chart-bar me-2"></i>Estadísticas PDF</a>
    </div></div>
  </div>
  <div class="col-md-6">
    <div class="card shadow"><div class="card-header fw-bold"><i class="fa fa-info-circle me-2"></i>Resumen</div>
    <div class="card-body">
      <ul class="list-group list-group-flush">
        <li class="list-group-item d-flex justify-content-between"><span>Subastas activas</span><strong><?=number_format($stats['subastas'])?></strong></li>
        <li class="list-group-item d-flex justify-content-between"><span>Comentarios</span><strong><?=number_format($stats['comentarios'])?></strong></li>
        <li class="list-group-item d-flex justify-content-between"><span>Artistas verificados</span><strong><?=number_format($stats['artistas'])?></strong></li>
      </ul>
    </div></div>
  </div>
</div>
