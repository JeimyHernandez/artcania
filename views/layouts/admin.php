<?php
if (!Auth::check() || !Auth::isAdmin()) {
    header('Location: ' . url('login')); exit;
}
$user          = Auth::user();
$flash_success = isset($flash_success) ? $flash_success : (Session::getFlash('success') ?: '');
$flash_error   = isset($flash_error)   ? $flash_error   : (Session::getFlash('error') ?: '');
$csrf_token    = $_SESSION['csrf_token'] ?? '';
$current       = ltrim(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH), '/');
?><!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Administrador – Artcania</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="<?= asset('css/main.css') ?>">
<link rel="stylesheet" href="<?= asset('css/admin.css') ?>">
</head>
<body class="artcania-body">
<div class="d-flex">

  <!-- Sidebar -->
  <nav class="sidebar-magic d-flex flex-column flex-shrink-0">
    <a href="<?= url('/') ?>" class="sidebar-brand"><img src="<?= asset('img/logo_artcadia.png') ?>" alt="Artcadia" class="brand-logo-img"><span>ARTCADIA</span></a>
    <span class="badge mb-3 px-2 py-1 mx-1" style="background:linear-gradient(135deg,rgba(220,53,69,.3),rgba(200,30,50,.2));color:#f8a0a0;border:1px solid rgba(220,53,69,.2);font-size:.68rem;font-family:'Cinzel',serif;width:fit-content">
      Administrador
    </span>
    <ul class="nav flex-column gap-1 flex-grow-1">
      <li><a href="<?= url('admin/dashboard') ?>" class="nav-link"><i class="fa fa-tachometer-alt me-2"></i>Dashboard</a></li>
      <li><a href="<?= url('admin/usuarios') ?>" class="nav-link"><i class="fa fa-users me-2"></i>Usuarios</a></li>
      <li><a href="<?= url('admin/roles') ?>" class="nav-link"><i class="fa fa-user-tag me-2"></i>Roles</a></li>
      <li><a href="<?= url('admin/artistas') ?>" class="nav-link"><i class="fa fa-palette me-2"></i>Artistas</a></li>
      <li><a href="<?= url('admin/curadores') ?>" class="nav-link"><i class="fa fa-eye me-2"></i>Curadores</a></li>
      <li><a href="<?= url('admin/obras') ?>" class="nav-link"><i class="fa fa-images me-2"></i>Obras</a></li>
      <li><a href="<?= url('admin/subastas') ?>" class="nav-link"><i class="fa fa-gavel me-2"></i>Subastas</a></li>
      <li><a href="<?= url('admin/exposiciones') ?>" class="nav-link"><i class="fa fa-building me-2"></i>Exposiciones</a></li>
      <li><a href="<?= url('admin/bitacora') ?>" class="nav-link"><i class="fa fa-list-alt me-2"></i>Bitácora</a></li>
      <li><a href="<?= url('admin/configuracion') ?>" class="nav-link"><i class="fa fa-cog me-2"></i>Configuración</a></li>
      <li><a href="<?= url('reporte/bitacora/pdf') ?>" class="nav-link" target="_blank"><i class="fa fa-file-pdf me-2" style="color:#dc3545"></i>PDF Bitácora</a></li>
      <li><a href="<?= url('reporte/bitacora/excel') ?>" class="nav-link" target="_blank"><i class="fa fa-file-excel me-2" style="color:#5DD6C0"></i>Excel Bitácora</a></li>
      <li><hr class="divider-magic my-2"></li>
    </ul>
    <div class="mt-auto pt-2" style="border-top:1px solid rgba(166,189,255,.08)">
      <div class="d-flex align-items-center gap-2 px-2 mb-2">
        <?php if(!empty($user['avatar'])): ?>
          <img src="<?= media_url('Originales/imagen/Avatares/'.$user['avatar']) ?>"
               style="width:28px;height:28px;border-radius:50%;object-fit:cover;border:1px solid rgba(166,189,255,.2)">
        <?php else: ?>
          <div style="width:28px;height:28px;border-radius:50%;background:linear-gradient(135deg,var(--purple),var(--teal));
                      display:flex;align-items:center;justify-content:center;font-size:.75rem;color:#fff;font-weight:700">
            <?= mb_strtoupper(mb_substr($user['nombre']??'A',0,1)) ?>
          </div>
        <?php endif; ?>
        <small style="color:rgba(244,247,251,.5);font-size:.75rem;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
          <?= e($user['nombre'] ?? '') ?>
        </small>
      </div>
      <a href="<?= url('logout') ?>" class="nav-link" style="color:rgba(220,53,69,.7);font-size:.82rem">
        <i class="fa fa-sign-out-alt me-2"></i>Cerrar sesión
      </a>
    </div>
  </nav>

  <!-- Contenido -->
  <main class="flex-grow-1 p-4 main-content">
    <?php if ($flash_success): ?>
    <div class="alert alert-success alert-dismissible fade show">
      <i class="fa fa-check-circle me-2"></i><?= e($flash_success) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>
    <?php if ($flash_error): ?>
    <div class="alert alert-danger alert-dismissible fade show">
      <i class="fa fa-exclamation-circle me-2"></i><?= e($flash_error) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>
    <?= $content ?>
  </main>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script>var BASE_URL = <?= json_encode(rtrim(artcania_config()['url'], '/')) ?>;</script>
<script src="<?= asset('js/main.js') ?>"></script>
<script>
$(document).ready(function() {
  $('.tabla-dt').DataTable({
    language: { url: 'https://cdn.datatables.net/plug-ins/1.13.8/i18n/es-MX.json' },
    pageLength: 15, responsive: true, order: [[0, 'desc']]
  });
});
</script>
<script src="<?= asset('js/cosmic.js') ?>"></script>
</body>
</html>
