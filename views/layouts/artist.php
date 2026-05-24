<?php
if (!Auth::check() || !Auth::isArtista()) {
    header('Location: ' . url('login')); exit;
}
$user          = Auth::user();
$flash_success = isset($flash_success) ? $flash_success : (Session::getFlash('success') ?: '');
$flash_error   = isset($flash_error)   ? $flash_error   : (Session::getFlash('error') ?: '');
$csrf_token    = $_SESSION['csrf_token'] ?? '';
?><!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Mi Studio – Artcania</title>
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
  <nav class="sidebar-magic d-flex flex-column flex-shrink-0">
    <a href="<?= url('/') ?>" class="sidebar-brand"><img src="<?= asset('img/logo_artcadia.png') ?>" alt="Artcadia" class="brand-logo-img"><span>ARTCADIA</span></a>
    <span class="badge mb-3 px-2 py-1 mx-1" style="background:linear-gradient(135deg,rgba(92,77,155,.3),rgba(93,214,192,.15));color:var(--teal);border:1px solid rgba(93,214,192,.2);font-size:.68rem;font-family:'Cinzel',serif;width:fit-content">
      Mi Studio ✦
    </span>
    <ul class="nav flex-column gap-1 flex-grow-1">
      <li><a href="<?= url('artista/dashboard') ?>" class="nav-link"><i class="fa fa-tachometer-alt me-2"></i>Dashboard</a></li>
      <li><a href="<?= url('artista/obras') ?>" class="nav-link"><i class="fa fa-images me-2"></i>Mis Obras</a></li>
      <li><a href="<?= url('artista/metricas') ?>" class="nav-link"><i class="fa fa-chart-line me-2"></i>Métricas</a></li>
      <li><a href="<?= url('artista/editar-perfil') ?>" class="nav-link"><i class="fa fa-user-edit me-2"></i>Mi Perfil Artístico</a></li>
      <li><a href="<?= url('artista/mis-fanarts') ?>" class="nav-link"><i class="fa fa-star me-2"></i>Fan Arts Recibidos</a></li>
      <li><a href="<?= url('artista/colaboraciones') ?>" class="nav-link"><i class="fa fa-handshake me-2"></i>Colaboraciones</a></li>
      <li><a href="<?= url('artista/premios') ?>" class="nav-link"><i class="fa fa-trophy me-2"></i>Premios</a></li>
      <li><hr class="divider-magic my-2"></li>
      <li><a href="<?= url('galeria') ?>" class="nav-link"><i class="fa fa-compass me-2"></i>Explorar Galería</a></li>
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
