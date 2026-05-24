<?php if(!isset($contactos)) $contactos = []; ?>
<div class="card shadow">
  <div class="card-body p-0">
    <?php foreach($contactos as $c): ?>
    <div class="border-bottom p-4 <?= !$c['leido']?'bg-light':'' ?>">
      <div class="d-flex justify-content-between align-items-start">
        <div>
          <h6 class="fw-bold mb-1"><?= e($c['asunto']) ?> <?= !$c['leido']?'<span class="badge bg-danger ms-1">Nuevo</span>':'' ?></h6>
          <small class="text-muted"><?= format_date($c['creado_en']) ?></small>
          <p class="mt-2 mb-0"><?= nl2br(e($c['mensaje'])) ?></p>
        </div>
        <span class="badge bg-<?= $c['respondido']?'success':'secondary' ?> ms-3"><?= $c['respondido']?'Respondido':'Sin respuesta' ?></span>
      </div>
    </div>
    <?php endforeach; ?>
    <?php if(empty($contactos)): ?><div class="p-5 text-center text-muted"><i class="fa fa-inbox fa-3x mb-3 d-block"></i>No tienes mensajes de contacto.</div><?php endif; ?>
  </div>
</div>
