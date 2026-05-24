<?php $pageTitle = 'Mis Obras'; ?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <h2 class="fw-bold"><i class="fa fa-images me-2"></i>Mis Obras</h2>
  <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNuevaObra"><i class="fa fa-plus me-2"></i>Subir Obra</button>
</div>
<div class="row g-4">
  <?php foreach($obras as $o): ?>
  <div class="col-md-4 col-lg-3">
    <div class="card h-100 shadow-sm">
      <?php if($o['imagen_principal']): ?>
      <img src="<?= media_url('Originales/imagen/Obras_digitales/'.$o['imagen_principal']) ?>" class="card-img-top" style="height:180px;object-fit:cover">
      <?php else: ?>
      <div class="bg-light d-flex align-items-center justify-content-center" style="height:180px"><i class="fa fa-image fa-3x text-muted"></i></div>
      <?php endif; ?>
      <div class="card-body p-2">
        <h6 class="fw-bold small mb-1"><?= e(truncate($o['titulo'],30)) ?></h6>
        <span class="badge bg-<?= $o['estado']==='aprobada'?'success':($o['estado']==='pendiente'?'warning':'danger') ?>"><?= e($o['estado']) ?></span>
        <?php if($o['nota_curador']): ?><p class="small text-muted mt-1"><?= e(truncate($o['nota_curador'],50)) ?></p><?php endif; ?>
        <a href="<?= url('artista/obras/'.$o['id'].'/editar') ?>" class="btn btn-sm btn-outline-secondary w-100 mt-2"><i class="fa fa-edit me-1"></i>Editar</a>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
  <?php if(empty($obras)): ?><div class="col-12 text-center py-5 text-muted"><i class="fa fa-image fa-3x mb-3 d-block"></i>No tienes obras aún. ¡Sube tu primera!</div><?php endif; ?>
</div>

<!-- Modal -->
<div class="modal fade" id="modalNuevaObra" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title fw-bold"><i class="fa fa-upload me-2"></i>Subir Nueva Obra</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
      <form method="POST" action="<?= url('artista/obras') ?>" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-8"><label class="form-label fw-semibold">Título *</label><input type="text" name="titulo" class="form-control" required minlength="3"></div>
            <div class="col-md-4"><label class="form-label fw-semibold">Precio</label><input type="number" name="precio" class="form-control" min="0" step="0.01" placeholder="Opcional"></div>
            <div class="col-12"><label class="form-label fw-semibold">Descripción *</label><textarea name="descripcion" class="form-control" rows="3" required minlength="10"></textarea></div>
            <div class="col-md-6"><label class="form-label fw-semibold">Técnica</label><input type="text" name="tecnica" class="form-control" placeholder="Óleo, acuarela, digital..."></div>
            <div class="col-md-6"><label class="form-label fw-semibold">Dimensiones</label><input type="text" name="dimensiones" class="form-control" placeholder="p.ej. 80x60 cm"></div>
            <div class="col-12"><label class="form-label fw-semibold">Imagen principal</label><input type="file" name="imagen" class="form-control" accept="image/*"></div>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary"><i class="fa fa-upload me-2"></i>Subir para revisión</button>
        </div>
      </form>
    </div>
  </div>
</div>
