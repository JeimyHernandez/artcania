<?php $pageTitle = 'Recuperar contraseña'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title><?= $pageTitle ?></title>
  <!-- Recursos locales del proyecto -->
  <link rel="stylesheet" href="<?= asset('css/bootstrap.min.css') ?>">
  <link rel="stylesheet" href="<?= asset('css/all.min.css') ?>">
  <link rel="stylesheet" href="<?= asset('css/main.css') ?>">
</head>
<body>
<div class="auth-card-wide" style="max-width:480px">
  <div class="text-center mb-3">

<!-- Logo nuevo de Artcania -->
<img src="<?= asset('img/logo.png') ?>"
     alt="Artcania"
     class="auth-logo-img"
     style="width:160px">
<h1 style="font-family:'Cinzel',serif;color:var(--moon);font-size:1.6rem;letter-spacing:.25em;margin:.4rem 0 1.5rem">
  ARTCANIA
</h1>
<h2 style="font-family:'Cinzel',serif;background:linear-gradient(180deg,#e9d8ff,#a766ff);-webkit-background-clip:text;background-clip:text;-webkit-text-fill-color:transparent;font-size:2.2rem;letter-spacing:.05em">
  Recuperar contraseña
</h2>
<div class="my-2" style="color:var(--gold)">
  ✦ ◆ ✦
</div>
<p style="color:rgba(212,184,255,.8);font-family:'Cormorant Garamond',serif;font-size:1.1rem">
  Ingresa tu correo electrónico y te enviaremos
  un enlace mágico para restablecer tu contraseña.
</p>
  </div>

  <!-- Formulario para solicitar recuperación de contraseña -->
  <form method="POST" action="<?= url('recuperar') ?>">

<!-- Protección CSRF -->
<?= csrf_field() ?>
<div class="mb-4 position-relative">
  <i class="fa fa-envelope position-absolute"
     style="top:14px;left:16px;color:var(--lavender)">
  </i>

  <!-- Correo donde se enviará el enlace de recuperación -->
  <input
    name="email"
    type="email"
    required
    class="input-magic"
    placeholder="Tu correo electrónico"
    style="padding-left:46px;padding:.9rem 1rem .9rem 46px"
  >
</div>
<button class="btn-magic btn-magic-solid w-100"
        style="padding:1rem;font-size:1.15rem;font-family:'Cinzel',serif">
  Enviar enlace mágico ✦
</button>
  </form>
  <div class="text-center mt-4">

<!-- Regresar al formulario de inicio de sesión -->
<a href="<?= url('login') ?>"
   style="color:var(--magenta);font-family:'Cormorant Garamond',serif;font-size:1.05rem">
  ← Volver al inicio de sesión
</a>
  </div>
</div>

<!-- Librerías JavaScript locales -->
<script src="<?= asset('js/jquery-3.6.0.min.js') ?>"></script>
<script src="<?= asset('js/popper.min.js') ?>"></script>
<script src="<?= asset('js/bootstrap.min.js') ?>"></script>
</body>
</html>