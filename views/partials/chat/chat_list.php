<?php
/** @var array $convs */
?>
<?php foreach($convs as $c): ?>
<div class="conv-item d-flex align-items-center gap-2 p-3 border-bottom" data-id="<?= $c['id'] ?>" style="cursor:pointer" onclick="selectConv(<?= $c['id'] ?>)">
  <img src="<?= avatar($c['otro_avatar']??'') ?>" class="rounded-circle" width="42" height="42" style="object-fit:cover;flex-shrink:0">
  <div class="flex-grow-1 overflow-hidden">
    <div class="d-flex justify-content-between">
      <strong class="small"><?= e($c['otro_nombre']) ?></strong>
      <?php if($c['no_leidos']>0): ?><span class="badge bg-danger" style="font-size:.65rem"><?= $c['no_leidos'] ?></span><?php endif; ?>
    </div>
    <small class="text-muted"><?= format_date($c['actualizado_en'],'d/m H:i') ?></small>
  </div>
</div>
<?php endforeach; ?>
