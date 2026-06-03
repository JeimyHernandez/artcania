<?php
if (!Auth::check() || !in_array(Auth::rol(), ['curador','admin'])) {
    header('Location: ' . url('login')); exit;
}
$user = Auth::user();
$cfg  = artcania_config();
$flash_success = isset($flash_success) ? $flash_success : (Session::getFlash('success') ?: '');
$flash_error   = isset($flash_error)   ? $flash_error   : (Session::getFlash('error')   ?: '');
$uri  = ltrim(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH), '/');
$base = ltrim(parse_url($cfg['url'], PHP_URL_PATH), '/');
$page = $base ? ltrim(substr($uri, strlen($base)), '/') : $uri;
?><!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= isset($pageTitle) ? e($pageTitle).' – ' : '' ?>Curador · Artcania</title>
  <link rel="stylesheet" href="<?= asset('css/bootstrap.min.css') ?>">
  <link rel="stylesheet" href="<?= asset('css/fontawesome.min.css') ?>">
  <link rel="stylesheet" href="<?= asset('css/main.css') ?>">
  <link rel="stylesheet" href="<?= asset('css/curador.css') ?>">
</head>
<body>

<aside class="artcania-sidebar" id="sidebar">
  <div class="sidebar-brand">
    <div class="sidebar-brand-text">✦ ARTCANIA</div>
    <div class="sidebar-subtitle" style="color:var(--teal)">Portal del Curador</div>
  </div>

  <nav class="sidebar-nav">
    <a href="<?= url('curador/dashboard') ?>" class="sidebar-nav-item <?= $page==='curador/dashboard'?'active':'' ?>">
      <i class="fa fa-chart-pie"></i> Dashboard
    </a>
    <a href="<?= url('curador/obras-pendientes') ?>" class="sidebar-nav-item <?= strpos(\$page,'curador/obras-pendientes')===0?'active':'' ?>">
      <i class="fa fa-hourglass-half"></i> Obras Pendientes
    </a>
    <a href="<?= url('curador/validar') ?>" class="sidebar-nav-item <?= strpos(\$page,'curador/validar')===0?'active':'' ?>">
      <i class="fa fa-circle-check"></i> Validar Obra
    </a>
    <a href="<?= url('curador/historial') ?>" class="sidebar-nav-item <?= strpos(\$page,'curador/historial')===0?'active':'' ?>">
      <i class="fa fa-clock-rotate-left"></i> Historial
    </a>
    <a href="<?= url('curador/destacados') ?>" class="sidebar-nav-item <?= strpos(\$page,'curador/destacados')===0?'active':'' ?>">
      <i class="fa fa-star"></i> Destacados
    </a>
    <a href="<?= url('curador/exposiciones') ?>" class="sidebar-nav-item <?= strpos(\$page,'curador/exposiciones')===0?'active':'' ?>">
      <i class="fa fa-landmark"></i> Exposiciones
    </a>
    <a href="<?= url('curador/moderar-comentarios') ?>" class="sidebar-nav-item <?= strpos(\$page,'curador/moderar')===0?'active':'' ?>">
      <i class="fa fa-comments"></i> Comentarios
    </a>
    <a href="<?= url('curador/reportes') ?>" class="sidebar-nav-item <?= strpos(\$page,'curador/reportes')===0?'active':'' ?>">
      <i class="fa fa-flag"></i> Reportes
    </a>
    <a href="<?= url('curador/metricas') ?>" class="sidebar-nav-item <?= strpos(\$page,'curador/metricas')===0?'active':'' ?>">
      <i class="fa fa-chart-bar"></i> Métricas
    </a>

    <div class="sidebar-section-label">Mi cuenta</div>
    <a href="<?= url('perfil') ?>" class="sidebar-nav-item <?= strpos(\$page,'perfil')===0?'active':'' ?>">
      <i class="fa fa-user"></i> Mi Perfil
    </a>
    <a href="<?= url('notificaciones') ?>" class="sidebar-nav-item <?= strpos(\$page,'notificaciones')===0?'active':'' ?>">
      <i class="fa fa-bell"></i> Notificaciones
    </a>
  </nav>

  <div class="sidebar-footer">
    <div class="sidebar-user-info">
      <img src="<?= avatar($user['avatar'] ?? '') ?>" alt="<?= e($user['nombre']) ?>">
      <div>
        <div class="user-name"><?= e($user['nombre']) ?></div>
        <div class="user-role" style="color:var(--teal)">Curador</div>
      </div>
    </div>
    <a href="<?= url('logout') ?>" class="btn btn-sm w-100 mt-2"
       style="background:rgba(248,113,113,.12);color:#f87171;border:1px solid rgba(248,113,113,.25);border-radius:10px;font-size:.78rem">
      <i class="fa fa-right-from-bracket me-1"></i>Salir
    </a>
  </div>
</aside>

<div class="artcania-main">
  <div class="d-flex align-items-center mb-4 gap-2">
    <button class="btn d-lg-none" id="sidebarToggle"
            style="background:var(--card-bg);border:1px solid var(--border);color:var(--pearl);border-radius:10px;padding:.4rem .7rem">
      <i class="fa fa-bars"></i>
    </button>
    <?php if($flash_success): ?>
      <div class="alert-success-magic flex-grow-1 py-2 px-3">
        <i class="fa fa-circle-check me-2"></i><?= e($flash_success) ?>
      </div>
    <?php endif; ?>
    <?php if($flash_error): ?>
      <div class="alert-danger-magic flex-grow-1 py-2 px-3">
        <i class="fa fa-circle-exclamation me-2"></i><?= e($flash_error) ?>
      </div>
    <?php endif; ?>
    <div class="ms-auto">
      <img src="<?= avatar($user['avatar'] ?? '') ?>" class="navbar-avatar"
           style="width:38px;height:38px" alt="">
    </div>
  </div>

  <?= $content ?>
</div>

<div class="d-lg-none" id="sidebarOverlay"
     style="display:none!important;position:fixed;inset:0;background:rgba(0,0,0,.6);z-index:1019"></div>

<script>var BASE_URL = <?= json_encode(rtrim($cfg['url'], '/')) ?>;</script>
<script src="<?= asset('js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= asset('js/jquery.min.js') ?>"></script>
<script src="<?= asset('js/main.js') ?>"></script>
<script>
$('#sidebarToggle').on('click',function(){ $('#sidebar').toggleClass('show'); $('#sidebarOverlay').toggle(); });
$('#sidebarOverlay').on('click',function(){ $('#sidebar').removeClass('show'); $(this).hide(); });
</script>
</body>
</html>

