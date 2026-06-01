<?php $pageTitle = 'Iniciar Sesión'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title><?= $pageTitle ?></title>
  <!-- Recursos locales para evitar dependencias externas (CDN) -->
  <link rel="stylesheet" href="<?= asset('css/bootstrap.min.css') ?>">
  <link rel="stylesheet" href="<?= asset('css/all.min.css') ?>">
  <link rel="stylesheet" href="<?= asset('css/main.css') ?>">
</head>

<body>
<div class="auth-shell anim-fade-up">
  <div class="text-center mb-2">

<!-- Logo principal de Artcania -->
<img src="<?= asset('img/logo.png') ?>"
     alt="Artcania"
     class="auth-logo-img">
<h1 class="auth-title">ARTCANIA</h1>
<div class="auth-divider">
  <span>✦</span>
  <span>✧</span>
  <span>✦</span>
</div>
<p class="auth-subtitle">
  ✦ CREE EN TU TALENTO. ES TUYO EL PINCEL ✦
</p>
  </div>

  <!-- Formulario de inicio de sesión -->
  <form method="POST"
        action="<?= url('login') ?>"
        novalidate
        class="mt-3">

<!-- Protección CSRF -->
<?= csrf_field() ?>
<div class="mb-3">
  <div class="input-group">
    <span class="input-group-text">
      <i class="fa fa-user"></i>
    </span>

    <!-- Campo de correo para autenticación -->
    <input
      type="text"
      name="email"
      class="form-control"
      required
      placeholder="Correo electrónico"
      autocomplete="username"
    >
  </div>
</div>

<!-- Campo de contraseña con opción de mostrar/ocultar -->
<div class="mb-3">
  <div class="input-group">
    <span class="input-group-text">
      <i class="fa fa-lock"></i>
    </span>
    <input
      type="password"
      name="password"
      id="pwd"
      class="form-control"
      required
      placeholder="Contraseña"
      autocomplete="current-password"
    >

    <!-- Botón para visualizar la contraseña -->
    <button
      type="button"
      class="input-group-text border-start-0"
      onclick="togglePwd()"
      style="cursor:pointer;background:rgba(20,10,48,.55)"
    >
      <i class="fa fa-eye" id="eyeIco"></i>
    </button>
  </div>
</div>
<div class="d-flex justify-content-between align-items-center mb-3 px-1">
  <div class="form-check">
    <input class="form-check-input"
           type="checkbox"
           id="remember"
           name="remember">
    <label class="form-check-label" for="remember">
      Recordarme
    </label>
  </div>

  <!-- Enlace para recuperación de contraseña -->
  <a href="<?= url('recuperar') ?>"
     class="small"
     style="color:var(--lavender)">
    ¿Olvidaste tu contraseña?
  </a>
</div>
<button type="submit"
        class="btn btn-magic w-100 py-3 mb-2">
  <i class="fa fa-wand-magic-sparkles me-2"></i>
  Iniciar sesión
</button>
<div class="mt-2 text-center">
  <!-- Reenvío de correo de verificación -->
  <a href="<?= url('reenviar-verificacion') ?>"
     class="small text-magenta">
    <i class="fa fa-envelope me-1"></i>
    Reenviar correo de verificación
  </a>
</div>
  </form>
  <div class="continue-divider">
    o continúa con
  </div>

  <!-- Opciones de autenticación mediante proveedores externos -->
  <div class="social-row">

<!-- Login con Google -->
<a href="<?= url('auth/google') ?>"
   class="social-orb">
  <i class="fab fa-google"></i>
</a>

<!-- Pendiente implementación o cambio -->
<a href="#"
   class="social-orb">
  <i class="fab fa-apple"></i>
</a>

<!-- Pendiente implementación o cambio-->
<a href="#"
   class="social-orb">
  <i class="fab fa-discord"></i>
</a>
  </div>
  <p class="text-center mt-3 mb-0"
     style="color:rgba(244,236,255,.65);font-size:.92rem">
¿Aún no tienes cuenta?
<a href="<?= url('registro') ?>"
   class="ms-1"
   style="color:var(--magenta);font-weight:700">
  Regístrate
</a>
  </p>

</div>

<!-- Librerías JavaScript locales -->
<script src="<?= asset('js/jquery-3.6.0.min.js') ?>"></script>
<script src="<?= asset('js/popper.min.js') ?>"></script>
<script src="<?= asset('js/bootstrap.min.js') ?>"></script>
<script>
  
/**
 * Mostrar u ocultar la contraseña ingresada por el usuario.
 */
function togglePwd()
{
  var p = document.getElementById('pwd');
  var i = document.getElementById('eyeIco');
  p.type = p.type === 'password'
    ? 'text'
    : 'password';
  i.className =
    p.type === 'text'
      ? 'fa fa-eye-slash'
      : 'fa fa-eye';
}
</script>
</body>
</html>