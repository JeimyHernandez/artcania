<?php if(!isset($fanarts)) $fanarts = []; ?>
<div class="row g-3">
  <?php foreach($fanarts as $f): ?>
  <div class="col-6 col-md-4 col-lg-3">
    <div class="card shadow-sm h-100">
      <?php if($f['imagen']): ?>
      <img src="<?= media_url('Fan_Art/imagen/'.$f['imagen']) ?>" class="card-img-top" style="height:160px;object-fit:cover">
      <?php endif; ?>
      <div class="card-body p-2">
        <h6 class="fw-bold small"><?= e(truncate($f['titulo'],25)) ?></h6>
        <small class="text-muted">por <?= e($f['autor']) ?></small>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
  <?php if(empty($fanarts)): ?><div class="col-12 text-center py-4 text-muted"><i class="fa fa-heart fa-3x mb-3 d-block"></i>Nadie ha creado Fan Art de tus obras todavía.</div><?php endif; ?>
</div>
