<?php $pageTitle = 'Recuperar contraseña'; ?>
<div class="card-auth">
  <div class="card-header-magic">
    <span class="auth-logo-gem">🔑</span>
    <div class="auth-logo-text" style="font-size:1.4rem">ARTCANIA</div>
    <div class="auth-tagline">Recupera el acceso a tu reino</div>
  </div>
  <div class="card-body">
    <?php $modo = isset($token) ? 'reset' : 'email'; ?>

    <?php if($modo === 'email'): ?>
      <p style="font-size:.85rem;color:var(--pearl-muted);margin-bottom:1.5rem;text-align:center">
        Ingresa tu correo y te enviaremos un enlace para restablecer tu contraseña.
      </p>
      <form method="POST" action="<?= url('recuperar') ?>">
        <?= csrf_field() ?>
        <div class="mb-4">
          <div class="input-group">
            <span class="input-group-text"><i class="fa fa-envelope"></i></span>
            <input type="email" name="email" class="form-control"
                   placeholder="tu@correo.com" value="<?= old('email') ?>" required autofocus>
          </div>
        </div>
        <button type="submit" class="btn btn-magic w-100 py-2">
          <i class="fa fa-paper-plane me-2"></i>Enviar enlace
        </button>
      </form>

    <?php else: ?>
      <p style="font-size:.85rem;color:var(--pearl-muted);margin-bottom:1.5rem;text-align:center">
        Crea tu nueva contraseña mágica.
      </p>
      <form method="POST" action="<?= url('reset') ?>">
        <?= csrf_field() ?>
        <input type="hidden" name="token" value="<?= e($token) ?>">
        <div class="mb-3">
          <div class="input-group">
            <span class="input-group-text"><i class="fa fa-lock"></i></span>
            <input type="password" name="password" id="p1"
                   class="form-control" placeholder="Nueva contraseña" required>
            <button type="button" class="input-group-text toggle-pass" data-target="p1"
                    style="cursor:pointer;border-radius:0 12px 12px 0;border-left:none">
              <i class="fa fa-eye"></i>
            </button>
          </div>
        </div>
        <div class="mb-4">
          <div class="input-group">
            <span class="input-group-text"><i class="fa fa-lock"></i></span>
            <input type="password" name="password_confirm" id="p2"
                   class="form-control" placeholder="Confirmar contraseña" required>
            <button type="button" class="input-group-text toggle-pass" data-target="p2"
                    style="cursor:pointer;border-radius:0 12px 12px 0;border-left:none">
              <i class="fa fa-eye"></i>
            </button>
          </div>
        </div>
        <button type="submit" class="btn btn-magic w-100 py-2">
          <i class="fa fa-key me-2"></i>Restablecer contraseña
        </button>
      </form>
    <?php endif; ?>

    <div class="text-center mt-3">
      <a href="<?= url('login') ?>" style="color:var(--purple-mid);font-size:.83rem;text-decoration:none">
        ← Volver al inicio de sesión
      </a>
    </div>
  </div>
</div>
<script>
document.querySelectorAll('.toggle-pass').forEach(function(btn){
  btn.addEventListener('click', function(){
    var inp = document.getElementById(this.dataset.target);
    var ico = this.querySelector('i');
    inp.type = inp.type === 'password' ? 'text' : 'password';
    ico.classList.toggle('fa-eye'); ico.classList.toggle('fa-eye-slash');
  });
});
</script>
