<?php $pageTitle = 'Bitácora de Auditoría'; ?>
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
  <h2 class="fw-bold"><i class="fa fa-list-alt me-2"></i>Bitácora de Auditoría</h2>
  <div class="d-flex gap-2">
    <a href="<?= url('reporte/bitacora/pdf') ?>" class="btn btn-danger btn-sm" target="_blank"><i class="fa fa-file-pdf me-1"></i>PDF</a>
    <a href="<?= url('reporte/bitacora/excel') ?>" class="btn btn-success btn-sm" target="_blank"><i class="fa fa-file-excel me-1"></i>Excel</a>
    <button class="btn btn-secondary btn-sm" onclick="window.print()"><i class="fa fa-print me-1"></i>Imprimir</button>
  </div>
</div>
<!-- Filtros -->
<form class="card shadow mb-4" method="GET">
  <div class="card-body"><div class="row g-2 align-items-end">
    <div class="col-md-3"><label class="form-label small fw-bold">Desde</label><input type="date" name="desde" class="form-control form-control-sm" value="<?= e($filters['desde']??'') ?>"></div>
    <div class="col-md-3"><label class="form-label small fw-bold">Hasta</label><input type="date" name="hasta" class="form-control form-control-sm" value="<?= e($filters['hasta']??'') ?>"></div>
    <div class="col-md-3"><label class="form-label small fw-bold">Acción</label>
      <select name="accion" class="form-select form-select-sm">
        <option value="">Todas</option>
        <?php foreach(['LOGIN','LOGOUT','REGISTRO','OBRA_CREADA','OBRA_VALIDADA','PERFIL_ACTUALIZADO','ROL_CAMBIO','USUARIO_TOGGLE'] as $a): ?>
        <option value="<?=$a?>" <?= ($filters['accion']??'')===$a?'selected':'' ?>><?=$a?></option>
        <?php endforeach; ?>
      </select></div>
    <div class="col-md-3 d-flex gap-2">
      <button type="submit" class="btn btn-primary btn-sm flex-grow-1"><i class="fa fa-filter me-1"></i>Filtrar</button>
      <a href="<?= url('admin/bitacora') ?>" class="btn btn-outline-secondary btn-sm">✕</a>
    </div>
  </div></div>
</form>
<div class="card shadow">
  <div class="card-header d-flex justify-content-between">
    <span class="fw-bold">Registros</span>
    <small class="text-muted">Total: <?= number_format($total) ?> | Página <?=$page?> de <?=$pages?></small>
  </div>
  <div class="card-body p-0 table-responsive">
    <table class="table table-sm table-striped mb-0 tabla-dt">
      <thead class="table-dark"><tr><th>#</th><th>Usuario</th><th>Acción</th><th>Detalle</th><th>IP</th><th>Fecha</th></tr></thead>
      <tbody>
        <?php foreach($logs as $l): ?>
        <tr>
          <td><?= $l['id'] ?></td>
          <td><?= e($l['nombre']??'-') ?><br><small class="text-muted"><?= e($l['email']??'') ?></small></td>
          <td><span class="badge bg-<?= $l['accion']==='LOGIN'?'success':($l['accion']==='LOGOUT'?'secondary':'primary') ?>"><?= e($l['accion']) ?></span></td>
          <td class="small"><?= e(truncate($l['detalle'],60)) ?></td>
          <td class="small text-muted"><?= e($l['ip']) ?></td>
          <td class="small"><?= format_date($l['creado_en']) ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<!-- Paginación -->
<nav class="mt-3">
  <ul class="pagination pagination-sm justify-content-center">
    <?php for($i=max(1,$page-3);$i<=min($pages,$page+3);$i++): ?>
    <li class="page-item <?= $i===$page?'active':'' ?>"><a class="page-link" href="?page=<?=$i?>&desde=<?=e($filters['desde']??'')?>&hasta=<?=e($filters['hasta']??'')?>&accion=<?=e($filters['accion']??'')?>"><?=$i?></a></li>
    <?php endfor; ?>
  </ul>
</nav>
<style>@media print{.sidebar,.btn,nav.pagination,form{display:none!important}}</style>
