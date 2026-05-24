<?php $pageTitle = 'Gestión de Fan Arts'; ?>
<?php if(!isset($fanarts)) $fanarts = []; ?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <h2 class="fw-bold"><i class="fa fa-heart me-2"></i>Gestión de Fan Arts</h2>
  <span class="badge bg-secondary fs-6"><?= count($fanarts) ?></span>
</div>
<div class="row g-3">
  <?php foreach($fanarts as $f): ?>
  <div class="col-6 col-md-3">
    <div class="card shadow-sm h-100">
      <?php if(!empty($f['imagen'])): ?>
      <img src="<?= media_url('Fan_Art/imagen/'.$f['imagen']) ?>" class="card-img-top" style="height:140px;object-fit:cover">
      <?php endif; ?>
      <div class="card-body p-2">
        <small class="fw-bold"><?= e(truncate($f['titulo'],25)) ?></small>
        <span class="d-block badge bg-<?= $f['aprobado']?'success':'warning' ?>"><?= $f['aprobado']?'Aprobado':'Pendiente' ?></span>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
</div>
