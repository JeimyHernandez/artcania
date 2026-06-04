<?php $pageTitle = 'Iniciar sesión'; ?>

<div class="text-center mb-3">
  <a href="<?= url('') ?>" class="auth-back-home">
    <i class="fa fa-arrow-left"></i> Volver al inicio
  </a>
</div>

<div class="card-auth">

  <!-- =========================================================
       Encabezado visual del formulario.
       Muestra el logo principal de Artcania junto al nombre
       de la plataforma y el eslogan para el proyecto <3.
  ========================================================= -->
  <div class="card-header-magic">

    <img
      src="<?= asset('img/logo.png') ?>"
      alt="Logo Artcania"
      class="auth-logo-img"
    >

    <div class="auth-logo-text">ARTCANIA</div>

    <div class="auth-tagline">
      ✧ Cree en tu talento, tuyo es el pincel ✧
    </div>

  </div>

  <div class="card-body">

  <div class="card-body">
    <form method="POST" action="<?= url('login') ?>" novalidate>
      <?= csrf_field() ?>

      <!-- Email -->
      <div class="mb-3">
        <div class="input-group">
          <span class="input-group-text"><i class="fa fa-user"></i></span>
          <input type="email" name="email" class="form-control"
                 placeholder="Usuario o correo"
                 value="<?= old('email') ?>" required autofocus>
        </div>
      </div>

      <!-- Password -->
      <div class="mb-3">
        <div class="input-group">
          <span class="input-group-text"><i class="fa fa-lock"></i></span>
          <input type="password" name="password" id="passInput"
                 class="form-control" placeholder="Contraseña" required>
          <button type="button" class="input-group-text" id="togglePass"
                  style="cursor:pointer;border-radius:0 12px 12px 0;border-left:none">
            <i class="fa fa-eye" id="passIcon"></i>
          </button>
        </div>
      </div>

      <!-- Recordar / Olvidé -->
      <div class="d-flex align-items-center justify-content-between mb-4">
        <div class="form-check mb-0">
          <input class="form-check-input" type="checkbox" name="remember" id="rememberMe"
                 style="background:transparent;border-color:var(--border)">
          <label class="form-check-label" for="rememberMe"
                 style="font-size:.83rem;color:var(--pearl-muted)">Recordarme</label>
        </div>
        <a href="<?= url('recuperar') ?>" style="font-size:.83rem;color:var(--pearl-muted);text-decoration:none">
          ¿Olvidaste tu contraseña?
        </a>
      </div>

      <!-- Botón -->
      <button type="submit" class="btn btn-magic w-100 py-2" style="font-size:1rem;letter-spacing:.08em">
        ✦ Iniciar sesión ✦
      </button>
    </form>

    <!-- Reenviar verificación (solo si error_reenviar) -->
    <?php if(!empty($flash_reenviar)): ?>
      <div class="text-center mt-2">
        <a href="<?= url('reenviar-verificacion') ?>" style="color:var(--teal);font-size:.83rem">
          <i class="fa fa-envelope me-1"></i>Reenviar correo de verificación
        </a>
      </div>
    <?php endif; ?>

    <!-- Divisor -->
    <div class="auth-divider">o continúa con</div>

    <!-- Google OAuth -->
    <a href="<?= url('auth/google') ?>"
       class="btn w-100 mb-3 d-flex align-items-center justify-content-center gap-2"
       style="background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.15);color:var(--pearl);border-radius:12px;padding:.6rem;font-weight:500;transition:all .25s"
       onmouseover="this.style.background='rgba(255,255,255,.12)'"
       onmouseout="this.style.background='rgba(255,255,255,.06)'">
      <svg width="18" height="18" viewBox="0 0 24 24">
        <path fill="#4285f4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
        <path fill="#34a853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
        <path fill="#fbbc05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
        <path fill="#ea4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
      </svg>
      Continuar con Google
    </a>

    <!-- Registro -->
    <div class="text-center" style="font-size:.85rem;color:var(--pearl-muted)">
      ¿Aún no tienes cuenta?
      <a href="<?= url('registro') ?>" style="color:var(--purple-mid);font-weight:600;text-decoration:none"> Regístrate</a>
    </div>
  </div>
  
</div>


<script>

document.getElementById('togglePass').addEventListener('click', function(){
  var inp = document.getElementById('passInput');
  var ico = document.getElementById('passIcon');
  if(inp.type === 'password'){
    inp.type = 'text';
    ico.classList.replace('fa-eye','fa-eye-slash');
  } else {
    inp.type = 'password';
    ico.classList.replace('fa-eye-slash','fa-eye');
  }
});
</script>
