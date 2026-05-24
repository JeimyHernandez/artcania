<?php $pageTitle = 'Mis Valoraciones'; ?>
<h2 class="fw-bold mb-4"><i class="fa fa-star text-warning me-2"></i>Mis Valoraciones</h2>
<?php if(!isset($valoraciones)) $valoraciones = []; ?>
<div class="row g-4">
  <?php foreach($valoraciones as $v): ?>
  <div class="col-md-6">
    <div class="card shadow-sm d-flex flex-row gap-3 p-3 h-100">
      <?php if($v['imagen_principal']): ?>
      <img src="<?= media_url('Originales/imagen/Obras_digitales/'.$v['imagen_principal']) ?>" width="80" height="80" style="object-fit:cover;border-radius:8px;flex-shrink:0">
      <?php endif; ?>
      <div>
        <h6 class="fw-bold mb-0"><?= e(truncate($v['titulo'],35)) ?></h6>
        <small class="text-muted">por <?= e($v['artista']) ?></small>
        <div class="mt-2">
          <?php for($i=1;$i<=5;$i++): ?>
          <i class="fa fa-star <?= $i<=$v['puntuacion']?'text-warning':'text-muted' ?>"></i>
          <?php endfor; ?>
          <small class="text-muted ms-2"><?= format_date($v['actualizado_en'],'d/m/Y') ?></small>
        </div>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
  <?php if(empty($valoraciones)): ?><div class="col-12 text-center text-muted py-4"><i class="fa fa-star fa-3x mb-3 d-block"></i>No has valorado ninguna obra todavía.</div><?php endif; ?>
</div>
