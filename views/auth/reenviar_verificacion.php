<?php $pageTitle = 'Reenviar verificación'; ?>

<div class="card card-auth shadow-lg w-100">
  <div class="card-header-magic text-center py-4">
    <div class="auth-brand mb-2" style="font-size:2rem">🎨</div>
    <h4 class="fw-bold mb-1" style="color:var(--pearl);font-size:1.2rem">Reenviar verificación</h4>
    <p class="mb-0" style="color:rgba(244,247,251,.6);font-size:.85rem">
      Te enviamos un nuevo enlace de activación
    </p>
  </div>

  <div class="card-body p-4">

    <?php if($flash_success): ?>
      <div class="alert alert-success rounded-3 py-2 mb-3">
        <i class="fa fa-check-circle me-2"></i><?= e($flash_success) ?>
      </div>
    <?php endif; ?>
    <?php if($flash_error): ?>
      <div class="alert alert-danger rounded-3 py-2 mb-3">
        <i class="fa fa-exclamation-circle me-2"></i><?= e($flash_error) ?>
      </div>
    <?php endif; ?>

    <form method="POST" action="<?= url('reenviar-verificacion') ?>" novalidate>
      <?= csrf_field() ?>
      <div class="mb-3">
        <label class="form-label fw-semibold" style="color:var(--pearl)">
          Correo electrónico
        </label>
        <input type="email" name="email" class="form-control"
               placeholder="tu@correo.com"
               value="<?= old('email') ?>"
               required autofocus>
      </div>
      <button type="submit" class="btn btn-magic w-100 py-2 fw-bold">
        <i class="fa fa-paper-plane me-2"></i>Reenviar enlace
      </button>
    </form>

    <hr style="border-color:rgba(255,255,255,.1);margin:1.2rem 0">

    <div class="text-center" style="font-size:.88rem;color:rgba(244,247,251,.6)">
      <a href="<?= url('login') ?>" style="color:var(--teal)">← Volver al login</a>
    </div>

  </div>
</div>

<!-- Nota informativa -->
<div class="w-100 mt-3" style="background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:12px;padding:1rem 1.2rem">
  <p class="mb-2 fw-semibold" style="color:var(--gold);font-size:.88rem">
    <i class="fa fa-lightbulb me-1"></i>Si el correo no llega:
  </p>
  <ul class="mb-0" style="color:rgba(244,247,251,.65);font-size:.83rem;padding-left:1.2rem">
    <li>Revisa la carpeta <strong>Spam / No deseado</strong></li>
    <li>Espera hasta 5 minutos</li>
    <li>Asegúrate de que escribiste el correo correcto</li>
    <li>Si el problema persiste, contacta al administrador</li>
  </ul>
</div>
