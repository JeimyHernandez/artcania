<?php $pageTitle = 'Obras Destacadas'; ?>
<h2 class="fw-bold mb-4"><i class="fa fa-star text-warning me-2"></i>Seleccionar Obras Destacadas</h2>
<?php $obras = Database::getInstance()->query("SELECT o.*,u.nombre as artista FROM obras o JOIN usuarios u ON u.id=o.artista_id WHERE o.estado='aprobada' ORDER BY o.visualizaciones DESC")->fetchAll(); ?>
<div class="row g-3">
  <?php foreach($obras as $o): ?>
  <div class="col-6 col-md-3">
    <div class="card shadow-sm h-100 <?= $o['destacada']?'border-warning':'' ?>">
      <?php if($o['imagen_principal']): ?>
      <img src="<?= media_url('Originales/imagen/Obras_digitales/'.$o['imagen_principal']) ?>" class="card-img-top" style="height:140px;object-fit:cover">
      <?php endif; ?>
      <div class="card-body p-2">
        <small class="fw-bold"><?= e(truncate($o['titulo'],25)) ?></small>
        <small class="d-block text-muted"><?= e($o['artista']) ?></small>
      </div>
      <div class="card-footer p-2">
        <form method="POST" action="<?= url('curador/destacar') ?>">
          <?= csrf_field() ?><input type="hidden" name="obra_id" value="<?= $o['id'] ?>">
          <input type="hidden" name="destacada" value="<?= $o['destacada']?0:1 ?>">
          <button type="submit" class="btn btn-sm w-100 <?= $o['destacada']?'btn-warning':'btn-outline-warning' ?>">
            <i class="fa fa-star me-1"></i><?= $o['destacada']?'Quitar Destacado':'Destacar' ?>
          </button>
        </form>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
</div>
