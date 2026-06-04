<?php $pageTitle = 'Editar Obra'; ?>
<div class="row justify-content-center">
<div class="col-lg-8">
  <div class="mb-4">
    <h2 class="fs-5 font-cinzel mb-1" style="color:var(--gold-light)">✦ Editar Obra</h2>
    <p style="color:var(--pearl-muted);font-size:.85rem"><?= e($obra['titulo'] ?? '') ?></p>
  </div>

  <form method="POST" action="<?= url('artista/obras/'.$obra['id']) ?>" enctype="multipart/form-data">
    <?= csrf_field() ?>

    <!-- Imagen actual -->
    <div class="card-magic p-4 mb-3">
      <?php if(!empty($obra['imagen_principal'])): ?>
      <div class="mb-3 text-center">
        <img src="<?= media_url('Originales/imagen/Obras_digitales/'.$obra['imagen_principal']) ?>"
             style="max-height:200px;border-radius:12px;border:1px solid var(--border)"
             alt="<?= e($obra['titulo']) ?>"
             onerror="this.src='<?= url('resources/img/placeholder.png') ?>'">
        <div style="font-size:.75rem;color:var(--pearl-muted);margin-top:.5rem">Imagen actual</div>
      </div>
      <?php endif; ?>
      <div class="upload-zone" onclick="document.getElementById('imgEditInput').click()" style="padding:1.5rem">
        <i class="fa fa-cloud-arrow-up" style="font-size:1.8rem"></i>
        <p class="mb-0" style="font-size:.85rem;color:var(--pearl-muted)">
          Clic para cambiar imagen (opcional)
        </p>
      </div>
      <input type="file" name="imagen" id="imgEditInput" accept="image/*" style="display:none">
    </div>

    <div class="card-magic p-4 mb-3">
      <div class="mb-3">
        <label class="form-label">Título <span style="color:#f87171">*</span></label>
        <input type="text" name="titulo" class="form-control" value="<?= e($obra['titulo'] ?? '') ?>" required maxlength="200">
      </div>
      <div class="mb-3">
        <label class="form-label">Descripción</label>
        <textarea name="descripcion" class="form-control" rows="4"><?= e($obra['descripcion'] ?? '') ?></textarea>
      </div>
      <div class="row g-3 mb-3">
        <div class="col-md-6">
          <label class="form-label">Técnica</label>
          <input type="text" name="tecnica" class="form-control" value="<?= e($obra['tecnica'] ?? '') ?>">
        </div>
        <div class="col-md-6">
          <label class="form-label">Dimensiones</label>
          <input type="text" name="dimensiones" class="form-control" value="<?= e($obra['dimensiones'] ?? '') ?>">
        </div>
      </div>
      <div class="mb-3">
        <label class="form-label">Precio</label>
        <div class="input-group">
          <span class="input-group-text">$</span>
          <input type="number" name="precio" class="form-control" step="0.01" min="0"
                 value="<?= e($obra['precio'] ?? '') ?>">
        </div>
      </div>
    </div>

    <div class="d-flex gap-2">
      <button type="submit" class="btn btn-magic px-4">
        <i class="fa fa-floppy-disk me-2"></i>Guardar cambios
      </button>
      <a href="<?= url('artista/obras') ?>" class="btn btn-outline-magic">Cancelar</a>
    </div>
  </form>
</div>
</div>
