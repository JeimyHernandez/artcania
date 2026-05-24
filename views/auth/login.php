<?php $pageTitle = 'Iniciar Sesión'; ?>
<div class="auth-shell anim-fade-up">

  <!-- Logo + título -->
  <div class="text-center mb-2">
    <img src="<?= asset("img/logo_artcadia.png") ?>" alt="Artcadia" class="auth-logo-img">
    <h1 class="auth-title">ARTCADIA</h1>
    <div class="auth-divider"><span>✦</span><span>✧</span><span>✦</span></div>
    <p class="auth-subtitle">Cree en tu talento,<br>tuyo es el pincel</p>
  </div>

  <!-- Formulario -->
  <form method="POST" action="<?= url('login') ?>" novalidate class="mt-3">
    <?= csrf_field() ?>

    <div class="mb-3">
      <div class="input-group">
        <span class="input-group-text"><i class="fa fa-user"></i></span>
        <input type="text" name="email" class="form-control" required
               placeholder="Usuario o correo" autocomplete="username">
      </div>
    </div>

    <div class="mb-3">
      <div class="input-group">
        <span class="input-group-text"><i class="fa fa-lock"></i></span>
        <input type="password" name="password" id="pwd" class="form-control"
               required placeholder="Contraseña" autocomplete="current-password">
        <button type="button" class="input-group-text border-start-0" onclick="togglePwd()"
                style="cursor:pointer;background:rgba(20,10,48,.55)" tabindex="-1">
          <i class="fa fa-eye" id="eyeIco"></i>
        </button>
      </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3 px-1">
      <div class="form-check">
        <input class="form-check-input" type="checkbox" id="remember" name="remember">
        <label class="form-check-label" for="remember">Recordarme</label>
      </div>
      <a href="<?= url('recuperar') ?>" class="small" style="color:var(--lavender)">
        ¿Olvidaste tu contraseña?
      </a>
    </div>

    <button type="submit" class="btn btn-magic w-100 py-3 mb-2">
      <i class="fa fa-wand-magic-sparkles me-2"></i>Iniciar sesión
    </button>

    <?php if(!empty($flash_reenviar)): ?>
    <div class="mt-2 text-center">
      <a href="<?= url('reenviar-verificacion') ?>" class="small text-magenta">
        <i class="fa fa-envelope me-1"></i>Reenviar correo de verificación
      </a>
    </div>
    <?php endif; ?>
  </form>

  <!-- Social -->
  <div class="continue-divider">o continúa con</div>
  <div class="social-row">
    <a href="<?= url('auth/google') ?>" class="social-orb" title="Google">
      <i class="fab fa-google"></i>
    </a>
    <a href="#" class="social-orb" title="Apple">
      <i class="fab fa-apple"></i>
    </a>
    <a href="#" class="social-orb" title="Discord">
      <i class="fab fa-discord"></i>
    </a>
  </div>

  <!-- Registro -->
  <p class="text-center mt-3 mb-0" style="color:rgba(244,236,255,.65);font-size:.92rem">
    ¿Aún no tienes cuenta?
    <a href="<?= url('registro') ?>" class="ms-1" style="color:var(--magenta);font-weight:700;text-shadow:0 0 12px rgba(199,125,255,.5)">
      Regístrate
    </a>
  </p>
  <div class="text-center mt-2" style="color:var(--lavender);opacity:.5;letter-spacing:.6rem">✦</div>
</div>

<script>
function togglePwd() {
  var p = document.getElementById('pwd'), i = document.getElementById('eyeIco');
  p.type = p.type === 'password' ? 'text' : 'password';
  i.className = p.type === 'text' ? 'fa fa-eye-slash' : 'fa fa-eye';
}
</script>
