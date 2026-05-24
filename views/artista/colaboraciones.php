<?php $pageTitle = 'Colaboraciones'; ?>
<h2 class="fw-bold mb-4"><i class="fa fa-handshake me-2"></i>Colaboraciones</h2>
<?php if(!isset($colaboraciones)) $colaboraciones = []; $cols = $colaboraciones; ?>
<div class="card shadow">
  <div class="card-body p-0">
    <?php foreach($cols as $c): ?>
    <div class="border-bottom p-3">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <strong><?= e($c['artista1']) ?></strong> <i class="fa fa-handshake mx-2 text-primary"></i> <strong><?= e($c['artista2']) ?></strong>
          <p class="small text-muted mb-0 mt-1"><?= e(truncate($c['descripcion']??'',80)) ?></p>
        </div>
        <span class="badge bg-<?= $c['estado']==='aceptada'?'success':($c['estado']==='propuesta'?'warning':($c['estado']==='completada'?'primary':'danger')) ?>"><?= e($c['estado']) ?></span>
      </div>
    </div>
    <?php endforeach; ?>
    <?php if(empty($cols)): ?><div class="p-5 text-center text-muted"><i class="fa fa-handshake fa-3x mb-3 d-block"></i>No tienes colaboraciones aún.</div><?php endif; ?>
  </div>
</div>
