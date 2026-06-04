<?php $pageTitle = 'Subir Obra'; ?>
<div class="row justify-content-center">
<div class="col-lg-8">
  <div class="mb-4">
    <h2 class="fs-5 font-cinzel mb-1" style="color:var(--gold-light)">✦ Subir Nueva Obra</h2>
    <p style="color:var(--pearl-muted);font-size:.85rem">Comparte tu arte con la comunidad de Artcania</p>
  </div>

  <form method="POST" action="<?= url('artista/obras') ?>" enctype="multipart/form-data">
    <?= csrf_field() ?>

    <!-- Upload zone -->
    <div class="card-magic p-4 mb-3">
      <div class="upload-zone" id="uploadZone" onclick="document.getElementById('imgInput').click()">
        <i class="fa fa-cloud-arrow-up"></i>
        <p class="mb-1" style="color:var(--pearl);font-weight:600">Arrastra tu obra aquí</p>
        <p style="font-size:.8rem;color:var(--pearl-muted)">o haz clic para seleccionar · JPG, PNG, WEBP · Máx. 10MB</p>
        <div id="imgPreview" class="mt-3" style="display:none">
          <img id="previewImg" src="" alt="" style="max-height:200px;border-radius:12px;border:1px solid var(--border)">
        </div>
      </div>
      <input type="file" name="imagen" id="imgInput" accept="image/*" required style="display:none">
    </div>

    <!-- Datos -->
    <div class="card-magic p-4 mb-3">
      <h6 class="font-cinzel mb-3" style="color:var(--gold-light)">Información de la obra</h6>

      <div class="mb-3">
        <label class="form-label">Título <span style="color:#f87171">*</span></label>
        <input type="text" name="titulo" class="form-control"
               placeholder="El nombre de tu obra" value="<?= old('titulo') ?>" required maxlength="200">
      </div>

      <div class="mb-3">
        <label class="form-label">Descripción</label>
        <textarea name="descripcion" class="form-control" rows="4"
                  placeholder="Describe tu obra, su historia, técnica..."><?= old('descripcion') ?></textarea>
      </div>

      <div class="row g-3 mb-3">
        <div class="col-md-6">
          <label class="form-label">Técnica</label>
          <input type="text" name="tecnica" class="form-control"
                 placeholder="Arte digital, pintura, etc." value="<?= old('tecnica') ?>">
        </div>
        <div class="col-md-6">
          <label class="form-label">Dimensiones</label>
          <input type="text" name="dimensiones" class="form-control"
                 placeholder="3840 x 2160 px" value="<?= old('dimensiones') ?>">
        </div>
      </div>

      <?php if(!empty($categorias)): ?>
      <div class="mb-3">
        <label class="form-label">Categoría</label>
        <select name="categoria_id" class="form-select">
          <option value="">Sin categoría</option>
          <?php foreach($categorias as $cat): ?>
            <option value="<?= $cat['id'] ?>" <?= old('categoria_id')==$cat['id'] ? 'selected':'' ?>>
              <?= e($cat['nombre']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <?php endif; ?>

      <div class="mb-3">
        <label class="form-label">Precio <span style="font-size:.75rem;color:var(--pearl-muted)">(opcional)</span></label>
        <div class="input-group">
          <span class="input-group-text">$</span>
          <input type="number" name="precio" class="form-control"
                 placeholder="0.00" step="0.01" min="0" value="<?= old('precio') ?>">
        </div>
      </div>
    </div>

    <div class="d-flex gap-2">
      <button type="submit" class="btn btn-magic px-4">
        <i class="fa fa-cloud-arrow-up me-2"></i>Publicar obra
      </button>
      <a href="<?= url('artista/obras') ?>" class="btn btn-outline-magic">Cancelar</a>
    </div>
  </form>
</div>
</div>

<script>
var inp = document.getElementById('imgInput');
var zone = document.getElementById('uploadZone');
var prev = document.getElementById('imgPreview');
var img  = document.getElementById('previewImg');

inp.addEventListener('change', function(){
  if(this.files[0]){
    var reader = new FileReader();
    reader.onload = function(e){
      img.src = e.target.result;
      prev.style.display = 'block';
    };
    reader.readAsDataURL(this.files[0]);
  }
});

zone.addEventListener('dragover',function(e){ e.preventDefault(); zone.classList.add('dragover'); });
zone.addEventListener('dragleave',function(){ zone.classList.remove('dragover'); });
zone.addEventListener('drop',function(e){
  e.preventDefault();
  zone.classList.remove('dragover');
  if(e.dataTransfer.files[0]){
    inp.files = e.dataTransfer.files;
    inp.dispatchEvent(new Event('change'));
  }
});
</script>
