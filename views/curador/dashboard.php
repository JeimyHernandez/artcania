<?php $pageTitle = 'Dashboard Curador'; ?>
<div class="mb-4">
  <h1 class="fs-4 mb-1"><i class="fa fa-masks-theater me-2" style="color:var(--teal)"></i>Panel del Curador ✦</h1>
  <p style="color:var(--pearl-muted);font-size:.85rem">Valida, cuida y da forma al universo artístico de Artcania</p>
</div>

<?php
$pendientes  = isset($obras) ? array_filter($obras, fn($o)=>$o['estado']==='pendiente') : [];
$aprobadas   = isset($obras) ? array_filter($obras, fn($o)=>$o['estado']==='aprobada')  : [];
$rechazadas  = isset($obras) ? array_filter($obras, fn($o)=>$o['estado']==='rechazada') : [];
?>
<div class="row g-3 mb-4">
  <div class="col-6 col-xl-3">
    <div class="stat-card">
      <div class="stat-icon purple"><i class="fa fa-hourglass-half"></i></div>
      <div class="stat-value"><?= count($pendientes) ?></div>
      <div class="stat-label">Pendientes</div>
    </div>
  </div>
  <div class="col-6 col-xl-3">
    <div class="stat-card">
      <div class="stat-icon teal"><i class="fa fa-circle-check"></i></div>
      <div class="stat-value"><?= count($aprobadas) ?></div>
      <div class="stat-label">Aprobadas</div>
    </div>
  </div>
  <div class="col-6 col-xl-3">
    <div class="stat-card">
      <div class="stat-icon pink"><i class="fa fa-circle-xmark"></i></div>
      <div class="stat-value"><?= count($rechazadas) ?></div>
      <div class="stat-label">Rechazadas</div>
    </div>
  </div>
  <div class="col-6 col-xl-3">
    <div class="stat-card">
      <div class="stat-icon gold"><i class="fa fa-comments"></i></div>
      <div class="stat-value"><?= count($comentarios ?? []) ?></div>
      <div class="stat-label">Coms. Pendientes</div>
    </div>
  </div>
</div>

<div class="row g-3">
  <div class="col-lg-6">
    <div class="card-magic p-3">
      <h6 class="font-cinzel mb-3" style="color:var(--gold-light)">✦ Acciones Rápidas</h6>
      <?php $links = [
        [url('curador/obras-pendientes'),'fa-hourglass-half','Revisar obras pendientes'],
        [url('curador/historial'), 'fa-history',  'Historial de validaciones'],
        [url('curador/moderar-comentarios'),'fa-comments',   'Moderar comentarios'],
        [url('curador/destacados'),      'fa-star',          'Seleccionar destacados'],
        [url('curador/exposiciones'),    'fa-landmark',      'Gestionar exposiciones'],
        [url('curador/metricas'),        'fa-chart-bar',     'Ver métricas'],
      ];
      foreach($links as $l): ?>
      <a href="<?= $l[0] ?>" class="d-flex align-items-center gap-3 py-2 text-decoration-none"
         style="color:var(--pearl-dim);border-bottom:1px solid var(--border)"
         onmouseover="this.style.color='var(--pearl)'" onmouseout="this.style.color='var(--pearl-dim)'">
        <i class="fa <?= $l[1] ?>" style="width:18px;text-align:center;color:var(--teal)"></i>
        <span style="font-size:.875rem"><?= $l[2] ?></span>
        <i class="fa fa-chevron-right ms-auto" style="font-size:.65rem;opacity:.4"></i>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
  <div class="col-lg-6">
    <div class="card-magic p-3">
      <div class="d-flex align-items-center justify-content-between mb-3">
        <h6 class="font-cinzel mb-0" style="color:var(--gold-light)">✦ Obras Recientes</h6>
        <a href="<?= url('curador/obras-pendientes') ?>" class="btn btn-sm btn-outline-magic">Ver todas</a>
      </div>
      <?php if(!empty($pendientes)): ?>
        <?php foreach(array_slice($pendientes, 0, 4) as $o): ?>
        <div class="d-flex align-items-center gap-3 pb-2 mb-2" style="border-bottom:1px solid var(--border)">
          <div style="width:44px;height:44px;border-radius:8px;overflow:hidden;flex-shrink:0;background:var(--purple-soft)">
            <?php if(!empty($o['imagen_principal'])): ?>
              <img src="<?= media_url('Originales/imagen/Obras_digitales/'.$o['imagen_principal']) ?>"
                   style="width:100%;height:100%;object-fit:cover" alt="">
            <?php else: ?><div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center">🎨</div><?php endif; ?>
          </div>
          <div class="flex-grow-1 overflow-hidden">
            <div style="font-size:.83rem;color:var(--pearl);white-space:nowrap;overflow:hidden;text-overflow:ellipsis"><?= e($o['titulo']) ?></div>
            <div style="font-size:.72rem;color:var(--pearl-muted)"><?= e($o['artista_nombre'] ?? '') ?></div>
          </div>
          <span class="badge-warning" style="font-size:.65rem">Pendiente</span>
        </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p style="color:var(--pearl-muted);font-size:.85rem">Sin obras pendientes ✓</p>
      <?php endif; ?>
    </div>
  </div>
</div>
