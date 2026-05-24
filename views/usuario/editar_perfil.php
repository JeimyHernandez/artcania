<?php $pageTitle = 'Editar Perfil'; ?>
<h2 class="fw-bold mb-4"><i class="fa fa-user-edit me-2"></i>Editar Perfil</h2>
<div class="card shadow">
  <div class="card-body">
    <form method="POST" action="<?= url('perfil') ?>" enctype="multipart/form-data">
      <?= csrf_field() ?>
      <div class="row g-3">
        <div class="col-md-3 text-center">
          <img src="<?= avatar($user['avatar']??'') ?>" class="rounded-circle mb-2 shadow" width="110" height="110" style="object-fit:cover" id="avatarPreview">
          <div><input type="file" name="avatar" class="form-control form-control-sm" accept="image/*" onchange="previewAvatar(this)"></div>
        </div>
        <div class="col-md-9">
          <div class="mb-3"><label class="form-label fw-semibold">Nombre completo</label>
            <input type="text" name="nombre" class="form-control" value="<?= e($user['nombre']) ?>" required minlength="3"></div>
          <div class="mb-3"><label class="form-label fw-semibold">Correo</label>
            <input type="email" class="form-control bg-light" value="<?= e($user['email']) ?>" disabled>
            <small class="text-muted">El correo no se puede cambiar desde aquí.</small></div>
          <div class="mb-3"><label class="form-label fw-semibold">Biografía</label>
            <textarea name="bio" class="form-control" rows="3"><?= e($user['bio']??'') ?></textarea></div>
          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="fa fa-save me-2"></i>Guardar</button>
            <a href="<?= url('perfil') ?>" class="btn btn-outline-secondary">Cancelar</a>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
<script>
function previewAvatar(input){
  if(input.files&&input.files[0]){
    const r=new FileReader();
    r.onload=e=>document.getElementById('avatarPreview').src=e.target.result;
    r.readAsDataURL(input.files[0]);
  }
}
</script>
