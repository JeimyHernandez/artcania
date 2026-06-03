<?php $pageTitle = 'Notificaciones'; ?>
<div class="d-flex align-items-center justify-content-between mb-4">
  <h2 class="fs-5 font-cinzel mb-0">✦ Notificaciones</h2>
  <?php if(!empty($notifs)): ?>
  <form method="POST" action="<?= url('notificaciones/leer') ?>">
    <?= csrf_field() ?>
    <button type="submit" class="btn btn-sm btn-outline-magic">
      <i class="fa fa-check-double me-1"></i>Marcar todas leídas
    </button>
  </form>
  <?php endif; ?>
</div>

<?php if(!empty($notifs)): ?>
<div class="card-magic p-0 overflow-hidden">
  <?php foreach($notifs as $n): ?>
  <div class="d-flex align-items-start gap-3 p-3"
       style="border-bottom:1px solid var(--border);<?= !$n['leida'] ? 'background:rgba(124,58,237,.06)' : '' ?>">
    <div style="width:38px;height:38px;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;background:<?= $n['leida'] ? 'rgba(124,58,237,.1)' : 'rgba(201,162,39,.15)' ?>">
      <i class="fa fa-bell" style="color:<?= $n['leida'] ? 'var(--pearl-muted)' : 'var(--gold-light)' ?>"></i>
    </div>
    <div class="flex-grow-1">
      <div style="font-size:.875rem;color:var(--pearl);<?= !$n['leida'] ? 'font-weight:600' : '' ?>">
        <?= e($n['mensaje']) ?>
      </div>
      <div style="font-size:.73rem;color:var(--pearl-muted);margin-top:.2rem">
        <?= format_date($n['creado_en']) ?>
      </div>
    </div>
    <?php if(!$n['leida']): ?>
      <div style="width:8px;height:8px;border-radius:50%;background:var(--gold-light);flex-shrink:0;margin-top:6px;box-shadow:0 0 6px var(--gold-glow)"></div>
    <?php endif; ?>
  </div>
  <?php endforeach; ?>
</div>
<?php else: ?>
<div class="text-center py-5" style="color:var(--pearl-muted)">
  <i class="fa fa-bell-slash fa-4x mb-3" style="opacity:.2;display:block"></i>
  <h5 class="font-cinzel">Sin notificaciones</h5>
  <p style="font-size:.85rem">Cuando haya actividad en tus obras o cuenta, aparecerá aquí</p>
</div>
<?php endif; ?>

