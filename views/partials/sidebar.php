<?php $u = Auth::user(); ?>
<div class="card shadow-sm mb-3">
  <div class="card-body text-center">
    <img src="<?= avatar($u['avatar']??'') ?>" class="rounded-circle mb-2" width="70" height="70" style="object-fit:cover">
    <p class="fw-bold mb-0"><?= e($u['nombre']) ?></p>
    <small class="text-muted"><?= e($u['rol']) ?></small>
  </div>
</div>
<ul class="nav flex-column gap-1">
  <?php if($u['rol']==='admin'): ?>
  <li><a href="<?=url('admin/dashboard')?>" class="nav-link"><i class="fa fa-tachometer-alt me-2"></i>Dashboard</a></li>
  <li><a href="<?=url('admin/usuarios')?>" class="nav-link"><i class="fa fa-users me-2"></i>Usuarios</a></li>
  <li><a href="<?=url('admin/bitacora')?>" class="nav-link"><i class="fa fa-list me-2"></i>Bitácora</a></li>
  <?php elseif($u['rol']==='artista'): ?>
  <li><a href="<?=url('artista/dashboard')?>" class="nav-link"><i class="fa fa-home me-2"></i>Dashboard</a></li>
  <li><a href="<?=url('artista/obras')?>" class="nav-link"><i class="fa fa-images me-2"></i>Mis Obras</a></li>
  <li><a href="<?=url('artista/metricas')?>" class="nav-link"><i class="fa fa-chart-line me-2"></i>Métricas</a></li>
  <?php elseif($u['rol']==='curador'): ?>
  <li><a href="<?=url('curador/dashboard')?>" class="nav-link"><i class="fa fa-home me-2"></i>Dashboard</a></li>
  <li><a href="<?=url('curador/obras-pendientes')?>" class="nav-link"><i class="fa fa-clock me-2"></i>Pendientes</a></li>
  <?php else: ?>
  <li><a href="<?=url('perfil')?>" class="nav-link"><i class="fa fa-user me-2"></i>Mi Perfil</a></li>
  <li><a href="<?=url('mis-favoritos')?>" class="nav-link"><i class="fa fa-heart me-2"></i>Favoritos</a></li>
  <li><a href="<?=url('notificaciones')?>" class="nav-link"><i class="fa fa-bell me-2"></i>Notificaciones</a></li>
  <?php endif; ?>
  <li><a href="<?=url('chat')?>" class="nav-link"><i class="fa fa-comments me-2"></i>Chat</a></li>
  <li><hr></li>
  <li><a href="<?=url('logout')?>" class="nav-link text-danger"><i class="fa fa-sign-out-alt me-2"></i>Salir</a></li>
</ul>
