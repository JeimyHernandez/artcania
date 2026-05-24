<?php $pageTitle = 'Mis Conversaciones'; ?>
<h2 class="fw-bold mb-4"><i class="fa fa-comments me-2"></i>Mis Conversaciones</h2>
<?php if(!isset($convs)) $convs = []; ?>
<div class="card shadow">
  <div class="card-body p-0">
    <?php foreach($convs as $c): ?>
    <a href="<?= url('chat') ?>" class="text-decoration-none text-dark">
      <div class="d-flex align-items-center gap-3 border-bottom p-3 hover-row">
        <img src="<?= avatar($c['otro_avatar']??'') ?>" class="rounded-circle" width="50" height="50" style="object-fit:cover">
        <div class="flex-grow-1">
          <strong><?= e($c['otro_nombre']) ?></strong>
          <?php if($c['no_leidos']>0): ?><span class="badge bg-danger ms-2"><?= $c['no_leidos'] ?></span><?php endif; ?>
          <small class="d-block text-muted"><?= format_date($c['actualizado_en']) ?></small>
        </div>
        <i class="fa fa-chevron-right text-muted"></i>
      </div>
    </a>
    <?php endforeach; ?>
    <?php if(empty($convs)): ?>
    <div class="p-5 text-center text-muted"><i class="fa fa-comments fa-3x mb-3 d-block"></i>No tienes conversaciones aún.</div>
    <?php endif; ?>
  </div>
</div>
