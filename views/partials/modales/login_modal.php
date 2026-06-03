<div class="modal fade" id="loginModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header" style="background:linear-gradient(135deg,#2c1654,#6c3483)">
        <h5 class="modal-title text-white fw-bold"><i class="fa fa-palette me-2"></i>Artcania</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-4">
        <form method="POST" action="<?= url('login') ?>">
          <?= csrf_field() ?>
          <div class="mb-3">
            <label class="form-label fw-semibold">Correo</label>
            <input type="email" name="email" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Contraseña</label>
            <input type="password" name="password" class="form-control" required>
          </div>
          <button type="submit" class="btn btn-primary w-100" style="background:#6c3483;border:none">
            <i class="fa fa-sign-in-alt me-2"></i>Ingresar
          </button>
        </form>
        <div class="text-center mt-3">
          <a href="<?= url('recuperar') ?>" class="small text-muted">¿Olvidaste tu contraseña?</a>
        </div>
        <hr>
        <a href="<?= url('registro') ?>" class="btn btn-outline-secondary w-100 btn-sm">Crear cuenta nueva</a>
      </div>
    </div>
  </div>
</div>

