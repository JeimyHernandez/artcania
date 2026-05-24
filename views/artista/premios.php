<?php if(!isset($premios)) $premios = []; ?>
<div class="row g-4">
  <?php foreach($premios as $p): ?>
  <div class="col-md-4">
    <div class="card shadow text-center border-warning h-100">
      <div class="card-body">
        <i class="fa fa-trophy fa-3x text-warning mb-3"></i>
        <h5 class="fw-bold"><?= e($p['titulo']) ?></h5>
        <p class="text-muted"><?= e($p['otorgado_por']??'Artcania') ?></p>
        <?php if($p['anio']): ?><span class="badge bg-warning text-dark"><?= e($p['anio']) ?></span><?php endif; ?>
        <?php if($p['descripcion']): ?><p class="small mt-2"><?= e($p['descripcion']) ?></p><?php endif; ?>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
  <?php if(empty($premios)): ?><div class="col-12 text-center py-4 text-muted"><i class="fa fa-trophy fa-3x mb-3 d-block"></i>Aún no tienes premios registrados.</div><?php endif; ?>
</div>
