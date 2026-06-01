<?php $pageTitle = 'Reenviar verificación'; ?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title><?= $pageTitle ?></title>
  <link rel="stylesheet" href="<?= asset('css/bootstrap.min.css') ?>">
  <link rel="stylesheet" href="<?= asset('css/all.min.css') ?>">
  <link rel="stylesheet" href="<?= asset('css/main.css') ?>">
</head>

<body>
<div class="auth-shell anim-fade-up">
  <div class="text-center mb-3">
    <img src="<?= asset('img/logo.png') ?>"
         alt="Artcania"
         class="auth-logo-img">
    <h1 class="auth-title" style="font-size:1.5rem">
      VERIFICACIÓN
    </h1>

    <div class="auth-divider">
      <span>✦</span>
      <span>✧</span>
      <span>✦</span>
    </div>

    <p class="auth-subtitle">
      Reenviaremos tu correo
      de verificación encantado
    </p>
  </div>

  <form method="POST"
        action="<?= url('reenviar-verificacion') ?>"
        novalidate>

    <?= csrf_field() ?>

    <div class="mb-3">
      <div class="input-group">
        <span class="input-group-text">
          <i class="fa fa-envelope"></i>
        </span>

        <input
          type="email"
          name="email"
          class="form-control"
          required
          placeholder="tu@correo.com"
        >
      </div>
    </div>

    <button type="submit"
            class="btn btn-magic w-100 py-3">
      <i class="fa fa-wand-magic-sparkles me-2"></i>
      Reenviar verificación
    </button>
  </form>

  <p class="text-center mt-3 mb-0"
     style="font-size:.92rem">

    <a href="<?= url('login') ?>"
       style="color:var(--magenta);font-weight:600">

      <i class="fa fa-arrow-left me-1"></i>
      Volver al inicio de sesión
    </a>
  </p>
</div>

<script src="<?= asset('js/jquery-3.6.0.min.js') ?>"></script>
<script src="<?= asset('js/popper.min.js') ?>"></script>
<script src="<?= asset('js/bootstrap.min.js') ?>"></script>

</body>
</html>
