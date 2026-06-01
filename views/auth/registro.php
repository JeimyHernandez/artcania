<?php $pageTitle = 'Crear cuenta'; ?>

<!-- archivos locales del proyecto -->
<link rel="stylesheet" href="<?= asset('css/bootstrap.min.css') ?>">
<link rel="stylesheet" href="<?= asset('css/all.min.css') ?>">
<link rel="stylesheet" href="<?= asset('css/main.css') ?>">
<div class="auth-shell anim-fade-up">

  <!-- Logo nuevo de Artcania -->
  <div class="text-center mb-4">
    <img src="<?= asset('img/logo.png') ?>"
         alt="Artcania"
         class="auth-logo-img"
         style="width:140px">
<h1 class="auth-title">ARTCANIA</h1>
<div style="color:var(--lavender);letter-spacing:.25em;font-size:.8rem;margin-top:.3rem">
  ✦ CREE EN TU TALENTO, ES TUYO EL PINCEL ✦
</div>
  </div>
  
  <!-- Formulario de registro -->
  <form method="POST" action="<?= url('registro') ?>">

<!-- Protección CSRF -->
<?= csrf_field() ?>

<!-- Nombre completo -->
<div class="mb-3 position-relative">
  <i class="fa fa-user position-absolute"
     style="top:14px;left:16px;color:var(--lavender)">
  </i>
  <input
    name="nombre"
    required
    class="input-magic"
    placeholder="Nombre"
    style="padding-left:46px"
  >
</div>

<!-- Nombre de usuario -->
<div class="mb-3 position-relative">
  <i class="fa fa-user position-absolute"
     style="top:14px;left:16px;color:var(--lavender)">
  </i>
  <input
    name="usuario"
    required
    class="input-magic"
    placeholder="Usuario"
    style="padding-left:46px"
  >
</div>

<!-- Correo electrónico -->
<div class="mb-3 position-relative">
  <i class="fa fa-envelope position-absolute"
     style="top:14px;left:16px;color:var(--lavender)">
  </i>
  <input
    name="email"
    type="email"
    required
    class="input-magic"
    placeholder="Correo"
    style="padding-left:46px"
  >
</div>

<!-- Contraseña -->
<div class="mb-3 position-relative">
  <i class="fa fa-lock position-absolute"
     style="top:14px;left:16px;color:var(--lavender)"></i>
  <input
    id="password"
    name="password"
    type="password"
    required
    class="input-magic"
    placeholder="Contraseña"
    style="padding-left:46px;padding-right:46px"
  >

  <!-- Botón mostrar/ocultar contraseña -->
  <i class="fa fa-eye position-absolute"
     id="eyePassword"
     style="top:14px;right:16px;color:var(--lavender);cursor:pointer"
     onclick="togglePassword('password','eyePassword')">
  </i>

  <!-- Requisitos mínimos definidos por el backend -->
  <small style="color:rgba(212,184,255,.75);
          display:block;
          margin-top:.4rem;
          font-size:.85rem">
    La contraseña debe tener mínimo 8 caracteres,
    una mayúscula y un número.
  </small>
</div>

<!-- Confirmación de contraseña -->
<div class="mb-3 position-relative">
  <i class="fa fa-lock position-absolute"
     style="top:14px;left:16px;color:var(--lavender)">
  </i>
  <input
    id="password_confirm"
    name="password_confirm"
    type="password"
    required
    class="input-magic"
    placeholder="Confirmar contraseña"
    style="padding-left:46px;padding-right:46px"
  >

  <!-- Botón mostrar/ocultar confirmación -->
  <i class="fa fa-eye position-absolute"
     id="eyeConfirm"
     style="top:14px;right:16px;color:var(--lavender);cursor:pointer"
     onclick="togglePassword('password_confirm','eyeConfirm')">
  </i>
</div>

<!-- Selección de rol -->
<div class="mb-3 p-3"
     style="background:rgba(12,6,30,.5);
            border:1px solid rgba(212,184,255,.2);
            border-radius:12px">
  <div style="color:var(--lavender)">
    <i class="fa fa-shield-halved me-2"></i>
    Rol
  </div>
  <div class="d-flex gap-4 mt-2">
    <label style="color:var(--moon)">
      <input type="radio"
             name="rol"
             value="usuario"
             checked>
      Usuario
    </label>
    <label style="color:var(--moon)">
      <input type="radio"
             name="rol"
             value="artista">
      Artista
    </label>
  </div>
</div>

<!-- Aceptación de términos -->
<div class="mb-3" style="color:var(--moon)">
  <label>
    <input type="checkbox" required>
    ✦ Acepto los
    <a href="#"
       style="color:var(--magenta)">
      términos y condiciones
    </a>
  </label>
</div>

<!-- Botón principal de registro -->
<button class="btn-magic btn-magic-solid w-100"
        style="padding:.9rem;font-size:1.1rem;letter-spacing:.2em">
  ✦ CREAR CUENTA ✦
</button>
  </form>

  <!-- Sección informativa inferior -->
  <div class="text-center mt-4">
<div style="font-family:'Cinzel',serif;
            color:var(--gold);
            letter-spacing:.2em;
            font-size:.85rem">
  ÚNETE A ARTCANIA
</div>
<small style="color:rgba(212,184,255,.6);
              font-style:italic;
              font-family:'Cormorant Garamond',serif">
  Donde la imaginación se convierte en eternidad.
</small>

<!-- Enlace hacia login -->
<div class="mt-2">
  <a href="<?= url('login') ?>"
     style="color:var(--magenta)">
    ¿Ya tienes cuenta? Inicia sesión
  </a>
</div>
  </div>
</div>
<script>
  
/*
|--------------------------------------------------------------------------
| Mostrar / Ocultar contraseña
|--------------------------------------------------------------------------
| Reutilizable para contraseña principal y confirmación.
*/
function togglePassword(inputId, eyeId)
{
    var input = document.getElementById(inputId);
    var eye   = document.getElementById(eyeId);
    if (input.type === 'password')
    {
        input.type = 'text';
        eye.className = 'fa fa-eye-slash position-absolute';
    }
    else
    {
        input.type = 'password';
        eye.className = 'fa fa-eye position-absolute';
    }
}
</script>