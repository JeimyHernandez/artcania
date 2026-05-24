<?php $pageTitle = 'Mis Favoritos'; ?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <h2 class="fw-bold"><i class="fa fa-heart text-danger me-2"></i>Mis Favoritos</h2>
  <span class="badge bg-danger fs-6"><?= count($favs) ?> obras</span>
</div>
<div class="row g-4">
  <?php foreach($favs as $f): ?>
  <div class="col-6 col-md-4 col-lg-3">
    <div class="card shadow-sm hover-card h-100">
      <?php if($f['imagen_principal']): ?>
      <img src="<?= media_url('Originales/imagen/Obras_digitales/'.$f['imagen_principal']) ?>" class="card-img-top" style="height:180px;object-fit:cover">
      <?php else: ?>
      <div class="bg-light d-flex align-items-center justify-content-center" style="height:180px"><i class="fa fa-image fa-3x text-muted"></i></div>
      <?php endif; ?>
      <div class="card-body p-3">
        <h6 class="fw-bold mb-1"><?= e(truncate($f['titulo'],30)) ?></h6>
        <small class="text-muted">por <?= e($f['artista']) ?></small>
        <?php if($f['precio']): ?><p class="text-success fw-bold mb-0 mt-1"><?= money($f['precio']) ?></p><?php endif; ?>
      </div>
      <div class="card-footer bg-transparent p-2 d-flex gap-1">
        <a href="<?= url('obra/'.$f['obra_id']) ?>" class="btn btn-sm btn-primary flex-grow-1">Ver Obra</a>
        <button class="btn btn-sm btn-outline-danger" onclick="toggleFav(<?= $f['obra_id'] ?>, this)"><i class="fa fa-heart"></i></button>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
  <?php if(empty($favs)): ?>
  <div class="col-12 text-center py-5 text-muted">
    <i class="fa fa-heart fa-3x mb-3 d-block text-danger"></i>
    <h5>No tienes obras en favoritos</h5>
    <a href="<?= url('galeria') ?>" class="btn btn-primary mt-2">Explorar Galería</a>
  </div>
  <?php endif; ?>
</div>
<script>
function toggleFav(id, btn){
  fetch('<?= url('favorito/toggle') ?>', {method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded','X-Requested-With':'XMLHttpRequest'},body:`_csrf=<?=csrf_token()?>&obra_id=${id}`})
  .then(r=>r.json()).then(d=>{ if(!d.added) btn.closest('.card').remove(); });
}
</script>
