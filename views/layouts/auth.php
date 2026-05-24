<?php
$flash_success   = isset($flash_success)   ? $flash_success   : (Session::getFlash('success') ?: '');
$flash_error     = isset($flash_error)     ? $flash_error     : (Session::getFlash('error') ?: '');
$flash_errors    = isset($flash_errors)    ? $flash_errors    : Session::getFlash('errors');
$flash_reenviar  = Session::getFlash('error_reenviar');
$csrf_token      = $_SESSION['csrf_token'] ?? '';
?><!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Artcania – Acceso al reino</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="<?= asset('css/main.css') ?>">
</head>
<body class="artcania-auth-bg d-flex align-items-center justify-content-center min-vh-100 py-4">

<!-- Glifos decorativos -->
<div aria-hidden="true" style="position:fixed;inset:0;pointer-events:none;overflow:hidden;z-index:2">
  <div style="position:absolute;top:12%;left:8%;font-size:1.6rem;color:rgba(212,184,255,.35);animation:floatStars 7s ease-in-out infinite">✦</div>
  <div style="position:absolute;top:68%;left:4%;font-size:1rem;color:rgba(199,125,255,.4);animation:floatStars 9s ease-in-out infinite 2s">✧</div>
  <div style="position:absolute;top:22%;right:7%;font-size:1.3rem;color:rgba(212,184,255,.3);animation:floatStars 8s ease-in-out infinite 1s">✦</div>
  <div style="position:absolute;top:82%;right:10%;font-size:.9rem;color:rgba(199,125,255,.4);animation:floatStars 10s ease-in-out infinite 3s">✧</div>
  <div style="position:absolute;top:45%;left:50%;font-size:1.1rem;color:rgba(212,184,255,.25);animation:floatStars 11s ease-in-out infinite 4s">✦</div>
</div>

<div class="container d-flex justify-content-center" style="position:relative;z-index:3">
  <div class="w-100" style="max-width:480px">
    <?php if ($flash_success): ?>
    <div class="alert alert-success text-center mb-3"><i class="fa fa-sparkles me-2"></i><?= e($flash_success) ?></div>
    <?php endif; ?>
    <?php if ($flash_error): ?>
    <div class="alert alert-danger text-center mb-3"><i class="fa fa-triangle-exclamation me-2"></i><?= e($flash_error) ?></div>
    <?php endif; ?>
    <?php if (!empty($flash_errors)): ?>
    <div class="alert alert-warning mb-3">
      <ul class="mb-0 small">
        <?php foreach ($flash_errors as $errs): foreach ($errs as $err): ?>
        <li><?= e($err) ?></li>
        <?php endforeach; endforeach; ?>
      </ul>
    </div>
    <?php endif; ?>
    <?= $content ?>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="<?= asset('js/cosmic.js') ?>"></script>
</body>
</html>
