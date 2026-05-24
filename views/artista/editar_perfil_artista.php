<?php $pageTitle = 'Editar Perfil Artista'; ?>
<h2 class="fw-bold mb-4"><i class="fa fa-user-edit me-2"></i>Editar Perfil de Artista</h2>
<div class="card shadow">
  <div class="card-body">
    <form method="POST" action="<?= url('artista/perfil') ?>" enctype="multipart/form-data">
      <?= csrf_field() ?>
      <div class="row g-3">
        <div class="col-md-4 text-center">
          <img src="<?= avatar(Auth::user()['avatar']??'') ?>" class="rounded-circle mb-2 shadow" width="120" height="120" style="object-fit:cover">
          <div><label class="form-label fw-semibold d-block">Avatar</label>
          <input type="file" name="avatar" class="form-control form-control-sm" accept="image/*"></div>
        </div>
        <div class="col-md-8">
          <div class="mb-3"><label class="form-label fw-semibold">Nombre completo</label>
            <input type="text" name="nombre" class="form-control" value="<?= e(Auth::user()['nombre']) ?>" required></div>
          <div class="row g-2">
            <div class="col-md-6"><label class="form-label fw-semibold">Especialidad</label>
              <input type="text" name="especialidad" class="form-control" value="<?= e($artista['especialidad']??'') ?>" placeholder="Arte digital, pintura..."></div>
            <div class="col-md-6"><label class="form-label fw-semibold">País</label>
              <input type="text" name="pais" class="form-control" value="<?= e($artista['pais']??'') ?>"></div>
          </div>
          <div class="mb-3 mt-2"><label class="form-label fw-semibold">Sitio web</label>
            <input type="url" name="sitio_web" class="form-control" value="<?= e($artista['sitio_web']??'') ?>" placeholder="https://..."></div>
          <div class="mb-3"><label class="form-label fw-semibold">Biografía</label>
            <textarea name="bio" class="form-control" rows="4"><?= e($artista['descripcion']??'') ?></textarea></div>
          <button type="submit" class="btn btn-primary"><i class="fa fa-save me-2"></i>Guardar</button>
        </div>
      </div>
    </form>
  </div>
</div>
