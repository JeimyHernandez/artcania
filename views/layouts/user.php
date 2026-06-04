<?php
if (!Auth::check()) { header('Location: ' . url('login')); exit; }
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
  <meta name="csrf" content="<?= htmlspecialchars($csrf_token ?? '', ENT_QUOTES) ?>">
  <title><?= isset($pageTitle) ? e($pageTitle).' – ' : '' ?>Artcania</title>
  <link rel="stylesheet" href="<?= asset('css/bootstrap.min.css') ?>">
  <link rel="stylesheet" href="<?= asset('css/fontawesome.min.css') ?>">
  <link rel="stylesheet" href="<?= asset('css/main.css') ?>">
</head>
<body>

<!-- Navbar compacta para usuario -->
<nav class="artcania-navbar navbar navbar-expand-lg">
  <div class="container-xl">
    <a class="navbar-brand" href="<?= url('') ?>">
      <span class="brand-gem"><i class="fa-solid fa-gem" style="font-size:1rem"></i></span> ARTCANIA
    </a>
    <button class="navbar-toggler border-0 ms-auto me-2" type="button"
            data-bs-toggle="collapse" data-bs-target="#navUser">
      <i class="fa fa-bars" style="color:var(--pearl-dim)"></i>
    </button>
    <div class="collapse navbar-collapse" id="navUser">
      <ul class="navbar-nav mx-auto gap-1">
        <li class="nav-item"><a class="nav-link <?= $page===''?'active':'' ?>" href="<?= url('') ?>">Inicio</a></li>
        <li class="nav-item"><a class="nav-link <?= $page==='galeria'?'active':'' ?>" href="<?= url('galeria') ?>">Galería</a></li>
        <li class="nav-item"><a class="nav-link <?= $page==='artistas'?'active':'' ?>" href="<?= url('artistas') ?>">Artistas</a></li>
        <li class="nav-item"><a class="nav-link <?= $page==='subastas'?'active':'' ?>" href="<?= url('subastas') ?>">Subastas</a></li>
        <li class="nav-item"><a class="nav-link <?= $page==='chat'?'active':'' ?>" href="<?= url('chat') ?>">Chat</a></li>
      </ul>
      <div class="d-flex align-items-center gap-2 mt-2 mt-lg-0">
        <button class="navbar-notif-btn" onclick="location.href='<?= url('notificaciones') ?>'" title="Notificaciones">
          <i class="fa fa-bell" style="font-size:.9rem"></i>
          <span class="badge-dot d-none" id="notifDot"></span>
        </button>
        <div class="dropdown">
          <div class="d-flex align-items-center gap-2" style="cursor:pointer" data-bs-toggle="dropdown">
            <img src="<?= avatar($user['avatar'] ?? '') ?>" class="navbar-avatar"
                 style="width:34px;height:34px" alt="<?= e($user['nombre']) ?>">
            <i class="fa fa-chevron-down" style="font-size:.65rem;color:var(--pearl-muted)"></i>
          </div>
          <ul class="dropdown-menu dropdown-menu-end"
              style="background:var(--panel-solid);border:1px solid var(--border-gold);border-radius:14px;margin-top:.5rem">
            <li><a class="dropdown-item" href="<?= url('perfil') ?>"
                   style="color:var(--pearl-dim);font-size:.85rem;padding:.6rem 1rem">
                <i class="fa fa-user me-2" style="color:var(--purple-mid)"></i>Mi perfil</a></li>
            <li><a class="dropdown-item" href="<?= url('mis-favoritos') ?>"
                   style="color:var(--pearl-dim);font-size:.85rem;padding:.6rem 1rem">
                <i class="fa fa-heart me-2" style="color:#f472b6"></i>Favoritos</a></li>
            <li><a class="dropdown-item" href="<?= url('mis-valoraciones') ?>"
                   style="color:var(--pearl-dim);font-size:.85rem;padding:.6rem 1rem">
                <i class="fa fa-star me-2" style="color:var(--gold-light)"></i>Valoraciones</a></li>
            <li><a class="dropdown-item" href="<?= url('notificaciones') ?>"
                   style="color:var(--pearl-dim);font-size:.85rem;padding:.6rem 1rem">
                <i class="fa fa-bell me-2" style="color:var(--teal)"></i>Notificaciones</a></li>
            <li><hr class="dropdown-divider" style="border-color:var(--border)"></li>
            <li><a class="dropdown-item" href="<?= url('logout') ?>"
                   style="color:#f87171;font-size:.85rem;padding:.6rem 1rem">
                <i class="fa fa-right-from-bracket me-2"></i>Cerrar sesión</a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</nav>

<?php if($flash_success || $flash_error): ?>
<div class="container-xl pt-3">
  <?php if($flash_success): ?>
    <div class="alert-success-magic d-flex align-items-center gap-2">
      <i class="fa fa-circle-check"></i> <?= e($flash_success) ?>
    </div>
  <?php endif; ?>
  <?php if($flash_error): ?>
    <div class="alert-danger-magic d-flex align-items-center gap-2">
      <i class="fa fa-circle-exclamation"></i> <?= e($flash_error) ?>
    </div>
  <?php endif; ?>
</div>
<?php endif; ?>

<main class="container-xl py-4"><?= $content ?></main>

<script>var BASE_URL = <?= json_encode(rtrim($cfg['url'], '/')) ?>;</script>
<script src="<?= asset('js/jquery.min.js') ?>"></script>
<script src="<?= asset('js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= asset('js/main.js') ?>"></script>
</body>
</html>
