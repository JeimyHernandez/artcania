<?php $pageTitle = 'Editar Perfil'; ?>
<div class="row justify-content-center">
<div class="col-lg-8">
  <div class="mb-4">
    <h2 class="fs-5 font-cinzel mb-1" style="color:var(--gold-light)">✦ Mi Perfil de Artista</h2>
    <p style="color:var(--pearl-muted);font-size:.85rem">Cuéntale al mundo tu historia</p>
  </div>

  <form method="POST" action="<?= url('artista/perfil') ?>" enctype="multipart/form-data">
    <?= csrf_field() ?>

    <!-- Avatar -->
    <div class="card-magic p-4 mb-3">
      <div class="d-flex align-items-center gap-4">
        <img src="<?= avatar(Auth::user()['avatar'] ?? '') ?>"
             id="avatarPreview"
             class="rounded-circle"
             style="width:88px;height:88px;object-fit:cover;border:3px solid var(--gold);box-shadow:0 0 20px var(--gold-glow)"
             alt="">
        <div>
          <label class="btn btn-outline-magic btn-sm mb-1" for="avatarInput">
            <i class="fa fa-camera me-1"></i>Cambiar avatar
          </label>
          <input type="file" name="avatar" id="avatarInput" accept="image/*" style="display:none"
                 onchange="var r=new FileReader();r.onload=function(e){document.getElementById('avatarPreview').src=e.target.result};r.readAsDataURL(this.files[0])">
          <p style="font-size:.75rem;color:var(--pearl-muted);margin:0">JPG, PNG, WEBP · Máx. 2MB</p>
        </div>
      </div>
    </div>

    <!-- Info básica -->
    <div class="card-magic p-4 mb-3">
      <h6 class="font-cinzel mb-3" style="color:var(--gold-light)">Información Artística</h6>
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Especialidad</label>
          <input type="text" name="especialidad" class="form-control"
                 placeholder="Arte digital, ilustración..." value="<?= e($artista['especialidad'] ?? '') ?>">
        </div>
        <div class="col-md-6">
          <label class="form-label">País</label>
          <input type="text" name="pais" class="form-control"
                 placeholder="El Salvador" value="<?= e($artista['pais'] ?? '') ?>">
        </div>
        <div class="col-md-6">
          <label class="form-label">Web / Portfolio</label>
          <input type="url" name="web" class="form-control"
                 placeholder="https://mipagina.com" value="<?= e($artista['web'] ?? '') ?>">
        </div>
        <div class="col-md-6">
          <label class="form-label">Instagram</label>
          <div class="input-group">
            <span class="input-group-text"><i class="fa fa-at"></i></span>
            <input type="text" name="instagram" class="form-control"
                   placeholder="usuario" value="<?= e($artista['instagram'] ?? '') ?>">
          </div>
        </div>
        <div class="col-12">
          <label class="form-label">Descripción / Bio artística</label>
          <textarea name="descripcion" class="form-control" rows="4"
                    placeholder="Cuéntanos sobre tu arte, tu estilo, tus inspiraciones..."><?= e($artista['descripcion'] ?? '') ?></textarea>
        </div>
      </div>
    </div>

    <div class="d-flex gap-2">
      <button type="submit" class="btn btn-magic px-4">
        <i class="fa fa-floppy-disk me-2"></i>Guardar cambios
      </button>
      <a href="<?= url('artista/dashboard') ?>" class="btn btn-outline-magic">Cancelar</a>
    </div>
  </form>
</div>
</div>
