<?php $pageTitle = 'Notificaciones'; ?>
<?php if(!isset($notifs)) $notifs = []; ?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <h2 class="fw-bold"><i class="fa fa-bell me-2"></i>Notificaciones</h2>
  <span class="badge bg-secondary"><?= count($notifs) ?></span>
</div>
<div class="card shadow">
  <div class="card-body p-0">
    <?php foreach($notifs as $n): ?>
    <div class="d-flex align-items-center border-bottom p-3 <?= !$n['leida']?'bg-light':'' ?>">
      <div class="me-3">
        <?php $ic=['venta'=>'fa-dollar-sign text-success','obra_aprobada'=>'fa-check-circle text-success','obra_rechazada'=>'fa-times-circle text-danger','comentario'=>'fa-comment text-primary'][$n['tipo']]??'fa-bell text-warning'; ?>
        <i class="fa <?= $ic ?> fa-lg"></i>
      </div>
      <div class="flex-grow-1">
        <p class="mb-0"><?= e($n['mensaje']) ?></p>
        <small class="text-muted"><?= format_date($n['creado_en']) ?></small>
      </div>
      <?php if(!$n['leida']): ?>
      <button class="btn btn-sm btn-outline-secondary ms-2" onclick="marcarLeida(<?= $n['id'] ?>, this)">
        <i class="fa fa-check"></i>
      </button>
      <?php endif; ?>
    </div>
    <?php endforeach; ?>
    <?php if(empty($notifs)): ?><div class="p-5 text-center text-muted"><i class="fa fa-bell-slash fa-3x mb-3 d-block"></i>No tienes notificaciones.</div><?php endif; ?>
  </div>
</div>
<script>
function marcarLeida(id, btn){
  fetch('<?= url('notificaciones/leer') ?>', {method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded','X-Requested-With':'XMLHttpRequest'},body:`_csrf=<?=csrf_token()?>&id=${id}`})
  .then(r=>r.json()).then(d=>{ if(d.ok){ btn.closest('.d-flex').classList.remove('bg-light'); btn.remove(); } });
}
</script>
