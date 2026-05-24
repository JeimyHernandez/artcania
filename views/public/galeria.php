<?php $pageTitle = 'Galería'; ?>
<div class="container py-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold"><i class="fa fa-images text-purple me-2"></i>Galería de Arte<?= !empty($q) ? ' – "'.e($q).'"' : '' ?></h2>
    <form class="d-flex" action="<?= url('buscar') ?>">
      <input class="form-control me-2" name="q" value="<?= e($q??'') ?>" placeholder="Buscar obras...">
      <button class="btn btn-outline-secondary" type="submit"><i class="fa fa-search"></i></button>
    </form>
  </div>
  <div class="row g-4">
    <?php $obras = $obras ?? []; foreach($obras as $o): ?>
    <div class="col-6 col-md-4 col-lg-3">
      <div class="card h-100 shadow-sm hover-card">
        <?php if($o['imagen_principal']): ?>
        <img src="<?= media_url('Originales/imagen/Obras_digitales/'.$o['imagen_principal']) ?>" class="card-img-top" style="height:220px;object-fit:cover" alt="<?=e($o['titulo'])?>">
        <?php else: ?>
        <div class="bg-light d-flex align-items-center justify-content-center" style="height:220px"><i class="fa fa-image fa-3x text-muted"></i></div>
        <?php endif; ?>
        <div class="card-body p-3">
          <h6 class="fw-bold mb-1"><?= e(truncate($o['titulo'],40)) ?></h6>
          <small class="text-muted">por <?= e($o['artista_nombre']) ?></small>
          <div class="d-flex justify-content-between align-items-center mt-2">
            <?php if($o['precio']): ?><span class="text-success fw-bold"><?= money($o['precio']) ?></span><?php else: ?><span class="badge bg-secondary">Sin precio</span><?php endif; ?>
            <small class="text-muted"><i class="fa fa-eye"></i> <?= number_format($o['visualizaciones']??0) ?></small>
          </div>
        </div>
        <div class="card-footer bg-transparent p-2">
          <a href="<?= url('obra/'.$o['id']) ?>" class="btn btn-sm btn-primary w-100">Ver Obra</a>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
    <?php if(empty($obras)): ?>
    <div class="col-12 text-center py-5 text-muted">
      <i class="fa fa-search fa-3x mb-3 d-block"></i>
      <h5>No se encontraron obras<?= !empty($q) ? ' para "'.e($q).'"' : '' ?></h5>
    </div>
    <?php endif; ?>
  </div>
  <?php if(!empty($page) && !empty($obras)): ?>
  <div class="d-flex justify-content-center gap-2 mt-5">
    <?php if($page>1): ?><a href="?page=<?=$page-1?>" class="btn btn-outline-secondary">« Anterior</a><?php endif; ?>
    <span class="btn btn-secondary disabled">Pág. <?=$page?></span>
    <a href="?page=<?=$page+1?>" class="btn btn-outline-secondary">Siguiente »</a>
  </div>
  <?php endif; ?>
</div>
