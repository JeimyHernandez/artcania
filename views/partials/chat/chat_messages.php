<?php
/** @var array $mensajes */
/** @var int $userId */
?>
<?php foreach($mensajes as $m): ?>
<div class="mb-2 d-flex <?= $m['remitente_id']==$userId?'justify-content-end':'justify-content-start' ?>">
  <div class="px-3 py-2 rounded-3 <?= $m['remitente_id']==$userId?'bg-primary text-white':'bg-white border' ?>" style="max-width:70%;font-size:.88rem">
    <small class="d-block fw-bold mb-1 opacity-75"><?= e($m['remitente_nombre']) ?></small>
    <?= nl2br(e($m['mensaje'])) ?>
    <small class="d-block mt-1 opacity-50" style="font-size:.7em"><?= format_date($m['creado_en'],'H:i') ?></small>
  </div>
</div>
<?php endforeach; ?>
