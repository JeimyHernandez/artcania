<?php $pageTitle = 'Editar Perfil'; ?>
<div class="row justify-content-center">
<div class="col-lg-7">
  <h2 class="fs-5 font-cinzel mb-4" style="color:var(--gold-light)">✦ Editar Perfil</h2>
  <form method="POST" action="<?= url('perfil') ?>" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <div class="card-magic p-4 mb-3">
      <div class="d-flex align-items-center gap-4 mb-4">
        <img src="<?= avatar($user['avatar'] ?? '') ?>" id="avPrev"
             class="rounded-circle flex-shrink-0"
             style="width:80px;height:80px;object-fit:cover;border:3px solid var(--gold)" alt="">
        <div>
          <label class="btn btn-outline-magic btn-sm" for="avInput">
            <i class="fa fa-camera me-1"></i>Cambiar foto
          </label>
          <input type="file" name="avatar" id="avInput" accept="image/*" style="display:none"
                 onchange="var r=new FileReader();r.onload=function(e){document.getElementById('avPrev').src=e.target.result};r.readAsDataURL(this.files[0])">
        </div>
      </div>
      <div class="row g-3">
        <div class="col-12">
          <label class="form-label">Nombre</label>
          <input type="text" name="nombre" class="form-control" value="<?= e($user['nombre'] ?? '') ?>" required>
        </div>
        <div class="col-12">
          <label class="form-label">Biografía</label>
          <textarea name="bio" class="form-control" rows="3" placeholder="Cuéntanos sobre ti..."><?= e($user['bio'] ?? '') ?></textarea>
        </div>
      </div>
    </div>
    <div class="d-flex gap-2">
      <button type="submit" class="btn btn-magic px-4"><i class="fa fa-floppy-disk me-2"></i>Guardar</button>
      <a href="<?= url('perfil') ?>" class="btn btn-outline-magic">Cancelar</a>
    </div>
  </form>
</div>
</div>
